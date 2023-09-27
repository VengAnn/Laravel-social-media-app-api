<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'following_user_id',
        'accepted',
        'blocked',
        'muted',
        'following',
    ];
    //Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function following_user()
    {
        return $this->belongsTo(User::class);
    }
}
