<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/gv/callback', 'API\GvController@callback');
Route::post('/vendor/payment-forward', 'API\EdcController@callbackPaymentVendor');
Route::post('/dropship/payment-forward', 'API\EdcController@callbackPaymentDropshipper');
Route::post('/edccash/callback', 'API\EdcController@callbackPaymentEdcCash');