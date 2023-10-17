<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('register', [RegisterController::class, 'register'])->name('register');

//---------------- LOGIN ---------------- //
Route::post('login', LoginController::class)->name('login');

//---------------- PASSWORD RESET URL's ---------------- //
Route::post('password/create', [\App\Http\Controllers\Password\ResetController::class, 'create']);
Route::get('password/find/{token}', [\App\Http\Controllers\Password\ResetController::class, 'find']);
Route::post('password/reset', [\App\Http\Controllers\Password\ResetController::class, 'reset']);

Route::get('attendance/procedure', [App\Http\Controllers\AttendanceController::class, 'callDatabaseProcedure']);