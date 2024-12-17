<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisteredUserController;
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

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/main', function () {
    return response()->json(['message' => 'Welcome to the main page.']);
})->middleware(['auth']);

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');


require __DIR__.'/auth.php';
