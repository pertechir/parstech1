@extends('layouts.app')

@section('title', 'افزودن شخص جدید')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">
            <i class="fas fa-user-plus"></i>
            افزودن شخص جدید
        </h2>
        <a href="{{ route('personal_accounting.people') }}" class="btn btn-secondary">بازگشت به لیست اشخاص</a>
    </div>
    <form method="post" action="{{ route('personal_accounting.people.store') }}" class="card p-4 shadow-sm">
        @csrf
        <div class="mb-3">
            <label class="form-label">نام</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">نام خانوادگی</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">موبایل</label>
            <input type="text" name="mobile" class="form-control">
        </div>
        <button class="btn btn-success">ذخیره</button>
    </form>
</div>
@endsection
