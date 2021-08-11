<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('vue_v2');
    })->name('admin.index');
    Route::get('{any}', function () {
        return view('vue_v2');
    })->where('any', '.*');
});
*/

Route::get('{any?}', function () {
    return view('vue_v2');
})
//->where('any', '.*')
->where('any', 'admin|admin/.*|recipes|recipes/.*|sanctum|sanctum/.*')
->name('index');
