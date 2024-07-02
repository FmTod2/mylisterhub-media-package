<?php

namespace MyListerHub\Media\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;
use RahulHaque\Filepond\Facades\Filepond;

class FilepondMax implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public int $size)
    {

    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $filepond = Filepond::field($value);

        /** @var \RahulHaque\Filepond\Models\Filepond|null $model */
        $model = $filepond->getModel();

        Storage::disk(config('filepond.temp_disk'))->size($model->filepath) <= $this->size;
    }
}
