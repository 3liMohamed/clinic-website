<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidOrHash implements ValidationRule
{

    public function __construct(
        protected int|string $filterOrPattern,
        protected string $message = 'validation.invalid_value_or_hash',
    ) {}
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if ($value === '#') {
            return;
        }

        $isValid = is_int($this->filterOrPattern)
            ? filter_var($value, $this->filterOrPattern) !== false
            : preg_match($this->filterOrPattern, $value) === 1;

        if (! $isValid) {
            $fail(__($this->message));
        }
    }

    public static function urlOrHash(): static
    {
        return new static(
            FILTER_VALIDATE_URL,
            'validation.invalid_url',
        );
    }

    public static function phoneOrHash(): static
    {
        return new static(
            '/^\+201[0125][0-9]{8}$/',
            'validation.invalid_phone',
        );
    }

    public static function emailOrHash(): static
    {
        return new static(
            '/^.+\\@.+\\..+$/',
            'validation.invalid_email',
        );
    }
}
