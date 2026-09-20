<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\TwoFactorChallengeController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TrashController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectRequestController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\TwoFactorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Bare /admin → send users to the dashboard (or login if guests).
    Route::redirect('/', '/admin/dashboard');

    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');

        // Two-factor challenge (second login step — user is not yet authenticated).
        Route::get('two-factor-challenge', [TwoFactorChallengeController::class, 'show'])->name('two-factor.challenge');
        Route::post('two-factor-challenge', [TwoFactorChallengeController::class, 'store'])->name('two-factor.verify')->middleware('throttle:6,1');
    });

    Route::middleware('admin')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // System guide (PDF) downloads.
        Route::get('docs/{locale}', [DocumentController::class, 'download'])->name('docs.download');

        // Profile
        Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::put('/', 'update')->name('update');
            Route::put('password', 'updatePassword')->name('password');
        });

        // Two-factor authentication (self-service — any admin can manage their own)
        Route::controller(TwoFactorController::class)->prefix('two-factor')->name('two-factor.')->group(function () {
            Route::post('enable', 'enable')->name('enable');
            Route::post('confirm', 'confirm')->name('confirm');
            Route::post('recovery-codes', 'regenerate')->name('recovery-codes');
            Route::delete('/', 'disable')->name('disable');
        });

        // Site settings (admins only)
        Route::controller(SettingController::class)->prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'edit')->middleware('permission:view_settings')->name('edit');
            Route::put('/', 'update')->middleware('permission:edit_settings')->name('update');
        });

        // Full database backup & restore (super admin only)
        Route::controller(BackupController::class)->prefix('backup')->name('backup.')->middleware('role:super_admin')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('export', 'export')->name('export');
            Route::post('import', 'import')->name('import');
        });

        // Resource management
        Route::resource('services', ServiceController::class);
        Route::resource('projects', ProjectController::class);
        Route::resource('team', TeamController::class)->parameters(['team' => 'team']);
        Route::resource('posts', PostController::class)->except('show');
        Route::resource('testimonials', TestimonialController::class)->except('show');
        Route::resource('faqs', FaqController::class)->except('show');
        Route::resource('technologies', TechnologyController::class)->except('show');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('social-links', SocialLinkController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');
        Route::delete('users/{user}/reset-two-factor', [UserController::class, 'resetTwoFactor'])->name('users.reset-two-factor');

        // Media (gallery item deletion)
        Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

        // Trash (soft-deleted content: restore / permanent delete)
        Route::controller(TrashController::class)->prefix('trash')->name('trash.')->group(function () {
            Route::get('{resource}', 'index')->name('index');
            Route::patch('{resource}/{id}/restore', 'restore')->name('restore');
            Route::delete('{resource}/{id}', 'forceDelete')->name('destroy');
        });

        // Contacts (read-only + actions)
        Route::controller(ContactController::class)->prefix('contacts')->name('contacts.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('export', 'export')->name('export');
            Route::get('{contact}', 'show')->name('show');
            Route::patch('{contact}/read', 'markAsRead')->name('read');
            Route::delete('{contact}', 'destroy')->name('destroy');
        });

        // Project requests
        Route::controller(ProjectRequestController::class)->prefix('project-requests')->name('project-requests.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('export', 'export')->name('export');
            Route::get('{projectRequest}', 'show')->name('show');
            Route::get('{projectRequest}/pdf', 'exportPdf')->name('pdf');
            Route::patch('{projectRequest}/status', 'updateStatus')->name('status');
            Route::delete('{projectRequest}', 'destroy')->name('destroy');
        });
    });
});
