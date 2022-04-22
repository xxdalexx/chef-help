<?php

use App\Http\Controllers\DevController;
use App\Http\Controllers\ThemeController;
use App\Http\Livewire\IngredientIndex;
use App\Http\Livewire\IngredientShow;
use App\Http\Livewire\MenuCategoryIndex;
use App\Http\Livewire\RecipeIndex;
use App\Http\Livewire\RecipeShow;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;

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

Route::view('/', 'welcome')->name('home');

Route::get('theme/{theme}', ThemeController::class)->name('change-theme');

Route::get('dev', [DevController::class, 'index']);

Route::get('recipes', RecipeIndex::class)->name('recipe.index');
Route::get('recipe/{recipe}', RecipeShow::class)->name('recipe.show');

Route::get('ingredients', IngredientIndex::class)->name('ingredient.index');
Route::get('ingredient/{ingredient}', IngredientShow::class)->name('ingredient.show');

Route::get('menu-categories', MenuCategoryIndex::class)->name('menu-category.index');
