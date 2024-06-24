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
                'regex:/^[a-z0-9]([-a-z0-9]*[a-z0-9])?$/',
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

            //STRING VARIABLES
            'str_name.*' => [
                'required'
            ],
            'str_val.*' => [
                'required'
            ],

            //NUMBER VARIABLES
            'num_name.*' => [
                'required'
            ],
            'num_val.*' => [
                'required',
                'numeric'
            ],

            //RAND VARIABLES
            'rand_name.*' => [
                'required'
            ],
            'min.*' => [
                'required',
                'numeric'
            ],
            'max.*' => [
                'required',
                'numeric'
            ],

            //FLAG VARIABLES
            'flag_name.*' => [
                'required'
            ],
            'flag_val.*' => [
                'nullable'
            ],
        ];
    }
    public function messages()
    {
        return [
            //NAME
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 255 characters.',
    
            //DEFINITION
            'definition.required' => 'The definition field is required.',
            'definition.exists' => 'The selected definition is invalid.',
    
            //ACCESS CODE
            'access_code.required' => 'The access code field is required.',
            'access_code.string' => 'The access code must be a string.',
            'access_code.max' => 'The access code may not be greater than 50 characters.',
    
            //QUANTITY
            'quantity.required' => 'The quantity field is required.',
            'quantity.integer' => 'The quantity must be an integer.',
            'quantity.min' => 'The quantity must be at least 1.',
        
            //PORT
            'port.required' => 'The port field is required.',
            'port.integer' => 'The port must be an integer.',
            'port.between' => 'The port must be between 30000 and 32767.',

            //DESCRIPTION
            'description.string' => 'The description must be a string.',
            'description.max' => 'The description may not be greater than 2000 characters.',

            //STRING VARIABLES
            'str_name.*.required' => 'The name field for string variable is required.',
            'str_val.*.required' => 'The value field for string variable is required.',

            //NUMBER VARIABLES
            'num_name.*.required' => 'The name field for number variable is required.',
            'num_val.*.required' => 'The value field for number variable is required.',
            'num_val.*.numeric' => 'The value field for number variable must be numeric.',

            //RAND VARIABLES
            'rand_name.*.required' => 'The name field for random variable is required.',
            'min.*.required' => 'The minimum value field for random variable is required.',
            'min.*.numeric' => 'The minimum value field for random variable must be numeric.',
            'max.*.required' => 'The maximum value field for random variable is required.',
            'max.*.numeric' => 'The maximum value field for random variable must be numeric.',

            //FLAG VARIABLES
            'flag_name.*.required' => 'The name field for flag variable is required.',
            'flag_val.*.nullable' => 'The value field for flag variable should be nullable.',
        ];
    }
}
