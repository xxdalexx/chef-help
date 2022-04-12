<?php

use App\Http\Controllers\DevController;
use App\Http\Livewire\RecipeIndex;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('dev', [DevController::class, 'index']);

Route::get('recipes', RecipeIndex::class)->name('recipe.index');
Route::get('recipe/{recipe}', function (Recipe $recipe) {
    dump($recipe);
})->name('recipe.show');
