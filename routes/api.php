<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\ContactController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// admin token "1|Kkw4uRVpyFdtbAL7i5tLRWgSNPvcobnkjr0KckVs"

Route::get('/seeProducts', [ProductController::class, 'view']);

Route::group(['middleware' => ['auth.basic']],function (){
    Route::post('/storeProduct', [ProductController::class, 'store']);
    Route::delete('deleteProduct/{id}', [ProductController::class, 'delete']); //Gate auth
});
Route::get('updateProduct/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum'); //sanctum apiToken


Route::post('/storeContact', [ContactController::class, 'store'])->middleware('auth');;

