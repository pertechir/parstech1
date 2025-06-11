// این فایل را در public/js/businesses-modal.js قرار بده

function initBusinessModalScripts() {
    // اگر قبلاً اسکریپت اجرا شده بود، دوباره اجرا نکن!
    if (window.__business_modal_inited) return;
    window.__business_modal_inited = true;

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

    document.getElementById('next-step-1')?.addEventListener('click', function() { goStep(1); syncNameStep2(); });
    document.getElementById('next-step-2')?.addEventListener('click', function() { goStep(2); });
    document.getElementById('prev-step-2')?.addEventListener('click', function() { goStep(0); });
    document.getElementById('prev-step-3')?.addEventListener('click', function() { goStep(1); });

    // باز و بسته شدن پاپ‌آپ داخلی
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

    // ثبت فرم
    document.getElementById('businessForm')?.addEventListener('submit', function(e){
        e.preventDefault();
        alert('کسب‌وکار با موفقیت ثبت شد!');
        document.getElementById('add-business-modal').style.display = 'none';
        document.body.style.overflow = '';
    });

    goStep(0);
}

// وقتی پاپ‌آپ از طریق Ajax لود شد، این تابع را اجرا کن
document.addEventListener('businesses-modal-loaded', function() {
    initBusinessModalScripts();
});
