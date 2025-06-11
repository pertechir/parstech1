<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ساخت اولین کسب‌وکار برای کاربر
        $tenant = Tenant::create([
            'id' => uniqid(), // یا هر الگویی که دوست داری
            'data' => [
                'name' => $user->name . ' کسب‌وکار',
            ],
        ]);
        // نسبت دادن کاربر به tenant
        $user->businesses()->attach($tenant->id);

        // ایجاد دیتابیس tenant
        $tenant->createDatabase();

        Auth::login($user);
        event(new Registered($user));

        // بعد ثبت‌نام، کاربر را به صفحه ساخت کسب‌وکار جدید یا داشبورد بفرست
        return redirect()->route('business.select');
    }
}
