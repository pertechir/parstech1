@extends('layouts.app')

@section('title', 'مدیریت اشخاص حسابداری شخصی')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">
            <i class="fas fa-users"></i>
            مدیریت اشخاص حسابداری شخصی
        </h2>
        <a href="{{ route('personal_accounting.people.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus"></i>
            افزودن شخص جدید
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <b>لیست اشخاص</b>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>نام</th>
                        <th>موبایل</th>
                        <th>وضعیت مالی</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($people as $person)
                        <tr>
                            <td>{{ $person->full_name ?? ($person->first_name . ' ' . $person->last_name) }}</td>
                            <td>{{ $person->mobile }}</td>
                            <td>
                                @php
                                    $balance = $person->personal_balance ?? 0;
                                @endphp
                                <span class="{{ $balance > 0 ? 'text-success' : ($balance < 0 ? 'text-danger' : 'text-secondary') }}">
                                    {{ number_format($balance) }} تومان
                                    @if($balance > 0)
                                        (طلبکار)
                                    @elseif($balance < 0)
                                        (بدهکار)
                                    @else
                                        (تسویه)
                                    @endif
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('personal_accounting.person', $person->id) }}" class="btn btn-primary btn-sm">
                                    مشاهده تراکنش‌ها
                                </a>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">هیچ شخصی ثبت نشده است.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
