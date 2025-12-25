<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return 'Hello World';
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/notes', [NoteController::class,'index'])->middleware('auth:sanctum');
Route::get('/notes/{note}', [NoteController::class,'show'])
    ->middleware('auth:sanctum')
    ->missing(function () {
        return response()->json(['message' => 'Not Found.'], 404);
    });
Route::put('/notes/{note}', [NoteController::class,'update'])
    ->middleware('auth:sanctum')
    ->missing(function () {
        return response()->json(['message' => 'Not Found.'], 404);
    });
Route::delete('/notes/{note}', [NoteController::class,'destroy'])
    ->middleware('auth:sanctum')
    ->missing(function () {
        return response()->json(['message' => 'Not Found.'], 404);
    });

Route::post('/notes', [NoteController::class,'store'])->middleware('auth:sanctum');

Route::fallback(function(){
    return response()->json(['message' => 'Not Found.'], 404);
});
