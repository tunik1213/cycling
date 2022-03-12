<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sight;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Route extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sights()
    {
        return $this->belongsToMany(Sight::class)->withPivot('row_number');;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function current_editing()
    {
        $user = Auth::user();
        if(empty($user)) return null;

        return Self::firstOrCreate([
            'user_id' => $user->id,
            'finished' => false
        ]);
    }

}
