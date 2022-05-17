<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\ApiV1\Modules\Items\Controllers\ItemsController;

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

Route::get('/items/{id}', [ItemsController::class, 'get']);

Route::delete('/items/{id}', [ItemsController::class, 'delete']);

Route::patch('/items/{id}', [ItemsController::class, 'patch']);

Route::put('/items/{id}', [ItemsController::class, 'put']);

Route::post('/items', [ItemsController::class, 'post']);

Route::get('/items_index', [ItemsController::class, 'getIndex']);
