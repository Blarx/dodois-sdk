<?php

use Dodois\Contracts\ConnectionContract;
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

    Route::get(config('dodois.connection.callbackUri', '/dodois/callback'), function (ConnectionContract $dodois, Request $request) {
        if (! $request->session()->has('code_verifier')) {
            return redirect()->route('dashboard.accounts')->with([
                'message' => __("Account create error, no ':field'.", [
                    'field' => 'code_verifier',
                ]),
            ]);
        }
        if (! $request->has('code')) {
            return redirect()->route('dashboard.accounts')->with([
                'message' => __("Account create error, no ':field'.", [
                    'field' => 'code',
                ]),
            ]);
        }

        $codeVerifier = $request->session()->pull('code_verifier');

        $response = $dodois->makeTokenRequest($codeVerifier, $request->code);

        return redirect()->route('dashboard.accounts')->with([
            'message' => __('Account sucessfully created!'),
        ]);
    });
});
