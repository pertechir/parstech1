<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseTransferController extends Controller
{
    public function index()
    {
        // $transfers = WarehouseTransfer::all();
        return view('warehouse.transfers'/*, compact('transfers')*/);
    }
}
