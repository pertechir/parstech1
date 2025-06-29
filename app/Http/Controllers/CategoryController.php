<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // لیست دسته‌بندی‌ها با زیر دسته‌ها
    public function index()
    {
        $categories = \App\Models\Category::with('childrenRecursive')->whereNull('parent_id')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $personCategories = Category::where('category_type', 'person')->get();
        $productCategories = Category::where('category_type', 'product')->get();
        $serviceCategories = Category::where('category_type', 'service')->get();

        $nextPersonCode = 'per' . (Category::where('category_type', 'person')->count() + 1001);
        $nextProductCode = 'pro' . (Category::where('category_type', 'product')->count() + 1001);
        $nextServiceCode = 'ser' . (Category::where('category_type', 'service')->count() + 1001);

        return view('categories.create', compact(
            'personCategories', 'productCategories', 'serviceCategories',
            'nextPersonCode', 'nextProductCode', 'nextServiceCode'
        ));
    }
    public function tableTree()
    {
        $categories = \App\Models\Category::with('children.children')->whereNull('parent_id')->orderBy('name')->get();
        return view('categories.table-tree', compact('categories'));
    }
    public function apiList(Request $request)
    {
        $type = $request->get('type', null);
        $query = \App\Models\Category::query();
        if ($type) {
            $query->where('category_type', $type);
        }
        return $query->orderBy('id', 'desc')->paginate(7); // هر صفحه ۷ تا
        return \App\Models\ServiceCategory::orderBy('id', 'desc')->paginate(7);
    }
    public function store(Request $request)
    {
        $category = \App\Models\Category::create([
            'name' => $request->name,
            'category_type' => 'service',
        ]);
        if($request->ajax())
            return response()->json(['id' => $category->id, 'name' => $category->name]);
        return redirect()->back();

        $request->validate([
            'name' => 'required|string|max:191',
            'code' => 'nullable|string|max:100',
            'category_type' => 'required|in:person,product,service',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'code', 'category_type', 'parent_id', 'description']);
        if (empty($data['code'])) {
            $prefix = [
                'person' => 'per',
                'product' => 'pro',
                'service' => 'ser',
            ];
            $count = Category::where('category_type', $data['category_type'])->count() + 1001;
            $data['code'] = $prefix[$data['category_type']] . $count;
        }
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ثبت شد.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $category->id)
            ->where('category_type', $category->category_type)
            ->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191',
            'code' => 'nullable|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'code', 'parent_id', 'description']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }
        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'دسته‌بندی با موفقیت بروزرسانی شد.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        foreach ($category->children as $child) {
            $child->delete();
        }
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'دسته‌بندی با موفقیت حذف شد.');
    }
}
