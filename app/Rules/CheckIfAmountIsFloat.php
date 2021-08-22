<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIfAmountIsFloat implements Rule
{
    public $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        is_integer($value) ?  $value = (float)$value : null;

        if(!is_float($value)) {
            $this->message = "Please provide a valid amount.";
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
