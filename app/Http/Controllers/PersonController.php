<?php

namespace App\Http\Controllers;

use Morilog\Jalali\Jalalian;
use App\Models\Person;
use Illuminate\Http\Request;
use Exception;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\CustomerPurchase;

class PersonController extends Controller
{


    public function getCities($province_id)
    {
        try {
            $cities = \App\Models\City::where('province_id', $province_id)
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
                ->map(function($city) {
                    return [
                        'id' => $city->id,
                        'text' => $city->name
                    ];
                });

            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => 'خطا در دریافت شهرها'], 500);
        }
    }
    public function nextCode()
    {
        try {
            // فقط کدهایی که دقیقا persons=عدد هستند (بدون منفی و بدون حروف اضافی)
            $lastPerson = Person::whereRaw("accounting_code REGEXP '^persons=[0-9]+$'")
                ->orderByRaw('CAST(SUBSTRING(accounting_code, 9) AS UNSIGNED) DESC')
                ->first();

            $nextNumber = 1001;
            if ($lastPerson && preg_match('/^persons=(\d+)$/', $lastPerson->accounting_code, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            }

            // اطمینان از یکتا بودن کد در صورت وجود کد مشابه دیگر
            $nextCode = 'persons=' . $nextNumber;
            while(Person::where('accounting_code', $nextCode)->exists()) {
                $nextNumber++;
                $nextCode = 'persons=' . $nextNumber;
            }

            return response()->json([
                'success' => true,
                'code' => $nextCode,
                'last_code' => $lastPerson ? $lastPerson->accounting_code : null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در تولید کد حسابداری',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {
        // Query Builder اصلی
        $query = Person::query();

        // جستجو
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('accounting_code', 'like', "%{$search}%");
            });
        }

        // فیلتر نوع
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // فیلتر وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فیلتر تاریخ
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                try {
                    $start = \Carbon\Carbon::createFromFormat('Y/m/d', trim($dates[0]))->startOfDay();
                    $end = \Carbon\Carbon::createFromFormat('Y/m/d', trim($dates[1]))->endOfDay();
                    $query->whereBetween('created_at', [$start, $end]);
                } catch (\Exception $e) {
                    // در صورت خطا در تاریخ، فیلتر را نادیده بگیر
                }
            }
        }

        // آمار کلی
        $totalPersons = Person::count();
        $totalTransactions = Person::sum('total_sales'); // یا هر فیلد دیگری که مجموع معاملات رو نشون میده
        $activeCustomers = Person::where('type', 'customer')
                               ->where('status', 'active')
                               ->count();

        // *** این خط را اضافه کن ***
        $debtorsCount = Person::where('balance', '>', 0)->count();

        // دریافت لیست اشخاص با اطلاعات مالی
        $persons = $query->latest()
                        ->paginate(10)
                        ->through(function ($person) {
                            // محاسبه نام کامل و سایر محاسبات مورد نیاز
                            return $person;
                        });

        return view('persons.index', compact(
            'persons',
            'totalPersons',
            'totalTransactions',
            'activeCustomers',
            'debtorsCount'
        ));
    }

    public function create()
    {
        $provinces = Province::all();
        // کد پیش‌فرض برای فرم (برای نمایش اولیه)
        // فقط کدهایی که با persons=- شروع می‌شوند را درنظر بگیر
        $lastPerson = Person::where('accounting_code', 'like', 'persons=%')
            ->orderByRaw('CAST(SUBSTRING(accounting_code, 9) AS UNSIGNED) DESC')
            ->first();

        if ($lastPerson && preg_match('/^persons=-(\d+)$/', $lastPerson->accounting_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1001;
        }
        $defaultCode = 'persons=-' . $nextNumber;

        return view('persons.create', compact('provinces', 'defaultCode'));
    }

    public function store(Request $request)
{
    DB::beginTransaction();
    try {
        if ($request->input('auto_code', '1') === '1') {
            $lastPerson = Person::whereRaw("accounting_code REGEXP '^persons=[0-9]+$'")
                ->orderByRaw('CAST(SUBSTRING(accounting_code, 9) AS UNSIGNED) DESC')
                ->lockForUpdate()
                ->first();

            $nextNumber = 1001;
            if ($lastPerson && preg_match('/^persons=(\d+)$/', $lastPerson->accounting_code, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            }

            $nextCode = 'persons=' . $nextNumber;
            while(Person::where('accounting_code', $nextCode)->exists()) {
                $nextNumber++;
                $nextCode = 'persons=' . $nextNumber;
            }

            $request->merge(['accounting_code' => $nextCode]);
        } else {
            if (Person::where('accounting_code', $request->accounting_code)->exists()) {
                DB::rollBack();
                return back()->withInput()
                    ->withErrors(['accounting_code' => 'این کد حسابداری قبلاً استفاده شده است.']);
            }
        }

        // سایر validationها و ایجاد شخص
        $rules = [
            'type' => 'required|in:customer,supplier,shareholder,employee',
            'province' => 'required|exists:provinces,id',
            'city' => 'required|exists:cities,id',
            'address' => 'required|string',
            'country' => 'required|string',
        ];
        if ($request->input('type') == 'supplier') {
            $rules['company_name'] = 'required|string';
        } else {
            $rules['first_name'] = 'required|string';
            $rules['last_name'] = 'required|string';
        }
        $validated = $request->validate($rules);
        $person = Person::create($request->all());

        DB::commit();
        return redirect()->route('persons.index')
            ->with('success', 'شخص جدید با موفقیت ایجاد شد.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()
            ->withErrors(['error' => $e->getMessage()]);
    }
}

    public function show(Person $person)
    {
        // آمار کلی به تفکیک محصولات و خدمات
        $productStats = $person->sales()
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('products.type', 'product')
            ->selectRaw('
                SUM(sale_items.quantity * sale_items.unit_price) as total_amount,
                COUNT(DISTINCT sales.id) as purchase_count,
                SUM(sales.paid_amount) as paid_amount
            ')
            ->first();

        $serviceStats = $person->sales()
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('products.type', 'service')
            ->selectRaw('
                SUM(sale_items.quantity * sale_items.unit_price) as total_amount,
                COUNT(DISTINCT sales.id) as purchase_count,
                SUM(sales.paid_amount) as paid_amount
            ')
            ->first();

        // روند خرید در بازه‌های مختلف
        $periods = [
            '5_days' => now()->subDays(5),
            '1_month' => now()->subMonth(),
            '3_months' => now()->subMonths(3),
            '6_months' => now()->subMonths(6),
            '1_year' => now()->subYear()
        ];

        $purchaseTrends = [];
        foreach ($periods as $key => $startDate) {
            $trend = $person->sales()
                ->where('created_at', '>=', $startDate)
                ->selectRaw('
                    DATE(created_at) as date,
                    SUM(final_amount) as total_amount,
                    SUM(paid_amount) as paid_amount
                ')
                ->groupBy('date')
                ->get();

            $purchaseTrends[$key] = [
                'labels' => $trend->pluck('date')->map(function($date) {
                    return jdate($date)->format('Y/m/d');
                }),
                'amounts' => [
                    'total' => $trend->pluck('total_amount'),
                    'paid' => $trend->pluck('paid_amount')
                ]
            ];
        }

        // محصولات و خدمات پرفروش به تفکیک
        $topProducts = $person->sales()
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('products.type', 'product')
            ->selectRaw('
                products.id,
                products.name,
                products.code,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.quantity * sale_items.unit_price) as total_amount,
                COUNT(DISTINCT sales.id) as purchase_count,
                MAX(sales.created_at) as last_purchase
            ')
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();

        $topServices = $person->sales()
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('products.type', 'service')
            ->selectRaw('
                products.id,
                products.name,
                products.code,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.quantity * sale_items.unit_price) as total_amount,
                COUNT(DISTINCT sales.id) as purchase_count,
                MAX(sales.created_at) as last_purchase
            ')
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();

        // آمار کلی
        $totalStats = [
            'total_amount' => ($productStats->total_amount ?? 0) + ($serviceStats->total_amount ?? 0),
            'total_paid' => ($productStats->paid_amount ?? 0) + ($serviceStats->paid_amount ?? 0),
            'remaining' => (($productStats->total_amount ?? 0) + ($serviceStats->total_amount ?? 0)) -
                          (($productStats->paid_amount ?? 0) + ($serviceStats->paid_amount ?? 0))
        ];

        // تراکنش‌ها با صفحه‌بندی
        $sales = $person->sales()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        // آمار اضافی مورد نیاز
        $totalPurchases = $person->sales()->count();
        $totalAmount = $person->sales()->sum('final_amount');
        $averageOrderValue = $totalPurchases > 0 ? $totalAmount / $totalPurchases : 0;

        // چک‌های در جریان
        $pendingCheques = $person->sales()
            ->whereNotNull('cheque_number')
            ->where('cheque_status', 'pending')
            ->where('cheque_due_date', '>=', now())
            ->select(['cheque_amount as amount', 'cheque_due_date as due_date',
                     'cheque_number as number', 'cheque_bank as bank'])
            ->orderBy('cheque_due_date')
            ->get();

        // تاریخچه پرداخت‌ها
        $payments = collect();
        $allPayments = $person->sales()
            ->where(function($query) {
                $query->whereNotNull('paid_at')
                      ->orWhereNotNull('cash_amount')
                      ->orWhereNotNull('card_amount')
                      ->orWhereNotNull('pos_amount')
                      ->orWhereNotNull('online_amount')
                      ->orWhereNotNull('cheque_amount');
            })
            ->get();

        foreach ($allPayments as $sale) {
            if ($sale->cash_amount > 0) {
                $payments->push([
                    'paid_at' => $sale->cash_paid_at,
                    'method' => 'نقدی',
                    'amount' => $sale->cash_amount,
                    'status' => 'paid',
                    'description' => "پرداخت نقدی برای فاکتور {$sale->invoice_number}"
                ]);
            }
            if ($sale->card_amount > 0) {
                $payments->push([
                    'paid_at' => $sale->card_paid_at,
                    'method' => 'کارت به کارت',
                    'amount' => $sale->card_amount,
                    'status' => 'paid',
                    'description' => "پرداخت کارت به کارت برای فاکتور {$sale->invoice_number}"
                ]);
            }
            if ($sale->pos_amount > 0) {
                $payments->push([
                    'paid_at' => $sale->pos_paid_at,
                    'method' => 'کارتخوان',
                    'amount' => $sale->pos_amount,
                    'status' => 'paid',
                    'description' => "پرداخت با کارتخوان برای فاکتور {$sale->invoice_number}"
                ]);
            }
            if ($sale->cheque_amount > 0) {
                $payments->push([
                    'paid_at' => $sale->cheque_received_at,
                    'method' => 'چک',
                    'amount' => $sale->cheque_amount,
                    'status' => $sale->cheque_status,
                    'description' => "چک شماره {$sale->cheque_number} برای فاکتور {$sale->invoice_number}"
                ]);
            }
        }

        $payments = $payments->sortByDesc('paid_at')->values();

        // یادداشت‌ها
        $notes = $person->notes()
            ->with('user')
            ->latest()
            ->get();

        return view('persons.show', compact(
            'person',
            'productStats',
            'serviceStats',
            'totalStats',
            'purchaseTrends',
            'topProducts',
            'topServices',
            'sales',
            'totalPurchases',
            'totalAmount',
            'averageOrderValue',
            'pendingCheques',
            'payments',
            'notes'
        ));
    }

    public function updatePercent(Request $request, Person $person)
    {
        $request->validate([
            'purchase_percent' => 'required|numeric|min:0|max:100'
        ]);

        $person->update([
            'purchase_percent' => $request->purchase_percent
        ]);

        return back()->with('success', 'درصد سهم با موفقیت به‌روزرسانی شد.');
    }


    /**
     * این متد برای ذخیره یادداشت جدید برای شخص استفاده می‌شود
     */
    public function storeNote(Request $request, Person $person)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ], [
            'content.required' => 'متن یادداشت الزامی است',
            'content.max' => 'متن یادداشت نمی‌تواند بیشتر از 1000 کاراکتر باشد'
        ]);

        try {
            $note = $person->notes()->create([
                'content' => $request->content,
                'user_id' => auth()->id()
            ]);

            return back()->with('success', 'یادداشت با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در ثبت یادداشت: ' . $e->getMessage());
        }
    }

    public function salesStats(Person $person)
    {
        $stats = [
            'total_purchases' => $person->sales()->count(),
            'total_amount' => $person->sales()->sum('final_amount'),
            'average_purchase' => $person->sales()->avg('final_amount'),
            'last_purchase_date' => $person->sales()->latest()->first()?->created_at,
            'payment_status' => [
                'paid' => $person->sales()->where('status', 'paid')->count(),
                'pending' => $person->sales()->where('status', 'pending')->count(),
                'cancelled' => $person->sales()->where('status', 'cancelled')->count()
            ]
        ];

        return response()->json($stats);
    }

    /**
     * این متد برای دریافت محصولات پرفروش شخص در API استفاده می‌شود
     */
    public function topProducts(Person $person)
    {
        $products = $person->sales()
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->selectRaw('
                products.id,
                products.name,
                COUNT(*) as purchase_count,
                SUM(sale_items.quantity) as total_quantity,
                SUM(sale_items.quantity * sale_items.unit_price) as total_amount
            ')
            ->groupBy('products.id', 'products.name')
            ->orderBy('purchase_count', 'desc')
            ->limit(10)
            ->get();

        return response()->json($products);
    }


    //ویرایش اشخاص
    public function edit(Person $person)
    {
        $provinces = Province::all();
        return view('persons.edit', compact('person', 'provinces'));
    }


    public function update(Request $request, Person $person)
    {
        $rules = [
            'type' => 'required|in:customer,supplier,shareholder,employee',
            'province' => 'required|exists:provinces,id',
            'city' => 'required|exists:cities,id',
            'address' => 'required|string',
            'country' => 'required|string',
        ];

        if ($request->input('type') == 'supplier') {
            $rules['company_name'] = 'required|string';
        } else {
            $rules['first_name'] = 'required|string';
            $rules['last_name'] = 'required|string';
        }

        $optionalFields = [
            'nickname', 'credit_limit', 'price_list', 'tax_type', 'national_code', 'economic_code',
            'registration_number', 'branch_code', 'description', 'postal_code', 'phone', 'mobile', 'fax',
            'phone1', 'phone2', 'phone3', 'email', 'website', 'birth_date', 'marriage_date', 'join_date',
            'company_name', 'title'
        ];
        foreach ($optionalFields as $field) {
            $rules[$field] = 'nullable';
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();

            // تبدیل تاریخ‌های شمسی به میلادی
            foreach (['birth_date', 'marriage_date', 'join_date'] as $dateField) {
                if ($request->has($dateField) && $request->$dateField) {
                    try {
                        $request[$dateField] = Jalalian::fromFormat('Y/m/d', $request->$dateField)->toCarbon()->toDateString();
                    } catch (\Exception $e) {
                        $request[$dateField] = null;
                    }
                }
            }

            $person->update($request->all());

            // به‌روزرسانی حساب‌های بانکی
            if ($request->has('bank_accounts')) {
                $person->bankAccounts()->delete(); // حذف حساب‌های قبلی
                $bankAccounts = [];
                foreach ($request->bank_accounts as $account) {
                    if (!empty($account['bank_name'])) {
                        $bankAccounts[] = [
                            'bank_name' => $account['bank_name'],
                            'branch' => $account['branch'] ?? null,
                            'account_number' => $account['account_number'] ?? null,
                            'card_number' => $account['card_number'] ?? null,
                            'iban' => $account['iban'] ?? null,
                        ];
                    }
                }
                if (!empty($bankAccounts)) {
                    $person->bankAccounts()->createMany($bankAccounts);
                }
            }

            DB::commit();
            return redirect()->route('persons.show', $person)->with('success', 'اطلاعات شخص با موفقیت به‌روزرسانی شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Person $person)
    {
        try {
            // بررسی وابستگی‌ها
            if ($person->sales()->exists()) {
                return back()->with('error', 'این شخص دارای فاکتور فروش است و نمی‌توان آن را حذف کرد.');
            }

            $person->bankAccounts()->delete();
            $person->notes()->delete();
            $person->delete();

            return redirect()->route('persons.index')->with('success', 'شخص با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در حذف شخص: ' . $e->getMessage());
        }
    }

    // بدهکاران
    public function debtors(Request $request)
    {
        try {
            // کوئری اصلی بدهکاران
            $query = Person::where('balance', '>', 0);

            // محاسبه آمار 6 ماه اخیر
            $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
            $monthlyStats = DB::table('persons')
                ->where('balance', '>', 0)
                ->where('created_at', '>=', $sixMonthsAgo)
                ->selectRaw('
                    DATE_FORMAT(created_at, "%Y-%m") as month,
                    SUM(balance) as total_debt,
                    COUNT(*) as debtors_count,
                    AVG(balance) as average_debt
                ')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // محاسبه آمار 6 ماه اخیر
            $chartData = [
                'labels' => [],
                'totalDebt' => [],
                'debtorsCount' => [],
                'averageDebt' => []
            ];

            foreach ($monthlyStats as $stat) {
                $jalaliDate = verta($stat->month . '-01')->format('%B');
                $chartData['labels'][] = $jalaliDate;
                $chartData['totalDebt'][] = (int)$stat->total_debt;
                $chartData['debtorsCount'][] = (int)$stat->debtors_count;
                $chartData['averageDebt'][] = (int)$stat->average_debt;
            }

            // محاسبه درصد تغییرات
            $trends = [
                'totalDebt' => 0,
                'debtorsCount' => 0,
                'averageDebt' => 0
            ];

            if ($monthlyStats->count() >= 2) {
                $current = $monthlyStats->last();
                $previous = $monthlyStats->get($monthlyStats->count() - 2);

                if ($previous->total_debt > 0) {
                    $trends['totalDebt'] = round((($current->total_debt - $previous->total_debt) / $previous->total_debt) * 100, 1);
                }
                if ($previous->debtors_count > 0) {
                    $trends['debtorsCount'] = round((($current->debtors_count - $previous->debtors_count) / $previous->debtors_count) * 100, 1);
                }
                if ($previous->average_debt > 0) {
                    $trends['averageDebt'] = round((($current->average_debt - $previous->average_debt) / $previous->average_debt) * 100, 1);
                }
            }

            // اعمال فیلترها
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%")
                      ->orWhere('mobile', 'like', "%{$request->search}%")
                      ->orWhere('accounting_code', 'like', "%{$request->search}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $totalDebt = $query->sum('balance');
            $debtorsCount = $query->count();
            $debtors = $query->paginate(15);

            // تبدیل داده‌ها به JSON
            $chartDataJson = json_encode($chartData);
            $trendsJson = json_encode($trends);

            return view('persons.debtors', compact(
                'debtors',
                'totalDebt',
                'debtorsCount',
                'chartData',
                'chartDataJson',
                'trendsJson',
                'trends'
            ));

        } catch (\Exception $e) {
            \Log::error('Error in debtors page: ' . $e->getMessage());
            return back()->withError('خطا در نمایش اطلاعات بدهکاران');
        }
    }

    // API برای جستجوی AJAX بدهکاران (برای انتخاب چندتایی و جمع بدهی)
public function debtorsAjax(Request $request)
{
    $ids = $request->input('ids', []);
    $persons = \App\Models\Person::whereIn('id', $ids)
        ->get(['id','full_name','mobile','balance','total_sales','total_purchases','accounting_code','type','status']);

    $total = $persons->sum('balance');
    return response()->json([
        'persons' => $persons,
        'total' => $total
    ]);
}



}
