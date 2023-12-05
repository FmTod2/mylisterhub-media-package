<?php

namespace MyListerHub\Media\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\MimeTypes;

class StorageFileMimes implements ValidationRule
{
    protected array $mimes = [];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(public string $disk, string ...$mimes)
    {
        $this->mimes = $mimes;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (in_array('jpg', $this->mimes, true) || in_array('jpeg', $this->mimes, true)) {
            $this->mimes = array_unique(array_merge($this->mimes, ['jpg', 'jpeg']));
        }

        $mimeType = Storage::disk($this->disk)->mimeType($value);
        $extension = MimeTypes::getDefault()->getExtensions($mimeType)[0] ?? null;

        if (! in_array($extension, $this->mimes, true)) {
            $fail(trans('validation.mimes', ['values' => implode(',', $this->mimes)]));
        }
    }
}
