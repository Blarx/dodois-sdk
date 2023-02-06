<?php

use Dodois\Contracts\ConnectionContract;
use Dodois\Events\CallbackRedirected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::middleware('web')->group(function () {
    Route::get('/dodois/redirect', function (ConnectionContract $dodois, Request $request) {
        $code_verifier = Str::random(128);
        $request->session()->put('code_verifier', $code_verifier);

        return redirect($dodois->generateAuthLink($code_verifier));
    })->name('dodois:redirect');

    Route::get(config('dodois.connection.callbackRoute', '/dodois/callback'), function (ConnectionContract $dodois, Request $request) {
        $response = redirect(config('dodois.connection.redirectUri', '/dashboard'));

        if (! $request->session()->has('code_verifier')) {
            CallbackRedirected::dispatch(
                $response,
                __("Account create error, no ':field'.", [
                    'field' => 'code_verifier',
                ]),
            );

            return $response;
        }
        if (! $request->has('code')) {
            CallbackRedirected::dispatch(
                $response,
                __("Account create error, no ':field'.", [
                    'field' => 'code',
                ]),
            );

            return $response;
        }

        $codeVerifier = $request->session()->pull('code_verifier');
        $dodois->makeTokenRequest($codeVerifier, $request->code);

        CallbackRedirected::dispatch(
            $response,
            __("Account create error, no ':field'.", [
                'field' => 'code',
            ]),
        );

        return $response;
    });
});
