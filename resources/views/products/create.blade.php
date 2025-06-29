@extends('layouts.app')
@section('title', 'افزودن محصول جدید')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/products-create.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css" />
    <style>
        .product-create-flex {display: flex; gap: 32px;}
        .product-preview {
            width: 340px; min-width: 270px; max-width: 400px;
            background: #fff; border-radius: 18px; box-shadow: 0 0 32px #2563eb11;
            padding: 2.2rem 1.3rem 1.2rem 1.3rem; position: sticky; top: 38px; height: fit-content;
        }
        .preview-header {font-size: 1.18rem; font-weight: bold; color: #2563eb; margin-bottom: 1.5rem; border-bottom: 2px solid #e0e7ff; padding-bottom: 0.8rem;}
        .preview-image {width: 100%; aspect-ratio: 1; background: #f6f9ff; border-radius: 12px; overflow: hidden; margin-bottom: 1.2rem; display: flex; flex-direction: column; align-items: center; justify-content: center;}
        .preview-image img {max-width: 90%; max-height: 90%; border-radius: 10px;}
        .image-placeholder {text-align: center; color: #a6b3c9; font-size: 2.2rem;}
        .preview-info {margin-bottom: 1.3rem;}
        .info-group {font-size: 1.05rem; padding: 7px 0; border-bottom: 1px solid #f4f4f4; display: flex; justify-content: space-between;}
        .info-group:last-child {border: none;}
        .info-label {color: #7b8794;}
        .preview-actions {display: flex; flex-direction: column; gap: 0.75rem;}
        .btn-save-preview {background: #10b981; color: #fff; font-weight: 500; border-radius: 8px; padding: 1rem 0; border: none;}
        .btn-cancel-preview {background: #f1f5f9; color: #64748b; border-radius: 8px; padding: 1rem 0; border: none; text-align: center; font-weight: 500;}
        .main-content-modern {flex: 1; background: #fff; border-radius: 24px; padding: 2.2rem 2.2rem 1.5rem 2.2rem; box-shadow: 0 12px 48px -12px #1c4a9c16;}
        .main-section-title {font-size: 1.7rem; font-weight: bold; color: #2563eb; margin-bottom: 1.3rem;}
        .tabs-nav-modern {display: flex; gap: 0.4rem; margin: 1.5rem 0 0 0; border-bottom: 1.5px solid #e5e7eb;}
        .tab-btn-modern {border: none; background: none; color: #64748b; font-weight: 500; padding: 0.7rem 1.6rem; border-radius: 10px 10px 0 0; cursor: pointer; transition: background 0.15s, color 0.15s; font-size: 1.09rem;}
        .tab-btn-modern.active {background: #2563eb; color: #fff; font-weight: bold;}
        .tab-content-modern {display: none; padding-top: 1.5rem;}
        .tab-content-modern.active {display: block; animation: fadeIn 0.3s;}
        @keyframes fadeIn {from{opacity:0;} to{opacity:1;}}
        @media (max-width: 1050px) {
            .product-create-flex {flex-direction: column; gap: 0;}
            .product-preview {width: 100%; max-width: 100%; position: static; margin-bottom: 30px;}
            .main-content-modern {padding: 1.2rem;}
        }
    </style>
@endsection

@section('content')
<div class="container-fluid product-create-page">
    <div class="product-create-flex">
        {{-- PREVIEW SIDEBAR --}}
        <aside class="product-preview" id="productPreview">
            <div class="preview-header"><i class="fa fa-eye ms-2"></i>پیش‌نمایش محصول</div>
            <div class="preview-image" id="preview-image">
                <div class="image-placeholder" id="image-placeholder">
                    <i class="fas fa-image"></i>
                </div>
            </div>
            <div class="preview-info">
                <div class="info-group"><span class="info-label">نام:</span><span id="preview-name">-</span></div>
                <div class="info-group"><span class="info-label">کد:</span><span id="preview-code">-</span></div>
                <div class="info-group"><span class="info-label">قیمت فروش:</span><span id="preview-price">-</span></div>
                <div class="info-group"><span class="info-label">موجودی:</span><span id="preview-stock">-</span></div>
            </div>
            <div class="preview-actions">
                <button type="submit" form="product-form" class="btn-save-preview"><i class="fas fa-save"></i>&nbsp;ذخیره محصول</button>
                <a href="{{ route('products.index') }}" class="btn-cancel-preview"><i class="fas fa-times"></i>&nbsp;انصراف</a>
            </div>
        </aside>

        {{-- صفحه اصلی --}}
        <main class="main-content-modern">
            <div class="main-section-title"><i class="fa fa-plus-circle ms-2"></i>افزودن محصول جدید</div>

            <form id="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">نام محصول <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name-input" class="form-control" required value="{{ old('name') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">کد کالا <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="code" id="product-code"
                                   class="form-control"
                                   value="{{ old('code', $default_code) }}"
                                   readonly
                                   data-default="{{ $default_code }}"
                                   required>
                            <span class="input-group-text">
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" id="code-edit-switch" checked>
                                </div>
                            </span>
                        </div>
                        <small class="text-muted">برای کد سفارشی، سوییچ را غیرفعال کن.</small>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">دسته‌بندی <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="category_id" class="form-select" id="category-select" required>
                                <option value="">انتخاب کنید...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" @if(old('category_id')==$cat->id) selected @endif>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal" id="openAddCategoryModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">قیمت فروش <span class="text-danger">*</span></label>
                        <input type="number" name="sell_price" id="sell-price-input" class="form-control" value="{{ old('sell_price') }}" required>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">موجودی اولیه</label>
                        <input type="number" name="stock" id="stock-input" class="form-control" value="{{ old('stock', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">حداقل موجودی هشدار <span class="text-danger">*</span></label>
                        <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 1) }}" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">وضعیت</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">فعال</label>
                        </div>
                    </div>
                </div>

                <div class="tabs-nav-modern" id="tabNav">
                    <button type="button" class="tab-btn-modern active" data-tab="tab1">تصاویر / فایل</button>
                    <button type="button" class="tab-btn-modern" data-tab="tab2">قیمت و سایر</button>
                    <button type="button" class="tab-btn-modern" data-tab="tab3">توضیحات</button>
                    <button type="button" class="tab-btn-modern" data-tab="tab4">بارکدها</button>
                    <button type="button" class="tab-btn-modern" data-tab="tab5">سهامداران</button>
                </div>

                {{-- Tab 1: تصاویر / فایل --}}
                <div class="tab-content-modern active" id="tab1">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">تصویر شاخص محصول</label>
                            <input type="file" name="image" class="form-control" accept="image/*" id="main-image-input">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">گالری تصاویر</label>
                            <div id="gallery-dropzone" class="dropzone"></div>
                            <input type="hidden" name="gallery[]" id="gallery-input">
                        </div>
                        <div class="col-md-6 mt-4">
                            <label class="form-label">ویدیوی معرفی محصول</label>
                            <input type="file" name="video" class="form-control" accept="video/*">
                        </div>
                    </div>
                </div>

                {{-- Tab 2: قیمت و سایر --}}
                <div class="tab-content-modern" id="tab2">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">قیمت خرید</label>
                            <input type="number" name="buy_price" class="form-control" value="{{ old('buy_price') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">تخفیف (%)</label>
                            <input type="number" name="discount" class="form-control" value="{{ old('discount') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">واحد</label>
                            <select name="unit" class="form-select">
                                <option value="">انتخاب کنید...</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->title }}" @if(old('unit')==$unit->title) selected @endif>{{ $unit->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">برند</label>
                            <div class="input-group">
                                <select name="brand_id" class="form-select" id="brand-select">
                                    <option value="">بدون برند</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" @if(old('brand_id')==$brand->id) selected @endif>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label">وزن (گرم)</label>
                            <input type="number" name="weight" class="form-control" value="{{ old('weight') }}">
                        </div>
                    </div>
                </div>

                {{-- Tab 3: توضیحات --}}
                <div class="tab-content-modern" id="tab3">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label">توضیحات کوتاه</label>
                            <textarea name="short_desc" class="form-control" rows="2">{{ old('short_desc') }}</textarea>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label">توضیحات کامل</label>
                            <textarea name="description" class="form-control" rows="6">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label">ویژگی‌های محصول</label>
                            <div id="attributes-area"></div>
                            <button type="button" class="btn btn-outline-success mt-2" id="add-attribute">افزودن ویژگی</button>
                        </div>
                    </div>
                </div>

                {{-- Tab 4: بارکدها --}}
                <div class="tab-content-modern" id="tab4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">بارکد محصول</label>
                            <input type="text" name="barcode" class="form-control" id="barcode-input">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">بارکد فروشگاهی</label>
                            <input type="text" name="store_barcode" class="form-control" id="store-barcode-input">
                        </div>
                    </div>
                </div>

                {{-- Tab 5: سهامداران --}}
                <div class="tab-content-modern" id="tab5">
                    <div class="mb-4">
                        <label class="form-label"><b>تخصیص سهم سهامداران برای این محصول</b></label>
                        <div class="alert alert-light border shadow-sm mb-2">
                            <small>
                            اگر هیچ سهامداری انتخاب نشود، سهم محصول به طور مساوی بین همه سهامداران تقسیم می‌شود.<br>
                            اگر فقط یک نفر انتخاب شود، کل محصول برای او خواهد بود.<br>
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
                                                >
                                            </div>
                                        </div>
                                        <input type="number"
                                            name="shareholder_percents[{{ $shareholder->id }}]"
                                            id="percent-{{ $shareholder->id }}"
                                            class="form-control shareholder-percent"
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
                        <small class="form-text text-muted" id="percent-warning" style="color:red;display:none"></small>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-4">ثبت محصول</button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg px-4">انصراف</a>
                </div>
            </form>
        </main>
    </div>
</div>

{{-- MODAL افزودن دسته‌بندی --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="add-category-form" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel"><i class="fas fa-plus"></i> افزودن دسته‌بندی جدید</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">نام دسته‌بندی <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="category-name-input" class="form-control" required>
                        <div class="invalid-feedback" id="category-name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">دسته والد</label>
                        <select name="parent_id" id="parent-category-select" class="form-select">
                            <option value="">بدون والد (دسته اصلی)</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">توضیح کوتاه</label>
                        <input type="text" name="description" id="category-desc-input" class="form-control">
                    </div>
                    <div class="alert alert-danger d-none" id="category-modal-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">ذخیره</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL افزودن برند --}}
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="add-brand-form" autocomplete="off" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBrandModalLabel"><i class="fas fa-plus"></i> افزودن برند جدید</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">نام برند <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="brand-name-input" class="form-control" required>
                        <div class="invalid-feedback" id="brand-name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تصویر برند (اختیاری)</label>
                        <input type="file" name="image" id="brand-image-input" class="form-control" accept="image/*">
                    </div>
                    <div class="alert alert-danger d-none" id="brand-modal-error"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">ذخیره</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // تب‌بندی
        const tabs = document.querySelectorAll('.tab-btn-modern');
        const panes = document.querySelectorAll('.tab-content-modern');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                panes.forEach(p => p.classList.remove('active'));
                document.getElementById(this.dataset.tab).classList.add('active');
            });
        });

        // کد کالا سوییچ
        const codeInput = document.getElementById('product-code');
        const codeSwitch = document.getElementById('code-edit-switch');
        if (codeSwitch) {
            codeSwitch.addEventListener('change', function() {
                if (this.checked) {
                    codeInput.setAttribute('readonly', 'readonly');
                    codeInput.value = codeInput.dataset.default || codeInput.value;
                } else {
                    codeInput.removeAttribute('readonly');
                }
            });
            codeSwitch.checked = true;
            codeInput.setAttribute('readonly', 'readonly');
            codeInput.dataset.default = codeInput.value;
        }

        // پیش‌نمایش زنده اطلاعات
        document.getElementById('name-input').addEventListener('input', function() {
            document.getElementById('preview-name').innerText = this.value || '-';
        });
        codeInput.addEventListener('input', function() {
            document.getElementById('preview-code').innerText = this.value || '-';
        });
        document.getElementById('sell-price-input').addEventListener('input', function() {
            document.getElementById('preview-price').innerText = this.value ? (parseInt(this.value).toLocaleString() + ' تومان') : '-';
        });
        document.getElementById('stock-input').addEventListener('input', function() {
            document.getElementById('preview-stock').innerText = this.value !== '' ? this.value : '-';
        });

        // تصویر اصلی - پیش‌نمایش
        const mainImageInput = document.getElementById('main-image-input');
        mainImageInput.addEventListener('change', function(){
            if(this.files && this.files[0]){
                const reader = new FileReader();
                reader.onload = function(e){
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    let preview = document.getElementById('preview-image');
                    preview.innerHTML = '';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // بارکد خودکار
        function randomNumericBarcode(length) {
            let res = '';
            for(let i=0;i<length;i++) res += Math.floor(Math.random()*10);
            return res;
        }
        function randomTextBarcode() {
            return 'BARCODE-' + Math.floor(100000 + Math.random()*900000);
        }
        document.getElementById('barcode-input').value = randomNumericBarcode(12);
        document.getElementById('store-barcode-input').value = randomTextBarcode();

        // سهامداران: فعال/غیرفعال کردن ورودی درصد
        document.querySelectorAll('.shareholder-checkbox').forEach(ch => {
            ch.addEventListener('change', function(){
                let percentInput = document.getElementById('percent-' + this.value);
                percentInput.disabled = !this.checked;
                if (!this.checked) percentInput.value = '';
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

        // ویژگی‌های دینامیک
        let attrArea = document.getElementById('attributes-area');
        let attrCount = 0;
        document.getElementById('add-attribute').addEventListener('click', function () {
            attrCount++;
            let row = document.createElement('div');
            row.className = 'row mb-2 attr-row';
            row.innerHTML = `
                <div class="col-md-5">
                    <input type="text" name="attributes[${attrCount}][key]" class="form-control" placeholder="عنوان ویژگی">
                </div>
                <div class="col-md-5">
                    <input type="text" name="attributes[${attrCount}][value]" class="form-control" placeholder="مقدار">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-attribute">حذف</button>
                </div>
            `;
            attrArea.appendChild(row);
            row.querySelector('.remove-attribute').onclick = () => row.remove();
        });

        // افزودن دسته‌بندی جدید با AJAX
        document.getElementById('add-category-form').addEventListener('submit', function (e) {
            e.preventDefault();
            let name = document.getElementById('category-name-input').value.trim();
            let parent_id = document.getElementById('parent-category-select').value;
            let desc = document.getElementById('category-desc-input').value.trim();
            let errorDiv = document.getElementById('category-modal-error');
            let nameErrorDiv = document.getElementById('category-name-error');
            errorDiv.classList.add('d-none'); nameErrorDiv.textContent = '';
            document.getElementById('category-name-input').classList.remove('is-invalid');
            if (!name) {
                nameErrorDiv.textContent = 'نام دسته‌بندی الزامی است.';
                document.getElementById('category-name-input').classList.add('is-invalid');
                return;
            }
            fetch("{{ route('categories.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    name,
                    parent_id,
                    description: desc,
                    category_type: 'product'
                })
            })
            .then(async res => {
                if (res.status === 422) {
                    const data = await res.json();
                    if (data.errors && data.errors.name && data.errors.name[0].includes('unique')) {
                        nameErrorDiv.textContent = 'این نام دسته‌بندی قبلاً ثبت شده است.';
                        document.getElementById('category-name-input').classList.add('is-invalid');
                    } else if (data.errors) {
                        errorDiv.textContent = Object.values(data.errors).map(arr => arr.join('، ')).join('، ');
                        errorDiv.classList.remove('d-none');
                    } else {
                        errorDiv.textContent = 'خطای اعتبارسنجی!';
                        errorDiv.classList.remove('d-none');
                    }
                    return;
                }
                return res.json();
            })
            .then(data => {
                if (!data || data.errors) return;
                if (data.id && data.name) {
                    let option = document.createElement('option');
                    option.value = data.id;
                    option.text = data.name;
                    option.selected = true;
                    document.getElementById('category-select').appendChild(option);

                    let option2 = document.createElement('option');
                    option2.value = data.id;
                    option2.text = data.name;
                    document.getElementById('parent-category-select').appendChild(option2);

                    document.getElementById('category-name-input').value = '';
                    document.getElementById('category-desc-input').value = '';
                    document.getElementById('parent-category-select').value = '';
                    bootstrap.Modal.getInstance(document.getElementById('addCategoryModal')).hide();
                }
            })
            .catch(() => {
                errorDiv.textContent = 'خطا در ارسال داده!';
                errorDiv.classList.remove('d-none');
            });
        });

    // افزودن برند جدید با AJAX
    document.getElementById('add-brand-form').addEventListener('submit', function (e) {
            e.preventDefault();
            let name = document.getElementById('brand-name-input').value.trim();
            let image = document.getElementById('brand-image-input').files[0];
            let errorDiv = document.getElementById('brand-modal-error');
            let nameErrorDiv = document.getElementById('brand-name-error');
            errorDiv.classList.add('d-none'); nameErrorDiv.textContent = '';
            document.getElementById('brand-name-input').classList.remove('is-invalid');
            if (!name) {
                nameErrorDiv.textContent = 'نام برند الزامی است.';
                document.getElementById('brand-name-input').classList.add('is-invalid');
                return;
            }
            let formData = new FormData();
            formData.append('name', name);
            if (image) formData.append('image', image);

            fetch("{{ route('brands.store') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            })
            .then(async res => {
                if (res.status === 422) {
                    const data = await res.json();
                    if (data.errors && data.errors.name && data.errors.name[0].includes('unique')) {
                        nameErrorDiv.textContent = 'این نام برند قبلاً ثبت شده است.';
                        document.getElementById('brand-name-input').classList.add('is-invalid');
                    } else if (data.errors) {
                        errorDiv.textContent = Object.values(data.errors).map(arr => arr.join('، ')).join('، ');
                        errorDiv.classList.remove('d-none');
                    } else {
                        errorDiv.textContent = 'خطای اعتبارسنجی!';
                        errorDiv.classList.remove('d-none');
                    }
                    return;
                }
                return res.json();
            })
            .then(data => {
                if (!data || data.errors) return;
                if (data.id && data.name) {
                    let option = document.createElement('option');
                    option.value = data.id;
                    option.text = data.name;
                    option.selected = true;
                    document.getElementById('brand-select').appendChild(option);

                    document.getElementById('brand-name-input').value = '';
                    document.getElementById('brand-image-input').value = '';
                    bootstrap.Modal.getInstance(document.getElementById('addBrandModal')).hide();
                }
            })
            .catch(() => {
                errorDiv.textContent = 'خطا در ارسال داده!';
                errorDiv.classList.remove('d-none');
            });
        });

});
    </script>
@endsection
