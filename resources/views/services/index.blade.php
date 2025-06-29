@extends('layouts.app')
@section('title', 'لیست خدمات')

@section('head')
<link rel="stylesheet" href="{{ asset('css/products-index.css') }}">
<style>
  /* اگر استایل اختصاصی دیگری لازم داشتی اینجا بگذار */
</style>
@endsection

@section('content')
<div class="container my-4">
    <div class="card shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%)">
            <h5 class="mb-0 fw-bold"><i class="fa fa-list-alt ms-2"></i>لیست خدمات</h5>
            <a href="{{ route('services.create') }}" class="btn btn-success fw-bold">
                <i class="fa fa-plus-circle ms-1"></i>افزودن خدمت جدید
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover align-middle products-list-table" id="servicesTable">
                    <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>تصویر</th>
                            <th>نام خدمات</th>
                            <th>کد خدمات</th>
                            <th>دسته‌بندی</th>
                            <th>واحد</th>
                            <th>قیمت (تومان)</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td>{{ ($services->currentPage()-1)*$services->perPage() + $loop->iteration }}</td>
                            <td>
                                @if($service->image)
                                    <img src="{{ asset('storage/'.$service->image) }}" alt="تصویر" style="width:46px; height:46px; object-fit:cover; border-radius:10px;">
                                @else
                                    <img src="{{ asset('img/no-image-service.svg') }}" style="width:46px; height:46px; object-fit:cover; border-radius:10px;">
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold">{{ $service->title }}</span>
                                <div class="small text-muted">{{ Str::limit($service->short_description, 32) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $service->service_code }}</span>
                            </td>
                            <td>
                                @php
                                    $cat = $serviceCategories->firstWhere('id', $service->service_category_id);
                                @endphp
                                <span class="badge bg-info text-white">{{ $cat ? $cat->name : '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">{{ $service->unit }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($service->price) }}</span>
                            </td>
                            <td>
                                @if($service->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-danger">غیرفعال</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-outline-info" title="ویرایش"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-sm btn-outline-secondary" title="نمایش"><i class="fa fa-eye"></i></a>
                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('آیا از حذف این خدمت مطمئن هستید؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-secondary py-4">هیچ خدمتی ثبت نشده است.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <nav>
                {!! $services->appends(request()->all())->links('pagination::bootstrap-5') !!}
            </nav>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#servicesTable').DataTable({
        language: { url: '/js/datatables/fa.json' }
    });
});
</script>
@endsection
