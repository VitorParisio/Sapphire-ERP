<?php

use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('tenant.index');
})->name('tenant.index');

Route::get('/create', [TenantController::class, 'create'])->name('tenant.create');

Route::get('/store', [TenantController::class, 'store'])->name('tenant.store');
