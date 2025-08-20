<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\website\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\dashboard\BrandController;
use App\Http\Controllers\website\ProductController;
use App\Http\Controllers\website\BrandsController;
use App\Http\Controllers\website\CategoriesController;
use App\Http\Controllers\website\ProfileController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::view('/', 'admin.auth.login');

