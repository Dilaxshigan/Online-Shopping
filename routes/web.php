<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index'); 
});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index'); 

    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/add-brand', [AdminController::class, 'add_brand'])->name('admin.add_brand');
    Route::post('/admin/store-brand', [AdminController::class, 'store_brand'])->name('admin.store_brand');
    Route::get('/admin/edit-brand/{id}', [AdminController::class, 'edit_brand'])->name('admin.edit_brand');
    Route::put('/admin/update-brand', [AdminController::class, 'update_brand'])->name('admin.update_brand');
    Route::delete('/admin/{id}/delete', [AdminController::class, 'delete_brand'])->name('admin.delete_brand');

    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/add-category', [AdminController::class, 'add_category'])->name('admin.add_category');
    Route::post('/admin/store-category', [AdminController::class, 'store_category'])->name('admin.store_category');
    Route::get('/admin/edit-category/{id}', [AdminController::class, 'edit_category'])->name('admin.edit_category');
    Route::put('/admin/update-category', [AdminController::class, 'update_category'])->name('admin.update_category');
});