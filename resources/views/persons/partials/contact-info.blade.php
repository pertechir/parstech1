<div class="row g-3">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">تلفن ثابت</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $person->phone ?? '') }}" placeholder="مثال: 05144234567">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">تلفن ۱</label>
            <input type="text" name="phone1" class="form-control @error('phone1') is-invalid @enderror"
                   value="{{ old('phone1', $person->phone1 ?? '') }}">
            @error('phone1') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">تلفن ۲</label>
            <input type="text" name="phone2" class="form-control @error('phone2') is-invalid @enderror"
                   value="{{ old('phone2', $person->phone2 ?? '') }}">
            @error('phone2') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">تلفن ۳</label>
            <input type="text" name="phone3" class="form-control @error('phone3') is-invalid @enderror"
                   value="{{ old('phone3', $person->phone3 ?? '') }}">
            @error('phone3') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">فکس</label>
            <input type="text" name="fax" class="form-control @error('fax') is-invalid @enderror"
                   value="{{ old('fax', $person->fax ?? '') }}">
            @error('fax') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">ایمیل</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $person->email ?? '') }}" dir="ltr">
            @error('email') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label">وب‌سایت</label>
            <input type="url" name="website" class="form-control @error('website') is-invalid @enderror"
                   value="{{ old('website', $person->website ?? '') }}" dir="ltr" placeholder="https://">
            @error('website') <div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
