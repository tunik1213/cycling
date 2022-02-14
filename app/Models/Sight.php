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

class Sight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'lat',
        'lng',
        'approx_location',
        'image',
        'area_id',
        'district_id',
        'map_image',
        'category_id',
        'sub_category_id',
        'radius',
        'locality',
        'moderator',
        'license'
    ];

    protected $hidden = [
        'image'
    ];

    //public int $visitsCount;

    public static function boot() {
  
        parent::boot();

        static::saving(function($sight) {            
                if (!$sight->isPublic() && Auth::user()->moderator ?? false) {
                $sight->moderator = Auth::user()->id;
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
            $approx = Self::getApprox($lat, $lng);
            $found = Self::where('approx_location',$approx)->count();
            if ($found > 0) continue;

            $photoRef=$point->photos[0]->photo_reference;
            $apikey = config('googlemaps.key');
            $imgPath = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=300&maxheight=300&photo_reference=$photoRef&key=$apikey";

            $newSight = Self::create([
                'user_id'=>0,
                'name'=>$point->name,
                'lat'=>$lat,
                'lng'=>$lng,
                'approx_location'=>$approx,
                'image'=>Image::make($imgPath)->encode('jpg', 75),
                'district_id'=>$district_id
            ]);

            $newSight->save();
        }
    }

    public static function getApprox($lat, $lng) : string
    {
        return (string)round($lat,3).':'.(string)round($lng,3);
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
}
