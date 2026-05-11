<?php

namespace Modules\Institution\Routes\V1;

use Illuminate\Support\Facades\Route;
use Modules\Auth\Middlewares\IsAdmin;

Route::middleware(['auth:api', IsAdmin::class])->group(function (): void {
    Route::get('/', 'index');
    Route::get('/show/{modelId}', 'show');
    Route::post('/create', 'create');
    Route::delete('/delete/{modelId}', 'delete');
    Route::post('/update/{modelId}', 'update');
    Route::delete('/force-delete/{modelId}', 'forceDelete');
    Route::get('/restore/{modelId}', 'restore');
});
