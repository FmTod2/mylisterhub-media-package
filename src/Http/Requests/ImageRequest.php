<?php

namespace MyListerHub\Media\Http\Requests;

use MyListerHub\API\Http\Request;

class ImageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function commonRules(): array
    {
        return [
            'name' => 'sometimes|nullable|string',
            'source' => 'required|string',
            'width' => 'sometimes|nullable|numeric',
            'height' => 'sometimes|nullable|numeric',
        ];
    }
}
