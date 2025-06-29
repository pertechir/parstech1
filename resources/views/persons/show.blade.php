@extends('layouts.app')

@section('title', 'جزئیات ' . $person->full_name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<style>
.info-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    padding: 20px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.info-card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.status-badge {
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
}

.chart-container {
    position: relative;
    height: 300px;
    margin-bottom: 20px;
}

.kpi-card {
    text-align: center;
    padding: 15px;
    border-radius: 10px;
    background: linear-gradient(45deg, #f8f9fa, #ffffff);
    border: 1px solid #e9ecef;
}

.kpi-card .value {
    font-size: 24px;
    font-weight: bold;
    margin: 10px 0;
}

.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    padding: 15px;
    border-right: 2px solid #e9ecef;
    position: relative;
    margin-bottom: 15px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    right: -9px;
    top: 20px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #4e73df;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- پیام‌های سیستم -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- سربرگ صفحه -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    {{ $person->full_name }}
                    <span class="badge bg-{{ $person->type == 'shareholder' ? 'primary' : 'danger' }}">
                        {{ $person->type == 'shareholder' ? 'سهامدار' : 'مشتری' }}
                    </span>
                </h1>
                <p class="text-muted mb-0">
                    <i class="fas fa-fingerprint me-1"></i>
                    کد: {{ $person->accounting_code }}
                </p>
            </div>
            <div class="text-muted">
                <small>
                    <i class="fas fa-clock me-1"></i>
                    {{ jdate(now())->format('Y/m/d H:i:s') }}
                </small>
                <br>
                <small>
                    <i class="fas fa-user me-1"></i>
                    {{ auth()->user()->name }}
                </small>
            </div>
        </div>
    </div>

    <!-- آمار کلی -->
    <div class="row mb-4">
        <!-- کل خرید -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">کل خرید</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalStats['total_amount']) }} تومان
                            </div>
                            <div class="text-xs text-success mt-1">
                                پرداخت شده: {{ number_format($totalStats['total_paid']) }} تومان
                            </div>
                            @if($totalStats['remaining'] > 0)
                                <div class="text-xs text-danger">
                                    باقیمانده: {{ number_format($totalStats['remaining']) }} تومان
                                </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- آمار محصولات -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">خرید محصولات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($productStats->total_amount ?? 0) }} تومان
                            </div>
                            <div class="text-xs mt-1">
                                تعداد خرید: {{ $productStats->purchase_count ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- آمار خدمات -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">خرید خدمات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($serviceStats->total_amount ?? 0) }} تومان
                            </div>
                            <div class="text-xs mt-1">
                                تعداد خرید: {{ $serviceStats->purchase_count ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cogs fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- میانگین خرید -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">میانگین خرید</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($averageOrderValue) }} تومان
                            </div>
                            <div class="text-xs mt-1">
                                تعداد کل خرید: {{ $totalPurchases }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- نمودارها -->
    <div class="row mb-4">
        <!-- نمودار روند خرید -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">روند خرید</h6>
                        <select class="form-select form-select-sm" id="trendPeriodSelect" style="width: auto">
                            <option value="5_days">5 روز گذشته</option>
                            <option value="1_month" selected>1 ماه گذشته</option>
                            <option value="3_months">3 ماه گذشته</option>
                            <option value="6_months">6 ماه گذشته</option>
                            <option value="1_year">1 سال گذشته</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="purchasesTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- نمودار نسبت محصولات و خدمات -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">نسبت محصولات و خدمات</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="purchaseTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تب‌ها -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#transactions">
                <i class="fas fa-history me-1"></i>تراکنش‌ها
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#products">
                <i class="fas fa-box me-1"></i>محصولات و خدمات
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#payments">
                <i class="fas fa-money-check me-1"></i>پرداخت‌ها
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#notes">
                <i class="fas fa-sticky-note me-1"></i>یادداشت‌ها
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <!-- تب تراکنش‌ها -->
        <div class="tab-pane fade show active" id="transactions">
            <div class="card">
                <div class="card-header">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="transaction-search" placeholder="جستجو در فاکتورها...">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control daterange" placeholder="بازه زمانی">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="status-filter">
                                <option value="">همه وضعیت‌ها</option>
                                <option value="paid">پرداخت شده</option>
                                <option value="pending">در انتظار پرداخت</option>
                                <option value="cancelled">لغو شده</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="filter-transactions">
                                <i class="fas fa-search me-1"></i>جستجو
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>شماره فاکتور</th>
                                    <th>تاریخ</th>
                                    <th>وضعیت</th>
                                    <th>مبلغ کل</th>
                                    <th>پرداخت شده</th>
                                    <th>مانده</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}" class="text-decoration-none">
                                            {{ $sale->invoice_number }}
                                        </a>
                                    </td>
                                    <td>{{ jdate($sale->created_at)->format('Y/m/d H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $sale->status == 'paid' ? 'success' :
                                            ($sale->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ $sale->status == 'paid' ? 'پرداخت شده' :
                                               ($sale->status == 'pending' ? 'در انتظار پرداخت' : 'لغو شده') }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($sale->total_amount) }} تومان</td>
                                    <td>{{ number_format($sale->paid_amount) }} تومان</td>
                                    <td>
                                        @if($sale->remaining_amount > 0)
                                            <span class="text-danger">
                                                {{ number_format($sale->remaining_amount) }} تومان
                                            </span>
                                        @else
                                            <span class="text-success">تسویه شده</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('sales.show', $sale) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               data-bs-toggle="tooltip"
                                               title="مشاهده">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-success"
                                                    onclick="showPaymentModal({{ $sale->id }})"
                                                    data-bs-toggle="tooltip"
                                                    title="ثبت پرداخت">
                                                <i class="fas fa-money-bill"></i>
                                            </button>
                                            <a href="{{ route('sales.print', $sale) }}"
                                               class="btn btn-sm btn-outline-info"
                                               target="_blank"
                                               data-bs-toggle="tooltip"
                                               title="چاپ">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $sales->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- تب محصولات و خدمات -->
        <!-- در تب محصولات و خدمات -->
        <div class="tab-pane fade" id="products">
            <div class="row">
                <!-- محصولات -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">محصولات خریداری شده</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>کد محصول</th>
                                            <th>نام محصول</th>
                                            <th>تعداد خرید</th>
                                            <th>مبلغ کل</th>
                                            <th>آخرین خرید</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topProducts as $product)
                                        <tr>
                                            <td>
                                                <a href="{{ route('products.show', $product->id) }}" class="text-primary">
                                                    {{ $product->code ?? 'بدون کد' }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('products.show', $product->id) }}" class="text-primary">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td>{{ $product->total_quantity }}</td>
                                            <td>{{ number_format($product->total_amount) }} تومان</td>
                                            <td>{{ $product->last_purchase ? jdate($product->last_purchase)->ago() : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- خدمات -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">خدمات خریداری شده</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>کد خدمت</th>
                                            <th>نام خدمت</th>
                                            <th>تعداد خرید</th>
                                            <th>مبلغ کل</th>
                                            <th>آخرین خرید</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topServices as $service)
                                        <tr>
                                            <td>
                                                <a href="{{ route('products.show', $service->id) }}" class="text-primary">
                                                    {{ $service->code ?? 'بدون کد' }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('products.show', $service->id) }}" class="text-primary">
                                                    {{ $service->name }}
                                                </a>
                                            </td>
                                            <td>{{ $service->total_quantity }}</td>
                                            <td>{{ number_format($service->total_amount) }} تومان</td>
                                            <td>{{ $service->last_purchase ? jdate($service->last_purchase)->ago() : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- تب پرداخت‌ها -->
        <div class="tab-pane fade" id="payments">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">تاریخچه پرداخت</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>تاریخ</th>
                                            <th>روش پرداخت</th>
                                            <th>مبلغ</th>
                                            <th>وضعیت</th>
                                            <th>توضیحات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                        <tr>
                                            <td>
                                                @if(isset($payment['paid_at']) && $payment['paid_at'])
                                                    {{ jdate($payment['paid_at'])->format('Y/m/d') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $payment['method'] ?? '-' }}</td>
                                            <td>{{ number_format($payment['amount'] ?? 0) }} تومان</td>
                                            <td>
                                                @if(isset($payment['status']))
                                                    <span class="badge bg-{{ $payment['status'] == 'paid' ? 'success' : 'warning' }}">
                                                        {{ $payment['status'] == 'paid' ? 'پرداخت شده' : 'در انتظار تایید' }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $payment['description'] ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                هیچ پرداختی ثبت نشده است.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <h5 class="mb-3">وضعیت پرداخت</h5>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>کل خرید:</span>
                                <strong class="text-primary">
                                    {{ number_format($totalStats['total_amount']) }} تومان
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>کل پرداختی:</span>
                                <strong class="text-success">
                                    {{ number_format($totalStats['total_paid']) }} تومان
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>مانده حساب:</span>
                                <strong class="text-{{ $totalStats['remaining'] > 0 ? 'danger' : 'success' }}">
                                    {{ number_format(abs($totalStats['remaining'])) }} تومان
                                    {{ $totalStats['remaining'] > 0 ? 'بدهکار' : 'بستانکار' }}
                                </strong>
                            </div>
                        </div>

                        @if(count($pendingCheques) > 0)
                        <hr>
                        <h6 class="mb-3">چک‌های در جریان</h6>
                        <div class="timeline">
                            @foreach($pendingCheques as $cheque)
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between">
                                    <span>{{ number_format($cheque->amount) }} تومان</span>
                                    <small>{{ jdate($cheque->due_date)->format('Y/m/d') }}</small>
                                </div>
                                <small class="text-muted d-block">
                                    {{ $cheque->bank ?? '-' }} - {{ $cheque->number ?? '-' }}
                                </small>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- تب یادداشت‌ها -->
        <div class="tab-pane fade" id="notes">
            <div class="row">
                <div class="col-md-8">
                    <div class="timeline">
                        @if(count($notes) > 0)
                            @foreach($notes as $note)
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>{{ $note->user->name }}</strong>
                                    <small class="text-muted">
                                        {{ jdate($note->created_at)->ago() }}
                                    </small>
                                </div>
                                <p class="mb-0">{{ $note->content }}</p>
                            </div>
                            @endforeach
                        @else
                            <p class="text-muted">هیچ یادداشتی ثبت نشده است.</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <h5 class="mb-3">افزودن یادداشت جدید</h5>
                        <form action="{{ route('person.notes.store', $person) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="content" rows="3" class="form-control @error('content') is-invalid @enderror"
                                          placeholder="یادداشت خود را بنویسید...">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                افزودن یادداشت
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال پرداخت -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ثبت پرداخت جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('payments.store') }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="sale_id" id="payment-sale-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">مبلغ (تومان)</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">روش پرداخت</label>
                        <select name="method" class="form-select" required>
                            <option value="cash">نقدی</option>
                            <option value="card">کارت به کارت</option>
                            <option value="cheque">چک</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">توضیحات</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">ثبت پرداخت</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- اول jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- بعد moment -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<!-- بعد moment-jalaali -->
<script src="https://cdn.jsdelivr.net/npm/moment-jalaali@0.9.2/build/moment-jalaali.js"></script>
<!-- بعد daterangepicker -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<!-- و در نهایت Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// تنظیمات Chart.js
Chart.defaults.font.family = 'IRANSans';
Chart.defaults.color = '#666';
Chart.defaults.responsive = true;
Chart.defaults.maintainAspectRatio = false;

// نمودار روند خرید
const purchasesTrendData = @json($purchaseTrends);
let currentTrendChart;

function updateTrendChart(period) {
    const data = purchasesTrendData[period];
    const ctx = document.getElementById('purchasesTrendChart').getContext('2d');

    if (currentTrendChart) {
        currentTrendChart.destroy();
    }

    currentTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'مبلغ کل',
                    data: data.amounts.total,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    fill: true
                },
                {
                    label: 'پرداخت شده',
                    data: data.amounts.paid,
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    rtl: true
                },
                tooltip: {
                    rtl: true,
                    titleAlign: 'right',
                    bodyAlign: 'right'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('fa-IR').format(value) + ' تومان';
                        }
                    }
                }
            }
        }
    });
}

