<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/', function () {
//     return redirect()->route('login');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Movies routes
    Route::get('/movies', [MovieController::class, 'index'])->name('movies');
    Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
    Route::post('/favorites/{movieId}', [MovieController::class, 'addToFavorites'])->name('favorites.add');
    Route::get('/favorites', [MovieController::class, 'viewFavorites'])->name('favorites.view');
    Route::delete('/favorites/{movieId}', [MovieController::class, 'removeFromFavorites'])->name('favorites.remove');
    Route::get('/recommendations', [MovieController::class, 'recommendations'])->name('movies.recommendations');
});