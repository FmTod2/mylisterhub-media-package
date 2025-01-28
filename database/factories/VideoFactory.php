<?php

namespace MyListerHub\Media\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MyListerHub\Media\Models\Video;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'path' => $this->faker->filePath(),
            'disk' => $this->faker->randomElement(['public', 's3']),
            'url' => $this->faker->url()
        ];
    }
}
