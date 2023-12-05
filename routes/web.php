<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'products' => ProductController::class,
]);

Route::controller(ProductController::class)->prefix('product')->group(function () {
    Route::get('', 'index')->name('product');
    Route::get('tambah', 'create')->name('product.tambah');
    Route::post('tambah', 'simpan')->name('product.tambah.simpan');
    Route::get('edit/{id}', 'edit')->name('product.edit');
    Route::post('edit/{id}', 'update')->name('product.tambah.update');
    Route::get('hapus/{id}', 'hapus')->name('product.hapus');
});
