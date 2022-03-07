<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Sight;
use App\Models\User;

class SightVersion extends Model
{
    use HasFactory;
    protected $fillable = ['sight_id','user_id','data'];

    public function sight()
    {
        return $this->belongsTo(Sight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function lastVersion($sight)
    {
        return Self::where('sight_id',$sight->id)
            ->whereNull('moderator')
            ->orderBy('id','desc')
            ->first();
    }
}
