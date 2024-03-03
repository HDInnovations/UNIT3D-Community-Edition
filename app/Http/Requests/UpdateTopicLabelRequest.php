<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicLabelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'approved' => [
                'boolean',
            ],
            'denied' => [
                'boolean',
            ],
            'solved' => [
                'boolean',
            ],
            'invalid' => [
                'boolean',
            ],
            'bug' => [
                'boolean',
            ],
            'suggestion' => [
                'boolean',
            ],
            'implemented' => [
                'boolean',
            ],
        ];
    }
}
