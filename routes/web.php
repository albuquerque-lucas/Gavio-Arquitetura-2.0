<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminCategoryController;

Route::get('/', function () {
    return view('welcome');
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

// Rotas para Usuários
Route::resource('/admin/users', AdminUserController::class)->names([
    'index' => 'admin.users.index',
    'create' => 'admin.users.create',
    'store' => 'admin.users.store',
    'edit' => 'admin.users.edit',
    'update' => 'admin.users.update',
    'destroy' => 'admin.users.destroy',
]);

// Rotas para Categorias
Route::resource('/admin/categories', AdminCategoryController::class)->names([
    'index' => 'admin.categories.index',
    'create' => 'admin.categories.create',
    'store' => 'admin.categories.store',
    'edit' => 'admin.categories.edit',
    'update' => 'admin.categories.update',
    'destroy' => 'admin.categories.destroy',
]);
