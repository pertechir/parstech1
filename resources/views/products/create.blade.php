@extends('layouts.app')
@section('title', 'افزودن محصول جدید')

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/products-create.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css" />
    <link rel="stylesheet" href="{{ asset('css/persian-datepicker.min.css') }}">
    <style>
        .modal-category-bg {
            display: none;
            position: fixed;
            z-index: 1400;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.28);
            align-items: center;
            justify-content: center;
        }
        .modal-category-bg.active {display: flex;}
        .modal-category-box {
            background: #fff;
            border-radius: 16px;
            width: 98vw;
            max-width: 430px;
            min-width: 280px;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 12px 40px #0002;
            padding: 25px 18px 16px 18px;
            margin: 0 auto;
            animation: catmodalshow .24s cubic-bezier(.6,1.5,.6,1) both;
        }
        @keyframes catmodalshow {
            from {transform: translateY(70px) scale(.93); opacity:.2;}
            to   {transform: none; opacity:1;}
        }
        .modal-category-title {
            font-size: 1.22rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 13px;
            text-align: right;
        }
        .category-search-box {
            margin-bottom: 10px;
        }
        .category-search-input {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #bae6fd;
            padding: 8px 12px;
            font-size: 1rem;
            margin-bottom: 7px;
        }
        .category-scroll-list {
            overflow-y: auto;
            max-height: 38vh;
            border-radius: 8px;
            border: 1px solid #e0e7ef;
            background: #f8fafc;
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .category-scroll-list li {
            padding: 11px 12px;
            cursor: pointer;
            font-size: 1rem;
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.17s;
            color: #334155;
            text-align: right;
        }
        .category-scroll-list li:last-child {border-bottom: none;}
        .category-scroll-list li:hover, .category-scroll-list li.selected {
            background: #e0f2fe;
            color: #0ea5e9;
            font-weight: bold;
        }
        .modal-category-close {
            position: absolute; left: 13px; top: 8px;
            font-size: 1.55rem;
            color: #e11d48;
            cursor: pointer;
            background: none;
            border: none;
            outline: none;
        }
        .category-selected-view {
            background: #e0f2fe;
            color: #2563eb;
            padding: 7px 18px;
            border-radius: 10px;
            display: inline-block;
            font-weight: bold;
            min-width: 110px;
            text-align: center;
        }
        .category-no-result {
            color: #ef4444;
            text-align: center;
            padding: 15px 0 10px 0;
        }
        @media (max-width:600px){
            .modal-category-box {max-width:97vw;min-width:0;padding:10px 4vw;}
            .modal-category-title {font-size: 1rem;}
            .category-scroll-list li {padding:7px 8px; font-size:0.95rem;}
        }
    </style>
@endsection

@section('content')
<div class="product-form-outer py-4">
    <div class="card shadow-lg">
        <div class="card-header product-header">
            <h1 class="product-title"><i class="bi bi-plus-circle-dotted me-2"></i>افزودن محصول جدید</h1>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-type"></i> نام محصول <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}" autofocus>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-upc-scan"></i> کد کالا</label>
                        <div class="input-group">
                            <input type="text" name="code" id="product-code" class="form-control"
                                   value="{{ old('code', $default_code ?? 'product-1001') }}"
                                   readonly data-default="{{ $default_code ?? 'product-1001' }}">
                            <span class="input-group-text p-0 px-1">
                                <input class="form-check-input m-0" type="checkbox" id="code-edit-switch">
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-cash-stack"></i> قیمت خرید</label>
                        <div class="input-group">
                            <input type="text" name="buy_price" class="form-control persian-number" required value="{{ old('buy_price') }}">
                            <span class="input-group-text">تومان</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-currency-dollar"></i> قیمت فروش</label>
                        <div class="input-group">
                            <input type="text" name="sell_price" class="form-control persian-number" required value="{{ old('sell_price') }}">
                            <span class="input-group-text">تومان</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-percent"></i> تخفیف (%)</label>
                        <input type="text" name="discount" class="form-control persian-number" value="{{ old('discount') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-box"></i> موجودی اولیه</label>
                        <input type="text" name="stock" class="form-control persian-number" value="{{ old('stock', 1) }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-exclamation-triangle"></i> هشدار موجودی</label>
                        <input type="text" name="stock_alert" class="form-control persian-number" value="{{ old('stock_alert', 1) }}">
                        <small class="text-muted">در صورت رسیدن موجودی به این عدد، هشدار نمایش داده می‌شود.</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-cart-plus"></i> حداقل سفارش</label>
                        <input type="text" name="min_order_qty" class="form-control persian-number" value="{{ old('min_order_qty', 1) }}">
                    </div>
                    <!-- دسته‌بندی محصول با پاپ‌آپ و جستجو -->
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-list-task"></i> دسته‌بندی <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span id="selected-category-view" class="category-selected-view">
                                @php
                                    $selectedCat = old('category_id') ? $categories->firstWhere('id', old('category_id')) : null;
                                @endphp
                                {{ $selectedCat ? $selectedCat->name : 'انتخاب نشده' }}
                            </span>
                            <button type="button" id="open-category-modal" class="btn btn-outline-primary">
                                انتخاب دسته‌بندی
                            </button>
                        </div>
                        <input type="hidden" name="category_id" id="category_id" value="{{ old('category_id') }}">
                        @error('category_id')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-calendar3"></i> تاریخ انقضا (اختیاری)</label>
                        <input type="text" name="expire_date" id="expire_date_picker" class="form-control" value="{{ old('expire_date') }}" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold"><i class="bi bi-calendar3"></i> تاریخ افزودن محصول</label>
                        <input type="text" name="added_at" id="added_at_picker" class="form-control" value="{{ old('added_at', '') }}" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-check form-switch pt-4">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label ms-2" for="is_active">فعال باشد</label>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs nav-tabs-rtl mb-4 mt-4" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="main-tab" data-bs-toggle="tab" data-bs-target="#mainTabPane" type="button" role="tab" aria-controls="mainTabPane" aria-selected="true">
                            <i class="bi bi-info-circle"></i> اطلاعات تکمیلی
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#mediaTabPane" type="button" role="tab" aria-controls="mediaTabPane" aria-selected="false">
                            <i class="bi bi-images"></i> رسانه و فایل‌ها
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="desc-tab" data-bs-toggle="tab" data-bs-target="#descTabPane" type="button" role="tab" aria-controls="descTabPane" aria-selected="false">
                            <i class="bi bi-file-earmark-text"></i> توضیحات و ویژگی‌ها
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="shareholder-tab" data-bs-toggle="tab" data-bs-target="#shareholderTabPane" type="button" role="tab" aria-controls="shareholderTabPane" aria-selected="false">
                            <i class="bi bi-people"></i> سهامداران
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="productTabContent">
                    <div class="tab-pane fade show active" id="mainTabPane" role="tabpanel" aria-labelledby="main-tab">
                        <div class="row gy-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">برند</label>
                                <div class="input-group">
                                    <select name="brand_id" class="form-select" id="brand-select">
                                        <option value="">بدون برند</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" @if(old('brand_id')==$brand->id) selected @endif>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#brandModal" id="showBrandModal">برند جدید</button>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">واحد اندازه‌گیری</label>
                                <select name="unit" id="selected-unit" class="form-select">
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->title }}" @if(old('unit', 'عدد') == $unit->title) selected @endif>
                                            {{ $unit->title }}@if($unit->id == 0) (پیش فرض) @endif
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-info mt-2 w-100" data-bs-toggle="modal" data-bs-target="#unitModal">مدیریت واحدها</button>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">وزن (گرم)</label>
                                <input type="text" name="weight" class="form-control persian-number" value="{{ old('weight') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">بارکد محصول</label>
                                <div class="input-group">
                                    <input type="text" name="barcode" id="barcode-field" class="form-control persian-number" value="{{ old('barcode') }}">
                                    <button type="button" class="btn btn-outline-primary" id="generate-barcode-btn" data-target="barcode-field">ساخت بارکد</button>
                                </div>
                                <span class="barcode-status"></span>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">بارکد فروشگاهی</label>
                                <div class="input-group">
                                    <input type="text" name="store_barcode" id="store-barcode-field" class="form-control persian-number" value="{{ old('store_barcode') }}">
                                    <button type="button" class="btn btn-outline-secondary" id="generate-store-barcode-btn" data-target="store-barcode-field">ساخت بارکد فروشگاه</button>
                                </div>
                                <span class="barcode-status"></span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="mediaTabPane" role="tabpanel" aria-labelledby="media-tab">
                        <div class="row gy-3 mb-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label">تصویر شاخص محصول</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">ویدیوی معرفی محصول</label>
                                <input type="file" name="video" class="form-control" accept="video/*">
                            </div>
                            <div class="col-12 mt-2">
                                <label class="form-label">گالری تصاویر</label>
                                <div id="gallery-dropzone" class="dropzone"></div>
                                <input type="hidden" name="gallery[]" id="gallery-input">
                                <small class="text-muted">حداکثر ۵ تصویر، هر تصویر کمتر از ۲ مگابایت.</small>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="descTabPane" role="tabpanel" aria-labelledby="desc-tab">
                        <div class="row gy-3 mb-2">
                            <div class="col-12">
                                <label class="form-label">توضیحات کوتاه</label>
                                <textarea name="short_desc" class="form-control" rows="2">{{ old('short_desc') }}</textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">توضیحات کامل</label>
                                <textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">ویژگی‌های محصول</label>
                                <div id="attributes-area"></div>
                                <button type="button" class="btn btn-outline-success mt-2" id="add-attribute"><i class="bi bi-plus"></i> افزودن ویژگی</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="shareholderTabPane" role="tabpanel" aria-labelledby="shareholder-tab">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label"><b>تخصیص سهم سهامداران برای این محصول</b></label>
                                <div class="alert alert-light border shadow-sm mb-2">
                                    <small>
                                        اگر هیچ سهامداری انتخاب نشود، سهم محصول به طور مساوی بین همه سهامداران تقسیم می‌شود.<br>
                                        اگر فقط یک نفر انتخاب شود، کل محصول برای او خواهد بود.<br>
                                        اگر چند نفر انتخاب شوند، درصد هرکدام را وارد کنید (مجموع باید ۱۰۰ باشد، اگر خالی بگذارید، به طور مساوی تقسیم می‌شود).
                                    </small>
                                </div>
                                @if($shareholders->count())
                                    <div class="row" id="shareholder-list">
                                        @foreach($shareholders as $shareholder)
                                            <div class="col-12 mb-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <input type="checkbox"
                                                                name="shareholder_ids[]"
                                                                value="{{ $shareholder->id }}"
                                                                id="sh-{{ $shareholder->id }}"
                                                                class="shareholder-checkbox"
                                                            >
                                                        </div>
                                                    </div>
                                                    <input type="text"
                                                        name="shareholder_percents[{{ $shareholder->id }}]"
                                                        id="percent-{{ $shareholder->id }}"
                                                        class="form-control shareholder-percent persian-number"
                                                        min="0" max="100" step="0.01"
                                                        placeholder="درصد سهم"
                                                        disabled
                                                    >
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">{{ $shareholder->full_name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning mt-2">هیچ سهامداری ثبت نشده است.</div>
                                @endif
                                <small class="form-text text-muted" id="percent-warning" style="color:red;display:none"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4"><i class="bi bi-check2-circle"></i> ثبت محصول</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg px-4">انصراف</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- پاپ‌آپ دسته‌بندی با جستجو -->
