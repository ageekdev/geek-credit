<?php

declare(strict_types=1);

namespace Ageekdev\GeekCredit\Traits;

use Ageekdev\GeekCredit\Models\Credit;
use Ageekdev\GeekCredit\Models\CreditTransaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCredit
{
    public function credits(): MorphMany
    {
        return $this->morphMany(Credit::class, 'holder');
    }

    public function creditTransactions(): MorphMany
    {
        return $this->morphMany(CreditTransaction::class, 'holder');
    }

    public function getRemainingBalanceAttribute(): float
    {
        return $this->credits()
            ->where('expires_at', '>', now())
            ->where('remaining_balance', '>', 0)
            ->sum('remaining_balance');
    }
}
