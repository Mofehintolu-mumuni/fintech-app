<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    
});

Route::group(['middleware' => ['auth:sanctum'] ,'namespace' => 'App\Http\Controllers'], function() {
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('add-account', 'TransactionController@addAccount')->name('add-account');
    Route::post('add-fund-to-account', 'TransactionController@addMoneyToAccount')->name('add-fund-to-account');
    Route::post('send-money', 'TransactionController@sendMoneyToUser')->name('send-money');
    Route::get('check-balance', 'TransactionController@getAccountBalance')->name('check-balance');
});


Route::group(['namespace' => 'App\Http\Controllers'], function() {
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@register')->name('register');
    
});
