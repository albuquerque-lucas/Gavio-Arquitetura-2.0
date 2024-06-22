<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PublicAppController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::get('/', [PublicAppController::class, 'renderHomePage'])->name('public.home');
Route::get('/quem-somos', [PublicAppController::class, 'renderAboutUsPage'])->name('public.about.us');
Route::get('/projetos/{categoryId}', [PublicAppController::class, 'renderProjectsPage'])->name('public.projects');


// Rotas de Autenticação
Route::get('login', [AuthenticationController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthenticationController::class, 'login']);
Route::get('register', [AuthenticationController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('forgot-password', [AuthenticationController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [AuthenticationController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [AuthenticationController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [AuthenticationController::class, 'resetPassword'])->name('password.update');

// Agrupar rotas protegidas pelo middleware 'auth'
Route::middleware('auth')->group(function () {
    // Rotas para Usuários
    Route::resource('/admin/users', AdminUserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Rotas para Projetos
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
});
