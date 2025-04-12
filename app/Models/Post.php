<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    protected $appends = [
        'created_at_for_humans',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function createdAtForHumans():Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->diffForHumans()
        );
    }
}