<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseStocktakingController extends Controller
{
    public function index()
    {
        // $stocktaking = WarehouseStocktaking::all();
        return view('warehouse.stocktaking'/*, compact('stocktaking')*/);
    }
}
