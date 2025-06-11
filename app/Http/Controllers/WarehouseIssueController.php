<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseIssueController extends Controller
{
    public function index()
    {
        // $issues = WarehouseIssue::all();
        return view('warehouse.issues'/*, compact('issues')*/);
    }
}
