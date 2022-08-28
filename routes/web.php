<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return "This is a test!";
});

Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return 'Routes cache cleared';
});

Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return 'Config cache cleared';
});

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return 'Application cache cleared';
});

Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return 'View cache cleared';
});
