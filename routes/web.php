<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::view("/", "index");
Route::resource('users', UserController::class);