// نمودار نسبت محصولات و خدمات
const purchaseTypeCtx = document.getElementById('purchaseTypeChart').getContext('2d');
new Chart(purchaseTypeCtx, {
    type: 'doughnut',
    data: {
        labels: ['محصولات', 'خدمات'],
        datasets: [{
            data: [
                {{ $productStats->total_amount ?? 0 }},
                {{ $serviceStats->total_amount ?? 0 }}
            ],
            backgroundColor: [
                'rgba(28, 200, 138, 0.8)', // سبز برای محصولات
                'rgba(78, 115, 223, 0.8)'   // آبی برای خدمات
            ],
            borderWidth: 1,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                rtl: true,
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                rtl: true,
                titleAlign: 'right',
                textDirection: 'rtl',
                bodyAlign: 'right',
                callbacks: {
                    label: function(context) {
                        const value = context.raw;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                        return ` ${context.label}: ${new Intl.NumberFormat('fa-IR').format(value)} تومان (${percentage}%)`;
                    }
                }
            }
        },
        cutout: '60%',
        radius: '90%'
    }
});

// تغییر دوره زمانی نمودار روند
document.getElementById('trendPeriodSelect').addEventListener('change', function() {
    updateTrendChart(this.value);
});

// تابع فرمت‌بندی اعداد
function number_format(number) {
    return new Intl.NumberFormat('fa-IR').format(number);
}

