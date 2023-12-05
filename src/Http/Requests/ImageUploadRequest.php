<?php

namespace MyListerHub\Media\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

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
        $fileRules = [
            'required',
            File::image(),
        ];

        return [
            'type' => [
                'nullable',
                'sometimes',
                Rule::in(['filepond', 'files']),
            ],
            'files.*' => $this->type() === 'filepond'
                ? Rule::filepond($fileRules)
                : $fileRules,
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
