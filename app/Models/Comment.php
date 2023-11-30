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
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function getUrlAttribute()
    {
        $obj = $this->commentable_type::find($this->commentable_id);
        return $obj->url . '#comment'.$this->id;
    }
}
