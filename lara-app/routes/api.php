<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Public Auth Routes (Generate Token)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Sanctum Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // optional: get own profile
    Route::get('/me', [AuthController::class, 'me']);

    // logout
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Authorization Tests (Spatie)
    |--------------------------------------------------------------------------
    */

    // Role-based test: only admin can access
    Route::get('/admin-test', function () {
        return response()->json(['message' => 'Admin access granted']);
    })->middleware('role:admin');

    /*
    |--------------------------------------------------------------------------
    | Students Routes (Permission Protected)
    |--------------------------------------------------------------------------
    */

    // Only users with "manage students" permission can CRUD students
    Route::apiResource('students', StudentController::class)
        ->middleware('permission:manage students');
});