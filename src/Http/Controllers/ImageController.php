<?php

namespace MyListerHub\Media\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MyListerHub\API\Http\Controller;
use MyListerHub\Media\Http\Requests\ImageRequest;
use MyListerHub\Media\Http\Requests\ImageUploadRequest;
use MyListerHub\Media\Http\Resources\ImageResource;
use MyListerHub\Media\Models\Image;
use RahulHaque\Filepond\Facades\Filepond;
use RahulHaque\Filepond\Models\Filepond as FilepondModel;

class ImageController extends Controller
{
    protected string $model = Image::class;

    protected string $request = ImageRequest::class;

    protected ?string $resource = ImageResource::class;

    public function upload(ImageUploadRequest $request): JsonResource|ResourceCollection
    {
        $validated = $request->validated();

        $files = $request->type() === 'filepond'
            ? Filepond::field($validated['files'])->getModel()
            : $request->file('files');

        $path = config('media.storage.images.path');
        $disk = config('media.storage.images.disk');

        $images = collect($files)->map(function (UploadedFile|FilepondModel $file) use ($path, $disk) {
            if ($file instanceof FilepondModel) {
                $content = Storage::disk(Filepond::getTempDisk())->get($file->filepath);
                $name = sprintf('%s_%s', now()->getTimestamp(), $file->filename);
                $image = \Spatie\Image\Image::load($content);

                Storage::disk($disk)->put("{$path}/{$name}", $content);

                Storage::disk(Filepond::getTempDisk())->delete($file->filepath);
                $file->delete();
            } else {
                $name = sprintf('%s_%s', now()->getTimestamp(), $file->getClientOriginalName());
                $image = \Spatie\Image\Image::load($file->getRealPath());

                $file->storeAs($path, $name, $disk);
            }

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
