<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AuthenticationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [AuthenticationController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthenticationController::class, 'login']);
Route::get('register', [AuthenticationController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::get('forgot-password', [AuthenticationController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthenticationController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [AuthenticationController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthenticationController::class, 'resetPassword'])->name('password.update');

// Rotas para Usuários
Route::middleware('auth')->group(function () {
    Route::resource('/admin/users', AdminUserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
});

Route::resource('/admin/projetos', AdminProjectController::class)->names([
    'index' => 'admin.projetos.index',
    'create' => 'admin.projetos.create',
    'store' => 'admin.projetos.store',
    'edit' => 'admin.projetos.edit',
    'update' => 'admin.projetos.update',
    'destroy' => 'admin.projetos.destroy',
]);

Route::patch('/admin/projetos/{id}/toggleCarousel', [AdminProjectController::class, 'toggleCarousel'])
    ->name('admin.projetos.toggleCarousel');

Route::post('/admin/projetos/{id}/add-image', [AdminProjectController::class, 'addImage'])
    ->name('admin.projetos.addImage');

Route::delete('/admin/projetos/{projectId}/delete-image/{imageId}', [AdminProjectController::class, 'deleteImage'])
    ->name('admin.projetos.deleteImage');


// Rotas para Categorias
Route::resource('/admin/categories', AdminCategoryController::class)->names([
    'index' => 'admin.categories.index',
    'create' => 'admin.categories.create',
    'store' => 'admin.categories.store',
    'edit' => 'admin.categories.edit',
    'update' => 'admin.categories.update',
    'destroy' => 'admin.categories.destroy',
]);
