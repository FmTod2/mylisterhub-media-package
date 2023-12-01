<?php

namespace MyListerHub\Media\DataMappers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class VideoMapper
{
    public static function fromUrls(iterable $videos): array
    {
        return array_map(static fn (string $url) => [
            'url' => $url,
            'name' => strtok(basename($url), '?'),
        ], Arr::fromArrayable($videos));
    }

    public static function toUrls(Collection|array $videos): array
    {
        return collect($videos)
            ->sortBy('order')
            ->pluck('url')
            ->map(fn (string $url) => str_replace(' ', '%20', $url))
            ->toArray();
    }
}
