<?php

namespace App\Http\Middleware;

use Closure;
use Stancl\Tenancy\Middleware\InitializeTenancyByTenantId;

class SwitchTenant
{
    public function handle($request, Closure $next)
    {
        if (session()->has('tenant_id')) {
            tenancy()->initialize(session('tenant_id'));
        }
        return $next($request);
    }
}
