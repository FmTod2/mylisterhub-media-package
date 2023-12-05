<?php

namespace MyListerHub\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MyListerHub\Media\Database\Factories\VideoFactory;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'path',
        'url',
    ];

    /**
     * Create a new factory instance for the model.
     */
    public static function newFactory(): VideoFactory
    {
        return new VideoFactory;
    }
}
