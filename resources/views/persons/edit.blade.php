@extends('layouts.app')

@section('title', 'ویرایش ' . $person->full_name)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* استایل‌ها عین صفحه افزودن */
.preview-card { position: sticky; top: 20px; background: #fff; border-radius: 15px; box-shadow: 0 0 20px rgba(0,0,0,0.05); padding: 20px; margin-bottom: 20px; transition: all 0.3s ease; }
.preview-card:hover { box-shadow: 0 0 30px rgba(0,0,0,0.1); }
.preview-avatar { width: 80px; height: 80px; background: #4e73df; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 15px; }
.nav-tabs { border-bottom: 2px solid #e3e6f0; margin-bottom: 25px; }
.nav-tabs .nav-link { border: none; color: #858796; padding: 12px 20px; font-weight: 500; transition: all 0.3s ease; }
.nav-tabs .nav-link.active { color: #4e73df; border-bottom: 3px solid #4e73df; background: transparent; }
.tab-content { background: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 0 20px rgba(0,0,0,0.05); }
.form-label { font-weight: 500; margin-bottom: 8px; }
.form-control { border-radius: 10px; padding: 10px 15px; }
.form-control:focus { box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.15); border-color: #4e73df; }
.bank-account-row { background: #f8f9fc; border-radius: 10px; padding: 15px; margin-bottom: 15px; transition: all 0.3s ease; }
.bank-account-row:hover { background: #eaecf4; }
.required-field::after { content: '*'; color: #e74a3b; margin-right: 4px; }
.actions-bar { position: sticky; bottom: 0; background: white; padding: 15px; border-top: 1px solid #e3e6f0; text-align: left; box-shadow: 0 -5px 20px rgba(0,0,0,0.05); }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Preview Card -->
        <div class="col-lg-3">
            <div class="preview-card" id="previewCard">
                <div class="preview-avatar" id="previewAvatar">{{ mb_substr($person->full_name,0,1,'UTF-8') }}</div>
                <div class="preview-info">
                    <h5 class="mb-3" id="previewName">{{ $person->full_name }}</h5>
                    <p class="mb-2" id="previewCode">کد: {{ $person->accounting_code }}</p>
                    <p class="mb-2" id="previewType">نوع: {{ $person->type_label }}</p>
                    <p class="mb-2" id="previewMobile">موبایل: {{ $person->mobile }}</p>
                    <p class="mb-0" id="previewCompany">شرکت: {{ $person->company_name ?? '-' }}</p>
                    <div class="mt-3">
                        <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ now()->format('Y/m/d H:i:s') }}</small>
                        <br>
                        <small class="text-muted"><i class="fas fa-user me-1"></i>{{ auth()->user()->name ?? '-' }}</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Form -->
        <div class="col-lg-9">
            <form id="personForm" action="{{ route('persons.update', $person) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="auto_code" value="0" id="auto_code_input">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#main"><i class="fas fa-user me-2"></i>اطلاعات اصلی</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#contact"><i class="fas fa-phone me-2"></i>اطلاعات تماس</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#address"><i class="fas fa-map-marker-alt me-2"></i>آدرس</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#bank"><i class="fas fa-university me-2"></i>اطلاعات بانکی</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#extra"><i class="fas fa-info-circle me-2"></i>اطلاعات تکمیلی</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="main">
                        @include('persons.partials.main-info', [
                            'person' => $person,
                            'defaultCode' => $person->accounting_code
                        ])
                    </div>
                    <div class="tab-pane fade" id="contact">
                        @include('persons.partials.contact-info', ['person' => $person])
                    </div>
                    <div class="tab-pane fade" id="address">
                        @php $provinces = $provinces ?? \App\Models\Province::all(); @endphp
                        @include('persons.partials.address-info', ['person' => $person, 'provinces' => $provinces])
                    </div>
                    <div class="tab-pane fade" id="bank">
                        @include('persons.partials.bank-info', ['person' => $person])
                    </div>
                    <div class="tab-pane fade" id="extra">
                        @include('persons.partials.extra-info', ['person' => $person])
                    </div>
                </div>
                <div class="actions-bar">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>ذخیره تغییرات</button>
                    <a href="{{ route('persons.show', $person) }}" class="btn btn-secondary ms-2"><i class="fas fa-times me-2"></i>انصراف</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    var defaultProvinceId = {{ old('province', $person->province ?? 11) }};
    var defaultCityId = {{ old('city', $person->city ?? 1256) }};
    if (!$('#province_select').val()) {
        $('#province_select').val(defaultProvinceId).trigger('change');
    }
    function updatePreview() {
        const firstName = $('input[name="first_name"]').val();
        const lastName = $('input[name="last_name"]').val();
        const fullName = `${firstName} ${lastName}`.trim();
        $('#previewName').text(fullName || 'نام و نام خانوادگی');
        $('#previewAvatar').text(fullName ? fullName[0].toUpperCase() : '?');
        $('#previewCode').text(`کد: ${$('input[name="accounting_code"]').val() || '-'}`);
        $('#previewType').text(`نوع: ${$('select[name="type"] option:selected').text() || '-'}`);
        $('#previewMobile').text(`موبایل: ${$('input[name="mobile"]').val() || '-'}`);
        $('#previewCompany').text(`شرکت: ${$('input[name="company_name"]').val() || '-'}`);
    }
    $('#personForm').on('input change', 'input, select', updatePreview);
    updatePreview();

    $('.select2').select2({ theme: 'bootstrap4', width: '100%' });
    $('.datepicker').persianDatepicker({ format: 'YYYY/MM/DD', initialValue: false, autoClose: true });

    // Dynamic Bank Accounts
    let bankIndex = $('.bank-account-row').length || 0;
    $('#addBankAccount').click(function() {
        const template = $('#bankAccountTemplate').html();
        const newRow = template.replace(/INDEX/g, bankIndex++);
        $('#bankAccountsContainer').append(newRow);
    });
    $(document).on('click', '.removeBankAccount', function() {
        $(this).closest('.bank-account-row').fadeOut(300, function() { $(this).remove(); });
    });

    // استان و شهر
    $('#province_select').on('change', function() {
        var provinceId = $(this).val();
        var citySelect = $('#city_select');
        if (provinceId) {
            $.ajax({
                url: `/persons/province/${provinceId}/cities`,
                method: 'GET',
                success: function(response) {
                    citySelect.empty().prop('disabled', false);
                    citySelect.append('<option value="">انتخاب شهر</option>');
                    response.forEach(function(city) {
                        citySelect.append(`<option value="${city.id}">${city.text}</option>`);
                    });
                    @if(old('city', $person->city))
                        citySelect.val('{{ old("city", $person->city) }}');
                    @endif
                },
                error: function() {
                    citySelect.html('<option value="">خطا در دریافت شهرها</option>');
                }
            });
        } else {
            citySelect.html('<option value="">ابتدا استان را انتخاب کنید</option>');
        }
    });
    if ($('#province_select').val() == defaultProvinceId) {
        $('#province_select').trigger('change');
    }

    // اعتبارسنجی فرم
    $('#personForm').on('submit', function(e) {
        if (!$('input[name="accounting_code"]').val()) {
            e.preventDefault();
            alert('کد حسابداری نمی‌تواند خالی باشد');
            return false;
        }
    });
});
</script>
@endpush
