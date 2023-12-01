<?php

namespace MyListerHub\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MyListerHub\Media\Database\Factories\VideoFactory;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public static function newFactory(): VideoFactory
    {
        return new VideoFactory;
    }
}
