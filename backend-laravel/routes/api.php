<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ConditionController;
use App\Http\Controllers\Api\V1\ConditionNumberController;
use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\UserController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API!',
        'status' => 'success'
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix'=>'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function() {

    // AUTHENTICATION
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // USER PROFILE
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // ITEMS - Specific routes first
    Route::get('items/active', [ItemController::class, 'getActiveItems']);
    Route::get('items/deleted', [ItemController::class, 'getDeletedItems']);
    Route::get('items/check/{uuid}', [ItemController::class, 'checkItem']);
    Route::post('items/restore/{uuid}', [ItemController::class, 'restoreItem']);
    Route::delete('items/delete/{uuid}', [ItemController::class, 'destroy']);
    Route::delete('items/delete-by-id/{id}', [ItemController::class, 'destroy']);
    Route::delete('items/force-delete/{uuid}', [ItemController::class, 'forceDelete']);
    
    // General resource routes last
    Route::apiResource('items', ItemController::class);
    Route::post('items/{uuid}/borrow', [ItemController::class, 'borrowItem']);

    Route::apiResource('locations', LocationController::class);

    Route::apiResource('users', UserController::class);
    Route::post('users/{id}/reassign-items', [UserController::class, 'reassignItems']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('conditions', ConditionController::class);
    Route::apiResource('condition_numbers', ConditionNumberController::class);

});