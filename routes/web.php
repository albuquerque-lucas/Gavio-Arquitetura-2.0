<?php

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminSiteAssetController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicAppController;
use Illuminate\Support\Facades\Route;

// Rotas de AutenticaÃ§Ã£o
Route::get('login', [AuthenticationController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthenticationController::class, 'login']);
// Route::get('register', [AuthenticationController::class, 'showRegistrationForm'])->name('register');
// Route::post('register', [AuthenticationController::class, 'register']);
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout')->middleware('auth');

// Route::get('forgot-password', [AuthenticationController::class, 'showForgotPasswordForm'])->name('password.request');
// Route::post('forgot-password', [AuthenticationController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('reset-password/{token}', [AuthenticationController::class, 'showResetPasswordForm'])->name('password.reset');
// Route::post('reset-password', [AuthenticationController::class, 'resetPassword'])->name('password.update');

// Rotas pÃºblicas
Route::get('/', [PublicAppController::class, 'renderHomePage'])->name('public.home');
Route::get('/sobre', [PublicAppController::class, 'renderAboutUsPage'])->name('public.about.us');
Route::get('/projetos/{slug}', [PublicAppController::class, 'renderProjectsPage'])->name('public.projects');
Route::get('/projeto/{slug}', [PublicAppController::class, 'showProject'])->name('public.project.show');
Route::get('/contato', [PublicAppController::class, 'renderContactPage'])->name('public.contact.us');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// Agrupar rotas protegidas pelo middleware 'auth'
Route::middleware('auth')->group(function () {
    Route::view('/admin', 'admin.home')->name('admin.home');

    // Rotas para UsuÃ¡rios
    Route::resource('/admin/users', AdminUserController::class)->scoped([
        'user' => 'slug',
    ])->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);

    // Rotas para Projetos
    Route::delete('/admin/projetos/bulk-delete', [AdminProjectController::class, 'bulkDelete'])->name('admin.projetos.bulkDelete');
    Route::patch('/admin/projetos/reorder', [AdminProjectController::class, 'reorder'])->name('admin.projetos.reorder');
    Route::resource('/admin/projetos', AdminProjectController::class)->scoped([
        'projeto' => 'uuid',
    ])->names([
        'index' => 'admin.projetos.index',
        'create' => 'admin.projetos.create',
        'store' => 'admin.projetos.store',
        'edit' => 'admin.projetos.edit',
        'update' => 'admin.projetos.update',
        'destroy' => 'admin.projetos.destroy',
    ]);
    Route::patch('/admin/projetos/{project:uuid}/toggleCarousel', [AdminProjectController::class, 'toggleCarousel'])->name('admin.projetos.toggleCarousel');
    Route::post('/admin/projetos/{project:uuid}/add-image', [AdminProjectController::class, 'addImage'])->name('admin.projetos.addImage');
    Route::delete('/admin/projetos/bulk-delete-images/{project:uuid}', [AdminProjectController::class, 'bulkDeleteImages'])->name('admin.projetos.bulkDeleteImages');
    Route::delete('/admin/projetos/{project:uuid}/delete-image/{imageId}', [AdminProjectController::class, 'deleteImage'])->name('admin.projetos.deleteImage');

    // Rotas para Categorias
    Route::resource('/admin/categories', AdminCategoryController::class)->scoped([
        'category' => 'uuid',
    ])->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    // Rotas para AparÃªncia (assets visuais do site)
    Route::get('/admin/aparencia', [AdminSiteAssetController::class, 'edit'])->name('admin.appearance.edit');
    Route::post('/admin/aparencia', [AdminSiteAssetController::class, 'update'])->name('admin.appearance.update');
});
