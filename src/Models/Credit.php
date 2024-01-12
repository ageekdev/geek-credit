<?php

namespace Ageekdev\GeekCredit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $holder_type
 * @property string|int $holder_id
 * @property float $initial_balance
 * @property float $remaining_balance
 * @property array $meta
 * @property ?\Illuminate\Support\Carbon $expires_at
 * @property-read ?\Illuminate\Support\Carbon $created_at
 * @property-read ?\Illuminate\Support\Carbon $updated_at
 */
class Credit extends Model
{
    protected $fillable = [
        'holder_type',
        'holder_id',
        'initial_balance',
        'remaining_balance',
        'expires_at',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function holder(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeIsHolder(Builder $query, Model $holder): Builder
    {
        return $query->where('holder_type', $holder->getMorphClass())
            ->where('holder_id', $holder->getKey());
    }

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeHasRemainingBalance(Builder $query): Builder
    {
        return $query->where('has_remaining_balance', 1);
    }
}
