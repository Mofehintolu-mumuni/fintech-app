<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    private $accessToken;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
    }


    /**
     * Login  test.
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json',
                                        ])->json('POST', '/api/login', [
                                            "email" => "mark@example.com",
                                            "password" => "i am mark"
                                        ]);

        $responseContent = json_decode($response->getContent(), 1);
        $this->assertNotNull( 
            $responseContent['data'], 
            "access token is null"
        );

        $this->accessToken = $responseContent['data'];
        $response->assertStatus(200);

    }


     /**
     * Add account.
     *
     * @return void
     */
    public function test_add_account()
    {
        $this->login();
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json',
                                        'Authorization' => "Bearer {$this->accessToken}"
                                        ])->json('POST', '/api/add-account', [
                                            "account_number" => "908546678794",
                                            "account_type" => "SAVINGS ACCOUNT",
                                            "bank_name" => "J P MORGAN"
                                        ]);

        $response->assertStatus(200);

    }


     /**
     * Add money to account.
     *
     * @return void
     */
    public function test_add_money_to_account()
    {   $this->login();
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json',
                                        'Authorization' => "Bearer {$this->accessToken}"
                                        ])->json('POST', '/api/add-fund-to-account', [
                                            "account_number" => "554667879",
                                            "amount"=> 500000.00
                                        ]);
                                     
                                    
        $response->assertStatus(200);

    }


     /**
     * Send money to user account.
     *
     * @return void
     */
    public function test_send_money_to_user_account()
    { $this->login();
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json',
                                        'Authorization' => "Bearer {$this->accessToken}"
                                        ])->json('POST', '/api/send-money', [
                                            "account_number" => "0009134433",
                                            "amount" => 50000.00,
                                            "sender_account_type" => "SAVINGS ACCOUNT",
                                            "sender_bank_name" => "J P MORGAN", 
                                            "receiver_account_number" => "22099002",
                                            "receiver_account_type" => "SAVINGS ACCOUNT",
                                            "receiver_bank_name" => "Bank of America"
                                        ]);
                                     
                                    
        $response->assertStatus(200);

    }



     /**
     * Check account balance.
     *
     * @return void
     */
    public function test_check_account_balance()
    { $this->login();
        $response = $this->withHeaders([
                                        'Content-Type' => 'application/json',
                                        'Accpet' => 'application/json',
                                        'Authorization' => 'Bearer'.' '.$this->accessToken
                                        ])->json('GET', '/api/check-balance', [
                                            "account_number" => "0009134433",
                                            "account_type" => "SAVINGS ACCOUNT",
                                            "bank_name" => "J P MORGAN"
                                        ]);
                                     
                                    
        $response->assertStatus(200);

    }


    public function login() {
        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accpet' => 'application/json',
            ])->json('POST', '/api/login', [
                "email" => "mark@example.com",
                "password" => "i am mark"
            ]);

            $responseContent = json_decode($response->getContent(), 1);
            $this->assertNotNull( 
            $responseContent['data'], 
            "access token is null"
            );

            $this->accessToken = $responseContent['data'];
    }



}
