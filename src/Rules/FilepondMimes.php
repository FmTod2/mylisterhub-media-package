<?php

namespace MyListerHub\Media\Rules;

use Closure;
use RahulHaque\Filepond\Facades\Filepond;

class FilepondMimes extends StorageFileMimes
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string ...$mimes)
    {
        parent::__construct(config('filepond.temp_disk'), ...$mimes);
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $filepond = Filepond::field($value);

        /** @var \RahulHaque\Filepond\Models\Filepond|null $model */
        $model = $filepond->getModel();

        parent::validate($attribute, $model->filepath, $fail);
    }
}
