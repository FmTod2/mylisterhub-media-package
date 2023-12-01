<?php

namespace MyListerHub\Media\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \FmTod\Media\Media
 */
class Media extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MyListerHub\Media\Media::class;
    }
}
