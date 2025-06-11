<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasCompany
{
    /**
     * Handle an incoming request.
     * اگر کاربر لاگین کرده هیچ کسب و کاری ندارد به صفحه ساخت کسب و کار هدایت شود،
     * اگر کسب و کاری دارد به لیست کسب و کارهای خودش منتقل شود.
     */
    public function handle(Request $request, Closure $next)
    {
        // اگر کاربر لاگین نیست، ادامه مسیر (احتمالا middleware auth اجرا می‌شود)
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // اگر کاربر مدیر کل (admin) است، این middleware کاری نکند
        if ($user->is_admin ?? false) {
            return $next($request);
        }

        // فرض: مدل Company رابطه با User دارد (مثلا hasMany یا belongsToMany)
        $companies = $user->companies();

        // اگر مسیر فعلی صفحه ساخت کسب و کار است، ادامه مسیر
        if ($request->routeIs('companies.create') || $request->routeIs('companies.store')) {
            return $next($request);
        }

        // اگر کاربر هیچ کسب‌وکاری ندارد، به صفحه ساخت کسب و کار هدایت شود
        if ($companies->count() == 0) {
            return redirect()->route('companies.create');
        }

        // اگر کاربر کسب‌وکار دارد، به لیست کسب و کارها هدایت شود (مگر اینکه الان همانجاست)
        if (!$request->routeIs('companies.index')) {
            return redirect()->route('companies.index');
        }

        return $next($request);
    }
}
