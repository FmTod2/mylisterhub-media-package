<?php

use Illuminate\Support\Facades\Route;
use MyListerHub\Media\Http\Controllers\ImageController;
use MyListerHub\Media\Http\Controllers\VideoController;

$options = config('media.route_options');

Route::group($options, function () {
    Route::post('images/upload', [ImageController::class, 'upload']);
    Route::apiResource('videos', VideoController::class);

    Route::post('videos/upload', [VideoController::class, 'upload']);
    Route::apiResource('images', ImageController::class);
});
