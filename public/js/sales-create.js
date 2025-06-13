
    $(function() {
        // راه‌اندازی تاریخ جلالی با پلاگین
        if (typeof persianDate !== "undefined" && typeof $.fn.persianDatepicker === "function") {
            $('#issued_at_jalali').persianDatepicker({
                format: 'YYYY/MM/DD',
                initialValue: false,
                autoClose: true,
                toolbox: {calendarSwitch: {enabled: false}},
                calendar: {persian: {locale: 'fa'}},
                onSelect: function(unix){
                    let pd = new persianDate(unix);
                    $('#issued_at_jalali').val(pd.format('YYYY/MM/DD'));
                }
            });
            // اگر مقدار اولیه لازم است
            if(!$('#issued_at_jalali').val()){
                var now = new persianDate();
                $('#issued_at_jalali').val(now.format('YYYY/MM/DD'));
            }
        } else {
            // اگر پلاگین جلالی نبود تاریخ میلادی نمایش داده شود
            var date = new Date();
            var pad = n => n < 10 ? '0'+n : n;
            var miladi = date.getFullYear() + '-' + pad(date.getMonth()+1) + '-' + pad(date.getDate());
            $('#issued_at_jalali').val(miladi);
        }
        $('#issued_at_jalali').prop('readonly', false).css('background', '#fff').css('cursor', 'pointer');

        // جستجوی ایجکس مشتری
        $('#customer_search').on('input', function() {
            let val = $(this).val();
            if(val.length < 1) { $('#customer-search-results').hide(); return; }
            $.get('/customers/ajax-list', {q: val, limit: 10}, function(data) {
                let html = '';
                if(data.length) {
                    data.forEach(function(c) {
                        let name = (c.first_name ? c.first_name : '') + ' ' + (c.last_name ? c.last_name : '');
                        let company = c.company_name ? ' - ' + c.company_name : '';
                        let mobile = c.mobile ? ' <small class="text-muted">' + c.mobile + '</small>' : '';
                        html += `<div class="dropdown-item" data-id="${c.id}">${name}${company}${mobile}</div>`;
                    });
                    $('#customer-search-results').html(html).show();
                } else {
                    $('#customer-search-results').hide();
                }
            }).fail(function(xhr) {
                $('#customer-search-results').hide();
                alert('خطا در ارتباط با سرور یا دریافت اطلاعات مشتری! لطفا با پشتیبانی تماس بگیرید.');
            });
        });
        $(document).on('click', '#customer-search-results .dropdown-item', function() {
            $('#customer_id').val($(this).data('id'));
            $('#customer_search').val($(this).text());
            $('#customer-search-results').hide();
        });
        $(document).on('click', function(e) {
            if(!$(e.target).closest('#customer_search').length) $('#customer-search-results').hide();
        });

        // اگر محصولات و خدمات با AJAX لود می‌شوند، لازم است sales-invoice.js و سایر اسکریپت‌ها لود شود
        // اگر مشکلی در نمایش محصولات بود، مطمئن شو فایل sales-invoice.js وجود دارد و در مسیر public/js است و هیچ خطای 404 در کنسول نیست
        // اگر لیست محصول/خدمت باز هم نمی‌آید، همینجا اطلاع بده تا کدهای AJAX آن بخش را هم بنویسم
    });
