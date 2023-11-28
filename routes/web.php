<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', "PaymentController@showHomePage")->Name('Home');
Route::get('/payment', "PaymentController@showPaymentForm")->Name('payment.form');
Route::post('/payment/process', "PaymentController@processPayment")->Name('processPayment');
Route::post('/payment/webhook', "PaymentController@handleOmiseWebhook")->Name('handleOmiseWebhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/payment/status', "PaymentController@checkWebhookStatus")->Name('checkWebhookStatus');
