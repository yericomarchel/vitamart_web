<?php

use App\Http\Controllers\Api\v1\CartController;
use App\Http\Controllers\Api\v1\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\v1'], function () {
    Route::apiResource('products', ProductController::class);
    Route::get('cart/{userId}', 'CartController@index');
    Route::post('cart/new', 'CartController@store');
    Route::put('cart/{cartId}/update', 'CartItemController@update');
    Route::put('cart/{cartId}/expire', 'CartController@expireCart');
});
