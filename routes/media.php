<?php

use Illuminate\Support\Facades\Route;
use MyListerHub\Media\Http\Controllers\ImageController;
use MyListerHub\Media\Http\Controllers\VideoController;

$options = config('media.route_options');

Route::group($options, function () {
    Route::apiResource('videos', VideoController::class);
    Route::apiResource('images', ImageController::class);
});
