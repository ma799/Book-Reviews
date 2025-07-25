@extends('Layouts.app')
@section('content')
  <h1 class="text-2xl font-bold text-center mb-10">
    Books
  </h1>
  <form method="GET" action="{{ route('books.index') }}" class="flex space-x-2 mb-4 items-center">
    <input type="text" name="title" placeholder="search by title" value="{{ request('title') }}"
      class="input h-10" />
          <input type="text" name="filter"  value="{{ request('filter') }}" class="hidden" />
    <button type="submit" class="btn h-10">Search</button>
    <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
  </form>

  {{-- Filters --}}
  @php
      $filters = [
        '' => 'Latest',
        'popular_last_month' => 'Popular Last Month',
        'popular_last_6months' => 'Popular Last 6 Months',
        'highest_rated_last_month' => 'Highest Rated Last Month',
        'highest_rated_last_6months' => 'Highest Rated Last 6 Months',
      ]
  @endphp

    <div class="filter-container">
        @foreach ($filters as $key =>$label)
        <a href="{{ route('books.index', [...request()->query(),'filter' => $key]) }}"
            class="{{request('filter') === $key ||($key == '' && request('filter') === null)   ? 'filter-item-active' : 'filter-item' }}">{{ $label }}</a>
        @endforeach
    </div>

  <ul>
    @forelse ($books as $book)
    <li class="mb-4">
        <div class="book-item">
            <div
            class="flex flex-wrap items-center justify-between">
            <div class="w-full flex-grow sm:w-auto">
                <a href="{{ route('books.show',$book) }}" class="book-title">{{ $book->title }}</a>
                <span class="book-author">by {{ $book->author }}</span>
            </div>
            <div>
                <div class="book-rating">
                {{ number_format($book->reviews_avg_rating,1)  }}
                </div>
                <div class="book-review-count">
                out of {{ $book->reviews_count }} {{ Str::plural('review',$book->reviews_count)  }}
                </div>
            </div>
            </div>
        </div>
    </li>
    @empty
    <li class="mb-4">
    <div class="empty-book-item">
        <p class="empty-text">No books found</p>
        <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
    </div>
    </li>
    @endforelse
  </ul>


    <div class="pagination">
        {{ $books->appends(request()->query())->links() }}
    </div>

@endsection
