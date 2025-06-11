@extends('layouts.app')

@section('content')
<div class="container">
    <h2>انتخاب کسب‌وکار</h2>
    <ul>
        @foreach($businesses as $business)
            <li>
                <a href="{{ route('business.switch', $business->id) }}">
                    {{ $business->data['name'] ?? $business->id }}
                </a>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('business.create') }}" class="btn btn-success">افزودن کسب‌وکار جدید</a>
</div>
@endsection
