<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Activity;
use Intervention\Image\ImageManagerStatic as Image;

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
        'image'
    ];

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }

    public static function import_google_maps($data) : void
    {
        foreach($data->results as $point) {

            if (!isset($point->photos)) continue;
            if (count($point->photos) == 0) continue;

            $loc = $point->geometry->location;
            $lat = $loc->lat;
            $lng = $loc->lng;
            $approx = (string)round($lat,3).':'.(string)round($lng,3);
            $found = Self::where('approx_location',$approx)->count();
            if ($found > 0) continue;

            $photoRef=$point->photos[0]->photo_reference;
            $apikey = config('googlemaps.key');
            $imgPath = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=200&photo_reference=$photoRef&key=$apikey";

            $newSight = Self::create([
                'user_id'=>0,
                'name'=>$point->name,
                'lat'=>$lat,
                'lng'=>$lng,
                'approx_location'=>$approx,
                'image'=>Image::make($imgPath)->encode('jpg', 75)
            ]);

            $newSight->save();
        }
    }
}
