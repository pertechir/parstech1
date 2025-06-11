<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseInventoryController extends Controller
{
    public function index()
    {
        // $inventory = WarehouseInventory::all();
        return view('warehouse.inventory'/*, compact('inventory')*/);
    }
}
