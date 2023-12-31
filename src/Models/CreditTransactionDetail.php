<?php

namespace Ageekdev\GeekCredit\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransactionDetail extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
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
