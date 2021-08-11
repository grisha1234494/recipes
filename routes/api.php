<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\RecipesController;
use App\Http\Controllers\Api\v1\RecipesCommentsController;

use App\Http\Controllers\Api\v1\Admin\RecipesController as AdminRecipesController;
use App\Http\Controllers\Api\v1\Admin\IngridientsController as AdminIngridientsController;
use App\Http\Controllers\Api\v1\Admin\ImagesController as AdminImagesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('sanctum')->group(function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('user', [AuthController::class, 'user']);
});

// AdminRecipes
/*middleware(['auth:sanctum', 'verified'])->*/
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function() {
    Route::resource('recipes', AdminRecipesController::class, ['as' => 'admin']);
    Route::resource('ingridients', AdminIngridientsController::class, ['as' => 'admin']);
    Route::resource('images', AdminImagesController::class, ['as' => 'admin']);
});

Route::get('recipes', [RecipesController::class, 'index'])->name('recipes.index');
Route::get('recipes/{id}', [RecipesController::class, 'show'])->name('recipes.show');
Route::middleware(['auth:sanctum'])->post('recipes/{recipe_id}/comment', [RecipesCommentsController::class, 'store'])->name('recipes.comments.create');

