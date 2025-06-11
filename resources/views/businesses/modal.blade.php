<div id="businesses-modal" style="display:none;">
    <div class="businesses-modal-bg"></div>
    <div class="businesses-modal-content">
        <button id="close-businesses-modal" type="button" class="btn-close-modal btn btn-danger">بستن</button>
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">کسب‌وکارها</h3>
                <button class="btn btn-success" id="add-business-btn">
                    <i class="fas fa-plus"></i>
                    افزودن کسب‌وکار
                </button>
            </div>
            <div class="text-muted text-center p-5" style="font-size:1.2rem;">
                هنوز هیچ کسب‌وکاری ثبت نشده است.
            </div>
        </div>
        <!-- پاپ‌آپ سه مرحله‌ای افزودن کسب‌وکار -->
        <div id="add-business-modal" style="display:none;">
            <div class="business-modal-steps-absolute">
                <form id="businessForm" class="business-form-steps">
                    <button id="close-add-business-modal" type="button" class="btn-close-modal btn btn-danger">بستن</button>
                    <!-- شماره مراحل -->
                    <div class="steps-progress">
                        <ul class="steps-progressbar">
                            <li class="step-item" id="stepper-1">
                                <span class="circle">۱</span>
                                <span class="step-label">اطلاعات پایه</span>
                            </li>
                            <li class="step-separator"></li>
                            <li class="step-item" id="stepper-2">
                                <span class="circle">۲</span>
                                <span class="step-label">اطلاعات کسب‌وکار</span>
                            </li>
                            <li class="step-separator"></li>
                            <li class="step-item" id="stepper-3">
                                <span class="circle">۳</span>
                                <span class="step-label">تنظیمات مالی</span>
                            </li>
                        </ul>
                    </div>
                    <div id="business-steps">
                        <!-- Step 1 -->
                        <div class="business-step" id="step-1">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">نام کسب‌وکار</label>
                                    <input type="text" name="name" id="biz_name_input" class="form-control" placeholder="مثلاً: پارس تک" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">زبان</label>
                                    <select name="language" class="form-select" required>
                                        <option value="fa">فارسی</option>
                                        <option value="en">انگلیسی</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary px-4" id="next-step-1">بعدی</button>
                            </div>
                        </div>
                        <!-- Step 2 -->
                        <div class="business-step d-none" id="step-2">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">نام کسب‌وکار</label>
                                    <input type="text" name="business_name" id="biz_name_step2" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">نام قانونی</label>
                                    <input type="text" name="legal_name" id="legal_name_step2" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">نوع کسب‌وکار</label>
                                    <select name="business_type" class="form-select" required>
                                        <option value="">انتخاب کنید</option>
                                        <option value="company">شرکت</option>
                                        <option value="shop">فروشگاه</option>
                                        <option value="personal">شخصی</option>
                                        <option value="other">سایر</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">زمینه فعالیت</label>
                                    <input type="text" name="activity_field" class="form-control">
                                </div>
                            </div>
                            <div class="row g-4 mt-2 bg-light p-3 rounded-3 border">
                                <div class="col-12 fw-bold mb-2">اطلاعات اقتصادی</div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">شناسه ملی</label>
                                    <input type="text" name="national_id" class="form-control">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">کد اقتصادی</label>
                                    <input type="text" name="economic_code" class="form-control">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">شماره ثبت</label>
                                    <input type="text" name="register_number" class="form-control">
                                </div>
                            </div>
                            <div class="row g-4 mt-2 bg-light p-3 rounded-3 border">
                                <div class="col-12 fw-bold mb-2">اطلاعات تماس</div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">استان</label>
                                    <select id="province_select" name="province"
                                            class="form-control" required data-old-value="">
                                        <option value="">انتخاب استان</option>
                                        <!-- استان‌ها را از کنترلر پاس بده: $provinces -->
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">شهر</label>
                                    <select id="city_select" name="city" class="form-control" required>
                                        <option value="">ابتدا استان را انتخاب کنید</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">کدپستی</label>
                                    <input type="text" name="postal_code" class="form-control">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">تلفن</label>
                                    <input type="text" name="phone" class="form-control">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">فکس</label>
                                    <input type="text" name="fax" class="form-control">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">آدرس</label>
                                    <input type="text" name="address" class="form-control">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">وب سایت</label>
                                    <input type="text" name="website" class="form-control">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">ایمیل</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" id="prev-step-2">قبلی</button>
                                <button type="button" class="btn btn-primary px-4" id="next-step-2">بعدی</button>
                            </div>
                        </div>
                        <!-- Step 3: ... -->
                        <div class="business-step d-none" id="step-3">
                            <!-- مرحله سوم (تنظیمات مالی و ...) همینطور که قبلاً نوشتم -->
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">سیستم حسابداری انبار</label>
                                    <select name="inventory_accounting_system" class="form-select" required>
                                        <option value="periodic" selected>ادواری</option>
                                        <option value="perpetual">دائمی</option>
                                    </select>
                                    <small class="form-text text-muted">اگر انواع سیستم‌های حسابداری انبار را نمی‌شناسید مقدار پیش‌فرض را تغییر ندهید.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">روش ارزیابی انبار</label>
                                    <select name="inventory_valuation_method" class="form-select" required>
                                        <option value="FIFO" selected>FIFO</option>
                                        <option value="LIFO">LIFO</option>
                                        <option value="WAC">میانگین موزون</option>
                                    </select>
                                    <small class="form-text text-muted">اگر انواع روش‌های ارزیابی انبار را نمی‌شناسید مقدار پیش‌فرض را تغییر ندهید.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">امکان استفاده از سیستم چند ارزی</label>
                                    <select name="multi_currency" class="form-select">
                                        <option value="yes">بله</option>
                                        <option value="no" selected>خیر</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">امکان استفاده از سیستم انبارداری</label>
                                    <select name="inventory_system" class="form-select">
                                        <option value="yes" selected>بله</option>
                                        <option value="no">خیر</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">واحد پول اصلی</label>
                                    <select name="main_currency" class="form-select" required disabled>
                                        <option value="IRR" selected>IRR - ریال ایران</option>
                                    </select>
                                    <small class="form-text text-warning">توجه کنید که واحد پول اصلی در آینده به هیچ صورت قابل تغییر نیست.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">تقویم</label>
                                    <select name="calendar" class="form-select" required>
                                        <option value="shamsi" selected>هجری شمسی</option>
                                        <option value="gregorian">میلادی</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">نرخ مالیات ارزش افزوده (%)</label>
                                    <input type="number" name="vat_rate" class="form-control" value="10" min="0" max="100">
                                </div>
                            </div>
                            <div class="row g-4 mt-2 bg-light p-3 rounded-3 border">
                                <div class="col-12 fw-bold mb-2">اطلاعات سال مالی</div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">تاریخ شروع</label>
                                    <input type="date" name="financial_year_start" class="form-control">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">تاریخ پایان</label>
                                    <input type="date" name="financial_year_end" class="form-control">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">عنوان سال مالی</label>
                                    <input type="text" name="financial_year_title" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary px-4" id="prev-step-3">قبلی</button>
                                <button type="submit" class="btn btn-success px-4" id="submit-business">ثبت نهایی</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- اسکریپت استان/شهر و کنترل فرم -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // مراحل فرم
    const steps = [
        document.getElementById('step-1'),
        document.getElementById('step-2'),
        document.getElementById('step-3')
    ];
    const steppers = [
        document.getElementById('stepper-1'),
        document.getElementById('stepper-2'),
        document.getElementById('stepper-3')
    ];
    let currentStep = 0;

    document.getElementById('businesses-btn')?.addEventListener('click', function() {
        document.getElementById('businesses-modal').style.display = 'block';
    });
    document.getElementById('close-businesses-modal')?.addEventListener('click', function() {
        document.getElementById('businesses-modal').style.display = 'none';
        document.getElementById('add-business-modal').style.display = 'none';
        document.body.style.overflow = '';
    });
    document.querySelector('.businesses-modal-bg')?.addEventListener('click', function() {
        document.getElementById('businesses-modal').style.display = 'none';
        document.getElementById('add-business-modal').style.display = 'none';
        document.body.style.overflow = '';
    });
    document.getElementById('add-business-btn')?.addEventListener('click', function() {
        document.getElementById('add-business-modal').style.display = 'block';
        document.body.style.overflow = 'hidden';
        goStep(0);
    });
    document.getElementById('close-add-business-modal')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('add-business-modal').style.display = 'none';
        document.body.style.overflow = '';
    });

    document.getElementById('next-step-1')?.addEventListener('click', function() { goStep(1); syncNameStep2(); });
    document.getElementById('next-step-2')?.addEventListener('click', function() { goStep(2); });
    document.getElementById('prev-step-2')?.addEventListener('click', function() { goStep(0); });
    document.getElementById('prev-step-3')?.addEventListener('click', function() { goStep(1); });
    function goStep(step){
        currentStep = step;
        steps.forEach((s, i) => s.classList.toggle('d-none', i !== currentStep));
        steppers.forEach((st, i) => {
            st.classList.remove('active','done');
            if(i < step) st.classList.add('done');
            else if(i === step) st.classList.add('active');
        });
        document.getElementById('business-steps').scrollTop = 0;
    }
    // انتقال مقدار نام کسب‌وکار از مرحله ۱ به مرحله ۲
    function syncNameStep2() {
        const bizNameInput = document.getElementById('biz_name_input');
        const bizNameStep2 = document.getElementById('biz_name_step2');
        const legalNameStep2 = document.getElementById('legal_name_step2');
        if (bizNameInput && bizNameStep2 && legalNameStep2) {
            bizNameStep2.value = bizNameInput.value;
            legalNameStep2.value = bizNameInput.value;
        }
    }
    document.getElementById('biz_name_input')?.addEventListener('input', syncNameStep2);

    // استان و شهر داینامیک
    const provinceSelect = document.getElementById('province_select');
    const citySelect = document.getElementById('city_select');
    if (provinceSelect && citySelect) {
        provinceSelect.addEventListener('change', function() {
            let provinceId = this.value;
            citySelect.innerHTML = '<option value="">در حال بارگذاری...</option>';
            if (provinceId) {
                fetch('/provinces/' + provinceId + '/cities')
                    .then(response => response.json())
                    .then(data => {
                        let items = '<option value="">انتخاب شهر</option>';
                        data.forEach(function(city){
                            items += `<option value="${city.id}">${city.name}</option>`;
                        });
                        citySelect.innerHTML = items;
                    })
                    .catch(() => {
                        citySelect.innerHTML = '<option value="">خطا در دریافت شهرها</option>';
                    });
            } else {
                citySelect.innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
            }
        });
    }

    document.getElementById('businessForm')?.addEventListener('submit', function(e){
        e.preventDefault();
        alert('کسب‌وکار با موفقیت ثبت شد!');
        document.getElementById('add-business-modal').style.display = 'none';
        document.body.style.overflow = '';
    });
    goStep(0);
});
</script>
