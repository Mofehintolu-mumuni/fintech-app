<?php

namespace App\Http\Traits;


trait TransactionUtil {

    public function recordTransactionOnLedger(object $accountObject, object $userInstance, string $transactionType, float $amount): bool{
 
        try{
            $accountObject->userTransactionHistory()->sync([$userInstance->id => ['transaction_type' => $transactionType, 'transaction_amount' => $amount, 'created_at' => now(), 'updated_at' => now()]]);
            $recordTransactionOnLedgerStatus = true;
        }catch(\Exception $e){
            $recordTransactionOnLedgerStatus = false;
        }

        return $recordTransactionOnLedgerStatus;
        
    }

    public function deductFunds(object $accountObject, float $amount):float {
        $accountUpdateStatus = $accountObject->update(['account_balance' => ($accountObject->account_balance - $amount)]);
        return $accountUpdateStatus;
    }

    public function addFunds(object $accountObject, float $amount):float  {
        $accountUpdateStatus = $accountObject->update(['account_balance' => ($accountObject->account_balance + $amount)]);
        return $accountUpdateStatus;
    }

}