<?php

namespace MyListerHub\Media\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MyListerHub\Media\Media
 */
class Media extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MyListerHub\Media\Media::class;
    }
}
