<?php

use App\Http\Controllers\AdminVisitorController;
use App\Http\Controllers\LiveStreamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LiveStreamController::class, 'welcome'])->name('visitor.form');
Route::post('/watch', [LiveStreamController::class, 'register'])->name('visitor.register');
Route::get('/live', [LiveStreamController::class, 'player'])->name('live.player');
Route::post('/leave', [LiveStreamController::class, 'reset'])->name('visitor.reset');

Route::get('/admin/login', [AdminVisitorController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminVisitorController::class, 'authenticate'])->name('admin.authenticate');
Route::post('/admin/logout', [AdminVisitorController::class, 'logout'])->name('admin.logout');
Route::get('/admin/visitors', [AdminVisitorController::class, 'index'])->name('admin.visitors');
Route::post('/admin/visitors/{visitor}/block', [AdminVisitorController::class, 'block'])->name('admin.visitors.block');
Route::post('/admin/visitors/{visitor}/unblock', [AdminVisitorController::class, 'unblock'])->name('admin.visitors.unblock');
