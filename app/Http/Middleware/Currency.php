<?php

namespace App\Http\Middleware;

use App\Models\Backend\Currency as CurrencyModel;
use Closure;
use Illuminate\Http\Request;

class Currency
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currency = session('currency') ?? setting_option('currency');

        CurrencyModel::setCode($currency);

        return $next($request);
    }
}
