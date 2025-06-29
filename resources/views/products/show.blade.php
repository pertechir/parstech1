@extends('layouts.app')
@section('title', 'مشاهده محصول پیشرفته')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/products-show.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        .product-meta {font-size:1.08rem;}
        .product-meta .meta-label {color:#2563eb; font-weight:bold;}
        .product-meta .meta-value {color:#374151;}
        .product-status-badge {font-size:0.98rem; border-radius:6px; padding:0.27em 0.95em;}
        .product-status-badge.active {background:#e0ffe4; color:#10b981;}
        .product-status-badge.inactive {background:#f6dada; color:#e11d48;}
        .product-gallery-grid {display:grid; grid-template-columns:repeat(auto-fit,minmax(110px,1fr)); gap:12px;}
        .product-gallery-grid img {border-radius:10px; border:1.5px solid #e0e7ef; background:#f6f9ff; width:100%; aspect-ratio:1/1; object-fit:cover;}
        .product-attrs-table {width:100%; margin-top:12px; border-collapse:collapse;}
        .product-attrs-table th, .product-attrs-table td {padding:7px 10px; border:1px solid #f1f5f9; text-align:right;}
        .product-attrs-table th {background:#f4f8ff; color:#2563eb;}
        .shareholder-badge {background:#e0e7ff; color:#232f57; font-size:0.98rem; border-radius:6px; padding:0.3em 1em; margin:0 0.3em 0.3em 0;}
        .barcode-img {display:block; margin:0 auto;}
        .tab-modern {display:flex; gap:0.4rem; border-bottom:2px solid #e3e8ef; margin-bottom:1.7rem;}
        .tab-modern-btn {border:none; background:none; color:#64748b; font-weight:500; padding:0.67rem 1.5rem; border-radius:10px 10px 0 0; cursor:pointer;}
        .tab-modern-btn.active {background:#2563eb; color:#fff;}
        .tab-modern-content {display:none;}
        .tab-modern-content.active {display:block; animation:fadeIn 0.28s;}
        @keyframes fadeIn {from{opacity:0;}to{opacity:1;}}
        .barcode-label-box {display:inline-block;margin:9px 14px;text-align:center;}
        .barcode-label-box .name {font-weight:600;font-size:1.05em;margin-bottom:0.6em;}
        .barcode-label-box .barcode {margin:0.5em 0;}
        .barcode-label-box .code {font-size:0.9em;letter-spacing:1px;}
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white d-flex align-items-center">
            <h4 class="mb-0 flex-grow-1">{{ $product->name }} ({{ $product->code }})</h4>
            <a href="{{ route('products.index') }}" class="btn btn-light">بازگشت به لیست</a>
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning ms-2">ویرایش محصول</a>
        </div>
        <div class="card-body">
            <div class="row flex-lg-nowrap flex-wrap">
                <!-- تصویر شاخص و بارکد و وضعیت -->
                <div class="col-lg-4 col-md-5 col-12 mb-3">
                    <div class="text-center mb-3">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" alt="تصویر محصول" class="img-fluid rounded shadow" style="max-height:220px;">
                        @else
                            <div class="alert alert-secondary">تصویر ندارد</div>
                        @endif
                    </div>
                    <div class="mb-3 mt-3">
                        <span class="product-status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                            {{ $product->is_active ? 'فعال' : 'غیرفعال' }}
                        </span>
                        <span class="badge bg-primary ms-2">{{ $product->stock }} {{ $product->unit ?? 'عدد' }}</span>
                        @if($product->stock <= $product->min_stock)
                            <span class="badge bg-warning text-dark ms-2">هشدار موجودی</span>
                        @endif
                    </div>
                    @if($product->barcode || $product->store_barcode)
                        <div class="mb-3">
                            <div class="barcode-label-box">
                                <div class="name">بارکد اصلی</div>
                                <svg id="barcode-main" class="barcode"></svg>
                                <div class="code">{{ $product->barcode }}</div>
                            </div>
                            @if($product->store_barcode)
                            <div class="barcode-label-box">
                                <div class="name">بارکد فروشگاهی</div>
                                <svg id="barcode-store" class="barcode"></svg>
                                <div class="code">{{ $product->store_barcode }}</div>
                            </div>
                            @endif
                        </div>
                    @endif
                    @if($product->video)
                        <div class="mb-2">
                            <video src="{{ asset('storage/'.$product->video) }}" controls style="width:100%; max-width:280px; border-radius:10px;"></video>
                        </div>
                    @endif
                </div>
                <!-- تب اطلاعات -->
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="tab-modern" id="prodTabs">
                        <button class="tab-modern-btn active" data-tab="tab-info">اطلاعات کلی</button>
                        <button class="tab-modern-btn" data-tab="tab-gallery">گالری</button>
                        <button class="tab-modern-btn" data-tab="tab-attrs">ویژگی‌ها</button>
                        <button class="tab-modern-btn" data-tab="tab-shareholders">سهامداران</button>
                        <button class="tab-modern-btn" data-tab="tab-desc">توضیحات</button>
                    </div>
                    <div class="tab-modern-content active" id="tab-info">
                        <div class="row product-meta">
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">نام محصول:</span>
                                <span class="meta-value">{{ $product->name }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">کد محصول:</span>
                                <span class="meta-value">{{ $product->code }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">دسته‌بندی:</span>
                                <span class="meta-value">{{ $product->category?->name ?? '-' }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">برند:</span>
                                <span class="meta-value">{{ $product->brand?->name ?? '-' }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">قیمت خرید:</span>
                                <span class="meta-value">{{ number_format($product->buy_price) }} تومان</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">قیمت فروش:</span>
                                <span class="meta-value">{{ number_format($product->sell_price) }} تومان</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">تخفیف:</span>
                                <span class="meta-value">{{ $product->discount ?? 0 }}%</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">واحد:</span>
                                <span class="meta-value">{{ $product->unit ?? '-' }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">وزن:</span>
                                <span class="meta-value">{{ $product->weight ?? '-' }} گرم</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">تاریخ ثبت:</span>
                                <span class="meta-value">{{ jdate($product->created_at)->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-modern-content" id="tab-gallery">
                        <h5>گالری تصاویر</h5>
                        @if($product->gallery && is_array($product->gallery) && count($product->gallery))
                            <div class="product-gallery-grid mt-3">
                                @foreach($product->gallery as $galleryImg)
                                    <img src="{{ asset('storage/'.$galleryImg) }}" alt="گالری" loading="lazy">
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-secondary mt-2">گالری تصویری ثبت نشده است.</div>
                        @endif
                    </div>
                    <div class="tab-modern-content" id="tab-attrs">
                        <h5>ویژگی‌های محصول</h5>
                        @if($product->attributes && is_array($product->attributes) && count($product->attributes))
                            <table class="product-attrs-table">
                                <thead><tr><th>عنوان</th><th>مقدار</th></tr></thead>
                                <tbody>
                                @foreach($product->attributes as $attr)
                                    <tr>
                                        <td>{{ $attr['key'] ?? '-' }}</td>
                                        <td>{{ $attr['value'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info mt-2">ویژگی‌ای ثبت نشده است.</div>
                        @endif
                    </div>
                    <div class="tab-modern-content" id="tab-shareholders">
                        <h5>سهامداران محصول</h5>
                        @if($product->shareholders && $product->shareholders->count())
                            <div>
                                @foreach($product->shareholders as $sh)
                                    <span class="shareholder-badge">{{ $sh->full_name }} ({{ $sh->pivot->percent }}%)</span>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light">بدون سهامدار خاص - سهم محصول بین کل سهامداران تقسیم شده است.</div>
                        @endif
                    </div>
                    <div class="tab-modern-content" id="tab-desc">
                        <h5>توضیحات محصول</h5>
                        <div class="mb-2"><b>توضیح کوتاه:</b></div>
                        <div class="border rounded p-2 mb-3 bg-light">{{ $product->short_desc }}</div>
                        <div class="mb-2"><b>توضیح کامل:</b></div>
                        <div class="border rounded p-2">{!! nl2br(e($product->description)) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 برای حذف و سایر اکشن‌های پیشرفته --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // تب‌ها
    const tabBtns = document.querySelectorAll('.tab-modern-btn');
    const tabContents = document.querySelectorAll('.tab-modern-content');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tabBtns.forEach(b=>b.classList.remove('active'));
            tabContents.forEach(c=>c.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });

    // بارکد svg
    @if($product->barcode)
        JsBarcode("#barcode-main", "{{ $product->barcode }}", {
            format: "CODE128",
            width: 2.1,
            height: 54,
            displayValue: false,
            margin: 0,
            lineColor: "#222"
        });
    @endif
    @if($product->store_barcode)
        JsBarcode("#barcode-store", "{{ $product->store_barcode }}", {
            format: "CODE128",
            width: 2.1,
            height: 54,
            displayValue: false,
            margin: 0,
            lineColor: "#222"
        });
    @endif

    // اکشن حذف (مثال SweetAlert)
    if(document.getElementById('deleteProductBtn')){
        document.getElementById('deleteProductBtn').onclick = function(e){
            e.preventDefault();
            Swal.fire({
                title: 'حذف محصول',
                text: 'آیا مطمئن هستید؟ این عملیات قابل بازگشت نیست.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'بله حذف کن',
                cancelButtonText: 'انصراف'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteProductForm').submit();
                }
            });
        }
    }
});
</script>
@endsection
