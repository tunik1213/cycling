<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Activity;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\District;
use App\Models\User;

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
        'district_id'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public static function getApprox($lat, $lng)
    {
        return (string)round($lat,3).':'.(string)round($lng,3);
    }
}
