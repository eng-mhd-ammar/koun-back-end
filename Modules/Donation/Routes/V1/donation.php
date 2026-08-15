<?php

namespace Modules\Institution\Routes\V1;

use Illuminate\Support\Facades\Route;
use Modules\Auth\Middlewares\IsAdmin;

Route::middleware(['auth:api'])->group(function (): void {
    Route::get('/', 'index');
    Route::get('/show/{modelId}', 'show');

    Route::post('/create', 'create');

    Route::middleware(['donation_owner:donation'])->group(function (): void {
        Route::delete('/delete/{modelId}', 'delete');
        Route::post('/update/{modelId}', 'update');
    });

    Route::middleware([IsAdmin::class])->group(function (): void {
        Route::get('/restore/{modelId}', 'restore');
        Route::get('/statistics', 'statistics');
        Route::delete('/force-delete/{modelId}', 'forceDelete');
    });
});
