@extends('layouts.app')

@section('title', 'لیست بدهکاران')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2.0.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">

<style>
    /* انیمیشن‌های پایه */
@keyframes slideInUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

.animate-item {
    opacity: 0;
}

.animate-in {
    animation: slideInUp 0.6s ease forwards;
}

.animate-out {
    animation: fadeOut 0.6s ease forwards;
}

/* تاخیر برای هر آیتم */
.delay-1 { animation-delay: 0.2s; }
.delay-2 { animation-delay: 0.4s; }
.delay-3 { animation-delay: 0.6s; }
.delay-4 { animation-delay: 0.8s; }
.delay-5 { animation-delay: 1s; }

.stats-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    padding: 25px;
    height: 100%;
    transition: all 0.3s ease;
    opacity: 0;
}

.filter-section {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    opacity: 0;
}

.table-section {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    opacity: 0;
}

.stats-value {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 15px 0;
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.pagination-section {
    opacity: 0;
}
.stats-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    padding: 25px;
    height: 100%;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.stats-value {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 15px 0;
    background: linear-gradient(45deg, #dc3545, #fd7e14);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stats-label {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 10px;
}

.filter-section {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.table-container {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.custom-table {
    width: 100%;
    margin-bottom: 0;
}

.custom-table thead th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 15px;
    font-weight: 600;
}

.custom-table tbody td {
    padding: 15px;
    vertical-align: middle;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-active {
    background-color: #e3fcef;
    color: #1cc88a;
}

.status-inactive {
    background-color: #f8f9fa;
    color: #6c757d;
}

.debt-amount {
    font-weight: 600;
    color: #dc3545;
}

.chart-container {
    position: relative;
    height: 200px;
    margin-top: 20px;
}

.person-detail {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.person-detail:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.person-avatar {
    width: 60px;
    height: 60px;
    background: #4e73df;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.filter-input {
    border-radius: 10px;
    border: 1px solid #e3e6f0;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.filter-input:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
}

.btn-filter {
    border-radius: 10px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    transform: translateY(-2px);
}

.pagination {
    margin-top: 20px;
    justify-content: center;
}

.pagination .page-item .page-link {
    padding: 8px 16px;
    border-radius: 8px;
    margin: 0 3px;
    color: #4e73df;
}

.pagination .page-item.active .page-link {
    background-color: #4e73df;
    border-color: #4e73df;
}

.select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #e3e6f0;
    border-radius: 10px;
}


.stats-card {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    height: 100%;
    transform-style: preserve-3d;
    perspective: 1000px;
    transition: all 0.5s ease;
    box-shadow:
        0 15px 35px rgba(0,0,0,0.1),
        0 5px 15px rgba(0,0,0,0.07);

}

.stats-content {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid rgba(0,0,0,0.05);
    position: relative;
    z-index: 2;
}

.stats-header {
    border-bottom: 2px solid rgba(0,0,0,0.05);
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.stats-title {
    font-size: 1rem;
    color: #6c757d;
    margin: 0;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.stats-value {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 10px 0;
    line-height: 1.2;
    background: linear-gradient(45deg, #2b354f, #4e73df);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
}

.stats-subtitle {
    font-size: 0.9rem;
    color: #858796;
}

.chart-wrapper {
    position: relative;
    height: 120px;
    margin-top: 20px;
    z-index: 1;
    background: rgba(255,255,255,0.5);
    border-radius: 10px;
    padding: 10px;
    box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
}

.trend-indicator {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
    margin-top: 10px;
}

.trend-up {
    color: #1cc88a;
}

.trend-down {
    color: #e74a3b;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px) rotateX(10deg);
    }
    to {
        opacity: 1;
        transform: translateY(0) rotateX(0);
    }
}


@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- آمار کلی -->
    <div class="row g-4 mb-4">
        <!-- کارت مجموع بدهی -->
        <div class="col-xl-4 col-md-6">
            <div class="stats-card animate-fade-in">
                <div class="stats-content">
                    <div class="stats-header">
                        <h6 class="stats-title">
                            <i class="fas fa-money-bill-wave text-primary"></i>
                            مجموع کل بدهی
                        </h6>
                        <div class="stats-value" data-value="{{ $totalDebt }}">
                            {{ number_format($totalDebt) }}
                        </div>
                        <div class="stats-subtitle">تومان</div>
                    </div>
                    <div class="trend-indicator trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>12.5% افزایش نسبت به ماه قبل</span>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="totalDebtChart"></canvas>
                </div>
            </div>
        </div>

        <!-- کارت تعداد بدهکاران -->
        <div class="col-xl-4 col-md-6">
            <div class="stats-card animate-fade-in" style="animation-delay: 0.2s">
                <div class="stats-content">
                    <div class="stats-header">
                        <h6 class="stats-title">
                            <i class="fas fa-users text-warning"></i>
                            تعداد بدهکاران
                        </h6>
                        <div class="stats-value" data-value="{{ $debtorsCount }}">
                            {{ number_format($debtorsCount) }}
                        </div>
                        <div class="stats-subtitle">نفر</div>
                    </div>
                    <div class="trend-indicator trend-down">
                        <i class="fas fa-arrow-down"></i>
                        <span>5.2% کاهش نسبت به ماه قبل</span>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="debtorsCountChart"></canvas>
                </div>
            </div>
        </div>

        <!-- کارت میانگین بدهی -->
        <div class="col-xl-4 col-md-6">
            <div class="stats-card animate-fade-in" style="animation-delay: 0.4s">
                <div class="stats-content">
                    <div class="stats-header">
                        <h6 class="stats-title">
                            <i class="fas fa-chart-line text-success"></i>
                            میانگین بدهی
                        </h6>
                        <div class="stats-value">
                            {{ $debtorsCount > 0 ? number_format($totalDebt / $debtorsCount) : 0 }}
                        </div>
                        <div class="stats-subtitle">تومان</div>
                    </div>
                    <div class="trend-indicator trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>8.3% افزایش نسبت به ماه قبل</span>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="averageDebtChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- فیلترها -->
    <div class="filter-section animate-fade-in" style="animation-delay: 0.6s">
        <form method="get" id="debtorsFilterForm" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control filter-input"
                       placeholder="جستجو (نام، موبایل، کد حسابداری)" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select filter-input select2">
                    <option value="">همه انواع</option>
                    <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>مشتری</option>
                    <option value="supplier" {{ request('type') == 'supplier' ? 'selected' : '' }}>تامین‌کننده</option>
                    <option value="employee" {{ request('type') == 'employee' ? 'selected' : '' }}>کارمند</option>
                    <option value="shareholder" {{ request('type') == 'shareholder' ? 'selected' : '' }}>سهامدار</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select filter-input">
                    <option value="">همه وضعیت‌ها</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="date_range" class="form-control filter-input daterange"
                       placeholder="بازه زمانی" value="{{ request('date_range') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 btn-filter">
                    <i class="fas fa-filter me-2"></i>فیلتر
                </button>
            </div>
        </form>
    </div>

    <!-- جدول بدهکاران -->
    <div class="table-container animate-fade-in" style="animation-delay: 0.8s">
        <table class="table custom-table" id="debtorsTable">
            <thead>
                <tr>
                    <th>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="selectAll">
                        </div>
                    </th>
                    <th>نام و نام خانوادگی</th>
                    <th>کد حسابداری</th>
                    <th>نوع</th>
                    <th>موبایل</th>
                    <th>مجموع بدهی</th>
                    <th>آخرین تراکنش</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($debtors as $person)
                <tr>
                    <td>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input person-checkbox"
                                   value="{{ $person->id }}">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="person-avatar" style="width: 40px; height: 40px; font-size: 1rem;">
                                {{ mb_substr($person->first_name, 0, 1) }}
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold">{{ $person->full_name }}</div>
                                @if($person->company_name)
                                    <small class="text-muted">{{ $person->company_name }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $person->accounting_code }}</td>
                    <td>
                        <span class="badge bg-{{ $person->type == 'customer' ? 'primary' :
                            ($person->type == 'supplier' ? 'success' :
                            ($person->type == 'employee' ? 'info' : 'warning')) }}">
                            {{ $person->type_label }}
                        </span>
                    </td>
                    <td>{{ $person->mobile }}</td>
                    <td>
                        <div class="debt-amount">{{ number_format($person->balance) }} تومان</div>
                        @if($person->last_transaction_at)
                            <small class="text-muted">بروزرسانی: {{ jdate($person->last_transaction_at)->ago() }}</small>
                        @endif
                    </td>
                    <td>{{ $person->last_transaction_at ? jdate($person->last_transaction_at)->format('Y/m/d') : '-' }}</td>
                    <td>
                        <span class="status-badge {{ $person->status == 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ $person->status == 'active' ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('persons.show', $person) }}"
                               class="btn btn-sm btn-outline-primary"
                               data-bs-toggle="tooltip"
                               title="مشاهده جزئیات">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button"
                                    class="btn btn-sm btn-outline-success"
                                    onclick="showPaymentModal({{ $person->id }})"
                                    data-bs-toggle="tooltip"
                                    title="ثبت پرداخت">
                                <i class="fas fa-money-bill"></i>
                            </button>
                            <a href="{{ route('persons.edit', $person) }}"
                               class="btn btn-sm btn-outline-warning"
                               data-bs-toggle="tooltip"
                               title="ویرایش">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <p>هیچ بدهکاری یافت نشد!</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                نمایش {{ $debtors->firstItem() ?? 0 }} تا {{ $debtors->lastItem() ?? 0 }}
                از {{ $debtors->total() ?? 0 }} مورد
            </div>
            {{ $debtors->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- مودال ثبت پرداخت -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ثبت پرداخت جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="paymentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">مبلغ پرداختی (تومان)</label>
                        <input type="number" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">روش پرداخت</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="cash">نقدی</option>
                            <option value="card">کارت به کارت</option>
                            <option value="cheque">چک</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">توضیحات</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
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
<!-- ترتیب لود شدن اسکریپت‌ها مهم است -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-jalaali@0.9.2/build/moment-jalaali.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // داده‌های نمودار از کنترلر
    const chartData = @json($chartData);
    const trends = @json($trends);

    // تنظیمات مشترک نمودارها
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                rtl: true,
                titleAlign: 'right',
                bodyAlign: 'right',
                callbacks: {
                    label: function(context) {
                        return new Intl.NumberFormat('fa-IR').format(context.raw) + ' تومان';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('fa-IR').format(value);
                    }
                }
            }
        }
    };

    try {
        // نمودار مجموع بدهی
        new Chart(document.getElementById('totalDebtChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.totalDebt,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    fill: true
                }]
            },
            options: commonOptions
        });

        // نمودار تعداد بدهکاران
        new Chart(document.getElementById('debtorsCountChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.debtorsCount,
                    borderColor: '#f6c23e',
                    backgroundColor: 'rgba(246, 194, 62, 0.1)',
                    fill: true
                }]
            },
            options: commonOptions
        });

        // نمودار میانگین بدهی
        new Chart(document.getElementById('averageDebtChart'), {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    data: chartData.averageDebt,
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    fill: true
                }]
            },
            options: commonOptions
        });

        // نمایش روند تغییرات
        document.querySelectorAll('[data-trend]').forEach(element => {
            const type = element.dataset.trend;
            const trend = trends[type] || 0;
            const isPositive = trend > 0;

            element.innerHTML = `
                <i class="fas fa-arrow-${isPositive ? 'up' : 'down'} ${isPositive ? 'text-success' : 'text-danger'}"></i>
                <span class="${isPositive ? 'text-success' : 'text-danger'}">
                    ${Math.abs(trend)}% ${isPositive ? 'افزایش' : 'کاهش'}
                </span>
            `;
        });

    } catch (e) {
        console.error('Chart Error:', e);
    }

    // تنظیمات Date Range Picker
    try {
        $('.daterange').daterangepicker({
            locale: {
                format: 'jYYYY/jMM/jDD',
                separator: ' - ',
                applyLabel: 'تایید',
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
            opens: 'left',
            autoUpdateInput: false
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('jYYYY/jMM/jDD') + ' - ' + picker.endDate.format('jYYYY/jMM/jDD'));
        });
    } catch (e) {
        console.error('DateRangePicker Error:', e);
    }

    // مدیریت چک‌باکس‌ها
    const selectAllCheckbox = document.getElementById('selectAll');
    const personCheckboxes = document.querySelectorAll('.person-checkbox');

    selectAllCheckbox?.addEventListener('change', function() {
        personCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // نمایش مودال پرداخت
    window.showPaymentModal = function(personId) {
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    };

    // انیمیشن اعداد
    document.querySelectorAll('[data-value]').forEach(element => {
        const value = parseInt(element.dataset.value);
        let current = 0;
        const step = value / 100;
        const interval = setInterval(() => {
            current += step;
            if (current >= value) {
                element.textContent = new Intl.NumberFormat('fa-IR').format(value);
                clearInterval(interval);
            } else {
                element.textContent = new Intl.NumberFormat('fa-IR').format(Math.floor(current));
            }
        }, 20);
    });
});
</script>
@endpush
