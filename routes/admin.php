<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
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
    });

    Route::middleware('admin')->group(function () {
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
            Route::get('/', 'edit')->name('edit');
            Route::put('/', 'update')->name('update');
            Route::put('password', 'updatePassword')->name('password');
        });

        // Site settings (admins only)
        Route::controller(SettingController::class)->prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'edit')->middleware('permission:view_settings')->name('edit');
            Route::put('/', 'update')->middleware('permission:edit_settings')->name('update');
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
