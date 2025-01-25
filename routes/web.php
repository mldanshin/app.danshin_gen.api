<?php

use App\Http\Controllers\IndexController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth.basic')->group(function () {
    Route::get('/', IndexController::class)->name('index');
    Route::post('/token', function (Request $request) {
        $user = $request->user();

        $token = null;
        if ($user->role === 'admin') {
            $token = $request->user()->createToken($request->name_token, ['admin', 'user']);
        } else {
            $token = $request->user()->createToken($request->name_token, ['user']);
        }

        return ['token' => $token->plainTextToken];
    })->name('token');
});

Route::get('login', fn () => response('Unauthorized', 401))->name('login');
