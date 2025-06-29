@extends('layouts.app')
@section('title', 'مشاهده خدمت پیشرفته')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/products-show.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.min.css">
    <style>
        .service-meta {font-size:1.08rem;}
        .service-meta .meta-label {color:#2563eb; font-weight:bold;}
        .service-meta .meta-value {color:#374151;}
        .service-status-badge {font-size:0.98rem; border-radius:6px; padding:0.27em 0.95em;}
        .service-status-badge.active {background:#e0ffe4; color:#10b981;}
        .service-status-badge.inactive {background:#f6dada; color:#e11d48;}
        .service-gallery-grid {display:grid; grid-template-columns:repeat(auto-fit,minmax(110px,1fr)); gap:12px;}
        .service-gallery-grid img {border-radius:10px; border:1.5px solid #e0e7ef; background:#f6f9ff; width:100%; aspect-ratio:1/1; object-fit:cover;}
        .service-attrs-table {width:100%; margin-top:12px; border-collapse:collapse;}
        .service-attrs-table th, .service-attrs-table td {padding:7px 10px; border:1px solid #f1f5f9; text-align:right;}
        .service-attrs-table th {background:#f4f8ff; color:#2563eb;}
        .shareholder-badge {background:#e0e7ff; color:#232f57; font-size:0.98rem; border-radius:6px; padding:0.3em 1em; margin:0 0.3em 0.3em 0;}
        .tab-modern {display:flex; gap:0.4rem; border-bottom:2px solid #e3e8ef; margin-bottom:1.7rem;}
        .tab-modern-btn {border:none; background:none; color:#64748b; font-weight:500; padding:0.67rem 1.5rem; border-radius:10px 10px 0 0; cursor:pointer;}
        .tab-modern-btn.active {background:#2563eb; color:#fff;}
        .tab-modern-content {display:none;}
        .tab-modern-content.active {display:block; animation:fadeIn 0.28s;}
        @keyframes fadeIn {from{opacity:0;}to{opacity:1;}}
    </style>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white d-flex align-items-center">
            <h4 class="mb-0 flex-grow-1">{{ $service->title }} ({{ $service->service_code }})</h4>
            <a href="{{ route('services.index') }}" class="btn btn-light">بازگشت به لیست</a>
            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning ms-2">ویرایش خدمت</a>
        </div>
        <div class="card-body">
            <div class="row flex-lg-nowrap flex-wrap">
                <!-- تصویر شاخص و وضعیت -->
                <div class="col-lg-4 col-md-5 col-12 mb-3">
                    <div class="text-center mb-3">
                        @if($service->image)
                            <img src="{{ asset('storage/'.$service->image) }}" alt="تصویر خدمت" class="img-fluid rounded shadow" style="max-height:220px;">
                        @else
                            <div class="alert alert-secondary">تصویر ندارد</div>
                        @endif
                    </div>
                    <div class="mb-3 mt-3">
                        <span class="service-status-badge {{ $service->is_active ? 'active' : 'inactive' }}">
                            {{ $service->is_active ? 'فعال' : 'غیرفعال' }}
                        </span>
                        <span class="badge bg-primary ms-2">{{ $service->unit ?? '-' }}</span>
                    </div>
                </div>
                <!-- تب اطلاعات -->
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="tab-modern" id="serviceTabs">
                        <button class="tab-modern-btn active" data-tab="tab-info">اطلاعات کلی</button>
                        <button class="tab-modern-btn" data-tab="tab-gallery">گالری</button>
                        <button class="tab-modern-btn" data-tab="tab-attrs">ویژگی‌ها</button>
                        <button class="tab-modern-btn" data-tab="tab-shareholders">سهامداران</button>
                        <button class="tab-modern-btn" data-tab="tab-desc">توضیحات</button>
                    </div>
                    <div class="tab-modern-content active" id="tab-info">
                        <div class="row service-meta">
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">نام خدمت:</span>
                                <span class="meta-value">{{ $service->title }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">کد خدمت:</span>
                                <span class="meta-value">{{ $service->service_code }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">دسته‌بندی:</span>
                                <span class="meta-value">{{ $service->category?->name ?? '-' }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">قیمت فروش:</span>
                                <span class="meta-value">{{ number_format($service->price) }} تومان</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">واحد:</span>
                                <span class="meta-value">{{ $service->unit ?? '-' }}</span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <span class="meta-label">تاریخ ثبت:</span>
                                <span class="meta-value">{{ jdate($service->created_at)->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-modern-content" id="tab-gallery">
                        <h5>گالری تصاویر</h5>
                        @if($service->gallery && is_array($service->gallery) && count($service->gallery))
                            <div class="service-gallery-grid mt-3">
                                @foreach($service->gallery as $galleryImg)
                                    <img src="{{ asset('storage/'.$galleryImg) }}" alt="گالری" loading="lazy">
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-secondary mt-2">گالری تصویری ثبت نشده است.</div>
                        @endif
                    </div>
                    <div class="tab-modern-content" id="tab-attrs">
                        <h5>ویژگی‌های خدمت</h5>
                        @if($service->attributes && is_array($service->attributes) && count($service->attributes))
                            <table class="service-attrs-table">
                                <thead><tr><th>عنوان</th><th>مقدار</th></tr></thead>
                                <tbody>
                                @foreach($service->attributes as $attr)
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
                        <h5>سهامداران خدمت</h5>
                        @if($service->shareholders && $service->shareholders->count())
                            <div>
                                @foreach($service->shareholders as $sh)
                                    <span class="shareholder-badge">{{ $sh->full_name }} ({{ $sh->pivot->percent }}%)</span>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-light">بدون سهامدار خاص - سهم خدمت بین کل سهامداران تقسیم شده است.</div>
                        @endif
                    </div>
                    <div class="tab-modern-content" id="tab-desc">
                        <h5>توضیحات خدمت</h5>
                        <div class="mb-2"><b>توضیح کوتاه:</b></div>
                        <div class="border rounded p-2 mb-3 bg-light">{{ $service->short_description }}</div>
                        <div class="mb-2"><b>توضیح کامل:</b></div>
                        <div class="border rounded p-2">{!! nl2br(e($service->full_description ?? $service->description)) !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
});
</script>
@endsection
