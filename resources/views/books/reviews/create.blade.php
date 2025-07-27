@extends('layouts.app')
@section('content')

<h1 class="text-2xl mb-4">{{ $book->title }}</h1>

<form action="{{ route('books.reviews.store',['book' => $book]) }}" method="POST">

    @csrf

    <label for="review">Review</label>
    <textarea name="review" id="review" class="input mt-1" required></textarea>
    @error('review')
    <div class="text-red-500 text-sm mb-4 w-full text-end">{{ $message }}</div>
    @enderror
    <label for="rating">Rating</label>
    <select name="rating" id="rating" class="input mb-4 mt-1" required>
        <option value="" disabled selected>Select a rating</option>
        @for ($i = 1; $i <= 5; $i++)
        <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
        @endfor

    </select>

    <button type="submit" class="btn">Submit</button>

</form>


@endsection
