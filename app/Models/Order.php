<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'shipping_address',
        'payment_method',
        'payment_status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    /**
     * Formatted total amount attribute.
     */
    protected function formattedTotal(): Attribute
    {
        return Attribute::make(
            get: fn (): string => 'Rp ' . number_format((float) $this->total_amount, 0, ',', '.'),
        );
    }

    /**
     * Customer who placed this order.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order items belonging to this order.
     *
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Products purchased in this order via order_items.
     *
     * @return BelongsToMany<Product, $this>
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot(['quantity', 'unit_price', 'subtotal'])
            ->withTimestamps();
    }

    /**
     * Scope orders by status.
     *
     * @param Builder<Order> $query
     * @return Builder<Order>
     */
    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to only completed orders.
     *
     * @param Builder<Order> $query
     * @return Builder<Order>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope to recent orders ordered by creation date.
     *
     * @param Builder<Order> $query
     * @return Builder<Order>
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->latest();
    }
}
