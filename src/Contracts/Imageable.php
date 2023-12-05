<?php

namespace MyListerHub\Media\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

interface Imageable
{
    public static function imageValidationRules(): array;

    public function images(): MorphToMany;

    public function createImages(Collection|array $images, bool $detaching = true, bool $copyImageFromUrl = false): static;

    public function syncImages(Collection|array $images, bool $detaching = true): static;
}
