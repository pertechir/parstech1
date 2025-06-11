<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'حسابیر') }}</title>

    <!-- فونت‌ها و استایل‌های پایه -->
    <link rel="stylesheet" href="{{ asset('fonts/fonts.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    <!-- استایل‌های سفارشی -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sales-show.css') }}">

    <!-- تقویم شمسی -->
    <link rel="stylesheet" href="{{ asset('css/mds.bs.datetimepicker.style.css') }}">

    <!-- تم ادمین -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- استایل‌های لایوویر -->
    @livewireStyles

    <!-- استایل‌های اضافی -->
    @yield('head')
    @stack('styles')

    <style>
        body {
            background: #f9fafb;
            min-height: 100vh;
            position: relative;
        }

        #main-content {
            margin-right: 278px;
            padding: 15px;
            min-height: calc(100vh - 60px);
            transition: margin .3s ease-in-out;
        }

        body.sidebar-collapsed #main-content {
            margin-right: 70px;
        }

        @media (max-width: 768px) {
            #main-content {
                margin-right: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- هدر -->
    @include('layouts.header')

    <!-- سایدبار -->
    @include('layouts.sidebar')

    <!-- محتوای اصلی -->
    <div class="main-content" id="main-content">
        @yield('content')
    </div>

    <!-- مدال ارز -->
    <div class="modal fade" id="currencyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">مدیریت واحدهای پول</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form id="currencyForm" autocomplete="off" onsubmit="return false;">
                        <input type="hidden" id="editCurrencyId" value="">
                        <div class="row g-2 mb-2">
                            <div class="col">
                                <input type="text" class="form-control" id="curTitle" placeholder="نام ارز">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="curSymbol" placeholder="نماد">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="curCode" placeholder="کد">
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-success" id="addCurrencyBtn">افزودن</button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-bordered table-sm mt-3" id="currenciesTable">
                        <thead>
                            <tr>
                                <th>نام ارز</th>
                                <th>نماد</th>
                                <th>کد</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- لیست ارزها اینجا بارگذاری می‌شود -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- اسکریپت‌های پایه -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token()]) !!};
    </script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- تقویم شمسی -->
    <script src="{{ asset('js/mds.bs.datetimepicker.js') }}"></script>

    <!-- اسکریپت‌های سفارشی -->
    <script src="{{ asset('js/header-custom.js') }}"></script>
    <script src="{{ asset('js/sidebar-custom.js') }}"></script>
    <script src="{{ asset('js/currency-modal.js') }}"></script>

    <!-- تم ادمین -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- اسکریپت‌های اضافی -->
    @yield('scripts')
    @stack('scripts')
    @livewireScripts
</body>
</html>
