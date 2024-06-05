<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
                'unique:categories',
                'max:50'
            ],
            'training_type' => [
                'required',
                'in:R,B,U'
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.unique' => 'The name has already been taken.',
            'name.max' => 'The name may not be greater than :max characters.',
            'training_type.required' => 'The training type field is required.',
            'training_type.in' => 'The selected training type is invalid.',
        ];
    }

}
