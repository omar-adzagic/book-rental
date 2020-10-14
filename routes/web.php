<?php

use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\LayoutsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LayoutsController::class, 'index'])->name('homepage');

// Books routes
Route::get('/books', [LayoutsController::class, 'index'])->name('books');
Route::post('/books/{book}/rent', [BooksController::class, 'rentBook']);
Route::get('/books/{book}', [LayoutsController::class, 'showBook'])->name('show-books');

// Users routes
Route::get('/get/user-info', [WebsiteController::class, 'getUserInfo']);

Route::group(['middleware' => ['auth']], function () {
    // Profile routes
    Route::get('/profile', [LayoutsController::class, 'index'])->name('profile');

    // Books routes
    Route::get('/get/books/my', [BooksController::class, 'getBooks']);
    Route::post('/books', [BooksController::class, 'store']);
    Route::post('/books/{book}/update', [BooksController::class, 'update']);
    Route::post('/books/{book}/toggle-visibility', [BooksController::class, 'toggleBookVisibility']);
    Route::get('/my-books', [LayoutsController::class, 'index']);

    // Genres routes
    Route::get('/get/genres', [GenresController::class, 'getGenres']);

    // Authors routes
    Route::get('/get/authors', [AuthorsController::class, 'getAuthors']);

    // Users routes
    Route::post('/users/{user}/update', [ProfileController::class, 'update']);
    Route::post('/users/{user}/change-password', [ProfileController::class, 'changePassword']);
});

// Books routes
Route::get('/get/books/homepage', [BooksController::class, 'getHomepageBooks']);
Route::get('/get/books', [BooksController::class, 'getBooks']);
Route::get('/get/books/{book}', [BooksController::class, 'show']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
