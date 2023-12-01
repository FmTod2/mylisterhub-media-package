<?php

namespace MyListerHub\Media\Http\Controllers;

use MyListerHub\API\Http\Controller;
use MyListerHub\Media\Models\Video;

class VideoController extends Controller
{
    protected string $model = Video::class;
}
