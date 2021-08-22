<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Interfaces\AccountTypeInterface;
use Illuminate\Foundation\Http\FormRequest;

class AddAccountRequest extends FormRequest
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
            'account_number' => ['string', 'required', 'unique:user_accounts'],
            'account_type' => ['string', 'required', Rule::in([AccountTypeInterface::CORPORATE_ACCOUNT, AccountTypeInterface::FIXED_DEPOSIT_ACCOUNT, AccountTypeInterface::CURRENT_ACCOUNT, AccountTypeInterface::SAVINGS_ACCOUNT])],
            'bank_name' => ['string', 'required'],
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
            'account_type.string' => 'Please provide a valid account type',
            'bank_name.string' => 'Please provide a valid bank name',
            'account_type.required' => 'Please provide a valid account type',
            'bank_name.required' => 'Please provide a valid bank name',
        ];
    }
}
