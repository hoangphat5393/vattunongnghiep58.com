<?php

namespace App\Models\Backend;

/**
 * Backward-compatible alias for Currency model.
 */
class ShopCurrency extends Currency
{
    public $table = 'currencies';
}
