<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'caption',
        'image_url',
        'user_id',
    ];

    //table post child of table user
    //have user and then have post
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //one post can have many comment and like 
    //need relationship with table comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
