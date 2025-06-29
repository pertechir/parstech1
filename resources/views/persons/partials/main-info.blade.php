<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <!-- کد حسابداری -->
            <div class="col-md-3">
                <div class="accounting-code-container">
                    <label class="form-label required-field">کد حسابداری</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="accounting_code" id="accounting_code"
                               value="{{ old('accounting_code', $person->accounting_code ?? $defaultCode ?? '') }}" readonly required>
                        <div class="input-group-append">
                            <div class="form-check form-switch ms-2 mt-2">
                                <input type="checkbox" class="form-check-input" id="autoCodeSwitch" checked>
                                <label class="form-check-label" for="autoCodeSwitch">کد خودکار</label>
                            </div>
                        </div>
                    </div>
                    @error('accounting_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- نوع -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label required-field">نوع</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">انتخاب کنید</option>
                        <option value="customer" {{ old('type', $person->type ?? '') == 'customer' ? 'selected' : '' }}>مشتری</option>
                        <option value="supplier" {{ old('type', $person->type ?? '') == 'supplier' ? 'selected' : '' }}>تامین کننده</option>
                        <option value="shareholder" {{ old('type', $person->type ?? '') == 'shareholder' ? 'selected' : '' }}>سهامدار</option>
                        <option value="employee" {{ old('type', $person->type ?? '') == 'employee' ? 'selected' : '' }}>کارمند</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- نام -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label required-field">نام</label>
                    <input type="text" name="first_name"
                           class="form-control @error('first_name') is-invalid @enderror"
                           value="{{ old('first_name', $person->first_name ?? '') }}" required>
                    @error('first_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- نام خانوادگی -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label required-field">نام خانوادگی</label>
                    <input type="text" name="last_name"
                           class="form-control @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name', $person->last_name ?? '') }}" required>
                    @error('last_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- نام شرکت -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">نام شرکت</label>
                    <input type="text" name="company_name"
                           class="form-control @error('company_name') is-invalid @enderror"
                           value="{{ old('company_name', $person->company_name ?? '') }}">
                    @error('company_name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- عنوان -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">عنوان</label>
                    <input type="text" name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $person->title ?? '') }}">
                    @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- نام مستعار -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">نام مستعار</label>
                    <input type="text" name="nickname"
                           class="form-control @error('nickname') is-invalid @enderror"
                           value="{{ old('nickname', $person->nickname ?? '') }}">
                    @error('nickname')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- کد ملی -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">کد ملی</label>
                    <input type="text" name="national_code"
                           class="form-control @error('national_code') is-invalid @enderror"
                           value="{{ old('national_code', $person->national_code ?? '') }}"
                           maxlength="10"
                           pattern="\d{10}">
                    @error('national_code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- اعتبار مالی -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">اعتبار مالی (ریال)</label>
                    <input type="number" name="credit_limit"
                           class="form-control @error('credit_limit') is-invalid @enderror"
                           value="{{ old('credit_limit', $person->credit_limit ?? 0) }}">
                    @error('credit_limit')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- لیست قیمت -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">لیست قیمت</label>
                    <input type="text" name="price_list"
                           class="form-control @error('price_list') is-invalid @enderror"
                           value="{{ old('price_list', $person->price_list ?? '') }}">
                    @error('price_list')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- کد اقتصادی -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">کد اقتصادی</label>
                    <input type="text" name="economic_code"
                           class="form-control @error('economic_code') is-invalid @enderror"
                           value="{{ old('economic_code', $person->economic_code ?? '') }}">
                    @error('economic_code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- شماره ثبت -->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">شماره ثبت</label>
                    <input type="text" name="registration_number"
                           class="form-control @error('registration_number') is-invalid @enderror"
                           value="{{ old('registration_number', $person->registration_number ?? '') }}">
                    @error('registration_number')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- توضیحات -->
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">توضیحات</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="2">{{ old('description', $person->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
