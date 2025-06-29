@extends('layouts.app')

@section('title', 'ویرایش خدمت')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/service-create.css') }}">
    <style>
        .switch-edit-code {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .input-group .input-group-text {
            min-width: 110px;
            justify-content: center;
        }
        .modal-backdrop.show {
            opacity: 0.2;
        }
        .list-group-item .unit-name {
            min-width: 100px;
            display: inline-block;
        }
        /* سهامداران: استایل مشابه محصول */
        .shareholder-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #2563eb;
        }
        .input-group .input-group-prepend,
        .input-group .input-group-append {
            display: flex;
            align-items: center;
        }
    </style>
@endsection

@section('content')

<section class="container pt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div id="service-header" class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fa fa-edit me-2"></i>ویرایش خدمت
                    </h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label required">نام خدمت</label>
                            <input type="text" name="title" id="title" class="form-control" required autofocus value="{{ old('title', $service->title) }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="service_code" class="form-label">کد خدمات</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    id="service_code"
                                    name="service_code"
                                    class="form-control"
                                    required
                                    value="{{ old('service_code', $service->service_code) }}"
                                >
                                <span class="input-group-text">
                                    <label class="switch-edit-code mb-0">
                                        <input type="checkbox" id="custom_code_switch" class="form-check-input ms-1">
                                        کد دلخواه
                                    </label>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-1">
                                در حالت پیش‌فرض کد به صورت خودکار ساخته می‌شود. برای وارد کردن کد دلخواه تیک را فعال کنید.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">تصویر خدمت</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            @if($service->image)
                                <img src="{{ asset('storage/'.$service->image) }}" alt="پیش نمایش" style="max-width:150px; margin-top:10px;">
                            @else
                                <img id="image_preview" class="preview-img" src="#" alt="پیش نمایش" style="display:none; max-width:150px; margin-top:10px;">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="service_info" class="form-label">اطلاعات خدمات</label>
                            <input type="text" name="service_info" id="service_info" class="form-control" value="{{ old('service_info', $service->service_info) }}">
                        </div>

                        <div class="mb-3">
                            <label for="service_category_id" class="form-label">دسته‌بندی خدمت</label>
                            <select name="service_category_id" id="service_category_id" class="form-select">
                                <option value="">انتخاب کنید</option>
                                @foreach($serviceCategories ?? [] as $cat)
                                    <option value="{{ $cat->id }}" {{ old('service_category_id', $service->service_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label required">مبلغ فروش (تومان)</label>
                            <input type="number" name="price" id="price" class="form-control" min="0" step="100" required value="{{ old('price', $service->price) }}">
                        </div>

                        <div class="mb-3">
                            <label for="unit_id" class="form-label required">واحد خدمت</label>
                            <div class="input-group">
                                <select name="unit_id" id="unit_id" class="form-select" required>
                                    <option value="">انتخاب واحد</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id', $service->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->title }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-primary" id="add-unit-btn">
                                    افزودن واحد جدید
                                </button>
                            </div>
                        </div>
                        <ul id="unit-list" class="list-group mb-3" style="display: none"></ul>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">توضیح کوتاه</label>
                            <input type="text" name="short_description" id="short_description" class="form-control" maxlength="255" value="{{ old('short_description', $service->short_description) }}">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">توضیحات</label>
                            <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $service->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="info_link" class="form-label">صفحه/لینک اطلاعات گرفته شده</label>
                            <textarea name="info_link" id="info_link" class="form-control" rows="2">{{ old('info_link', $service->info_link) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="full_description" class="form-label">توضیحات کامل</label>
                            <textarea name="full_description" id="full_description" class="form-control" rows="5">{{ old('full_description', $service->full_description) }}</textarea>
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                فعال باشد
                            </label>
                        </div>

                        {{-- بخش سهامداران پیشرفته --}}
                        @foreach($shareholders as $shareholder)
                        <div class="form-section-title">سهامداران و درصد هرکدام</div>
<div class="mb-4">
    <div class="alert alert-light border shadow-sm mb-2">
        <small>
            اگر هیچ سهامداری انتخاب نشود، سهم خدمت به طور مساوی بین همه سهامداران تقسیم می‌شود.<br>
            اگر فقط یک نفر انتخاب شود، کل خدمت برای او خواهد بود.<br>
            اگر چند نفر انتخاب شوند، درصد هرکدام را وارد کنید (مجموع باید ۱۰۰ باشد، اگر خالی ماند بین انتخاب‌شده‌ها تقسیم می‌شود).
        </small>
    </div>
    <div class="row" id="shareholder-list">
        @foreach($shareholders as $shareholder)
        <div class="col-md-4 mb-2">
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox"
                               name="shareholder_ids[]"
                               value="{{ $shareholder->id }}"
                               id="sh-{{ $shareholder->id }}"
                               class="shareholder-checkbox"
                               @if( old('shareholder_ids') && in_array($shareholder->id, old('shareholder_ids', [])) )
                                   checked
                               @endif
                        >
                    </div>
                </div>
                <input type="number"
                       name="shareholder_percents[{{ $shareholder->id }}]"
                       id="percent-{{ $shareholder->id }}"
                       class="form-control shareholder-percent"
                       min="0" max="100" step="0.01"
                       placeholder="درصد سهم"
                       value="{{ old('shareholder_percents.'.$shareholder->id) }}"
                       @if( !old('shareholder_ids') || !in_array($shareholder->id, old('shareholder_ids', [])) )
                           disabled
                       @endif
                >
                <div class="input-group-append">
                    <span class="input-group-text">{{ $shareholder->full_name }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <small class="form-text text-muted" id="percent-warning" style="color:red;display:none"></small>
</div>
                    @endforeach

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa fa-save me-1"></i>ثبت تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal افزودن واحد جدید -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="add-unit-form" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUnitModalLabel">افزودن واحد جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="new-unit-input" class="form-control mb-2" placeholder="نام واحد جدید" required>
                <input type="hidden" id="edit-unit-id">
                <ul id="unit-modal-list" class="list-group mt-2"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="submit" class="btn btn-primary">
                    ثبت واحد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/service-create.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let codeInput = document.getElementById('service_code');
    let customSwitch = document.getElementById('custom_code_switch');
    @if(!$service->service_code)
    let loadingCode = false;
    function fetchNextCode() {
        if(loadingCode) return;
        loadingCode = true;
        fetch('/services/next-code')
            .then(res => res.json())
            .then(data => {
                codeInput.value = data.code;
                codeInput.readOnly = true;
                loadingCode = false;
            }).catch(() => {
                codeInput.value = '';
                loadingCode = false;
            });
    }
    fetchNextCode();
    @endif

    customSwitch.addEventListener('change', function() {
        if(customSwitch.checked) {
            codeInput.readOnly = false;
            codeInput.value = '';
            codeInput.focus();
        } else {
            @if(!$service->service_code)
            fetchNextCode();
            @endif
        }
    });

    // پیش‌نمایش تصویر جدید در صورت انتخاب
    const imageInput = document.getElementById('image');
    let imagePreview = document.getElementById('image_preview');
    if(!imagePreview){
        imagePreview = document.createElement('img');
        imagePreview.id = 'image_preview';
        imagePreview.className = 'preview-img';
        imagePreview.style.display = 'none';
        imageInput.parentNode.appendChild(imagePreview);
    }
    if(imageInput){
        imageInput.addEventListener('change', function() {
            if (imageInput.files && imageInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.style.display = "block";
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(imageInput.files[0]);
            }
        });
    }

    // سهامداران: فعال/غیرفعال کردن ورودی درصد + منطق درصدها مثل محصولات
    document.querySelectorAll('.shareholder-checkbox').forEach(ch => {
        ch.addEventListener('change', function(){
            let percentInput = document.getElementById('percent-' + this.value);
            percentInput.disabled = !this.checked;
            if (!this.checked) percentInput.value = '';
            updatePercents();
        });
    });

    // منطق درصد سهم‌ها
    let checkboxes = document.querySelectorAll('.shareholder-checkbox');
    let percents = document.querySelectorAll('.shareholder-percent');
    let warning = document.getElementById('percent-warning');
    function updatePercents() {
        let checked = [];
        checkboxes.forEach((ch, idx) => {
            let percentInput = document.getElementById('percent-' + ch.value);
            if (ch.checked) checked.push(ch.value);
            percentInput.disabled = !ch.checked;
            if (!ch.checked) percentInput.value = '';
        });
        if (checked.length === 0) {
            percents.forEach(inp => inp.value = '');
            warning.style.display = 'none';
        } else if (checked.length === 1) {
            percents.forEach(inp => inp.value = '');
            document.getElementById('percent-' + checked[0]).value = 100;
            warning.innerText = '';
            warning.style.display = 'none';
        } else {
            let allEmpty = true;
            checked.forEach(id => {
                let val = document.getElementById('percent-' + id).value;
                if (val && parseFloat(val) > 0) allEmpty = false;
            });
            if (allEmpty) {
                let share = (100 / checked.length).toFixed(2);
                checked.forEach(id => {
                    document.getElementById('percent-' + id).value = share;
                });
            }
            let sum = checked.reduce((acc, id) => acc + parseFloat(document.getElementById('percent-' + id).value || 0), 0);
            if (sum !== 100 && !allEmpty) {
                warning.innerText = 'مجموع درصدها باید ۱۰۰ باشد. مجموع فعلی: ' + sum;
                warning.style.display = 'block';
            } else {
                warning.innerText = '';
                warning.style.display = 'none';
            }
        }
    }
    checkboxes.forEach(ch => { ch.addEventListener('change', updatePercents); });
    percents.forEach(inp => { inp.addEventListener('input', updatePercents); });
    updatePercents();
});
</script>
@endsection
