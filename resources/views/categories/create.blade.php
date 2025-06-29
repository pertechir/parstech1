@extends('layouts.app')

@section('title', 'ایجاد دسته‌بندی جدید')

@section('content')
<link rel="stylesheet" href="{{ asset('css/category-create.css') }}">

<div class="container mt-4 category-create-container">
    <div class="card category-create-card shadow-lg" id="category-create-card" style="border-radius: 24px;">
        <div class="card-header text-white category-create-header" id="category-create-header" style="background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%); border-radius: 24px 24px 0 0;">
            <h5 class="mb-0" id="category-create-title">ایجاد دسته‌بندی جدید</h5>
        </div>
        <div class="card-body category-create-body">

            {{-- پیام موفقیت --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- نمایش خطاها --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- دکمه‌های تب‌بندی --}}
            <div class="mb-4 d-flex justify-content-center category-create-tabs gap-2">
                <button type="button" class="btn category-create-tab-btn" id="btn-person" onclick="showTab('person')" style="border-radius: 16px 16px 0 0; font-weight: 900;">دسته‌بندی اشخاص</button>
                <button type="button" class="btn category-create-tab-btn" id="btn-product" onclick="showTab('product')" style="border-radius: 16px 16px 0 0; font-weight: 900;">دسته‌بندی کالا</button>
                <button type="button" class="btn category-create-tab-btn" id="btn-service" onclick="showTab('service')" style="border-radius: 16px 16px 0 0; font-weight: 900;">دسته‌بندی خدمات</button>
            </div>

            {{-- فرم دسته‌بندی اشخاص --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-person" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="person">
                <div class="mb-3 text-center">
                    <div class="img-upload-wrapper mx-auto" style="max-width:110px;">
                        <img id="img-person" src="{{ asset('img/category-person.png') }}" alt="پیش‌فرض اشخاص" class="img-thumbnail category-create-img shadow-sm" onclick="triggerFileInput('person_image')" style="border-radius: 16px; cursor:pointer;">
                        <div class="img-overlay" onclick="triggerFileInput('person_image')"><span>تغییر</span></div>
                        <input type="file" name="image" id="person_image" class="form-control category-create-input img-hidden-input" accept="image/*" onchange="previewImage(this, 'img-person')" style="display: none;">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="person_name" class="form-label category-create-label fw-bold text-primary">نام دسته‌بندی اشخاص</label>
                    <input type="text" name="name" id="person_name" class="form-control category-create-input" required>
                </div>
                <div class="mb-3">
                    <label for="person_code" class="form-label category-create-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="person_code" class="form-control category-create-input" value="{{ $nextPersonCode ?? 'per1001' }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="person_parent_id" class="form-label category-create-label">زیر دسته</label>
                    <select name="parent_id" id="person_parent_id" class="form-control category-create-input">
                        <option value="">بدون زیر دسته</option>
                        @foreach($personCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="person_description" class="form-label category-create-label">توضیحات</label>
                    <textarea name="description" id="person_description" class="form-control category-create-input" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-gradient px-5 py-2" style="border-radius: 12px;">ثبت دسته‌بندی اشخاص</button>
                </div>
            </form>

            {{-- فرم دسته‌بندی کالا --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-product" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="product">
                <div class="mb-3 text-center">
                    <div class="img-upload-wrapper mx-auto" style="max-width:110px;">
                        <img id="img-product" src="{{ asset('img/category-product.png') }}" alt="پیش‌فرض کالا" class="img-thumbnail category-create-img shadow-sm" onclick="triggerFileInput('product_image')" style="border-radius: 16px; cursor:pointer;">
                        <div class="img-overlay" onclick="triggerFileInput('product_image')"><span>تغییر</span></div>
                        <input type="file" name="image" id="product_image" class="form-control category-create-input img-hidden-input" accept="image/*" onchange="previewImage(this, 'img-product')" style="display: none;">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="product_name" class="form-label category-create-label fw-bold text-success">نام دسته‌بندی کالا</label>
                    <input type="text" name="name" id="product_name" class="form-control category-create-input" required>
                </div>
                <div class="mb-3">
                    <label for="product_code" class="form-label category-create-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="product_code" class="form-control category-create-input" value="{{ $nextProductCode ?? 'pro1001' }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="product_parent_id" class="form-label category-create-label">زیر دسته</label>
                    <select name="parent_id" id="product_parent_id" class="form-control category-create-input">
                        <option value="">بدون زیر دسته</option>
                        @foreach($productCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label category-create-label">توضیحات</label>
                    <textarea name="description" id="product_description" class="form-control category-create-input" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-gradient px-5 py-2" style="border-radius: 12px;">ثبت دسته‌بندی کالا</button>
                </div>
            </form>

            {{-- فرم دسته‌بندی خدمات --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-service" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="service">
                <div class="mb-3 text-center">
                    <div class="img-upload-wrapper mx-auto" style="max-width:110px;">
                        <img id="img-service" src="{{ asset('img/category-service.png') }}" alt="پیش‌فرض خدمات" class="img-thumbnail category-create-img shadow-sm" onclick="triggerFileInput('service_image')" style="border-radius: 16px; cursor:pointer;">
                        <div class="img-overlay" onclick="triggerFileInput('service_image')"><span>تغییر</span></div>
                        <input type="file" name="image" id="service_image" class="form-control category-create-input img-hidden-input" accept="image/*" onchange="previewImage(this, 'img-service')" style="display: none;">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="service_name" class="form-label category-create-label fw-bold text-info">نام دسته‌بندی خدمات</label>
                    <input type="text" name="name" id="service_name" class="form-control category-create-input" required>
                </div>
                <div class="mb-3">
                    <label for="service_code" class="form-label category-create-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="service_code" class="form-control category-create-input" value="{{ $nextServiceCode ?? 'ser1001' }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="service_parent_id" class="form-label category-create-label">زیر دسته</label>
                    <select name="parent_id" id="service_parent_id" class="form-control category-create-input">
                        <option value="">بدون زیر دسته</option>
                        @foreach($serviceCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="service_description" class="form-label category-create-label">توضیحات</label>
                    <textarea name="description" id="service_description" class="form-control category-create-input" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-gradient px-5 py-2" style="border-radius: 12px;">ثبت دسته‌بندی خدمات</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.category-create-card {
    background: #fff;
    box-shadow: 0 8px 36px #2563eb13;
    border-radius: 24px;
    padding: 2.2rem 2.3rem 1.3rem 2.3rem;
    max-width: 600px;
    margin: 2.5rem auto 0 auto;
    transition: box-shadow 0.22s;
}
.category-create-header {
    font-size: 1.5rem;
    font-weight: 900;
    color: #fff;
    letter-spacing: 0.03em;
    text-align: center;
    background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
    border-radius: 24px 24px 0 0;
    padding: 1.2rem;
    margin-bottom: 5px;
}
.category-create-tabs .category-create-tab-btn {
    border: none;
    background: #ecf2ff;
    color: #2563eb;
    font-weight: 900;
    font-size: 1.09em;
    border-radius: 1.3rem 1.3rem 0 0;
    padding: 0.85rem 2.4rem;
    transition: background 0.18s, color 0.18s;
    cursor: pointer;
    outline: none;
    margin: 0 2px;
}
.category-create-tabs .category-create-tab-btn.active,
.category-create-tabs .category-create-tab-btn:focus {
    background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
    color: #fff !important;
    box-shadow: 0 5px 18px #2563eb25;
    border-bottom: 3px solid #fff;
}
.category-create-img {
    width: 95px !important;
    height: 95px !important;
    object-fit: cover;
    border-radius: 1.2rem;
    background: #eef2ff;
    box-shadow: 0 2px 8px #2563eb13;
    cursor: pointer;
    transition: box-shadow 0.18s;
}
.img-upload-wrapper {
    position: relative;
    display: inline-block;
}
.img-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    border-radius: 1.2rem;
    background: rgba(36,99,235,0.09);
    color: #2563eb;
    opacity: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 1.13em;
    cursor: pointer;
    transition: opacity 0.16s;
}
.img-upload-wrapper:hover .img-overlay {
    opacity: 1;
}
.category-create-label {
    font-weight: 800;
    color: #2563eb;
    font-size: 1.05em;
}
.category-create-input {
    border-radius: 14px;
    border: 2px solid #e0e7ff;
    min-height: 48px;
    font-size: 1.07em;
    background: #f8fafc;
    font-weight: 600;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.category-create-input:focus {
    border-color: #2563eb;
    background: #eef2ff;
    box-shadow: 0 0 0 3px #2563eb22;
}
.btn-gradient {
    background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
    color: #fff !important;
    border: none;
    border-radius: 13px;
    padding: 13px 42px;
    font-weight: 900;
    font-size: 1.18em;
    box-shadow: 0 4px 14px #2563eb22;
    transition: background 0.2s, box-shadow 0.2s;
    letter-spacing: 0.03em;
}
.btn-gradient:hover {
    background: linear-gradient(90deg, #1e2549 0%, #2563eb 90%);
    box-shadow: 0 7px 24px #2563eb33;
}
@media (max-width: 700px) {
    .category-create-card { padding: 1rem 0.7rem;}
    .category-create-header { font-size: 1.1rem;}
    .category-create-img { width: 72px !important; height: 72px !important; }
}
</style>

<script>
    const tabColors = {
        person: {
            bg: '#1a73e8',
            btn: '#1a73e8',
            card: '#e3f0ff'
        },
        product: {
            bg: '#388e3c',
            btn: '#388e3c',
            card: '#e8f5e9'
        },
        service: {
            bg: '#fbc02d',
            btn: '#fbc02d',
            card: '#fffde7'
        }
    };

    function showTab(type) {
        document.getElementById('form-person').style.display = (type === 'person') ? 'block' : 'none';
        document.getElementById('form-product').style.display = (type === 'product') ? 'block' : 'none';
        document.getElementById('form-service').style.display = (type === 'service') ? 'block' : 'none';

        document.getElementById('btn-person').classList.remove('active');
        document.getElementById('btn-product').classList.remove('active');
        document.getElementById('btn-service').classList.remove('active');

        document.getElementById('btn-person').style.background = '#ecf2ff';
        document.getElementById('btn-product').style.background = '#ecf2ff';
        document.getElementById('btn-service').style.background = '#ecf2ff';

        document.getElementById('btn-person').style.color = '#2563eb';
        document.getElementById('btn-product').style.color = '#2563eb';
        document.getElementById('btn-service').style.color = '#2563eb';

        document.getElementById('btn-' + type).classList.add('active');
        document.getElementById('btn-' + type).style.background = 'linear-gradient(90deg, #2563eb 0%, #3b82f6 100%)';
        document.getElementById('btn-' + type).style.color = '#fff';

        document.getElementById('category-create-header').style.background = 'linear-gradient(90deg, #2563eb 0%, #3b82f6 100%)';
        document.getElementById('category-create-card').style.background = '#fff';

        let label = '';
        if (type === 'person') label = 'ایجاد دسته‌بندی اشخاص';
        if (type === 'product') label = 'ایجاد دسته‌بندی کالا';
        if (type === 'service') label = 'ایجاد دسته‌بندی خدمات';
        document.getElementById('category-create-title').textContent = label;
    }

    document.addEventListener("DOMContentLoaded", function() {
        showTab('product');
    });

    function previewImage(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(imgId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function triggerFileInput(inputId) {
        document.getElementById(inputId).click();
    }
</script>
@endsection
