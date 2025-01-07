<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChampionshipController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RequestLogController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('log', [RequestLogController::class, 'index']);

    Route::group(['prefix' => 'auth'], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });

    Route::apiResource('role', RoleController::class);
    Route::post('role/{role}/assign/permission', [RoleController::class, 'assignPermission']);
    Route::post('role/{role}/revoke/permission', [RoleController::class, 'revokePermission']);

    Route::apiResource('permission', PermissionController::class);

    ROute::apiResource('user', UserController::class);
    Route::post('user/{user}/assign/role', [UserController::class, 'assignRole']);
    Route::post('user/{user}/revoke/role', [UserController::class, 'revokeRole']);
    Route::post('user/{user}/assign/permission', [UserController::class, 'assignPermission']);
    Route::post('user/{user}/revoke/permission', [UserController::class, 'revokePermission']);

});

