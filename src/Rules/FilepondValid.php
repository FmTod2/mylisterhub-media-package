<?php

namespace MyListerHub\Media\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use RahulHaque\Filepond\Facades\Filepond;

class FilepondValid implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute field must be a string');
        }

        $filepond = Filepond::field($value);

        /** @var \RahulHaque\Filepond\Models\Filepond|null $model */
        $model = $filepond->getModel();

        if (! $model) {
            $fail('Could not find the model for the given :attribute field');
        }

        $exists = new StorageFileExists($filepond->getTempDisk());

        $exists->validate($attribute, $model->filepath, $fail);
    }
}
