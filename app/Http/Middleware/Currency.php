<?php

namespace App\Http\Middleware;

use App\Models\Backend\ShopCurrency;

use Closure;

class Currency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currency = session('currency') ?? setting_option('currency');

        ShopCurrency::setCode($currency);
        return $next($request);
    }
}
