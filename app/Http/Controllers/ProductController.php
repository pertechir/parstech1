<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Service;
use App\Models\Person;

class ProductController extends Controller
{
    /**
     * نمایش لیست محصولات
     */
    public function index()
    {
        $products = Product::with(['category', 'brand', 'shareholders'])->orderBy('id', 'desc')->paginate(20);
        $categories = Category::where('category_type', 'product')->get();
        $brands = Brand::all();
        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * فرم افزودن محصول جدید
     */
    public function create()
    {
        // پیدا کردن آخرین کد اتوماتیک product-XXX
        $lastAuto = Product::where('code', 'like', 'product-%')->orderBy('id', 'desc')->first();
        $nextNum = 1;
        if ($lastAuto && preg_match('/product-(\d+)/', $lastAuto->code, $m)) {
            $nextNum = intval($m[1]) + 1;
        }
        $default_code = 'product-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $categories = Category::where('category_type', 'product')->get();
        $brands = Brand::all();
        $units = Unit::all();
        $shareholders = Person::where('type', 'shareholder')->get();
        return view('products.create', compact('default_code', 'categories', 'brands', 'units', 'shareholders'));
    }
    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'shareholders'])->findOrFail($id);
        return view('products.show', compact('product'));
    }
    /**
     * ذخیره محصول جدید
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:255|unique:products,code',
            'category_id' => 'required|exists:categories,id',
            'sell_price'  => 'required|numeric',
            'min_stock'   => 'required|numeric|min:1',
            'brand_id'    => 'nullable|exists:brands,id',
            'unit'        => 'nullable|string|max:255',
            'stock'       => 'nullable|numeric',
            'weight'      => 'nullable|numeric',
            'buy_price'   => 'nullable|numeric',
            'discount'    => 'nullable|numeric',
            'barcode'     => 'nullable|string|max:255',
            'store_barcode' => 'nullable|string|max:255',
            'short_desc'  => 'nullable|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'video'       => 'nullable|file|mimes:mp4,avi,mov|max:10240',
        ]);

        $data = $request->only([
            'name', 'code', 'category_id', 'brand_id', 'unit', 'stock', 'min_stock', 'weight', 'buy_price',
            'sell_price', 'discount', 'barcode', 'store_barcode', 'short_desc', 'description'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('products/videos', 'public');
        }
        if (!isset($data['stock'])) $data['stock'] = 0;
        if (!isset($data['min_stock'])) $data['min_stock'] = 1;

        // اگر کد کالا به فرمت product-XXX بود، آخرین شماره را به روز کن
        if (preg_match('/^product-(\d{3,})$/', $data['code'])) {
            // هیچ کاری نیاز نیست، شمارنده در create محاسبه می‌شود
        }

        $product = Product::create($data);

        // ذخیره گالری تصاویر اگر ارسال شده
        if ($request->has('gallery')) {
            $gallery = $request->input('gallery');
            $product->gallery = is_array($gallery) ? $gallery : explode(',', $gallery);
            $product->save();
        }

        // ذخیره ویژگی‌ها اگر ارسال شده
        if ($request->has('attributes')) {
            $product->attributes = $request->input('attributes');
            $product->save();
        }

        // ذخیره سهم سهامداران
        if ($request->has('shareholder_ids')) {
            $syncData = [];
            $percents = $request->input('shareholder_percents', []);
            $ids = $request->input('shareholder_ids', []);
            $totalPercent = 0;
            foreach ($ids as $id) {
                $percent = isset($percents[$id]) ? floatval($percents[$id]) : 0;
                $syncData[$id] = ['percent' => $percent];
                $totalPercent += $percent;
            }
            if (count($ids) === 1) {
                $syncData[$ids[0]] = ['percent' => 100];
            }
            elseif (count($ids) > 1 && $totalPercent < 100) {
                $remained = 100 - $totalPercent;
                $extra = $remained / count($ids);
                foreach ($ids as $id) {
                    $syncData[$id]['percent'] += $extra;
                }
            }
            $product->shareholders()->sync($syncData);
        } else {
            // اگر هیچ سهامداری انتخاب نشد، بین همه سهامداران تقسیم کن
            $allShareholders = Person::where('type', 'shareholder')->get();
            $count = $allShareholders->count();
            if ($count > 0) {
                $percent = 100 / $count;
                $syncData = [];
                foreach ($allShareholders as $sh) {
                    $syncData[$sh->id] = ['percent' => $percent];
                }
                $product->shareholders()->sync($syncData);
            }
        }

        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ثبت شد.');
    }

    /**
     * فرم ویرایش محصول
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('category_type', 'product')->get();
        $brands = Brand::all();
        $units = Unit::all();
        $shareholders = Person::where('type', 'shareholder')->get();
        return view('products.edit', compact('product', 'categories', 'brands', 'units', 'shareholders'));
    }

    /**
     * بروزرسانی محصول
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:255|unique:products,code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'sell_price'  => 'required|numeric',
            'min_stock'   => 'required|numeric|min:1',
            'brand_id'    => 'nullable|exists:brands,id',
            'unit'        => 'nullable|string|max:255',
            'stock'       => 'nullable|numeric',
            'weight'      => 'nullable|numeric',
            'buy_price'   => 'nullable|numeric',
            'discount'    => 'nullable|numeric',
            'barcode'     => 'nullable|string|max:255',
            'store_barcode' => 'nullable|string|max:255',
            'short_desc'  => 'nullable|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'video'       => 'nullable|file|mimes:mp4,avi,mov|max:10240',
        ]);

        $data = $request->only([
            'name', 'code', 'category_id', 'brand_id', 'unit', 'stock', 'min_stock', 'weight', 'buy_price',
            'sell_price', 'discount', 'barcode', 'store_barcode', 'short_desc', 'description'
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('products/videos', 'public');
        }

        if (!isset($data['stock'])) $data['stock'] = 0;
        if (!isset($data['min_stock'])) $data['min_stock'] = 1;

        $product->update($data);

        // گالری تصاویر
        if ($request->has('gallery')) {
            $gallery = $request->input('gallery');
            $product->gallery = is_array($gallery) ? $gallery : explode(',', $gallery);
            $product->save();
        }

        // ویژگی‌ها
        if ($request->has('attributes')) {
            $product->attributes = $request->input('attributes');
            $product->save();
        }

        // بروزرسانی سهم سهامداران
        if ($request->has('shareholder_ids')) {
            $syncData = [];
            $percents = $request->input('shareholder_percents', []);
            $ids = $request->input('shareholder_ids', []);
            $totalPercent = 0;
            foreach ($ids as $id) {
                $percent = isset($percents[$id]) ? floatval($percents[$id]) : 0;
                $syncData[$id] = ['percent' => $percent];
                $totalPercent += $percent;
            }
            if (count($ids) === 1) {
                $syncData[$ids[0]] = ['percent' => 100];
            }
            elseif (count($ids) > 1 && $totalPercent < 100) {
                $remained = 100 - $totalPercent;
                $extra = $remained / count($ids);
                foreach ($ids as $id) {
                    $syncData[$id]['percent'] += $extra;
                }
            }
            $product->shareholders()->sync($syncData);
        } else {
            // اگر هیچ سهامداری انتخاب نشد، بین همه سهامداران تقسیم کن
            $allShareholders = Person::where('type', 'shareholder')->get();
            $count = $allShareholders->count();
            if ($count > 0) {
                $percent = 100 / $count;
                $syncData = [];
                foreach ($allShareholders as $sh) {
                    $syncData[$sh->id] = ['percent' => $percent];
                }
                $product->shareholders()->sync($syncData);
            }
        }

        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ویرایش شد.');
    }

    /**
     * حذف محصول
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'محصول با موفقیت حذف شد.');
    }

    /**
     * Ajax list for products with category filter and search.
     */
    public function ajaxList(Request $request)
    {
        $query = Product::with('category')
            ->whereHas('category', function($q) {
                $q->where('category_type', 'product');
            });

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function($q) use ($search){
                $q->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }

        $products = $query->limit($request->input('limit', 10))->get();

        $data = $products->map(function($item){
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'image' => $item->image,
                'stock' => $item->stock,
                'sell_price' => $item->sell_price,
                'category' => $item->category->name ?? '-',
                'category_type' => $item->category->category_type ?? '-',
            ];
        });
        return response()->json($data);
    }

    /**
     * دریافت اطلاعات یک محصول (برای افزودن به سبد خرید)
     */
    public function itemInfo(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');

        if ($type === 'product') {
            $product = Product::with('category')->findOrFail($id);
            return response()->json([
                'id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'image' => $product->image,
                'stock' => $product->stock,
                'sell_price' => $product->sell_price,
                'category' => $product->category->name ?? '-',
                'unit' => $product->unit ?? '-',
            ]);
        } elseif ($type === 'service') {
            $service = Service::with('category')->findOrFail($id);
            return response()->json([
                'id' => $service->id,
                'code' => $service->service_code,
                'name' => $service->title,
                'image' => null,
                'stock' => 1,
                'sell_price' => $service->price,
                'category' => $service->category ? $service->category->name : '-',
                'unit' => $service->unit ?? '-',
                'description' => $service->short_description ?? $service->description,
            ]);
        }

        return response()->json(['error' => 'Invalid type'], 400);
    }
}
