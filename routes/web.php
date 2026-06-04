<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

    Route::middleware(['auth'])->group(function () {
    
    // URL to handle saving a new task (POST)
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // URL to handle updating an existing task (POST or PUT)
    Route::post('/tasks/update/{id}', [TaskController::class, 'update'])->name('tasks.update');

    // URL to handle deleting a task (GET or DELETE)
    Route::get('/tasks/delete/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
});
    Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

    Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/update/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::get('/tasks/delete/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/myprofile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update_info');
    Route::post('/profile/update-avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update_avatar');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update_password');
    // Form submissions endpoint
    Route::post('/process-task', [TaskController::class, 'processTask'])->name('tasks.process');

    // Deletion endpoints
    Route::get('/process-task', [TaskController::class, 'processTaskGet'])->name('tasks.process.get');
});
