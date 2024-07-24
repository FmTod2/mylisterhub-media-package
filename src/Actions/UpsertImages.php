<?php

namespace MyListerHub\Media\Actions;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use MyListerHub\Core\Concerns\Actions\AsAction;
use MyListerHub\Media\Models\Image;

/**
 * Handles the upsertion of images during an import process.
 *
 * This class takes a collection of image data, validates the length of the image source,
 * and either updates existing images or creates new ones based on the provided data.
 * It returns an associative array mapping image IDs to their order in the import.
 *
 * @method static array run(Collection $images)
 */
class UpsertImages
{
    use AsAction;

    public function handle(Collection $images): array
    {
        return $images->reduce(static function (array $carry, array $imageData, int $index) {
            if (strlen($imageData['source']) > 300) {
                throw new InvalidArgumentException('Image source/url is too long.');
            }

            $image = Image::updateOrCreate(
                attributes: ['source' => $imageData['source']],
                values: $imageData,
            );

            $carry[$image->getKey()] = ['order' => $index];

            return $carry;
        }, []);
    }
}
