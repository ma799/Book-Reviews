<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

    public function index(Request $request)
    {
        $title = $request->query('title');
        $filter= $request->query('filter', 'latest');
        $books = Book::when($title,
        fn($query,$title) =>$query->title($title));
        $books = match($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withCount('reviews')->withAvg('reviews', 'rating'),
        };
        // ->when($filter,
        // fn($query, $filter) => $query->filter($filter))
        // $books = $books->get();

        $cacheKey = 'books:' . $filter . ':' . $title;
        $books = cache()->remember($cacheKey,3600,fn() => $books->get());
        return view('books.index',['books' => $books]);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Book $book)
    {
        return view('books.show',
        [
            'book' => $book->load([
                'reviews' => fn($query) => $query->latest()
            ])
        ]);
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        //
    }
}
