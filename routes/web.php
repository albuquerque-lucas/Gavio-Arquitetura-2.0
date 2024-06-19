<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminProjectController;

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

Route::patch('/admin/projetos/{id}/toggleCarousel', [AdminProjectController::class, 'toggleCarousel'])->name('admin.projetos.toggleCarousel');
Route::post('/admin/projetos/{id}/add-image', [AdminProjectController::class, 'addImage'])->name('admin.projetos.addImage');
Route::delete('/admin/projetos/{projectId}/delete-image/{imageId}', [AdminProjectController::class, 'deleteImage'])->name('admin.projetos.deleteImage');

