<?php

namespace Ageekdev\GeekCredit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $credit_transaction_id
 * @property int $credit_id
 * @property float $amount
 * @property-read ?\Illuminate\Support\Carbon $created_at
 * @property-read ?\Illuminate\Support\Carbon $updated_at
 */
class CreditTransactionDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'credit_transaction_id',
        'credit_id',
        'amount',
    ];

    public function credit(): BelongsTo
    {
        return $this->belongsTo(Credit::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CreditTransaction::class);
    }
}
