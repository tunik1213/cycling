<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Jobs\CheckRoutePassing;

class Route extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot() {
  
        parent::boot();

        static::saved(function($route){
            if($route->finished && $route->sights()->count() > 1) {
                CheckRoutePassing::dispatch($route);
            }
        });

    }

    public function sights()
    {
        return $this->belongsToMany(Sight::class)->withPivot('row_number')->orderBy('row_number');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function find_or_create()
    {
        $user = Auth::user();
        if(empty($user)) return null;

        return Self::firstOrCreate([
            'user_id' => $user->id,
            'finished' => false
        ]);
    }

    public static function current_editing()
    {
        $user = Auth::user();
        if(empty($user)) return null;

        return Self::where([
            'user_id' => $user->id,
            'finished' => false
        ])->first();
    }

    public function canEdit() : bool
    {
        $user = Auth::user();
        if(empty($user)) return false;

        if($user->moderator) return true;

        if($this->finished) return false;

        if($this->user_id == $user->id) return true;

        return false;
    }

    public function isPublic() : bool
    {
        return ($this->moderator != null);
    }
}
