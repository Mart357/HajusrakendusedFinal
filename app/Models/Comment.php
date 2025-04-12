<?php

namespace App\Models;

use App\Observers\CommentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;


#[ObservedBy(CommentObserver::class)]
class Comment extends Model
{
    protected $guarded = [];

    protected $appends = [
        'created_at_for_humans',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function createdAtForHumans():Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->diffForHumans()
        );
    }
}
