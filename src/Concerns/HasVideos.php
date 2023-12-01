<?php

namespace MyListerHub\Media\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use MyListerHub\Media\Models\Video;

trait HasVideos
{
    /**
     * Validation rules for models with custom attributes.
     *
     * @return string[]
     */
    public static function videoValidationRules(): array
    {
        return [
            'videos' => 'sometimes|nullable|array',
            'videos.*' => 'sometimes|nullable',
            'videos.*.id' => 'sometimes|nullable|integer|exists:videos,id',
        ];
    }

    /**
     * Get product videos.
     */
    public function videos(): MorphToMany
    {
        return $this->morphToMany(Video::class, 'videoable')->withPivot(['order'])->orderByRaw('-`videoable`.`order` DESC');
    }

    /**
     * Sync videos based on the provided data.
     */
    public function createVideos(Collection|array $videos, bool $detaching = true): static
    {
        if (! $videos instanceof Collection) {
            $videos = collect($videos);
        }

        $uploadedVideos = $videos
            ->mapWithKeys(function (mixed $videoData, int $index) {
                if ($videoData instanceof Video) {
                    return [$videoData->id => ['order' => $videoData->pivot?->order ?? $index]];
                }

                if ($videoData instanceof UploadedFile) {
                    $video = Video::createFromFile($videoData);

                    return [$video->id => ['order' => $index]];
                }

                if (is_string($videoData)) {
                    $video = Video::createFromUrl($videoData);

                    return [$video->id => ['order' => $index]];
                }

                if (is_array($videoData)) {
                    $video = match (true) {
                        isset($videoData['id']) => Video::find($videoData['id']),
                        isset($videoData['source']) => Video::updateOrCreate(['source' => $videoData['source']], $videoData),
                        isset($videoData['url']) => Video::createFromUrl($videoData['url']),
                        default => null,
                    };

                    if (! $video) {
                        return null;
                    }

                    return [$video->id => ['order' => $videoData['order'] ?? $index]];
                }

                return null;
            })
            ->filter();

        $this->videos()->sync($uploadedVideos, $detaching);

        return $this;
    }

    /**
     * Sync given videos with the current videoable model.
     *
     * @param  \Illuminate\Support\Collection|Video[]  $videos
     */
    public function syncVideos(Collection|array $videos, bool $detaching = true): static
    {
        if (! $videos instanceof Collection) {
            $videos = collect($videos);
        }

        $videoIds = $videos->mapWithKeys(fn (Video $video) => [$video->id => ['order' => $video->pivot?->order]]);

        $this->videos()->sync($videoIds, $detaching);

        return $this;
    }
}
