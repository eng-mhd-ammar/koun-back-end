<?php

namespace Modules\Institution\Routes\V1;

use Illuminate\Support\Facades\Route;
use Modules\Auth\Middlewares\IsAdmin;
use Modules\DonationRequest\Middlewares\BranchOwner;

Route::middleware(['auth:api'])->group(function (): void {
    Route::get('/', 'index');
    Route::get('/show/{modelId}', 'show');

    Route::middleware(['branch_owner:donation_request_item'])->group(function (): void {
        Route::post('/create', 'create');
        Route::delete('/delete/{modelId}', 'delete');
        Route::post('/update/{modelId}', 'update');
    });

    Route::middleware([IsAdmin::class])->group(function (): void {
        Route::delete('/force-delete/{modelId}', 'forceDelete');
        Route::get('/restore/{modelId}', 'restore');
    });
});
