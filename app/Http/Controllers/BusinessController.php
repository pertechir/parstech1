<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\TenantManager;

class BusinessController extends Controller
{
    // نمایش لیست کسب‌وکارهای کاربر
    public function select()
    {
        $businesses = Auth::user()->businesses;
        return view('businesses.select', compact('businesses'));
    }

    // سوییچ به کسب‌وکار انتخابی
    public function switch($tenant_id)
    {
        $tenant = Tenant::findOrFail($tenant_id);

        // اعتبارسنجی مالکیت
        if (!Auth::user()->businesses->contains($tenant)) {
            abort(403, 'شما به این کسب‌وکار دسترسی ندارید.');
        }

        // فعال‌سازی tenant
        tenancy()->initialize($tenant);

        session(['tenant_id' => $tenant_id]);

        return redirect()->route('dashboard');
    }
}
