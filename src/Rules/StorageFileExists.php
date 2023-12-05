<?php

namespace MyListerHub\Media\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;

class StorageFileExists implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        public string $disk
    ) {
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Storage::disk($this->disk)->fileExists($value)) {
            $fail("The file for the given :attribute field does not exist in the {$this->disk} disk");
        }
    }
}
