<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function select()
    {
        $businesses = Auth::user()->businesses;
        return view('businesses.select', compact('businesses'));
    }

    public function switch($tenant_id)
    {
        $tenant = Tenant::findOrFail($tenant_id);

        if (!Auth::user()->businesses->contains($tenant)) {
            abort(403, 'شما به این کسب‌وکار دسترسی ندارید.');
        }

        tenancy()->initialize($tenant);

        session(['tenant_id' => $tenant_id]);

        return redirect()->route('dashboard');
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
            'name' => 'required|string|max:255',
        ]);

        $tenant = Tenant::create([
            'id' => uniqid(), // یا هر الگویی که دوست داری
            'data' => [
                'name' => $request->name,
            ],
        ]);
        // نسبت دادن کاربر به tenant
        Auth::user()->businesses()->attach($tenant->id);

        // ایجاد دیتابیس و اجرای مایگریشن
        $tenant->createDatabase();
        $tenant->runMigrations();

        return redirect()->route('business.select')->with('success', 'کسب‌وکار جدید با موفقیت ایجاد شد.');
    }
}
