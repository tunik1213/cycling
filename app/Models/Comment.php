<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    public function commentable()
    {
        return $this->morphTo();
    }

    protected $fillable = array(
        'author_id',
        'parent_id',
        'text'
    );

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function children()
    {
        return $this->hasMany(Self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Self::class, 'parent_id');
    }
}
