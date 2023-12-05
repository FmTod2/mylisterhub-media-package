<?php

namespace MyListerHub\Media\Observers;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use MyListerHub\Media\Models\Image;

class ImageObserver
{
    /**
     * Handle the Image "created" event.
     */
    public function saving(Image $image): void
    {
        $path = config('media.storage.images.path', 'media/images');
        $disk = config('media.storage.images.disk', 'public');

        if (Str::isMatch('/http(s)?:\/\//', $image->source)) {
            return;
        }

        if (isset($image->width, $image->height)) {
            return;
        }

        if (! Storage::disk($disk)->exists("{$path}/{$image->source}")) {
            return;
        }

        try {
            $content = Storage::disk($disk)->get("{$path}/{$image->source}");
            $details = \Spatie\Image\Image::load($content);

            $image->update([
                'width' => $details->getWidth(),
                'height' => $details->getHeight(),
            ]);
        } catch (Exception $exception) {
        }
    }
}
