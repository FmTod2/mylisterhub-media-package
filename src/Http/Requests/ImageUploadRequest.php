<?php

namespace MyListerHub\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use MyListerHub\Media\Rules\FilepondMimes;
use MyListerHub\Media\Rules\FilepondValid;

class ImageUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => [
                'nullable',
                'sometimes',
                Rule::in(['filepond', 'files']),
            ],
            'files.*' => $this->type() === 'filepond'
                ? ['required', new FilepondValid, new FilepondMimes('jpg', 'jpeg', 'png')]
                : ['required', File::image()],
        ];
    }

    /**
     * Get the type of upload.
     */
    public function type(): string
    {
        return $this->input('type', 'files');
    }
}
