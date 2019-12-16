<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function(){
    return redirect(route('wallet.index'));
});

Route::group(['prefix' => 'wallets'], function(){
    Route::get('/', 'WalletController@index')->name('wallet.index');
    Route::get('/create', 'WalletController@create')->name('wallet.create');
    Route::post('/create', 'WalletController@store');
    Route::get('{wallet}/transactions', 'WalletController@transactions')->name('wallet.transactions');
    Route::post('{wallet}/delete', 'WalletController@delete')->name('wallet.delete');
    Route::get('{wallet}', 'WalletController@get')->name('wallet.get');
});

Route::get('/api/wallets', 'WalletController@apiIndex')->name('api.wallet.index');
