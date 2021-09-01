<?php

use Illuminate\Support\Facades\Route;
use App\Events\SentMessage;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/chat', function () {
    return view('chat');
})->name('chat')->middleware('auth');

Route::post('/message', function (Request $request) {
    broadcast(new SentMessage($request->input('message')));

    return $request->input('message');
})->middleware('auth');

Route::get('login', function () {
    return redirect()->route('home');
})->name('login');

Route::get('login/{id}', function ($id) {
    Auth::loginUsingId($id);
    return redirect()->route('chat');
});

Route::get('logout', function () {
    Auth::logout();
    return redirect()->route('home');
});
