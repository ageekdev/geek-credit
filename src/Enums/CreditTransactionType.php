<?php

namespace Ageekdev\GeekCredit\Enums;

enum CreditTransactionType: int
{
    case In = 1;
    case Out = 2;

    public function text(): string
    {
        return match ($this->value) {
            self::In => 'In',
            self::Out => 'Out',
        };
    }

    public function isOut(): bool
    {
        return $this->value === self::Out;
    }

    public function isIn(): bool
    {
        return $this->value === self::In;
    }
}
