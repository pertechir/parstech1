@extends('layouts.app')

@section('content')
<div class="container">
    <h1>آیتم‌های انبار: {{ $warehouse->name }}</h1>
    <ul>
        @foreach($items as $item)
            <li>{{ $item->product->name }} - موجودی: {{ $item->stock }}</li>
        @endforeach
    </ul>
    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">بازگشت به لیست انبارها</a>
</div>
@endsection