// نمایش مودال پرداخت
function showPaymentModal(saleId) {
    document.getElementById('payment-sale-id').value = saleId;
    new bootstrap.Modal(document.getElementById('paymentModal')).show();
}

// تنظیمات Date Range Picker
$('.daterange').daterangepicker({
    locale: {
        format: 'jYYYY/jMM/jDD',
        separator: ' - ',
        applyLabel: 'اعمال',
        cancelLabel: 'انصراف',
        fromLabel: 'از',
        toLabel: 'تا',
        customRangeLabel: 'بازه دلخواه',
        weekLabel: 'هفته',
        daysOfWeek: ['ی', 'د', 'س', 'چ', 'پ', 'ج', 'ش'],
        monthNames: [
            'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
            'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
        ],
        firstDay: 6
    },
    startDate: moment().subtract(29, 'days'),
    endDate: moment(),
    ranges: {
        'امروز': [moment(), moment()],
        'دیروز': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '۷ روز اخیر': [moment().subtract(6, 'days'), moment()],
        '۳۰ روز اخیر': [moment().subtract(29, 'days'), moment()],
        'این ماه': [moment().startOf('month'), moment().endOf('month')],
        'ماه قبل': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
});

// فعال‌سازی تولتیپ‌ها
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

// نمایش نمودار اولیه
updateTrendChart('1_month');
</script>
@endpush
