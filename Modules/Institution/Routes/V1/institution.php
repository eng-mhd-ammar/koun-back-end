<?php

namespace Modules\Institution\Routes\V1;

use Illuminate\Support\Facades\Route;
use Modules\Auth\Middlewares\IsAdmin;

Route::middleware(['auth:api'])->group(function (): void {
    Route::get('/', 'index');
    Route::get('/show/{modelId}', 'show');
    Route::post('/create', 'create');
    
    Route::middleware(['ensure_institution_admin:institution'])->group(function (): void {
        Route::post('/update/{modelId}', 'update');
        Route::delete('/delete/{modelId}', 'delete');
    });

    Route::middleware([IsAdmin::class])->group(function (): void {
        Route::delete('/force-delete/{modelId}', 'forceDelete');
        Route::get('/restore/{modelId}', 'restore');
    });
});
