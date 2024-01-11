<?php

namespace MyListerHub\Media\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
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
        'disk',
        'url',
    ];

    /**
     * Create a new factory instance for the model.
     */
    public static function newFactory(): VideoFactory
    {
        return new VideoFactory;
    }

    /**
     * Create a new video from a file.
     */
    public static function createFromFile(UploadedFile|File $file, ?string $name = null, ?string $disk = null): static
    {
        $path = config('media.storage.videos.path', 'media/videos');

        if (is_null($name) || $name === '') {
            $name = sprintf('%s_%s', now()->getTimestamp(), $file->getClientOriginalName());
        }

        if (is_null($disk)) {
            $disk = (string) config('media.storage.videos.disk', 'public');
        }

        Storage::disk($disk)->putFileAs($path, $file, $name);

        return static::create([
            'name' => $name,
            'path' => "{$path}/{$name}",
            'disk' => $disk,
            'url' => Storage::disk($disk)->url("{$path}/{$name}"),
        ]);
    }

    /**
     * Create a new video from an url.
     */
    public static function createFromUrl(string $url, ?string $name = null, bool $upload = false, ?string $disk = null): static
    {
        if (is_null($name) || $name === '') {
            $name = (string) Str::of($url)
                ->afterLast('/')
                ->before('?')
                ->trim()
                ->prepend('_')
                ->prepend(now()->getTimestamp());

            throw_if(! $name, InvalidArgumentException::class, 'Could not guess the name of the image. Please provide a filename.');
        }

        if (! $upload) {
            return static::create([
                'name' => $name,
                'url' => $url,
            ]);
        }

        $path = config('media.storage.videos.path', 'media/images');

        if (is_null($disk)) {
            $disk = (string) config('media.storage.images.disk', 'public');
        }

        $file = file_get_contents($url);
        Storage::disk($disk)->put("{$path}/{$name}", $file);

        return static::create([
            'name' => $name,
            'path' => "{$path}/{$name}",
            'disk' => $disk,
            'url' => Storage::disk($disk)->url("{$path}/{$name}"),
        ]);
    }
}
