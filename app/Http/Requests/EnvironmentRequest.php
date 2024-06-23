<?php

namespace App\Http\Requests;

use App\Rules\DefinitionBelongsToUser;
use Illuminate\Foundation\Http\FormRequest;

class EnvironmentRequest extends FormRequest
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
                'string',
                'max:255',
            ],
            'definition' => [
                'required',
                'exists:user_definitions,id',
                new DefinitionBelongsToUser
            ],
            'access_code' => [
                'required',
                'string',
                'max:20',
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'port' => [
                'required',
                'integer',
                'between:30000,32767',
            ],
            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            //CUSTOM VARIABLES
            'type.*' => [
                'required',
                'in:string,number,rand,flag',
            ],
            'variable.*' => [
                'required',
            ],
            'value' => [
                'required_if:type.*,string',
                'required_if:type.*,number',
                'array'
            ],
            'value.*' => [
                'required_if:type.*,string,number',
            ],
            'min' => [
                'required_if:type.*,rand',
                'array'
            ],
            'min.*' => [
                'required_if:type.*,rand',
                'nullable',
                'numeric'
            ],
            'max.*' => [
                'required_if:type.*,rand',
                'nullable',
                'numeric'
            ],
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
    
            'definition.required' => 'The definition field is required.',
            'definition.exists' => 'The selected definition is invalid.',
    
            'access_code.required' => 'The access code field is required.',
            'access_code.string' => 'The access code must be a string.',
            'access_code.max' => 'The access code may not be greater than 50 characters.',
    
            'quantity.required' => 'The quantity field is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'quantity.min' => 'The quantity must be at least 1.',
    
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 2000 characters.',
    
            'port.required' => 'The port field is required.',
            'port.integer' => 'The port must be an integer.',
            'port.between' => 'The port must be between 30000 and 32767.',

            'type.*.required' => 'The type field is required.',
            'type.*.in' => 'The type must be one of the following: string, number, rand, flag.',
            'variable.*.required' => 'The variable field is required.',
            'value.*.required_if' => 'The value field is required when the type is string or number.',
            'min.*.required_if' => 'The Min field is required when the type is Random Number.',
            'min.*.numeric' => 'The Min field must be a number.',
            'max.*.required_if' => 'The Max field is required when the type is Random Number.',
            'max.*.numeric' => 'The Max field must be a number.',
        ];
    }
}
