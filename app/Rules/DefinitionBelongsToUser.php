<?php

namespace App\Rules;

use App\Models\UserDefinition;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class DefinitionBelongsToUser implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!UserDefinition::where('id', $value)->where('user_id', Auth::id())->exists()) {
            $fail('The specified definition does not belong to you.');
        }
    }
}
