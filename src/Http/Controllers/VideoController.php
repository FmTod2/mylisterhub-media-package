<?php

namespace MyListerHub\Media\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use MyListerHub\API\Http\Controller;
use MyListerHub\Media\Http\Requests\VideoRequest;
use MyListerHub\Media\Http\Requests\VideoUploadRequest;
use MyListerHub\Media\Http\Resources\VideoResource;
use MyListerHub\Media\Models\Video;
use RahulHaque\Filepond\Facades\Filepond;

class VideoController extends Controller
{
    protected string $model = Video::class;

    protected string $request = VideoRequest::class;

    protected ?string $resource = VideoResource::class;

    public function upload(VideoUploadRequest $request): JsonResource|ResourceCollection
    {
        $validated = $request->validated();

        /** @var \Illuminate\Http\UploadedFile[] $files */
        $files = $request->type() === 'filepond'
            ? Filepond::field($validated['files'])->getFile()
            : $request->file('files');

        $path = config('media.storage.videos.path');
        $disk = config('media.storage.videos.disk');

        $images = collect($files)->map(fn (UploadedFile $file) => Video::create([
            'name' => $file->getClientOriginalName(),
            'path' => $file->store($path, $disk),
            'disk' => $disk,
            'url' => Storage::disk($disk)->url($path),
        ]));

        return $this->response($images);
    }
}
