<?php

namespace MyListerHub\Media\Models;

use Exception;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use League\Flysystem\UnableToCheckFileExistence;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'source',
        'width',
        'height',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
    ];

    public static function createFromFile(UploadedFile|File $file, string $name = null, string $disk = null): static
    {
        $path = config('media.storage.images.path', 'media/images');
        $image = \Spatie\Image\Image::load($file);

        if (is_null($name) || $name === '') {
            $name = sprintf('%s_%s', now()->getTimestamp(), $file->getClientOriginalExtension());
        }

        if (is_null($disk)) {
            $disk = (string) config('media.storage.images.disk', 'public');
        }

        Storage::disk($disk)->putFileAs($path, $file, $name);

        return static::create([
            'source' => $name,
            'name' => $name,
            'width' => $image->getWidth(),
            'height' => $image->getHeight(),
        ]);
    }

    public static function createFromUrl(string $url, string $name = null, bool $upload = false, string $disk = null): static
    {
        $path = config('media.storage.images.path', 'media/images');

        if (is_null($disk)) {
            $disk = (string) config('media.storage.images.disk', 'public');
        }

        if (is_null($name) || $name === '') {
            $name = (string) Str::of($url)
                ->afterLast('/')
                ->before('?')
                ->trim()
                ->prepend('_')
                ->prepend(now()->getTimestamp());

            throw_if(! $name, InvalidArgumentException::class, 'Could not guess the name of the image. Please provide a filename.');
        }

        try {
            $file = file_get_contents($url);

            if ($upload) {
                Storage::disk($disk)->put("{$path}/{$name}", $file);
            }

            $image = \Spatie\Image\Image::load($file);

            return static::create([
                'name' => $name,
                'source' => $upload ? $name : $url,
                'width' => $image->getWidth(),
                'height' => $image->getHeight(),
            ]);
        } catch (Exception $exception) {
            throw_if($upload, $exception);

            return static::create([
                'name' => $name,
                'source' => $url,
                'width' => null,
                'height' => null,
            ]);
        }
    }

    protected function name(): Attribute
    {
        return Attribute::get(
            fn ($value, $attributes) => empty($attributes['name'])
                ? Str::before(Str::afterLast($this->source, '/'), '?')
                : $attributes['name'],
        );
    }

    protected function url(): Attribute
    {
        return Attribute::get(function ($value, $attributes) {
            if (! isset($attributes['source'])) {
                return '';
            }

            if (Str::isMatch('/http(s)?:\/\//', $this->source)) {
                return $this->source;
            }

            $path = config('media.storage.images.path', 'media/images');
            $disk = config('media.storage.images.disk', 'public');

            return Storage::disk($disk)->tenantUrl("{$path}/{$this->source}");
        });
    }

    protected function size(): Attribute
    {
        return Attribute::get(function (): int {
            $path = config('media.storage.images.path', 'media/images');
            $disk = config('media.storage.images.disk', 'public');

            $filePath = "{$path}/{$this->name}";

            if (Str::isMatch('/http(s)?:\/\//', $this->source)) {
                return 0;
            }

            try {
                $exist = Storage::disk($disk)->exists($filePath);
            } catch (UnableToCheckFileExistence) {
                $exist = false;
            }

            if (! $exist) {
                return 0;
            }

            return Storage::disk($disk)->fileSize($filePath);
        });
    }
}
