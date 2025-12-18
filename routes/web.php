<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'app', ['view' => 'frontend']);
Route::view('/admin', 'app', ['view' => 'admin']);
