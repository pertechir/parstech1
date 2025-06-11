<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse; // حتماً این خط را اضافه کن

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        // جستجو و فیلتر نمونه
        $query = Warehouse::query();

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                  ->orWhere('code', 'like', "%{$request->q}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active' ? 1 : 0);
        }

        // رابطه‌ها و شمارش کالاها
        $warehouses = $query->with(['branch', 'manager'])
            ->withCount('items')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('warehouse.index', compact('warehouses'));
    }
    public function create()
    {
        $products = \App\Models\Product::all();
        return view('warehouse.create', compact('products'));
    }
    public function store(Request $request)
    {
        $warehouse = Warehouse::create($request->only([
            'name', 'code', 'branch_id', 'icon', 'is_active', 'manager_id', 'total_stock', 'min_stock', 'description'
        ]));

        // فرض بر اینکه مدل Item وجود دارد و هر انبار با محصولات از طریق Item رابطه دارد
        foreach (\App\Models\Product::all() as $product) {
            $warehouse->items()->create([
                'product_id' => $product->id,
                'stock' => 0, // مقدار اولیه موجودی
                // سایر فیلدهای لازم را اینجا اضافه کن
            ]);
        }

        return redirect()->route('warehouses.index')->with('success', 'انبار با موفقیت ایجاد شد و همه محصولات به آن اضافه شدند.');
    }
}
