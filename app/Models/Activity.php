<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'strava_id',
        'user_id',
        'start_date',
        'summary_polyline',
        'name'
    ];

    public function sights()
    {
        //return $this->belongsToMany(Sight::class);
    }

    public function getNameAttribute()
    {
        $name = (empty($this->name)) ? 'Заїзд' : $this->name;
        $dt = \Carbon\Carbon::createFromTimeStamp(strtotime($this->start_date))->locale('uk_UK')->diffForHumans();

        return $name.' '.$dt;
    }

    public static function link($user_id=null,$sight_id=null)
    {
        $url = '/activities?';
        if ($user_id) $url .= 'user='.$user_id.'&';
        if ($sight_id) $url .= 'sight='.$sight_id.'&';
        return preg_replace('/&$/', '', $url);
    }

}
