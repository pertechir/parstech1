document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('businesses-btn');
    const modal = document.getElementById('businesses-modal');
    const closeBtn = document.getElementById('close-businesses-modal');
    const addBizBtn = document.getElementById('add-business-btn');
    const addBizModal = document.getElementById('add-business-modal');
    const closeAddBizModal = document.getElementById('close-add-business-modal');
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

    // انتقال مقدار نام کسب‌وکار از مرحله ۱ به مرحله ۲
    const bizNameInput = document.getElementById('biz_name_input');
    const bizNameStep2 = document.getElementById('biz_name_step2');
    const legalNameStep2 = document.getElementById('legal_name_step2');
    if(bizNameInput && bizNameStep2 && legalNameStep2) {
        bizNameInput.addEventListener('input', function() {
            bizNameStep2.value = this.value;
            legalNameStep2.value = this.value;
        });
    }

    if(openBtn && modal && closeBtn) {
        openBtn.addEventListener('click', function() {
            modal.style.display = 'block';
        });
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            addBizModal.style.display = 'none';
            document.body.style.overflow = '';
        });
        document.querySelector('.businesses-modal-bg').addEventListener('click', function() {
            modal.style.display = 'none';
            addBizModal.style.display = 'none';
            document.body.style.overflow = '';
        });
    }
    if(addBizBtn && addBizModal) {
        addBizBtn.addEventListener('click', function() {
            addBizModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            goStep(0);
        });
    }
    if(closeAddBizModal && addBizModal) {
        closeAddBizModal.addEventListener('click', function(e) {
            e.preventDefault();
            addBizModal.style.display = 'none';
            document.body.style.overflow = '';
        });
    }
    document.getElementById('next-step-1')?.addEventListener('click', function() {
        goStep(1);
        if(bizNameInput && bizNameStep2 && legalNameStep2) {
            bizNameStep2.value = bizNameInput.value;
            legalNameStep2.value = bizNameInput.value;
        }
    });
    document.getElementById('next-step-2')?.addEventListener('click', function() {
        goStep(2);
    });
    document.getElementById('prev-step-2')?.addEventListener('click', function() {
        goStep(0);
    });
    document.getElementById('prev-step-3')?.addEventListener('click', function() {
        goStep(1);
    });

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
    document.getElementById('businessForm')?.addEventListener('submit', function(e){
        e.preventDefault();
        alert('کسب‌وکار با موفقیت ثبت شد!');
        addBizModal.style.display = 'none';
        document.body.style.overflow = '';
    });
    goStep(0);
});
