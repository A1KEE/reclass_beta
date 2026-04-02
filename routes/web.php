<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (APPLICANT SIDE)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

// Root → Applicant Form
Route::get('/', function () {
    return redirect('/applicants/create');
});

// Applicant form page
Route::get('/applicants/create', [ApplicationController::class, 'create'])
    ->name('applicants.create');

// Submit application
Route::post('/applicants', [ApplicationController::class, 'store'])
    ->name('applicants.store');

// AJAX: Get QS / requirements
Route::get('/get-qs', [ApplicationController::class, 'getQS'])
    ->name('get.qs');

// AJAX: Experience requirement
Route::post('/qs/experience-requirement', [ApplicationController::class, 'experienceRequirement']);

// Notify unqualified applicants
Route::post('/notify-unqualified', [ApplicationController::class, 'notifyUnqualified'])
    ->name('applicants.notifyUnqualified');

// Load PPST data
Route::get('/load-ppst', [ApplicationController::class, 'loadPPST']);

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (ADMIN SIDE)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

// 🔥 CHANGE PASSWORD PAGE
    Route::get('/change-password', [ChangePasswordController::class, 'index'])
        ->name('change.password');

    // 🔥 SAVE NEW PASSWORD
    Route::post('/change-password', [ChangePasswordController::class, 'update'])
        ->name('change.password.update');

        Route::get('/applicant/dashboard', function () {
        return "Applicant Dashboard";
    })->name('applicant.dashboard');


    Route::prefix('admin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        // Applicants list
        Route::get('/applicants', [AdminController::class, 'applicants'])
            ->name('admin.applicants');

        // View applicant details
        Route::get('/applicants/{id}', [AdminController::class, 'show'])
            ->name('admin.applicants.show');

        Route::post('/admin/applicants/{id}/status', [AdminController::class, 'updateStatus'])
        ->name('admin.applicants.status');

        Route::get('/admin/settings', [AdminController::class, 'settings'])
        ->name('admin.settings')
        ->middleware('auth');

        Route::put('/admin/scores/{id}', [AdminController::class, 'update'])
    ->name('admin.scores.update');
    });

});