@if ($rating)
    <div class="star-rating">
        @for ($i = 1; $i <= 5; $i++)
            <span>{{ $i <= round($rating) ? '★' : '☆' }}</span>
        @endfor
    </div>
        @else
            No rating yet
@endif
