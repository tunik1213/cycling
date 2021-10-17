<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;

class SightCategory extends Model
{
    use HasFactory;

    public function sights()
    {
        return $this->hasMany(Sight::class, 'category_id')->orderBy('name');
    }
}
