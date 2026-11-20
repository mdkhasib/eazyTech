<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

/* ───────────── Public website ───────────── */
Route::name('site.')->controller(SiteController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/services', 'services')->name('services');
    Route::get('/services/{slug}', 'service')->name('service');
    Route::get('/portfolio', 'portfolio')->name('portfolio');
    Route::get('/portfolio/{slug}', 'project')->name('project');
    Route::get('/about', 'about')->name('about');
    Route::get('/careers', 'careers')->name('careers');
    Route::post('/careers/apply', 'apply')->middleware('throttle:6,1')->name('apply');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContact')->middleware('throttle:6,1')->name('contact.send');
});

/* ───────────── Admin panel ───────────── */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');

    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', fn () => redirect()->route('admin.dashboard'));
        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->middleware('perm:dashboard')->name('dashboard');

        // Team & HR
        Route::middleware('perm:team')->group(function () {
            Route::resource('team', Admin\TeamMemberController::class)->except('show')->parameters(['team' => 'member']);
            Route::resource('departments', Admin\DepartmentController::class)->except('show');
        });
        Route::middleware('perm:hr')->group(function () {
            Route::get('hr', [Admin\HrController::class, 'index'])->name('hr');
            Route::resource('worklogs', Admin\WorkLogController::class)->except('show');
            Route::patch('worklogs/{id}/status', [Admin\WorkLogController::class, 'setStatus'])->name('worklogs.status');
            Route::resource('leaves', Admin\LeaveRequestController::class)->except('show');
            Route::patch('leaves/{id}/status', [Admin\LeaveRequestController::class, 'setStatus'])->name('leaves.status');
        });
        Route::middleware('perm:careers')->group(function () {
            Route::resource('jobs', Admin\JobOpeningController::class)->except('show');
            Route::resource('applications', Admin\JobApplicationController::class)->only(['index', 'edit', 'update', 'destroy']);
        });

        // Work
        Route::middleware('perm:tasks')->group(function () {
            Route::resource('tasks', Admin\TaskController::class)->except('show');
            Route::patch('tasks/{id}/move', [Admin\TaskController::class, 'move'])->name('tasks.move');
        });
        Route::resource('projects', Admin\ProjectController::class)->except('show')->middleware('perm:projects');
        Route::resource('clients', Admin\ClientController::class)->except('show')->middleware('perm:clients');

        // Website content
        Route::resource('portfolio', Admin\PortfolioController::class)->except('show')->middleware('perm:portfolio');
        Route::resource('services', Admin\ServiceController::class)->except('show')->middleware('perm:services');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show')->middleware('perm:testimonials');
        Route::middleware('perm:messages')->group(function () {
            Route::resource('messages', Admin\MessageController::class)->only(['index', 'edit', 'update', 'destroy']);
            Route::patch('messages/{id}/status', [Admin\MessageController::class, 'setStatus'])->name('messages.status');
        });

        // Access & settings
        Route::resource('users', Admin\UserController::class)->except('show')->middleware('perm:users');
        Route::resource('roles', Admin\RoleController::class)->except('show')->middleware('perm:roles');
        Route::middleware('perm:settings')->group(function () {
            Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings');
            Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
        });
    });
});
