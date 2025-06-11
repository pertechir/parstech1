<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseReportController extends Controller
{
    public function index()
    {
        // $reports = [];
        return view('warehouse.reports'/*, compact('reports')*/);
    }
}
