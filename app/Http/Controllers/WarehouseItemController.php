<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;

class WarehouseItemController extends Controller
{
    public function index(Warehouse $warehouse)
    {
        $items = $warehouse->items()->with('product')->get();
        return view('warehouse.items', compact('warehouse', 'items'));
    }
}
