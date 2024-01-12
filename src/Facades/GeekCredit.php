<?php

namespace Ageekdev\GeekCredit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ageekdev\GeekCredit\GeekCredit
 */
class GeekCredit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'geek-credit';
    }
}
