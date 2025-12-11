<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Redirect;

Route::get('/', function () {
    return Redirect::to('category-search');
});

Route::get('category-search', [CategoryController::class, 'search'])->name('category.search');
