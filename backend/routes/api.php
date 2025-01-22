<?php

use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkplaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

//users
Route::get('/users', [UserController::class, 'index']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user = $request->user();
    $user->load('role', 'departments');
    return $user;
});

//Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/users/{id}/change-role', [UserController::class, 'changeRole']);

Route::delete('/users/{id}', [UserController::class, 'deleteUser']);

Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.logout');


//conferences
Route::get('/conferences', [ConferenceController::class, 'index']);

Route::get('/conferences/{department_id}', [ConferenceController::class, 'show']);

Route::get('/createconference', [ConferenceController::class, 'index']);

Route::post('/conferences/by-departments', [ConferenceController::class, 'findForDepartments']);

Route::post('/conferences', [ConferenceController::class, 'store']);

Route::put('/conferences/{id}', [ConferenceController::class, 'update']);

Route::get('/conference-tasks/{conferenceId}', [ConferenceController::class, 'checkUserTasks']);



//works
Route::post('/works', [WorkController::class, 'store']);

Route::middleware('auth:sanctum')->post('/works', [WorkController::class, 'store']);

Route::middleware('auth:sanctum')->get('/categories/{id}/works', [WorkController::class, 'getUserWorksByCategory']);

Route::get('/files/download/{fileName}', [FileController::class, 'download']);


//categories
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{id}', [CategoryController::class, 'show']);


//news
Route::get('/news', [NewsController::class, 'index']);


//notifications
Route::get('/notifications', [NotificationController::class, 'index']);

Route::middleware(['auth:sanctum'])->put('/notifications/{id}/state', [NotificationController::class, 'updateState']);

Route::middleware(['auth:sanctum'])->get('/user-notifications', function (Request $request) {
    return $request->user()->notifications;
});

Route::post('/send-role-request', [NotificationController::class, 'sendRoleRequestNotification']);



//places
Route::get('/universities', [UniversityController::class, 'index']);

Route::get('/faculties', [FacultyController::class, 'getByUniversity']);

Route::get('/departments', [DepartmentController::class, 'getByFaculty']);

Route::post('/submit-workplace', [WorkplaceController::class, 'submitWorkplace']);

Route::get('/publications', [PublicationController::class, 'index']);

Route::post('/publications', [PublicationController::class, 'store']);

Route::post('/reviews', [ReviewController::class, 'store']);

Route::get('/reviews/{publicationId}', [ReviewController::class, 'show']);

Route::put('/publications/{id}', [PublicationController::class, 'update']);
Route::post('/publications/{id}/upload', [PublicationController::class, 'upload']);

Route::get('/publication/{id}', [PublicationController::class, 'show']);
Route::get('/publications/{id}/download', [PublicationController::class, 'download']);

Route::delete('/publications/{id}/remove-file', [PublicationController::class, 'removeFile']);
