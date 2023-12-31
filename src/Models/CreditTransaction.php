<?php

declare(strict_types=1);

namespace Ageekdev\GeekCredit\Models;

use Ageekdev\GeekCredit\Enums\CreditTransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property CreditTransactionType $type
 */
class CreditTransaction extends Model
{
    protected $fillable = [
        'holder_type',
        'holder_id',
        'amount',
        'type',
        'name',
        'description',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'type' => CreditTransactionType::class,
    ];

    public function holder(): MorphTo
    {
        return $this->morphTo();
    }

    public function details(): HasMany
    {
        return $this->hasMany(CreditTransactionDetail::class);
    }

    public function isOut(): bool
    {
        return $this->type->isOut();
    }

    public function isIn(): bool
    {
        return $this->type->isIn();
    }
}
