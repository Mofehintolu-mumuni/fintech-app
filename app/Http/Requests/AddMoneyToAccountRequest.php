<?php

namespace App\Http\Requests;

use App\Rules\CheckIfAmountIsFloat;
use Illuminate\Foundation\Http\FormRequest;

class AddMoneyToAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_number' => ['string', 'required', 'exists:user_accounts,account_number'],
            'amount' => ['numeric', 'required', new checkIfAmountIsFloat()]
        ];
    }

    /**
     * Get the validation messages that apply to the rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'account_number.string' => 'Please provide a valid account number',
            'account_number.required' => 'Please provide a valid account number',
            'account_number.exists' => 'Please provide a valid account number',
            'amount.numeric' => 'Please provide a valid amount to add',
            'amount.required' => 'Please provide a valid amount to add',
        ];
    }
}
