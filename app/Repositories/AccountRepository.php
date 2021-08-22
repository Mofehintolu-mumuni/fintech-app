<?php

namespace App\Repositories;

use App\Models\Accounts;
use App\Interfaces\AcountsInterface;


class AccountRepository implements AcountsInterface {

public $accountModel;

public function __construct(Accounts $accountModel)
{
    $this->accountModel = $accountModel;
}

public function addAccountNumber(object $userInstance, object $request):object {
    $addedAccount = $this->accountModel::firstOrCreate(['user_id' => $userInstance->id,
                                                        'account_number' => $request->account_number,
                                                        'account_balance' => $request->account_balance ?? 0.00,
                                                        'account_type' => $request->account_type,
                                                        'bank_name' => $request->bank_name,
                                                       ]);
    return $addedAccount;
}

public function getUserAccount(string $accountNumber, $accountType, $bankName, $userInstance) {
    $userAccountObject = $this->accountModel->where('account_number', $accountNumber);
    if(!is_null($userInstance)) {
        $userAccountObject = $userAccountObject->where('user_id', $userInstance->id);
    }

    if(!is_null($accountType)) {
        $userAccountObject = $userAccountObject->where('account_type', $accountType);
    }

    if(!is_null($bankName)) {
        $userAccountObject = $userAccountObject->where('bank_name', $bankName);
    }
                                            
    $userAccountObject = $userAccountObject->first();

    return $userAccountObject;
}


public function checkAccountBalance(object $userInstance, string $accountNumber, string $accountType, string $bank): object{

    $accountBalance = (object)['account_balance' => 0.00];
    $accountObject = $this->accountModel::with('userTransactionHistory')->where('account_number', $accountNumber)
                                        ->where('account_type', $accountType)
                                        ->where('user_id', $userInstance->id)
                                        ->where('bank_name', $bank)->first();

    !is_null($accountObject) ? $accountBalance = $accountObject : null;

    return $accountBalance;

}

}