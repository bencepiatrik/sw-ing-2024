<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\FileController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/works', [WorkController::class, 'store']);

Route::middleware('auth:sanctum')->get('/categories/{id}/works', [WorkController::class, 'getUserWorksByCategory']);

Route::get('/files/download/{fileName}', [FileController::class, 'download']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::post('/works', [WorkController::class, 'store']);

Route::get('/users', [UserController::class, 'index']);

Route::post('/users/{id}/change-role', [UserController::class, 'changeRole']);

Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
