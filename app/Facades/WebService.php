<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getSEO(array $data)
 * @method static string formatMoney12($number, $fractional = null)
 * @method static array objectToArray($object)
 * @method static string format_price($price)
 * @method static bool objectEmpty($object)
 */
class WebService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'WebService';
    }
}
