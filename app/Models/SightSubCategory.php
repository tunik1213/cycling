<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SightSubCategory extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(SightCategory::class);
    }
}
