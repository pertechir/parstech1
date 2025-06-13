<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;

class BusinessController extends Controller
{
    public function modal()
    {
        $provinces = Province::all();
        return view('businesses.modal', compact('provinces'));
    }
}
