<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DefinitionRequest extends FormRequest
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
            'name' => [
                'required', 
                'regex:/^[a-z0-9]([-a-z0-9]*[a-z0-9])?$/',
                'unique:definitions,name'
            ],
            'category' => [
                'required',
                'string'
            ],
            'definition' => [
                'required',
                'file',
                'mimes:json'
            ],
            'private' => [
                'required',
                'in:0,1'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'tags' => [
                'nullable',
                'string'
            ]
        ];
    }

    public function messages()
    {
        return [
            'category.required' => 'The category field is required.',
            'name.required' => 'The name field is required.',
            'name.regex' => 'The name must follow the Kubernetes namespace name regex.',
            'private.required' => 'The private field is required.',
            'private.in' => 'The private field must be either 1 or 0.',
        ];
    }
}
