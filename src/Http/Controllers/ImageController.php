<?php

namespace MyListerHub\Media\Http\Controllers;

use MyListerHub\API\Http\Controller;
use MyListerHub\Media\Models\Image;

class ImageController extends Controller
{
    protected string $model = Image::class;
}
