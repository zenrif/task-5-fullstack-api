<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['maker'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function maker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
