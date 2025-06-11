@extends('layouts.app')

@section('content')
<div class="container">
    <h2>ایجاد کسب‌وکار جدید</h2>
    <form method="POST" action="{{ route('businesses.store') }}">
        @csrf
        <input type="text" name="name" placeholder="نام کسب‌وکار">
        <button type="submit">ثبت</button>
    </form>
</div>
@endsection
