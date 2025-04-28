<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'product_details'])->name('shop.product_details');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add_to_cart'])->name('cart.add');
Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'increase_cart_quantity'])->name('cart.increase_cart_quantity');
Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'decrease_cart_quantity'])->name('cart.decrease_cart_quantity');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'remove_item'])->name('cart.remove_item');
Route::delete('/cart/clear', [CartController::class, 'empty_cart'])->name('cart.empty_cart');

Route::get('/wishlist',[WishlistController::class,'index'])->name('wishlist.index');
Route::post('/wishlist/add',[WishlistController::class,'add_to_wishlist'])->name('wishlist.add');
Route::delete('/wishlist/remove/{rowId}',[WishlistController::class,'remove_item_from_wishlist'])->name('wishlist.remove');
Route::delete('/wishlist/clear',[WishlistController::class,'empty_wishlist'])->name('wishlist.empty');
Route::post('/wishlist/move-to-cart/{rowId}',[WishlistController::class,'move_to_cart'])->name('wishlist.move.to.cart');

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
    Route::delete('/admin/{id}/delete-category', [AdminController::class, 'delete_category'])->name('admin.delete_category');

    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/add-product', [AdminController::class, 'add_product'])->name('admin.add_product');
    Route::post('/admin/store-product', [AdminController::class, 'store_product'])->name('admin.store_product');
    Route::get('/admin/edit-product/{id}', [AdminController::class, 'edit_product'])->name('admin.edit_product');
    Route::put('/admin/update-product', [AdminController::class, 'update_product'])->name('admin.update_product');
    Route::delete('/admin/{id}/delete-product', [AdminController::class, 'delete_product'])->name('admin.delete_product');

    Route::get('/admin/coupons',[AdminController::class,'coupons'])->name('admin.coupons');
});