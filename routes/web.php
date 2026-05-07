<?php

use App\Http\Controllers\Admin\BadgeController;
use App\Http\Controllers\Admin\BulkIssuanceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IssuanceController;
use App\Http\Controllers\Admin\TrailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicIssuanceController;
use App\Http\Controllers\PublicTrailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.home')
        : redirect()->route('login');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('admin.home');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');
    Route::get('/badges/create', [BadgeController::class, 'create'])->name('badges.create');
    Route::post('/badges', [BadgeController::class, 'store'])->name('badges.store');
    Route::get('/badges/{badge}/edit', [BadgeController::class, 'edit'])->name('badges.edit');
    Route::put('/badges/{badge}', [BadgeController::class, 'update'])->name('badges.update');
    Route::delete('/badges/{badge}', [BadgeController::class, 'destroy'])->name('badges.destroy');

    Route::get('/issuances', [IssuanceController::class, 'index'])->name('issuances.index');
    Route::get('/issuances/create', [IssuanceController::class, 'create'])->name('issuances.create');
    Route::post('/issuances', [IssuanceController::class, 'store'])->name('issuances.store');
    Route::get('/issuances/{issuance}/revoke', [IssuanceController::class, 'revokeForm'])->name('issuances.revoke.form');
    Route::post('/issuances/{issuance}/revoke', [IssuanceController::class, 'revoke'])->name('issuances.revoke');

    Route::get('/issuances/bulk', [BulkIssuanceController::class, 'index'])->name('issuances.bulk');
    Route::post('/issuances/bulk', [BulkIssuanceController::class, 'upload'])->name('issuances.bulk.upload');
    Route::get('/issuances/bulk/template', [BulkIssuanceController::class, 'template'])->name('issuances.bulk.template');
    Route::post('/issuances/bulk/process', [BulkIssuanceController::class, 'process'])->name('issuances.bulk.process');

    Route::get('/trails', [TrailController::class, 'index'])->name('trails.index');
    Route::get('/trails/create', [TrailController::class, 'create'])->name('trails.create');
    Route::post('/trails', [TrailController::class, 'store'])->name('trails.store');
    Route::get('/trails/{trail}', [TrailController::class, 'show'])->name('trails.show');
    Route::get('/trails/{trail}/edit', [TrailController::class, 'edit'])->name('trails.edit');
    Route::put('/trails/{trail}', [TrailController::class, 'update'])->name('trails.update');
    Route::delete('/trails/{trail}', [TrailController::class, 'destroy'])->name('trails.destroy');
});

Route::get('/a/{public_id}', [PublicIssuanceController::class, 'show'])->name('public.issuances.show');
Route::get('/a/{public_id}/pdf', [PublicIssuanceController::class, 'pdf'])->name('public.issuances.pdf');
Route::get('/t/{trail}', [PublicTrailController::class, 'show'])->name('public.trails.show');

require __DIR__.'/auth.php';
