<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;
use App\Models\User;
use App\Models\Visit;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'strava_id',
        'user_id',
        'start_date',
        'polyline',
        'summary_polyline',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class, 'act_id');
    }

}
