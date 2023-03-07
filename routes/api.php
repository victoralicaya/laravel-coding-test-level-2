<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::post('login', [UserController::class, 'login']);

    Route::post('users', [UserController::class, 'store']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::middleware('checkUserRole:admin')->group(function() {
            Route::get('users', [UserController::class, 'index']);
            Route::get('user/{user}', [UserController::class, 'show']);
            Route::patch('user/{user}/edit', [UserController::class, 'update']);
            Route::delete('user/{user}/delete', [UserController::class, 'destroy']);
        });

        Route::middleware('checkUserRole:admin,product_owner')->group(function(){
            Route::get('projects', [ProjectController::class, 'index']);
            Route::post('projects', [ProjectController::class, 'store']);
            Route::get('project/{project}', [ProjectController::class, 'show']);
            Route::patch('project/{project}/edit', [ProjectController::class, 'update']);
            Route::delete('project/{project}/delete', [ProjectController::class, 'destroy']);
        });
    });
});

