<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home', ['title' => 'Appetized · Offentlig side']);
Route::view('/admin', 'admin', ['title' => 'Appetized · Admin']);
