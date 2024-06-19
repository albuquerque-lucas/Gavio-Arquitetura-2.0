<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\AdminProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/admin/projetos', AdminProjectController::class)->only([
    'index', 'show'
]);
