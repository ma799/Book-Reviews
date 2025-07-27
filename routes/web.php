<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookReviewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::resource('books',BookController::class)
->only(['index','show']);

Route::resource('books.reviews',BookReviewsController::class)
->scoped(['review' => 'book'])
->only(['create','store']);

Route::post('books/{book}/reviews', [BookReviewsController::class, 'store'])
->middleware('throttle:reviews')
->name('books.reviews.store');
