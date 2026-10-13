<?php

namespace App\Models;

use Database\Factories\ReviewFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /** @use HasFactory<ReviewFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'is_approved',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Customer who submitted this review.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Product that was reviewed.
     *
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope to approved reviews only.
     *
     * @param Builder<Review> $query
     * @return Builder<Review>
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to reviews with rating at or above a threshold.
     *
     * @param Builder<Review> $query
     * @return Builder<Review>
     */
    public function scopeRating(Builder $query, int $minRating): Builder
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope to recent reviews.
     *
     * @param Builder<Review> $query
     * @return Builder<Review>
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest();
    }
}
