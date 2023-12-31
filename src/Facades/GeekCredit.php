<?php

namespace Ageekdev\GeekCredit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ageekdev\GeekCredit\geek-credit
 */
class GeekCredit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'geek-credit';
    }
}
