<?php

Route::get('/all','AccountController@index');

Route::get('/', function (){
    return redirect()->route('login');
});
Route::get('/oauth/gmail', 'TestController@call')->name('account-login');
Route::get('/get', 'AccountController@create');
Route::get('/test', 'TestController@test');
Route::get('/get-token', 'AccountController@insert')->name('user.token.get');
Route::get('/oauth/gmail/logout', 'EmailController@destroy')->name('logout');
Route::get('/gmail-login', function () {
    return view('welcome');
})->name('login');
Route::get('/gmail/{box?}/{inbox_id?}','EmailController@index')->name('inbox');
Route::get('/message/{id}','EmailController@show')->name('message');
Route::post('/gmail','EmailController@store')->name('send.email');
Route::post('/trash','EmailController@trash')->name('trash.email');

Route::get('/attachment/download/{attachment}','AttachmentController@show');

Route::get('/data','EmailController@getEmailData');

