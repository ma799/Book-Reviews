<?php

namespace App\Models;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query
            ->withCount([
                'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
            ])
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query
            ->withAvg([
                'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
            ], 'rating')
            // ->withCount([
            //     'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
            // ])
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }


    // public function scopeFilter(Builder $query, ?string $filter = null): Builder|QueryBuilder
    // {
    //     if ($filter === 'popular_last_month') {
    //         return $query->popular($from = now()->subMonth(), $to = now())
    //             ->highestRated($from = now()->subMonth(), $to = now())
    //             ->minReviews(2);
    //     } elseif ($filter === 'popular_last_6months') {
    //         return $query->popular($from = now()->subMonths(6), $to = now())
    //             ->highestRated($from = now()->subMonths(6), $to = now())
    //             ->minReviews(5);
    //     } elseif ($filter === 'highest_rated_last_month') {
    //         return $query->highestRated($from = now()->subMonth(), $to = now())
    //             ->popular($from = now()->subMonth(), $to = now())
    //             ->minReviews(2);
    //     } elseif ($filter === 'highest_rated_last_6months') {
    //         return $query->highestRated($from = now()->subMonths(6), $to = now())
    //             ->popular($from = now()->subMonths(6), $to = now())
    //             ->minReviews(5);
    //     }
    //     return $query->latest('created_at')->highestRated()->popular();
    // }


    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);
    }



    private function dateRangeFilter(Builder $query, ?string $from = null, ?string $to = null): Builder|QueryBuilder
    {
        // Validate dates
        if ($from && $to && $from > $to) {
            // Option 1: Swap dates if wrong order
            [$from, $to] = [$to, $from];

            // Option 2: Return empty results for invalid range
            // $query->whereRaw('1 = 0');
            // return $query;
        }

        if ($from && !$to) {
            return $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            return $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            return $query->whereBetween('created_at', [$from, $to]);
        }

        return $query;
    }
}
