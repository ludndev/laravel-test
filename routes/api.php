<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'status' => true,
        'message' => 'Get user data',
        'data' => $request->user()
    ]);
});

Route::middleware('api_token_check')->group(function () {

    Route::get('/enums', ['App\Http\Controllers\API\EnumController', 'index']);

    Route::resource(
        'authors',
        'App\Http\Controllers\API\AuthorController'
    );

    Route::resource(
        'genres',
        'App\Http\Controllers\API\GenreController'
    );

    Route::resource(
        'books',
        'App\Http\Controllers\API\BookController'
    );

    Route::resource(
        'users',
        'App\Http\Controllers\API\UserController'
    )->except([
        'update', 'destroy'
    ]);

});
