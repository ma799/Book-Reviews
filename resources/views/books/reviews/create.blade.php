@extends('layouts.app')
@section('content')

<h1 class="text-2xl">{{ $book->title }}</h1>

<form action="{{ route('books.reviews.store',['book' => $book]) }}" method="POST">

    @csrf

    <label for="review">Review</label>
    <textarea name="review" id="review" class="input mb-4" required></textarea>

    <label for="rating">Rating</label>
    <select name="rating" id="rating" class="input mb-4" required>
        <option value="" disabled selected>Select a rating</option>
        @for ($i = 1; $i <= 5; $i++)
        <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
        @endfor

    </select>

    <button type="submit" class="btn">Submit</button>

</form>


@endsection
