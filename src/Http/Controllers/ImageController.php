<?php

namespace MyListerHub\Media\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;
use MyListerHub\API\Http\Controller;
use MyListerHub\Media\Http\Requests\ImageRequest;
use MyListerHub\Media\Http\Requests\ImageUploadRequest;
use MyListerHub\Media\Http\Resources\ImageResource;
use MyListerHub\Media\Models\Image;
use RahulHaque\Filepond\Facades\Filepond;

class ImageController extends Controller
{
    protected string $model = Image::class;

    protected string $request = ImageRequest::class;

    protected ?string $resource = ImageResource::class;

    public function upload(ImageUploadRequest $request): JsonResource|ResourceCollection
    {
        $validated = $request->validated();

        /** @var \Illuminate\Http\UploadedFile[] $files */
        $files = $request->type() === 'filepond'
            ? Filepond::field($validated['files'])->getFile()
            : $request->file('files');

        $path = config('media.storage.images.path');
        $disk = config('media.storage.images.disk');

        $images = collect($files)->map(function (UploadedFile $file) use ($path, $disk) {
            $file->store($path, $disk);

            $name = sprintf('%s_%s', now()->getTimestamp(), $file->getClientOriginalName());
            $image = \Spatie\Image\Image::load($file->getRealPath());

            return Image::create([
                'name' => $name,
                'source' => $name,
                'width' => $image->getWidth(),
                'height' => $image->getHeight(),
            ]);
        });

        return $this->response($images);
    }
}
