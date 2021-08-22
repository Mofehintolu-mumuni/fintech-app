<?php

namespace App\Interfaces;

interface AcountsInterface {

    public function addAccountNumber(object $userInstance, object $request):object;
    
    public function getUserAccount(string $accountNumber, string $accountType, string $bankName, object $userInstance);
    
    public function checkAccountBalance (object $userInstance, string $accountNumber, string $accountType, string $bank):object;

}