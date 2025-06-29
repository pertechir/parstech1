@extends('layouts.app')

@section('title', 'لیست اشخاص')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sales-list.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="{{ asset('css/persian-datepicker.min.css') }}">
<style>
.person-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.person-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.stats-item {
    padding: 10px;
    border-radius: 8px;
    background: linear-gradient(45deg, #f8f9fa, #ffffff);
    border: 1px solid #e9ecef;
}

.person-type-customer { border-right: 4px solid #4e73df; }
.person-type-supplier { border-right: 4px solid #1cc88a; }
.person-type-employee { border-right: 4px solid #f6c23e; }
.person-type-shareholder { border-right: 4px solid #e74a3b; }

.financial-badge {
    font-size: 0.85rem;
    padding: 5px 10px;
    border-radius: 15px;
}

.financial-badge.positive {
    background-color: #e3fcef;
    color: #1cc88a;
}

.financial-badge.negative {
    background-color: #fce3e3;
    color: #e74a3b;
}

.search-box {
    background: #f8f9fc;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

.table-responsive {
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
}

.avatar-initial {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header & Stats -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">لیست اشخاص</h1>
        <a href="{{ route('persons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>افزودن شخص جدید
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                کل اشخاص</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPersons }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                مجموع معاملات</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalTransactions) }} تومان
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                مشتریان فعال</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeCustomers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                بدهکاران
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <a href="{{ route('persons.debtors') }}">
                                    {{ $debtorsCount }}
                                </a>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    لیست <a href="{{ route('persons.debtors') }}" class="text-warning text-decoration-underline">بدهکاران</a> را ببینید.
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Search Box -->
    <div class="search-box">
        <form action="{{ route('persons.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control"
                           placeholder="جستجو..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">همه انواع</option>
                        <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>مشتری</option>
                        <option value="supplier" {{ request('type') == 'supplier' ? 'selected' : '' }}>تامین کننده</option>
                        <option value="employee" {{ request('type') == 'employee' ? 'selected' : '' }}>کارمند</option>
                        <option value="shareholder" {{ request('type') == 'shareholder' ? 'selected' : '' }}>سهامدار</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" name="date_range" class="form-control daterange"
                               placeholder="بازه زمانی" value="{{ request('date_range') }}">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>جستجو
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Persons List -->
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th>کد</th>
                    <th>نام و نام خانوادگی</th>
                    <th>نوع</th>
                    <th>موبایل</th>
                    <th>مجموع پرداختی‌ها</th>
                    <th>مجموع خریدها</th>
                    <th>مانده حساب</th>
                    <th>آخرین تراکنش</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($persons as $person)
                    <tr>
                        <td>{{ $person->accounting_code }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-initial rounded-circle me-2
                                {{ $person->type == 'shareholder' ? 'bg-primary' :
                                ($person->type == 'customer' ? 'bg-danger' : 'bg-secondary') }}">
                                {{ !empty($person->first_name) ? mb_substr($person->first_name, 0, 1, 'UTF-8') : 'ش' }}
                            </div>
                                <div>
                                    <div class="fw-bold">{{ $person->full_name }}</div>
                                    @if($person->company_name)
                                        <small class="text-muted">{{ $person->company_name }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $person->type == 'shareholder' ? 'primary' :
                                                   ($person->type == 'customer' ? 'danger' : 'secondary') }}">
                                {{ $person->type == 'shareholder' ? 'سهامدار' :
                                   ($person->type == 'customer' ? 'مشتری' : '') }}
                            </span>
                        </td>
                        <td>{{ $person->mobile }}</td>
                        <td>{{ number_format($person->total_purchases) }} تومان</td>
                        <td>{{ number_format($person->total_sales) }} تومان</td>
                        <td>
                            <span class="financial-badge {{ $person->balance >= 0 ? 'positive' : 'negative' }}">
                                {{ number_format(abs($person->balance)) }} تومان
                                {{ $person->balance >= 0 ? 'بستانکار' : 'بدهکار' }}
                            </span>
                        </td>
                        <td>{{ $person->last_transaction_at ? jdate($person->last_transaction_at)->ago() : '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('persons.show', $person) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   data-bs-toggle="tooltip"
                                   title="مشاهده جزئیات">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('persons.edit', $person) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   data-bs-toggle="tooltip"
                                   title="ویرایش">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('persons.destroy', $person) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="tooltip"
                                            title="حذف"
                                            onclick="return confirm('آیا از حذف این شخص اطمینان دارید؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-users fa-3x text-muted mb-2"></i>
                                <p class="text-muted">هیچ شخصی یافت نشد</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            نمایش {{ $persons->firstItem() ?? 0 }} تا {{ $persons->lastItem() ?? 0 }} از {{ $persons->total() ?? 0 }} مورد
        </div>
        {{ $persons->withQueryString()->links() }}
    </div>
</div>
@endsection
@push('scripts')
<!-- به ترتیب لود شدن مهم است -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment-jalaali@0.9.2/build/moment-jalaali.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    // تنظیمات DateRangePicker
    $('#dateRange').daterangepicker({
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
        autoUpdateInput: false,
        showDropdowns: true,
        opens: 'left'
    });

    // هندل کردن انتخاب تاریخ
    $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('jYYYY/jMM/jDD') + ' - ' + picker.endDate.format('jYYYY/jMM/jDD'));
    });

    // هندل کردن پاک کردن تاریخ
    $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // Select All functionality
    $('#selectAll').change(function() {
        $('.sale-checkbox').prop('checked', $(this).prop('checked'));
    });
});

function deleteSale(id) {
    if (confirm('آیا از حذف این فاکتور اطمینان دارید؟')) {
        axios.delete(`/sales/${id}`)
            .then(response => {
                if (response.data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                alert('خطا در حذف فاکتور');
                console.error(error);
            });
    }
}

function printInvoice(id) {
    window.open(`/sales/${id}/print`, '_blank');
}

function changePerPage(select) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', select.value);
    window.location.href = url.toString();
}

// تابع تبدیل اعداد به فارسی
function toFaNumber(str) {
    return (str+'').replace(/[0-9]/g, function(w){return '۰۱۲۳۴۵۶۷۸۹'[+w]});
}

// تبدیل همه اعداد به فارسی
function convertAllNumbersToFa() {
    document.querySelectorAll('.farsi-number').forEach(function(el) {
        el.textContent = toFaNumber(el.textContent);
    });
}

// اجرای تبدیل اعداد بعد از لود صفحه
document.addEventListener('DOMContentLoaded', convertAllNumbersToFa);
</script>
@endpush
