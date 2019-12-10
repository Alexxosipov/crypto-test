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
Route::group(['prefix' => 'wallets'], function(){
    Route::get('/', 'WalletController@index')->name('wallet.index');
    Route::get('/create', 'WalletController@create')->name('wallet.create');
    Route::post('/create', 'WalletController@store');
});
