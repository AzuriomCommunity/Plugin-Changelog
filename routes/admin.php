<?php

use Azuriom\Plugin\Changelog\Controllers\Admin\CategoryController;
use Azuriom\Plugin\Changelog\Controllers\Admin\UpdateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your plugin. These
| routes are loaded by the RouteServiceProvider of your plugin within
| a group which contains the "web" middleware group and your plugin name
| as prefix. Now create something great!
|
*/

Route::middleware('can:changelog.admin')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::resource('updates', UpdateController::class)->except('show');

    Route::post('/updates/update-order', [CategoryController::class, 'updateOrder'])->name('categories.update-order');
});
