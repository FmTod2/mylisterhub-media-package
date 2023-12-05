<?php

namespace MyListerHub\Media\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

interface Videoable
{
    public static function videoValidationRules(): array;

    public function images(): MorphToMany;

    public function createVideos(Collection|array $videos, bool $detaching = true): static;

    public function syncVideos(Collection|array $videos, bool $detaching = true): static;
}
