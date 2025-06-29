@extends('layouts.app')

@section('title', 'حسابداری شخصی')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/personal-accounting.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
<div class="personal-accounting-container">
    <!-- داشبورد -->
    <div class="dashboard-cards">
        <div class="dashboard-card card-income">
            <div class="card-icon"><i class="fas fa-arrow-down"></i></div>
            <div class="card-title">کل درآمد</div>
            <div class="card-value" id="total-income">۰</div>
        </div>
        <div class="dashboard-card card-expense">
            <div class="card-icon"><i class="fas fa-arrow-up"></i></div>
            <div class="card-title">کل هزینه</div>
            <div class="card-value" id="total-expense">۰</div>
        </div>
        <div class="dashboard-card card-balance">
            <div class="card-icon"><i class="fas fa-wallet"></i></div>
            <div class="card-title">موجودی فعلی</div>
            <div class="card-value" id="balance">۰</div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <!-- نمودار -->
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-chart-line"></i> روند مالی</span>
                    <select class="form-select w-auto" id="report-range">
                        <option value="month">ماه جاری</option>
                        <option value="year">سال جاری</option>
                        <option value="all">همه</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="finance-chart" height="120"></canvas>
                </div>
            </div>

            <!-- جدول تراکنش‌ها -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-list"></i> لیست تراکنش‌ها
                    <button class="btn btn-success btn-sm float-end" id="add-transaction-btn">
                        <i class="fas fa-plus"></i> تراکنش جدید
                    </button>
                </div>
                <div class="card-body">
                    <div class="row mb-3 g-2">
                        <div class="col-md-5">
                            <input type="text" class="form-control" id="search-transaction" placeholder="جستجو در تراکنش‌ها...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="category-filter">
                                <option value="">همه دسته‌بندی‌ها</option>
                                <option value="food">غذا</option>
                                <option value="transport">حمل و نقل</option>
                                <option value="home">خانه</option>
                                <option value="salary">درآمد حقوق</option>
                                <option value="other">سایر</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="type-filter">
                                <option value="">همه</option>
                                <option value="income">درآمد</option>
                                <option value="expense">هزینه</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="transactions-table">
                            <thead>
                                <tr>
                                    <th>تاریخ</th>
                                    <th>دسته‌بندی</th>
                                    <th>شرح</th>
                                    <th>مبلغ</th>
                                    <th>نوع</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- تراکنش‌ها با JS پر می‌شود -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3" id="no-transactions" style="display:none;">
                        <span class="text-muted">تراکنشی ثبت نشده است.</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- سایدبار ابزار سریع -->
        <div class="col-lg-4">
            <!-- فرم افزودن/ویرایش تراکنش -->
            <div class="card" id="transaction-form-card" style="display:none;">
                <div class="card-header">
                    <span id="transaction-form-title">افزودن تراکنش جدید</span>
                    <button type="button" class="btn-close float-end" id="close-transaction-form"></button>
                </div>
                <div class="card-body">
                    <form id="transaction-form">
                        <input type="hidden" id="transaction-id">
                        <div class="mb-3">
                            <label class="form-label">تاریخ</label>
                            <input type="date" class="form-control" id="transaction-date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">مبلغ</label>
                            <input type="number" class="form-control" id="transaction-amount" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">شرح</label>
                            <input type="text" class="form-control" id="transaction-description">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">دسته‌بندی</label>
                            <select class="form-select" id="transaction-category" required>
                                <option value="">انتخاب کنید</option>
                                <option value="food">غذا</option>
                                <option value="transport">حمل و نقل</option>
                                <option value="home">خانه</option>
                                <option value="salary">درآمد حقوق</option>
                                <option value="other">سایر</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نوع تراکنش</label>
                            <select class="form-select" id="transaction-type" required>
                                <option value="expense">هزینه</option>
                                <option value="income">درآمد</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="save-transaction-btn">
                            ذخیره
                        </button>
                    </form>
                </div>
            </div>
            <!-- خلاصه دسته‌بندی -->
            <div class="card mt-4">
                <div class="card-header">
                    <i class="fas fa-tags"></i> خلاصه هزینه‌ها بر اساس دسته‌بندی
                </div>
                <div class="card-body">
                    <ul class="list-group" id="category-summary-list">
                        <!-- با جاوااسکریپت پر می‌شود -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/personal-accounting.js') }}"></script>
@endsection
