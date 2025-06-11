@extends('layouts.app')

@section('title', 'مدیریت انبارها')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">مدیریت انبارها</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('warehouse.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i>
                افزودن انبار جدید
            </a>
        </div>
    </div>

    {{-- فیلتر و جستجو --}}
    <form class="row g-2 mb-3" method="GET" action="{{ route('warehouse.index') }}">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control" placeholder="جستجو بر اساس نام یا کد انبار..." value="{{ request('q') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">همه وضعیت‌ها</option>
                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>فعال</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>غیرفعال</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100" type="submit">
                <i class="fas fa-search"></i> جستجو
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('warehouse.index') }}" class="btn btn-outline-secondary w-100">
                پاک‌سازی فیلترها
            </a>
        </div>
        <div class="col-md-2">
            <a href="#" class="btn btn-outline-success w-100" onclick="exportExcel(event)">
                <i class="fas fa-file-excel"></i> خروجی اکسل
            </a>
        </div>
    </form>

    {{-- جدول انبارها --}}
    <form method="POST" action="{{ route('warehouse.bulkAction') }}">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>ردیف</th>
                            <th>نام انبار</th>
                            <th>کد انبار</th>
                            <th>شعبه</th>
                            <th>تعداد کالا</th>
                            <th>موجودی کل</th>
                            <th>مسئول انبار</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($warehouses as $warehouse)
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="{{ $warehouse->id }}">
                            </td>
                            <td>{{ $loop->iteration + ($warehouses->currentPage() - 1) * $warehouses->perPage() }}</td>
                            <td>
                                @if($warehouse->icon)
                                    <img src="{{ asset('storage/'.$warehouse->icon) }}" alt="icon" class="rounded" width="32" height="32">
                                @else
                                    <i class="fas fa-warehouse fa-lg text-secondary"></i>
                                @endif
                                <a href="{{ route('warehouses.show', $warehouse) }}" class="fw-bold">
                                    {{ $warehouse->name }}
                                </a>
                            </td>
                            <td>{{ $warehouse->code }}</td>
                            <td>{{ $warehouse->branch?->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('warehouse.items', $warehouse) }}" class="btn btn-sm btn-outline-info">
                                    مشاهده ({{ $warehouse->items_count ?? 0 }})
                                </a>
                            </td>
                            <td>
                                <span class="{{ ($warehouse->total_stock ?? 0) < ($warehouse->min_stock ?? 0) ? 'text-danger fw-bold' : '' }}">
                                    {{ number_format($warehouse->total_stock ?? 0) }}
                                </span>
                                @if(($warehouse->total_stock ?? 0) < ($warehouse->min_stock ?? 0))
                                    <i class="fas fa-exclamation-circle text-danger" title="کمتر از حداقل موجودی"></i>
                                @endif
                            </td>
                            <td>{{ $warehouse->manager?->name ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $warehouse->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $warehouse->is_active ? 'فعال' : 'غیرفعال' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('warehouse.edit', $warehouse) }}" class="btn btn-sm btn-outline-primary" title="ویرایش"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('warehouse.items', $warehouse) }}" class="btn btn-sm btn-outline-secondary" title="کالاهای انبار"><i class="fas fa-cubes"></i></a>
                                <a href="{{ route('warehouse.receipts', ['warehouse'=>$warehouse->id]) }}" class="btn btn-sm btn-outline-success" title="ثبت ورود کالا"><i class="fas fa-arrow-circle-down"></i></a>
                                <a href="{{ route('warehouse.issues', ['warehouse'=>$warehouse->id]) }}" class="btn btn-sm btn-outline-danger" title="ثبت خروج کالا"><i class="fas fa-arrow-circle-up"></i></a>
                                <form action="{{ route('warehouse.destroy', $warehouse) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف انبار تایید شود؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">انباری ثبت نشده است.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- صفحه‌بندی --}}
                <div class="mt-3">
                    {{ $warehouses->withQueryString()->links() }}
                </div>
            </div>
        </div>

        {{-- اکشن‌های گروهی --}}
        <div class="row mt-2">
            <div class="col-md-3">
                <select name="action" class="form-select form-select-sm">
                    <option value="">انتخاب عملیات گروهی</option>
                    <option value="delete">حذف گروهی</option>
                    <option value="deactivate">غیرفعال‌سازی گروهی</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary btn-sm" type="submit">اعمال</button>
            </div>
        </div>
    </form>
</div>

<script>
    // انتخاب همه چک‌باکس‌ها
    document.getElementById('checkAll')?.addEventListener('click', function() {
        document.querySelectorAll('input[name="ids[]"]').forEach(cb=>cb.checked=this.checked);
    });

    // تابع ساختگی خروجی اکسل
    function exportExcel(e) {
        e.preventDefault();
        alert('خروجی اکسل، به زودی فعال می‌شود!');
    }
</script>
@endsection
