<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\CheckIfAmountIsFloat;
use App\Interfaces\AccountTypeInterface;
use Illuminate\Foundation\Http\FormRequest;

class SendMoneyToUserRequest extends FormRequest
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
            'amount' => ['numeric', 'required', new checkIfAmountIsFloat()],
            'sender_account_type' => ['string', 'required', Rule::in([AccountTypeInterface::CORPORATE_ACCOUNT, AccountTypeInterface::FIXED_DEPOSIT_ACCOUNT, AccountTypeInterface::CURRENT_ACCOUNT, AccountTypeInterface::SAVINGS_ACCOUNT])],
            'sender_bank_name' => ['string', 'required'],
            'receiver_account_number' => ['string', 'required', 'exists:user_accounts,account_number'],
            'receiver_account_type' => ['string', 'required'],
            'receiver_bank_name' => ['string', 'required'],
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
            'account_number.string' => 'Please provide a valid personal account number',
            'account_number.required' => 'Please provide a valid personal account number',
            'amount.numeric' => 'Please provide a valid amount',
            'amount.required' => 'Please provide a valid amount',
            'sender_account_type.string' => 'Please provide a valid account type',
            'sender_account_type.required' => 'Please provide a valid account type',
            'sender_bank_name.string' => 'Please provide a valid bank name',
            'sender_bank_name.required' => 'Please provide a valid bank name',
            'receiver_account_number.string' => 'Please provide a valid receiver account number',
            'receiver_account_number.required' => 'Please provide a valid receiver account number',
            'receiver_account_type.string' => 'Please provide a valid receiver account type',
            'receiver_account_type.required' => 'Please provide a valid receiver account type',
            'receiver_bank_name.string' => 'Please provide a valid receiver bank name',
            'receiver_bank_name.required' => 'Please provide a valid receiver bank name',
        ];
    }
}
