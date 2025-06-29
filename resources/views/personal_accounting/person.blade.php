@extends('layouts.app')

@section('title', 'حسابداری ' . $person->first_name . ' ' . $person->last_name)

@section('styles')
<style>
    .profile-header { background: #f5f8fb; border-radius: 1rem; padding: 2rem 1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 2rem;}
    .profile-avatar { width: 80px; height: 80px; background: #e7eaf0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #6366f1; font-weight: bold;}
    .profile-info { flex:1; }
    .profile-balance { font-size: 1.5rem; font-weight: bold;}
    .profile-balance.positive { color: #16a34a; }
    .profile-balance.negative { color: #dc2626; }
    .profile-balance.zero { color: #666;}
    .trx-table th, .trx-table td { vertical-align: middle; text-align: center;}
    .trx-type-income { color: #16a34a; font-weight: bold;}
    .trx-type-expense, .trx-type-pay, .trx-type-debt { color: #dc2626; font-weight: bold;}
    .trx-type-credit, .trx-type-receive { color: #0ea5e9; font-weight: bold;}
    .action-btns { display: flex; gap: 6px; justify-content: center;}
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

    <!-- لیست تراکنش‌ها -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <b>تراکنش‌های مالی</b>
        </div>
        <div class="card-body p-0">
            @if($person->transactions->count())
                <table class="table table-striped mb-0 trx-table">
                    <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>نوع</th>
                            <th>مبلغ</th>
                            <th>توضیحات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($person->transactions->sortByDesc('created_at') as $trx)
                            <tr>
                                <td>{{ jdate($trx->created_at)->format('Y/m/d H:i') }}</td>
                                <td class="trx-type-{{ $trx->type }}">
                                    @switch($trx->type)
                                        @case('income')   دریافتی @break
                                        @case('expense')  هزینه/خرج @break
                                        @case('receive')  دریافت قرض @break
                                        @case('pay')      پرداخت قرض @break
                                        @case('debt')     بدهی جدید @break
                                        @case('credit')   طلب جدید @break
                                        @default {{ $trx->type }}
                                    @endswitch
                                </td>
                                <td>{{ number_format($trx->amount) }}</td>
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
                    هیچ تراکنشی ثبت نشده است.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
