<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Area;
use App\Models\Sight;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
      'name', 'image', 'area_id'
    ];

    public function sights()
    {
        return $this->hasMany(Sight::class)->orderBy('name');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
