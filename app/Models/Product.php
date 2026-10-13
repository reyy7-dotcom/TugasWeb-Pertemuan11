<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'sku',
        'image_url',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Formatted price attribute (e.g. Rp 1.500.000).
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn (): string => 'Rp ' . number_format((float) $this->price, 0, ',', '.'),
        );
    }

    /**
     * Category that this product belongs to.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Order items containing this product.
     *
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Customer reviews for this product.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Orders containing this product via order_items.
     *
     * @return BelongsToMany<Order, $this>
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot(['quantity', 'unit_price', 'subtotal'])
            ->withTimestamps();
    }

    /**
     * Scope to active products only.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to products that are in stock.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope to products within a given price range.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopePriceBetween(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope to products belonging to a category.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to search products by name or description.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Scope to sort products by average review rating descending.
     *
     * @param Builder<Product> $query
     * @return Builder<Product>
     */
    public function scopeTopRated(Builder $query): Builder
    {
        return $query->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating');
    }
}
