<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseReceiptController extends Controller
{
    public function index()
    {
        // $receipts = WarehouseReceipt::all();
        return view('warehouse.receipts'/*, compact('receipts')*/);
    }
}
