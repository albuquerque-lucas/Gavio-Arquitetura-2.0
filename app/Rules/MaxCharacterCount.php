<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxCharacterCount implements ValidationRule
{
    protected $max;
    protected $inputLength;

    public function __construct($max)
    {
        $this->max = $max;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->inputLength = strlen($value);

        if ($this->inputLength > $this->max) {
            $fail("O campo :attribute deve ter no máximo {$this->max} caracteres, mas foram digitados {$this->inputLength}.");
        }
    }
}
