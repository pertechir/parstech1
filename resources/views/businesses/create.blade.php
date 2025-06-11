@extends('layouts.app')

@section('content')
<div class="container">
    <h2>افزودن کسب‌وکار جدید</h2>
    <form method="POST" action="{{ route('business.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">نام کسب‌وکار</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button class="btn btn-success">ثبت کسب‌وکار</button>
    </form>
</div>
@endsection
