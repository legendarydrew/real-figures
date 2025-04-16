<?php

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Intervention\Image\Laravel\Facades\Image;

class IsBase64Image implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // https://stackoverflow.com/a/39442808/4073160
        try
        {
            Image::read($value);
        }
        catch (Exception $e)
        {
            $fail("$attribute is an invalid image: {$e->getMessage()}");
        }
    }
}
