@extends('layouts.app')

@section('content')
<div class="container">
    <h1>افزودن انبار جدید</h1>
    <form action="{{ route('warehouses.store') }}" method="post">
        @csrf
        <div class="mb-3">
            <label>نام انبار</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <!-- سایر فیلدهای انبار ... -->

        <h4>همه محصولات به این انبار اضافه خواهند شد:</h4>
        <ul>
            @foreach($products as $product)
                <li>{{ $product->name }} (کد: {{ $product->code }})</li>
            @endforeach
        </ul>

        <button class="btn btn-success">ثبت و افزودن همه محصولات به انبار</button>
    </form>
</div>
@endsection
