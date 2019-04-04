<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckForId;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| order routes starts from here
*/


Route::post('orders', 'OrderController@create');

Route::patch('orders/{id}', 'OrderController@update');

Route::get('orders', 'OrderController@index');

Route::fallback(function(){
    return response()->json(['status' => 'Not Found.'], 404);
})->name('api.fallback.404');
