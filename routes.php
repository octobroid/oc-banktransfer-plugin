<?php

Route::group(['prefix' => 'api/v1'], function() {
    // MIGS
    Route::post('payment/checkout', [
        'as'   => 'migs.checkout',
        'uses' => 'Octobro\BankTransfer\Http\PaymentController@checkout'
    ]);

    Route::get('payment/finish', 'Octobro\BankTransfer\Http\PaymentController@finish');

    Route::get('payment/unfinish', 'Octobro\BankTransfer\Http\PaymentController@unfinish');

    Route::get('payment/error', 'Octobro\BankTransfer\Http\PaymentController@error');
});

