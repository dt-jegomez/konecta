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

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::patch('settings/profile', 'Settings\ProfileController@update');
    Route::patch('settings/password', 'Settings\PasswordController@update');

    Route::prefix('clientes')->group( function(){

        Route::post('create','ClienteController@create');
        Route::put('{id}/updated','ClienteController@updated');
        Route::get('listar','ClienteController@listar');
        Route::delete('{id}/eliminar','ClienteController@eliminar');

    });

    Route::prefix('usuarios')->group( function(){

        Route::post('create','UserController@create');
        Route::put('{id}/updated','UserController@updated');
        Route::get('listar','UserController@listar');
        Route::delete('{id}/eliminar','UserController@eliminar');

    });
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login');
    // Route::post('register', 'Auth\RegisterController@register');

    // Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    // Route::post('password/reset', 'Auth\ResetPasswordController@reset');

    // Route::post('email/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');
    // Route::post('email/resend', 'Auth\VerificationController@resend');

    // Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
    // Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');

    
});
