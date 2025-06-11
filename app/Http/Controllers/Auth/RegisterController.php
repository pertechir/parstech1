<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    // نمایش فرم ثبت نام
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // پردازش ثبت نام کاربر
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // ساخت کاربر
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // ورود خودکار کاربر
        Auth::login($user);

        // ری‌دایرکت به لیست کسب‌وکارها تا کاربر اولین کسب‌وکارش را بسازد
        return redirect()->route('businesses.index')->with('success', 'ثبت‌نام با موفقیت انجام شد! حالا کسب‌وکار جدید خود را بسازید.');
    }
}
