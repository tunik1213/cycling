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
        'summary_polyline'
    ];

    public function sights()
    {
        //return $this->belongsToMany(Sight::class);
    }

    public function getNameAttribute()
    {
        if(empty($this->name)) {
            $dt = \Carbon\Carbon::createFromTimeStamp(strtotime($this->start_date))->locale('uk_UK')->diffForHumans();
            return 'Заїзд '.$dt;
        }

        return $this->name;
    }

}
