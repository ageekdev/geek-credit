<?php

namespace Ageekdev\GeekCredit\Enums;

enum CreditTransactionType: int
{
    case In = 1;
    case Out = 2;

    public function text(): string
    {
        return match ($this) {
            self::In => 'In',
            self::Out => 'Out',
        };
    }

    public function isOut(): bool
    {
        return $this === self::Out;
    }

    public function isIn(): bool
    {
        return $this === self::In;
    }
}
