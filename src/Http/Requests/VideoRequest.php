<?php

namespace MyListerHub\Media\Http\Requests;

use MyListerHub\API\Http\Request;

class VideoRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function commonRules(): array
    {
        return [
            'name' => 'sometimes|nullable|string',
            'path' => 'sometimes|nullable|string',
            'disk' => 'sometimes|nullable|string',
            'url' => 'required|string',
        ];
    }
}
