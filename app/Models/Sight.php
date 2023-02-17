<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Activity;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\District;
use App\Models\User;
use App\Models\SightCategory;
use App\Jobs\CheckInvites;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SightVersion;
use App\Models\Route;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Comment;
use App\Notifications\CommonNotification;

class Sight extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'image'
    ];

    private const MIN_DISTANCE_BETWEEN_POINTS = 50;

    public static $sources = [
        0 => 'Velocian',
        -1 => '<a rel="nofollow" target="_blank" href="https://ostriv.org/">Національний заповідник Хотиця</a>',
        -2 => '<a rel="nofollow" target="_blank" href="http://mycity.kherson.ua/">Моє місто - Херсон</a>',
    ];

    public static function boot() {
  
        parent::boot();

        static::saving(function($sight) {   

            $user = Auth::user();
            if(empty($user)) return false;

            if ($user->moderator) {
                if (!$sight->isPublic()) {
                    $sight->moderator = $user->id;
                }
            }

            if(empty($sight->id)) return true; // перше створення, зберігаємо як є, без версій

            $lv = SightVersion::lastVersion($sight);

            if ($user->moderator) {
                if(!empty($lv)) {
                    $lv->moderator = $user->id;
                    $lv->save();

                    $text = 'Модератор '.$user->link.' схвалив твою правку до локації '.$sight->link;
                    $image = $user->avatarUrl;
                    $n = new CommonNotification($text,$image,'success');
                    $lv->user->notify($n);
                }

            } else {

                if(empty($lv)) {
                    if(!$sight->isPublic() && $sight->user_id == $user->id) return true; // правка своєї локації до того як вона пройде модерацію теж зберігаємо одразу, без версіонування

                    $lv = SightVersion::create([
                        'sight_id' => $sight->id,
                        'user_id' => $user->id,
                        'data' => $sight->serialize()
                    ]);
                } else {
                    $lv->data = $sight->serialize();
                    $lv->save(); 
                }
                
                return false;
            }

        });

        static::saved(function($sight){
            if (Auth::user()->moderator ?? false) {
                if ($sight->isDirty('lat') || $sight->isDirty('lng') || $sight->isDirty('radius')) {
                    CheckInvites::dispatch($sight);
                }
            }
        });

    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(SightCategory::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(SightSubCategory::class,'sub_category_id');
    }
    public function versions()
    {
        return $this->hasMany(SightVersion::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }



    public static function import_google_maps($data,$district_id) : void
    {
        foreach($data->results as $point) {

            if (!isset($point->photos)) continue;
            if (count($point->photos) == 0) continue;

            $loc = $point->geometry->location;
            $lat = (float)$loc->lat;
            if (($lat < 44) || ($lat > 53)) continue;
            $lng = (float)$loc->lng;
            if (($lng < 21) || ($lng > 41)) continue;

            $photoRef=$point->photos[0]->photo_reference;
            $apikey = config('googlemaps.key');
            $imgPath = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=300&maxheight=300&photo_reference=$photoRef&key=$apikey";

            $newSight = Self::create([
                'user_id'=>0,
                'name'=>$point->name,
                'lat'=>$lat,
                'lng'=>$lng,
                'image'=>Image::make($imgPath)->encode('jpg', 75),
                'district_id'=>$district_id
            ]);

            $newSight->save();
        }
    }

    public function string_coordinates() : string
    {
        $lat=number_format($this->lat,7,'.','');
        $lng=number_format($this->lng,7,'.','');
        return $lat.','.$lng;
    }

    public function gm_link() : string
    {
        return 'http://www.google.com/maps/place/'.$this->string_coordinates();
    }

    public function map_image()
    {
        $imagePath = 
            'https://maps.googleapis.com/maps/api/staticmap?key='
            .env('GOOGLE_MAPS_UNRESTRICTED_KEY')
            .'&size=400x400&zoom=14&markers=|'
            .$this->string_coordinates();
        $img = Image::make($imagePath)
                ->encode('jpg', 75);

        return $img;
    }

    public function getCategoryLinkAttribute()
    {
        if ($this->category)
            return view('sights.category_link',['sight'=>$this]);
        else
            return '';
    }

    public function isPublic() : bool
    {
        return ($this->moderator != null);
    }
    public static function unmoderated_count() : int
    {
        return DB::select('select count(*) as count from sights where moderator is null;')[0]->count;
    }

    public function canEdit() : bool
    {
        $u = Auth::user();
        if(empty($u)) return false;

        if($u->moderator) return true;

        $lv = SightVersion::lastVersion($this);
        if(empty($lv)) return true;
        if($lv->user_id == $u->id) return true;

        return false;

    }

    public function serialize()
    {
        $a = $this->toArray();
        $a['image'] = base64_encode($this->image);
        return serialize($a);
    }

    public static function unserialize($str)
    {
        $data = unserialize($str);
        $data['image'] = base64_decode($data['image']);
        //return Self::hydrate($data);

        $result = new Sight;
        $result->fill($data);

        return $result;
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }

    public function nearbySights($tolerance = 0.01)
    {
        $result = Sight::whereNotNull('moderator')
        ->where('id','<>',$this->id)
        ->whereBetween('lat', [$this->lat - $tolerance, $this->lat + $tolerance])
        ->whereBetween('lng', [$this->lng - $tolerance, $this->lng + $tolerance])
        ->orderBy('classiness')
        ->limit(4)
        ->get();


        if ($result->count()==0) {
            if($tolerance < 0.2) {
                return $this->nearbySights($tolerance+0.05);
            }
        }
            
        return $result;
         
    }

    public function findDuplicate($tolerance = 0.01) : ?Sight
    {
        $result = Sight::whereNotNull('moderator')
        ->where('id','<>',$this->id)
        ->whereBetween('lat', [$this->lat - $tolerance, $this->lat + $tolerance])
        ->whereBetween('lng', [$this->lng - $tolerance, $this->lng + $tolerance])
        ->get();

        if ($result->count()==0) {
            if($tolerance < 0.2) {
                return $this->findDuplicate($tolerance+0.05);
            }
        } else {
            foreach($result as $s) {
                if($this->distanceTo($s) <= Self::MIN_DISTANCE_BETWEEN_POINTS) return $s;
            }
        }

        return null;
    }

    public function findClosest($tolerance = 0.01) : array
    {
        $found = [];

        $result = Sight::whereNotNull('moderator')
        ->where('id','<>',$this->id)
        ->whereBetween('lat', [$this->lat - $tolerance, $this->lat + $tolerance])
        ->whereBetween('lng', [$this->lng - $tolerance, $this->lng + $tolerance])
        ->get();

        if ($result->count()==0) {
            if($tolerance < 0.2) {
                return $this->findClosest($tolerance+0.05);
            }
        } else {
            foreach($result as $s) {
                if($this->distanceTo($s) <= Self::MIN_DISTANCE_BETWEEN_POINTS) array_push($found, $s);
            }
        }

        return $found;
    }

    public function distanceTo($sight) : float 
    {
        return \GeometryLibrary\SphericalUtil::computeDistanceBetween( $this, $sight);
    }

    public static function classinessList() : array {
        return [
            1 => '1. Найцiкавiше',
            2 => '2. Дуже цiкаве',
            3 => '3. Цiкаве',
            4 => '4. Точка вiдвiдування'
        ];
    }

/*    public function getImageAttribute()
    {
        // if (empty($this->image))
        //     return file_get_contents(env('APP_ROOT').'/public/images/no-image.jpg');
        // else
            return $this->image;
    }*/

    protected function image() : Attribute
    {
        return Attribute::make(
            get: fn($value) => (empty($value)) ? file_get_contents(env('APP_ROOT').'/public/images/no-image.jpg') : $value
        );
    }

    public function comments0() {
        return $this->comments()->where('parent_id',0)->get();
    }

    public function getUrlAttribute()
    {
        return route('sights.show', ['sight' => $this]);
    }

    public function getLinkAttribute()
    {
        return view('sights.link',['sight'=>$this]);
    }
}
