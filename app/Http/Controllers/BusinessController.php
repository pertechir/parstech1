<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Business;
use Stancl\Tenancy\Database\Models\Tenant;

class BusinessController extends Controller
{
    // لیست کسب‌وکارهای کاربر جاری
    public function index()
    {
        $businesses = Auth::user()->businesses;
        return view('businesses.index', compact('businesses'));
    }

    // نمایش فرم ایجاد کسب‌وکار جدید
    public function create()
    {
        return view('businesses.create');
    }

    // ذخیره کسب‌وکار جدید و ساخت دیتابیس tenant
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:businesses',
        ]);

        // ساخت tenant و دیتابیس جدید (اتوماتیک با پکیج tenancy)
        $tenant = Tenant::create([
            'id' => uniqid('business_'),
            'data' => [
                'name' => $request->name,
                'user_id' => Auth::id(),
            ],
        ]);
        // اینجا جداول tenant اتوماتیک ساخته می‌شوند (اگر config تنظیم باشد)

        // اتصال کسب‌وکار به کاربر
        $business = Business::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
            'tenant_id' => $tenant->id,
        ]);

        return redirect()->route('businesses.index')->with('success', 'کسب‌وکار جدید ایجاد شد.');
    }

    // سوییچ به یک کسب‌وکار (tenant)
    public function switch($id)
    {
        $business = Business::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        session(['tenant_id' => $business->tenant_id]);

        return redirect('/dashboard')->with('success', 'به کسب‌وکار ' . $business->name . ' سوییچ شد.');
    }
}
