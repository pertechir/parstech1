@extends('layouts.app')

@section('title', 'ویرایش شخص')

@section('content')
<!-- فونت Vazirmatn -->
<link href="https://cdn.jsdelivr.net/npm/@fontsource/vazirmatn@latest/400.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fontsource/vazirmatn@latest/700.css" rel="stylesheet" />
<!-- persian-datepicker CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<style>
:root {
  --main-bg: #f7fafc;
  --header-bg: #e9ecef;
  --primary: #246bfd;
  --primary-dark: #174cb1;
  --accent1: #32c48d;
  --danger: #f04848;
  --muted: #6c757d;
  --text: #222b45;
  --border: #dde3ea;
  --card-bg: #fff;
  --radius-lg: 16px;
  --radius-md: 8px;
  --radius-sm: 4px;
  --section-shadow: 0 2px 16px 0 rgba(36, 107, 253, 0.06);
  --transition: all .22s cubic-bezier(.4,0,.2,1);
}
body, .large-form-container {
    background: var(--main-bg);
    font-family: 'Vazirmatn', Tahoma, Arial, sans-serif;
    color: var(--text);
    direction: rtl;
}
.large-form-container {
    max-width: 1100px;
    margin: 2rem auto;
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    box-shadow: var(--section-shadow);
    padding: 2.5rem 2rem;
    direction: rtl;
}
@media (max-width: 768px) {
    .large-form-container {padding: 0.7rem;}
}
.form-section {
    margin-bottom: 2rem;
    border-radius: var(--radius-lg);
    background: var(--card-bg);
    box-shadow: 0 1px 8px 0 rgba(36,107,253,0.06);
}
.section-header {
    background: var(--header-bg);
    color: var(--primary-dark);
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    padding: 1rem 1.4rem;
    font-weight: 700;
    font-size: 1.12rem;
    display: flex;
    align-items: center;
    gap: .65rem;
    margin-bottom: 0;
}
.section-header .section-icon {
    font-size: 1.18em;
    margin-left: .5rem;
    opacity: 0.80;
}
.section-header .step-num {
    background: var(--accent1);
    color: #fff;
    border-radius: 50px;
    font-size: .98em;
    padding: .1em .7em;
    margin-left: .7em;
}
.section-body {
    padding: 2rem 1.4rem 1.4rem 1.4rem;
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    background: var(--card-bg);
}
@media (max-width:600px) {
    .section-body {padding: 0.9rem;}
    .section-header {padding: .7rem;}
}
.form-label {
    font-weight: 700;
    color: var(--primary);
    font-size: 1.05rem;
    margin-bottom: .3rem;
    display: block;
    text-align: right;
}
.required-field:after {
    content: "*";
    color: var(--danger);
    margin-right: .18em;
}
.form-control {
    border-radius: var(--radius-md)!important;
    border: 1.5px solid var(--border);
    font-size: 1.03rem;
    background: var(--main-bg);
    transition: var(--transition);
    padding: .6rem 1rem;
    min-height: 41px;
    direction: rtl;
    text-align: right;
    font-family: inherit;
}
input:focus, select:focus,textarea:focus {
    border-color: var(--primary);
    background: #fff;
}
.is-invalid, .is-invalid:focus {
    border-color: var(--danger)!important;
    background: #ffeaea!important;
}
.invalid-feedback {
    font-size: .95rem;
    color: var(--danger);
    margin-top: 3px;
    text-align: right;
}
input[type=text],input[type=email],input[type=number],input[type=url],select,textarea {
    width: 100%;
}
textarea {resize: vertical;}
.accounting-code-container {display:flex;align-items:center;gap:.6rem;}
.switch {position:relative;display:inline-block;width:42px;height:24px;}
.switch input {display:none;}
.slider {position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#cbd5e1;border-radius:24px;transition:.35s;}
.slider:before {position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background:white;transition:.35s;border-radius:50%;}
.switch input:checked + .slider {background:var(--accent1);}
.switch input:checked + .slider:before {transform:translateX(18px);}
.accounting-code-label { font-size:.97em;color:var(--muted);}
.form-section .row { margin-bottom: 1.1rem;}
.form-actions {
    gap:1.2rem;
    display:flex;
    flex-wrap:wrap;
    margin-top:2.2rem;
    justify-content: flex-start;
}
.btn {
    min-width: 90px;
    padding: .6rem 1.35rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    border: none;
    transition: var(--transition);
    font-size: 1.01rem;
    letter-spacing: .03em;
    box-shadow: 0 2px 8px rgba(36,107,253,0.07);
}
.btn-primary {
    background: var(--primary);
    color: #fff;
}
.btn-primary:hover, .btn-primary:focus { background: var(--primary-dark);}
.btn-secondary {
    background: var(--accent1);
    color: #fff;
}
.btn-secondary:hover, .btn-secondary:focus { filter: brightness(0.93);}
.btn-light {
    background: #f4f7fa;
    color: var(--primary);
}
.btn-light:hover { background: var(--header-bg);}
.btn-danger {background: var(--danger); color: #fff;}
.btn-danger:hover {background: #ffeaea; color: var(--danger);}
.bank-account-row {background: var(--main-bg);}
.bank-account-row input {background: #fff;}
.alert {
    border-radius: var(--radius-md);
    font-size: 1.06em;
}
@media (max-width:991px){
    .large-form-container {padding: 1.2rem;}
}
@media (max-width:600px){
    .large-form-container {padding:.25rem;}
}
::-webkit-input-placeholder { color: #b6bbc3 !important; }
::-moz-placeholder { color: #b6bbc3 !important; }
:-ms-input-placeholder { color: #b6bbc3 !important; }
::placeholder { color: #b6bbc3 !important; }
.persian-datepicker-container {
    direction: rtl !important;
}
select.form-control {
    padding-right: 1rem;
}
</style>

<div class="large-form-container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="person-form" action="{{ route('persons.update', $person->id) }}" method="POST" novalidate autocomplete="off">
                @csrf
                @method('PUT')

                <!-- اطلاعات اصلی -->
                <div class="form-section">
                    <div class="section-header main-info">
                        <span class="step-num">۱</span>
                        <i class="fas fa-user section-icon"></i>
                        اطلاعات اصلی شخص
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <!-- نام -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="first_name">نام</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name', $person->first_name) }}" required>
                                    @error('first_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- نام خانوادگی -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="last_name">نام خانوادگی</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name', $person->last_name) }}" required>
                                    @error('last_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- نوع -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="type">نوع شخص</label>
                                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="">انتخاب کنید</option>
                                        <option value="customer" {{ old('type', $person->type) == 'customer' ? 'selected' : '' }}>مشتری</option>
                                        <option value="supplier" {{ old('type', $person->type) == 'supplier' ? 'selected' : '' }}>تامین کننده</option>
                                        <option value="shareholder" {{ old('type', $person->type) == 'shareholder' ? 'selected' : '' }}>سهامدار</option>
                                        <option value="employee" {{ old('type', $person->type) == 'employee' ? 'selected' : '' }}>کارمند</option>
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- دسته‌بندی -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="category">دسته‌بندی</label>
                                    <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $person->category) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- کد حسابداری -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="accounting_code">کد مشتری</label>
                                    <div class="accounting-code-container">
                                        <input type="text" name="accounting_code"
                                               id="accounting_code"
                                               class="form-control @error('accounting_code') is-invalid @enderror"
                                               value="{{ old('accounting_code', $person->accounting_code) }}"
                                               required {{ old('auto_code', '0') === '1' ? 'readonly' : '' }}>
                                        <label class="switch" style="margin-bottom: 0;">
                                            <input type="checkbox" id="autoCodeSwitch" name="auto_code"
                                                   value="1" {{ old('auto_code', '0') === '1' ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="accounting-code-label">کد خودکار</span>
                                    </div>
                                    @error('accounting_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- شرکت -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="company_name">شرکت</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $person->company_name) }}">
                                </div>
                            </div>
                            <!-- عنوان -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="title">عنوان</label>
                                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $person->title) }}">
                                </div>
                            </div>
                            <!-- نام مستعار -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="nickname">نام مستعار</label>
                                    <input type="text" name="nickname" id="nickname" class="form-control"
                                           value="{{ old('nickname', $person->nickname) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- اطلاعات عمومی -->
                <div class="form-section">
                    <div class="section-header general-info">
                        <span class="step-num">۲</span>
                        <i class="fas fa-info-circle section-icon"></i>
                        اطلاعات عمومی
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="credit_limit">اعتبار مالی (ریال)</label>
                                    <input type="number" name="credit_limit" id="credit_limit" class="form-control" value="{{ old('credit_limit', $person->credit_limit) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="price_list">لیست قیمت</label>
                                    <input type="text" name="price_list" id="price_list" class="form-control" value="{{ old('price_list', $person->price_list) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="tax_type">نوع مالیات</label>
                                    <input type="text" name="tax_type" id="tax_type" class="form-control" value="{{ old('tax_type', $person->tax_type) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="national_code">کد ملی</label>
                                    <input type="text" name="national_code" id="national_code" class="form-control @error('national_code') is-invalid @enderror"
                                           value="{{ old('national_code', $person->national_code) }}" maxlength="10" pattern="\d{10}">
                                    @error('national_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="economic_code">کد اقتصادی</label>
                                    <input type="text" name="economic_code" id="economic_code" class="form-control" value="{{ old('economic_code', $person->economic_code) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="registration_number">شماره ثبت</label>
                                    <input type="text" name="registration_number" id="registration_number" class="form-control" value="{{ old('registration_number', $person->registration_number) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="branch_code">کد شعبه</label>
                                    <input type="text" name="branch_code" id="branch_code" class="form-control" value="{{ old('branch_code', $person->branch_code) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="description">توضیحات</label>
                                    <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $person->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- آدرس -->
                <div class="form-section">
                    <div class="section-header address-info">
                        <span class="step-num">۳</span>
                        <i class="fas fa-map-marker-alt section-icon"></i>
                        آدرس
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="address">آدرس کامل</label>
                                    <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $person->address) }}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- کشور -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="country">کشور</label>
                                    <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror"
                                           value="{{ old('country', $person->country ?? 'ایران') }}" required>
                                    @error('country')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- استان -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="province">استان</label>
                                    <select name="province" id="province" class="form-control @error('province') is-invalid @enderror" required>
                                        <option value="">انتخاب استان</option>
                                    </select>
                                    @error('province')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- شهر -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="city">شهر</label>
                                    <select name="city" id="city" class="form-control @error('city') is-invalid @enderror" required>
                                        <option value="">ابتدا استان را انتخاب کنید</option>
                                    </select>
                                    @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- کد پستی -->
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="postal_code">کد پستی</label>
                                    <input type="text" name="postal_code" id="postal_code" class="form-control"
                                           value="{{ old('postal_code', $person->postal_code) }}" maxlength="10" pattern="\d{10}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- اطلاعات تماس -->
                <div class="form-section">
                    <div class="section-header contact-info">
                        <span class="step-num">۴</span>
                        <i class="fas fa-phone section-icon"></i>
                        اطلاعات تماس
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="phone">تلفن(ثابت)</label>
                                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $person->phone) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="mobile">موبایل</label>
                                    <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile', $person->mobile) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="fax">فکس</label>
                                    <input type="text" name="fax" id="fax" class="form-control" value="{{ old('fax', $person->fax) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="phone1">تلفن ۱</label>
                                    <input type="text" name="phone1" id="phone1" class="form-control" value="{{ old('phone1', $person->phone1) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="phone2">تلفن ۲</label>
                                    <input type="text" name="phone2" id="phone2" class="form-control" value="{{ old('phone2', $person->phone2) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="phone3">تلفن ۳</label>
                                    <input type="text" name="phone3" id="phone3" class="form-control" value="{{ old('phone3', $person->phone3) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="email">ایمیل</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $person->email) }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="website">وب سایت</label>
                                    <input type="url" name="website" id="website" class="form-control" value="{{ old('website', $person->website) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- حساب‌های بانکی -->
                <div class="form-section">
                    <div class="section-header bank-info">
                        <span class="step-num">۵</span>
                        <i class="fas fa-university section-icon"></i>
                        حساب‌های بانکی
                        <button type="button" class="btn btn-light ms-auto" id="add-bank-account" style="margin-right:auto;">
                            <i class="fas fa-plus"></i> افزودن حساب بانکی
                        </button>
                    </div>
                    <div class="section-body">
                        <div id="bank-accounts">
                            @php
                            $bankAccounts = old('bank_accounts', $person->bank_accounts ?? []);
                            @endphp
                            @foreach($bankAccounts as $idx => $account)
                                <div class="bank-account-row mb-3 border rounded p-2" data-index="{{ $idx }}">
                                    <div class="form-row row">
                                        <div class="col-md-2 mb-2">
                                            <input type="text" name="bank_accounts[{{ $idx }}][bank_name]" class="form-control" placeholder="نام بانک"
                                                value="{{ $account['bank_name'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <input type="text" name="bank_accounts[{{ $idx }}][branch]" class="form-control" placeholder="شعبه"
                                                value="{{ $account['branch'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <input type="text" name="bank_accounts[{{ $idx }}][account_number]" class="form-control" placeholder="شماره حساب"
                                                value="{{ $account['account_number'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <input type="text" name="bank_accounts[{{ $idx }}][card_number]" class="form-control" placeholder="شماره کارت"
                                                value="{{ $account['card_number'] ?? '' }}">
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <input type="text" name="bank_accounts[{{ $idx }}][iban]" class="form-control" placeholder="شماره شبا"
                                                value="{{ $account['iban'] ?? '' }}">
                                        </div>
                                        <div class="col-md-1 mb-2 d-flex align-items-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-bank-account" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- تاریخ‌ها -->
                <div class="form-section">
                    <div class="section-header date-info">
                        <span class="step-num">۶</span>
                        <i class="fas fa-calendar section-icon"></i>
                        تاریخ‌ها
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="birth_date">تاریخ تولد</label>
                                    <input type="text" name="birth_date" id="birth_date" class="form-control datepicker" value="{{ old('birth_date', $person->birth_date) }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="form-label" for="marriage_date">تاریخ ازدواج</label>
                                    <input type="text" name="marriage_date" id="marriage_date" class="form-control datepicker" value="{{ old('marriage_date', $person->marriage_date) }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="form-label required-field" for="join_date">تاریخ عضویت</label>
                                    <input type="text" name="join_date" id="join_date" class="form-control datepicker"
                                           value="{{ old('join_date', $person->join_date) }}" autocomplete="off" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- دکمه‌های فرم -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> ذخیره تغییرات
                    </button>
                    <a href="{{ route('persons.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> انصراف
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- فونت آیکون اگر در layout نیست -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- PersianDate & PersianDatePicker -->
<script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.js"></script>
<script>
// استان و شهر نمونه (در پروژه واقعی، از سرور بگیر!)
const provinces = [
    { id: 1, name: 'تهران', cities: ['تهران', 'شمیرانات', 'اسلامشهر', 'ری'] },
    { id: 2, name: 'اصفهان', cities: ['اصفهان', 'کاشان', 'خمینی‌شهر'] },
    { id: 3, name: 'فارس', cities: ['شیراز', 'مرودشت', 'کازرون'] },
    { id: 4, name: 'خراسان رضوی', cities: ['مشهد', 'نیشابور', 'سبزوار'] },
    { id: 5, name: 'آذربایجان شرقی', cities: ['تبریز', 'مراغه', 'مرند'] }
];

document.addEventListener('DOMContentLoaded', function() {
    // تاریخ شمسی
    document.querySelectorAll('.datepicker').forEach(function(input) {
        $(input).persianDatepicker({
            format: 'YYYY/MM/DD',
            initialValue: !!input.value,
            autoClose: true,
            observer: true,
            toolbox: {
                calendarSwitch: { enabled: false },
                todayButton: { enabled: true },
                submitButton: { enabled: true }
            }
        });
    });

    // استان/شهر
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    // استان‌ها را لود کن
    provinceSelect.innerHTML = '<option value="">انتخاب استان</option>' + provinces.map(
        p => `<option value="${p.name}" ${p.name === "{{ old('province', $person->province) }}" ? 'selected' : ''}>${p.name}</option>`
    ).join('');
    // اگر استان انتخاب شده قبلی وجود دارد، شهرها را لود کن
    function loadCities(selectedProvince, selectedCity) {
        citySelect.innerHTML = '<option value="">انتخاب شهر</option>';
        const provinceObj = provinces.find(p => p.name === selectedProvince);
        if(provinceObj) {
            provinceObj.cities.forEach(function(city) {
                citySelect.innerHTML += `<option value="${city}" ${city === selectedCity ? 'selected' : ''}>${city}</option>`;
            });
        }
    }
    // نخستین بار
    loadCities("{{ old('province', $person->province) }}", "{{ old('city', $person->city) }}");
    // با تغییر استان
    provinceSelect.addEventListener('change', function() {
        loadCities(this.value, "");
    });

    // حساب‌های بانکی داینامیک
    let bankAccountIndex = document.querySelectorAll('#bank-accounts .bank-account-row').length || 0;
    document.getElementById('add-bank-account').addEventListener('click', function() {
        bankAccountIndex++;
        let html = `
            <div class="bank-account-row mb-3 border rounded p-2" data-index="${bankAccountIndex}">
                <div class="form-row row">
                    <div class="col-md-2 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][bank_name]" class="form-control" placeholder="نام بانک">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][branch]" class="form-control" placeholder="شعبه">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][account_number]" class="form-control" placeholder="شماره حساب">
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][card_number]" class="form-control" placeholder="شماره کارت">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="bank_accounts[${bankAccountIndex}][iban]" class="form-control" placeholder="شماره شبا">
                    </div>
                    <div class="col-md-1 mb-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-bank-account" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('bank-accounts').insertAdjacentHTML('beforeend', html);
    });
    document.getElementById('bank-accounts').addEventListener('click', function(e) {
        if(e.target.closest('.remove-bank-account')) {
            e.target.closest('.bank-account-row').remove();
        }
    });

    // اعتبارسنجی ساده
    document.getElementById('person-form').addEventListener('submit', function(e) {
        let isValid = true;
        this.querySelectorAll('[required]').forEach(function(input){
            if(!input.value || input.value.trim() === ''){
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        if(!isValid) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    document.querySelectorAll('input, select, textarea').forEach(function(el){
        el.addEventListener('input', function(){
            if(this.value.trim() !== '') {
                this.classList.remove('is-invalid');
            }
        });
    });

    // کد مشتری خودکار
    let autoCodeSwitch = document.getElementById('autoCodeSwitch');
    if(autoCodeSwitch){
        autoCodeSwitch.addEventListener('change', function(){
            let codeInput = document.getElementById('accounting_code');
            if(this.checked){
                codeInput.setAttribute('readonly', 'readonly');
                // اگر API برای گرفتن کد اتومات داری اینجا AJAX بنویس، فعلا فقط خالی می‌کند:
                codeInput.value = "{{ $person->accounting_code ?? '' }}";
            } else {
                codeInput.removeAttribute('readonly');
            }
        });
    }
});
</script>
@endsection
