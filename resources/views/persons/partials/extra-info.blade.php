<div class="row g-3">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">تاریخ تولد</label>
            <input type="text" name="birth_date" class="form-control datepicker"
                   value="{{ old('birth_date', isset($person->birth_date) && $person->birth_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($person->birth_date))->format('Y/m/d') : '') }}" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">تاریخ ازدواج</label>
            <input type="text" name="marriage_date" class="form-control datepicker"
                   value="{{ old('marriage_date', isset($person->marriage_date) && $person->marriage_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($person->marriage_date))->format('Y/m/d') : '') }}" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">تاریخ عضویت</label>
            <input type="text" name="join_date" class="form-control datepicker"
                   value="{{ old('join_date', isset($person->join_date) && $person->join_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($person->join_date))->format('Y/m/d') : date('Y/m/d')) }}" autocomplete="off">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">کد اقتصادی</label>
            <input type="text" name="economic_code" class="form-control"
                   value="{{ old('economic_code', $person->economic_code ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">شماره ثبت</label>
            <input type="text" name="registration_number" class="form-control"
                   value="{{ old('registration_number', $person->registration_number ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">اعتبار مالی (ریال)</label>
            <input type="number" name="credit_limit" class="form-control"
                   value="{{ old('credit_limit', $person->credit_limit ?? 0) }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label">توضیحات</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $person->description ?? '') }}</textarea>
        </div>
    </div>
</div>
