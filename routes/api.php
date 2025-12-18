<?php

use App\Http\Controllers\Api\MenuItemController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function (): void {
    Route::apiResource('menu-items', MenuItemController::class)->names('menu-items');
});
