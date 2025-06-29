@extends('layouts.app')

@section('title', 'حسابداری ' . $person->first_name . ' ' . $person->last_name)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/personal-accounting.css') }}">
<style>
    .profile-header { background: #f5f8fb; border-radius: 1rem; padding: 2rem 1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 2rem;}
    .profile-avatar { width: 80px; height: 80px; background: #e7eaf0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #6366f1; font-weight: bold;}
    .profile-info { flex:1; text-align:right;}
    .profile-balance { font-size: 1.5rem; font-weight: bold;}
    .profile-balance.positive { color: #16a34a; }
    .profile-balance.negative { color: #dc2626; }
    .profile-balance.zero { color: #666;}
    .stats-row {margin-bottom:2.5rem;}
    .stats-card { transition: all 0.3s; border-right: 4px solid; border-radius: 12px;}
    .stats-card:hover { transform: translateY(-5px);}
    .stats-card.income { border-right-color: #16a34a; }
    .stats-card.expense { border-right-color: #dc2626; }
    .stats-card.debt { border-right-color: #0ea5e9; }
    .stats-card.budget { border-right-color: #8b5cf6; }
    .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 1.1rem; margin-bottom: 2rem;}
    .quick-action-btn { display: flex; align-items: center; justify-content: center; padding: 1.1rem; border-radius: 10px; background: #fff; border: 1px solid #e5e7eb; transition: all 0.2s; text-decoration: none; color: inherit; font-weight: 500; font-size: 1.07rem; gap: 0.65rem;}
    .quick-action-btn:hover { background:#f3f4f6; box-shadow:0 4px 16px -2px rgba(80,80,120,0.09);}
    .quick-action-btn i { font-size: 1.45rem; margin-left: 0.5rem;}
    .trx-table th, .trx-table td { vertical-align: middle; text-align: center;}
    .trx-type-income { color: #16a34a; font-weight: bold;}
    .trx-type-expense { color: #dc2626; font-weight: bold;}
    .trx-type-receive { color: #0ea5e9; font-weight: bold;}
    .trx-type-pay { color: #6366f1; font-weight: bold;}
    .trx-type-debt { color: #b91c1c; font-weight: bold;}
    .trx-type-credit { color: #0e7490; font-weight: bold;}
    .action-btns { display: flex; gap: 6px; justify-content: center;}
    .section-title {font-size:1.2rem;font-weight:700;color:#1e293b;margin-bottom:1rem;text-align:right;display:flex;align-items:center;gap:8px;}
    .section-title i {font-size:1.1rem;}
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- هدر پروفایل -->
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="profile-info">
            <h3 class="mb-0">{{ $person->first_name }} {{ $person->last_name }}</h3>
            <div class="text-muted mb-2">شماره تماس: {{ $person->mobile ?: '-' }}</div>
            <a href="{{ route('personal_accounting.index') }}" class="btn btn-outline-secondary btn-sm">بازگشت به لیست اشخاص</a>
        </div>
        <div>
            @php
                $balance = $person->transactions->reduce(function($carry, $trx){
                    if (in_array($trx->type, ['income','receive','credit'])) return $carry + $trx->amount;
                    else return $carry - $trx->amount;
                }, 0);
            @endphp
            <div class="profile-balance
                   {{ $balance > 0 ? 'positive' : ($balance < 0 ? 'negative' : 'zero') }}">
                مانده حساب: {{ number_format($balance) }}
                <span class="fs-6 text-muted">تومان</span>
                <span>
                    @if($balance > 0)
                        (بستانکار)
                    @elseif($balance < 0)
                        (بدهکار)
                    @else
                        (تسویه)
                    @endif
                </span>
            </div>
        </div>
    </div>

    @php
        $transactions = $person->transactions;
        $totalIncome = $transactions->whereIn('type', ['income', 'receive', 'credit'])->sum('amount');
        $totalExpense = $transactions->whereIn('type', ['expense', 'pay', 'debt'])->sum('amount');
        $totalDebt = $transactions->where('type', 'debt')->sum('amount');
        $thisMonth = \Carbon\Carbon::now()->startOfMonth();
        $thisMonthTransactions = $transactions->where('created_at', '>=', $thisMonth);
        $thisMonthIncome = $thisMonthTransactions->whereIn('type', ['income', 'receive', 'credit'])->sum('amount');
        $thisMonthExpense = $thisMonthTransactions->whereIn('type', ['expense', 'pay', 'debt'])->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;
        $hasDebt = $transactions->where('type', 'debt')->where('created_at', '>=', \Carbon\Carbon::now()->subMonths(3))->count() > 0;
        $grouped = $transactions->sortByDesc('created_at')->groupBy('type');
        $typeLabels = [
            'income' => ['icon'=>'fa-arrow-down','title'=>'دریافتی','class'=>'income'],
            'expense' => ['icon'=>'fa-arrow-up','title'=>'هزینه/خرج','class'=>'expense'],
            'receive' => ['icon'=>'fa-hand-holding-usd','title'=>'دریافت قرض','class'=>'receive'],
            'pay' => ['icon'=>'fa-money-bill-wave','title'=>'پرداخت قرض','class'=>'pay'],
            'debt' => ['icon'=>'fa-exclamation-triangle','title'=>'بدهی جدید','class'=>'debt'],
            'credit' => ['icon'=>'fa-check-circle','title'=>'طلب جدید','class'=>'credit'],
        ];
    @endphp

    <!-- آمار شخص -->
    <div class="row stats-row">
        <div class="col-md-3 mb-3">
            <div class="card stats-card income">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">کل دریافتی‌ها</h6>
                    <h3 class="card-title text-success">
                        {{ number_format($totalIncome) }}
                        <small>تومان</small>
                    </h3>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-arrow-up text-success"></i>
                            {{ number_format($thisMonthIncome) }} تومان این ماه
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card expense">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">کل هزینه‌ها</h6>
                    <h3 class="card-title text-danger">
                        {{ number_format($totalExpense) }}
                        <small>تومان</small>
                    </h3>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-arrow-down text-danger"></i>
                            {{ number_format($thisMonthExpense) }} تومان این ماه
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card debt">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">مجموع بدهی‌ها</h6>
                    <h3 class="card-title text-info">
                        {{ number_format($totalDebt) }}
                        <small>تومان</small>
                    </h3>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $hasDebt ? 'دارای بدهی' : 'بدون بدهی' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card budget">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">مانده کل</h6>
                    <h3 class="card-title {{ $totalBalance > 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format(abs($totalBalance)) }}
                        <small>تومان</small>
                    </h3>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-chart-line"></i>
                            {{ $totalBalance > 0 ? 'مثبت' : 'منفی' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- دکمه‌های دسترسی سریع -->
    <div class="quick-actions mb-4">
        <a href="#" class="quick-action-btn">
            <i class="fas fa-tags text-primary"></i>
            <span>دسته‌بندی هزینه‌ها</span>
        </a>
        <a href="#" class="quick-action-btn">
            <i class="fas fa-chart-pie text-success"></i>
            <span>مدیریت بودجه</span>
        </a>
        <a href="#" class="quick-action-btn">
            <i class="fas fa-chart-bar text-warning"></i>
            <span>گزارشات</span>
        </a>
        <a href="#" class="quick-action-btn">
            <i class="fas fa-bell text-danger"></i>
            <span>یادآورها</span>
        </a>
        <a href="#" class="quick-action-btn">
            <i class="fas fa-university text-info"></i>
            <span>حساب‌های بانکی</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- فرم افزودن تراکنش -->
    <div class="card mb-4">
        <div class="card-header"><b>افزودن تراکنش جدید</b></div>
        <div class="card-body">
            <form method="POST" action="{{ route('personal_accounting.person.add_transaction', $person->id) }}">
                @csrf
                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">نوع تراکنش</label>
                        <select class="form-select" name="type" required>
                            <option value="">انتخاب کنید</option>
                            <option value="income">دریافتی</option>
                            <option value="expense">هزینه/خرج</option>
                            <option value="receive">دریافت قرض</option>
                            <option value="pay">پرداخت قرض</option>
                            <option value="debt">بدهی جدید</option>
                            <option value="credit">طلب جدید</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">مبلغ (تومان)</label>
                        <input type="number" name="amount" class="form-control" required min="1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">توضیحات</label>
                        <input type="text" name="description" class="form-control" maxlength="255">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-success w-100">ثبت تراکنش</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول‌های جداگانه برای هر نوع تراکنش -->
    @foreach($typeLabels as $type => $info)
        <div class="card shadow-sm mb-4">
            <div class="card-header section-title">
                <i class="fas {{ $info['icon'] }}"></i>
                لیست {{ $info['title'] }}
            </div>
            <div class="card-body p-0">
                @if(isset($grouped[$type]) && $grouped[$type]->count())
                    <table class="table table-striped mb-0 trx-table">
                        <thead>
                            <tr>
                                <th>تاریخ</th>
                                <th>مبلغ</th>
                                <th>توضیحات</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grouped[$type] as $trx)
                                <tr>
                                    <td>{{ jdate($trx->created_at)->format('Y/m/d H:i') }}</td>
                                    <td class="trx-type-{{ $type }}">{{ number_format($trx->amount) }}</td>
                                    <td>{{ $trx->description }}</td>
                                    <td>
                                        <form action="{{ route('personal_accounting.person.delete_transaction', [$person->id, $trx->id]) }}" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-3 text-center text-muted">
                        هیچ {{ $info['title'] }}ی ثبت نشده است.
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