<div class="modal-category-bg" id="category-modal-bg">
    <div class="modal-category-box">
        <button type="button" class="modal-category-close" id="close-category-modal" title="بستن">&times;</button>
        <div class="modal-category-title">انتخاب دسته‌بندی محصول</div>
        <div class="category-search-box">
            <input type="text" id="category-search" class="category-search-input" placeholder="جستجوی دسته‌بندی...">
        </div>
        <ul class="category-scroll-list" id="category-list">
            @php
                $productCategories = $categories->filter(function($cat){ return !isset($cat->type) || $cat->type == 'product'; });
            @endphp
            @foreach($productCategories as $cat)
                <li data-id="{{ $cat->id }}">
                    {{ $cat->name }}
                </li>
            @endforeach
        </ul>
        <div id="cat-no-result" class="category-no-result d-none">هیچ دسته‌ای پیدا نشد!</div>
    </div>
</div>
@include('products.units-modal')
@include('products.brand-modal')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/persian-date.min.js') }}"></script>
    <script src="{{ asset('js/persian-datepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js"></script>
    <script src="{{ asset('js/products-create-advanced.js') }}"></script>
    <script>
        $(function() {
            // پاپ‌آپ دسته‌بندی
            $('#open-category-modal').click(function(){
                $('#category-modal-bg').addClass('active');
                setTimeout(()=>{$('#category-search').focus();}, 200)
            });
            $('#close-category-modal').click(function(){
                $('#category-modal-bg').removeClass('active');
            });
            $('#category-modal-bg').click(function(e){
                if(e.target === this) $('#category-modal-bg').removeClass('active');
            });
            // انتخاب دسته
            $('#category-list').on('click', 'li', function(){
                let id = $(this).data('id');
                let title = $(this).text();
                $('#category_id').val(id);
                $('#selected-category-view').text(title);
                $('#category-modal-bg').removeClass('active');
            });
            // جستجو دسته‌بندی
            $('#category-search').on('input', function(){
                let val = $(this).val().trim().toLowerCase();
                let found = false;
                $('#category-list li').each(function(){
                    let text = $(this).text().trim().toLowerCase();
                    if(val === "" || text.includes(val)){
                        $(this).show();
                        found = true;
                    }else{
                        $(this).hide();
                    }
                });
                $('#cat-no-result').toggleClass('d-none', found);
            });

            // فعال/غیرفعال کردن input درصد سهامدار
            $('.shareholder-checkbox').on('change', function(){
                let input = $('#percent-' + $(this).val());
                if($(this).is(':checked')){
                    input.prop('disabled', false);
                }else{
                    input.prop('disabled', true).val('');
                }
            });

            // تاریخ شمسی
            $('#expire_date_picker').persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                initialValue: false
            });
            let today = new persianDate().format('YYYY/MM/DD');
            let $addedAt = $('#added_at_picker');
            if(!$addedAt.val()) $addedAt.val(today);
            $addedAt.persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                initialValue: false
            });

            // کنترل سوییچ کد کالا
            const codeSwitch = document.getElementById('code-edit-switch');
            const codeInput = document.getElementById('product-code');
            const codeDefault = codeInput ? codeInput.getAttribute('data-default') : 'product-1001';
            if (codeSwitch && codeInput) {
                codeSwitch.addEventListener('change', function () {
                    codeInput.readOnly = !this.checked;
                    if(this.checked){
                        codeInput.value = '';
                        codeInput.focus();
                    } else {
                        codeInput.value = codeDefault;
                    }
                });
                codeInput.readOnly = true;
                codeInput.value = codeDefault;
            }
        });
    </script>
@endsection
