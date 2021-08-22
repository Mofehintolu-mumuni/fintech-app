<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Interfaces\HttpStatusCode;
use App\Http\Traits\TransactionUtil;
use App\Repositories\AccountRepository;
use App\Http\Requests\AddAccountRequest;
use App\Services\ApiJsonResponserService;
use App\Interfaces\TransactionTypeInterface;
use App\Http\Requests\SendMoneyToUserRequest;
use App\Http\Requests\getAccountBalanceRequest;
use App\Http\Requests\AddMoneyToAccountRequest;



class TransactionController extends Controller {

    use TransactionUtil;

public function __construct(AccountRepository $accountRepository)
{

    $this->accountRepository = $accountRepository;
    
}

 /** 
     * @OA\Post(
     *     path="/api/add-account",
     *     summary="Add user account | route('add-account')",
     *     description="Add user account ",
     *     tags={"Wafi app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="account_number",type="integer"),
     *                 @OA\Property(property="account_type",type="string"),
     *                 @OA\Property(property="bank_name",type="string"),
     *                 example={
     *                      "account_number": "0009134433",
     *                      "account_type": "SAVINGS ACCOUNT",
     *                      "bank_name": "J P MORGAN"
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="q",
     *          description="Add user account",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
public function addAccount(AddAccountRequest $request) {
    $userInstance = \Auth::user();

    $addedAccount = $this->accountRepository->addAccountNumber($userInstance, $request);

    if(is_null($addedAccount)) {
        return ApiJsonResponserService::sendData(HttpStatusCode::SERVER_ERROR, 'Account not added successfully', []);
    }

    return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Account added successfully', $addedAccount);

}


 /** 
     * @OA\Post(
     *     path="/api/add-fund-to-account",
     *     summary="Fund user account | route('add-fund-to-account')",
     *     description="Fund user account ",
     *     tags={"Wafi app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="account_number",type="integer"),
     *                 @OA\Property(property="amount",type="float"),
     *                 example={
     *                      "account_number" : "0009134433",
     *                       "amount": 50000.00
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="q",
     *          description="Fund user account",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
public function addMoneyToAccount(AddMoneyToAccountRequest $request) {

    $userInstance = \Auth::user();

    $userAccountObject = $this->accountRepository->getUserAccount($request->account_number, null, null, $userInstance);

    if(is_null($userAccountObject)) {
        return ApiJsonResponserService::sendData(HttpStatusCode::SERVER_ERROR, 'Money not added to account successfully', []);
    }

    DB::beginTransaction();

    $userAccountUpdateStatus = $this->addFunds($userAccountObject, $request->amount);
    
    if(!$userAccountUpdateStatus) {
        DB::rollBack();
        return ApiJsonResponserService::sendData(HttpStatusCode::SERVER_ERROR, 'Money not added to account successfully', []);
    }

    $userAccountObject->account_balance = $userAccountObject->account_balance + $request->amount;

    //register transaction on ledger
    if(!$this->recordTransactionOnLedger($userAccountObject, $userInstance, TransactionTypeInterface::MONEY_DEPOSIT, $request->amount)){
        DB::rollBack();
        return ApiJsonResponserService::sendData(HttpStatusCode::SERVER_ERROR, 'Money not added to account successfully', []);
    };

    DB::commit();

    return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Money added to account successfully', $userAccountObject);

}



    /** 
     * @OA\Post(
     *     path="/api/send-money",
     *     summary="Send money to user | route('send-money')",
     *     description="Send money to user",
     *     tags={"Wafi app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="account_number",type="integer"),
     *                 @OA\Property(property="amount",type="float"),
     *                 @OA\Property(property="sender_account_type",type="string"),
     *                 @OA\Property(property="sender_bank_name",type="string"),
     *                 @OA\Property(property="receiver_account_number",type="string"),
     *                 @OA\Property(property="receiver_account_type",type="string"),
     *                 @OA\Property(property="receiver_bank_name",type="string"),
     *                 example={
     *                      "account_number" : "0009134433",
     *                       "amount": 50000.00,
     *                       "sender_account_type": "SAVINGS ACCOUNT",
     *                       "sender_bank_name": "J P MORGAN", 
     *                       "receiver_account_number": "22099002",
     *                       "receiver_account_type": "SAVINGS ACCOUNT",
     *                       "receiver_bank_name": "Bank of America"
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="q",
     *          description="Send money to user",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
public function sendMoneyToUser(SendMoneyToUserRequest $request) {

    $userInstance = \Auth::user();

    $accountBalance = $this->accountRepository->checkAccountBalance($userInstance, $request->account_number, $request->sender_account_type, $request->sender_bank_name);

    //check if account balance greater than or equal to amount to send
    if($accountBalance->account_balance < $request->amount) {
        return ApiJsonResponserService::sendData(HttpStatusCode::SERVER_ERROR, 'Account balance not sufficient for transaction', []);
    }

    //perfrom transaction
    $senderAccountObject = $this->accountRepository->getUserAccount($request->account_number, $request->sender_account_type, $request->sender_bank_name, $userInstance);

    DB::beginTransaction();
    $deductFundsStatus = $this->deductFunds($senderAccountObject, $request->amount);

    if(!$deductFundsStatus) {
        DB::rollBack();
        return ApiJsonResponserService::sendData(HttpStatusCode::SERVER_ERROR, 'Transaction not successful', []);
    }

    //perfrom transaction
    $receiverAccountObject = $this->accountRepository->getUserAccount($request->receiver_account_number, $request->receiver_account_type, $request->receiver_bank_name, null);

    $receiverAccountUpdateStatus = $this->addFunds($receiverAccountObject, $request->amount);

    if(!$receiverAccountUpdateStatus) {
        $addFundsToSenderStatus = $this->addFunds($senderAccountObject, $request->amount);
        if(!$addFundsToSenderStatus) {
            \Log::critical("{$request->amount} was deducted from {$request->account_number} belonging to {$userInstance->name} and not reverted successfully");
        }
        DB::rollBack();
        return ApiJsonResponserService::sendData(HttpStatusCode::SERVER_ERROR, 'Transaction not successful', []);
    }


     //register transaction on ledger
     if(!$this->recordTransactionOnLedger($senderAccountObject, $userInstance, TransactionTypeInterface::MONEY_TRANSFER, $request->amount)){
        DB::rollBack();
        return ApiJsonResponserService::sendData(HttpStatusCode::SERVER_ERROR, 'Transaction not successful', []);
     }

    DB::commit();

    return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Transaction was successful', []);

}

    /** 
     * @OA\Get(
     *     path="/api/check-balance",
     *     summary="Check account balance | route('check-balance')",
     *     description="Check account balance",
     *     tags={"Wafi app"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *            @OA\Schema(
     *                 @OA\Property(property="account_number",type="integer"),
     *                 @OA\Property(property="account_type",type="string"),
     *                 @OA\Property(property="bank_name",type="string"),
     *                 example={"account_number": "22099002",
     *                       "account_type": "SAVINGS ACCOUNT",
     *                       "bank_name": "Bank of America"
     *                  }
     *            )
     *         )
     *     ),
     *     @OA\Parameter(
     *          name="q",
     *          description="Check account balance",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *     ),
     *     security={ {"bearer": {}} },
     * )
     */
public function getAccountBalance(getAccountBalanceRequest $request) {
    $userInstance = \Auth::user();

    $accountBalance = $this->accountRepository->checkAccountBalance($userInstance, $request->account_number, $request->account_type, $request->bank_name);

    return ApiJsonResponserService::sendData(HttpStatusCode::OK, 'Account balance retrieved successfully', $accountBalance);

}



}