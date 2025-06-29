<div class="row g-3">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label required-field">کشور</label>
            <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                   value="{{ old('country', $person->country ?? 'ایران') }}" required>
            @error('country') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label required-field">استان</label>
            <select name="province" id="province_select" class="form-control select2 @error('province') is-invalid @enderror" required>
                <option value="">انتخاب استان</option>
                @foreach($provinces as $province)
                <option value="{{ $province->id }}" {{ old('province', $person->province ?? '') == $province->id ? 'selected' : '' }}>
                    {{ $province->name }}
                </option>
                @endforeach
            </select>
            @error('province') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label required-field">شهر</label>
            <select name="city" id="city_select" class="form-control select2 @error('city') is-invalid @enderror" required>
                <option value="">ابتدا استان را انتخاب کنید</option>
            </select>
            @error('city') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">کد پستی</label>
            <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
                   value="{{ old('postal_code', $person->postal_code ?? '') }}" maxlength="10">
            @error('postal_code') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label required-field">آدرس کامل</label>
            <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $person->address ?? '') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
