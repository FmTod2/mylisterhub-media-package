<?php

namespace MyListerHub\Media\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MyListerHub\Media\Models\Image;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(15),
            'source' => $this->faker->imageUrl(),
            'width' => $this->faker->numerify(),
            'height' => $this->faker->numerify(),
        ];
    }
}
