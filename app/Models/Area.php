<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
      'name', 'image'
    ];

    public function districts()
    {
        return $this->hasMany(District::class)->orderBy('name');
    }

    public function getLinkAttribute()
    {
        return view('areas.link',['area'=>$this]);
    }

    public function getDisplayNameAttribute()
    {
        return $this->name . (($this->name=='Крим')?'':' область');
    }
}
