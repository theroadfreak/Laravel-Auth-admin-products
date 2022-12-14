<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
Route::get('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken('admin');

    return ['token' => $token->plainTextToken];
});*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/admin', function () {
    return view('adminDashboard');
})->middleware(['auth', 'isAdmin'])->name('adminDashboard');

require __DIR__.'/auth.php';

