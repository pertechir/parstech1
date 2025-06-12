@extends('layouts.app')

@section('title', 'لیست اشخاص')

@section('content')
<style>
:root {
  --main-bg: #f7fafc;
  --header-bg: #e9ecef;
  --primary: #246bfd;
  --primary-dark: #174cb1;
  --accent1: #32c48d;
  --danger: #f04848;
  --muted: #6c757d;
  --text: #222b45;
  --border: #dde3ea;
  --card-bg: #fff;
  --radius-lg: 16px;
  --radius-md: 8px;
  --radius-sm: 4px;
  --section-shadow: 0 2px 16px 0 rgba(36, 107, 253, 0.06);
  --transition: all .22s cubic-bezier(.4,0,.2,1);
}
body {
    background: var(--main-bg);
    font-family: 'Vazirmatn', Tahoma, Arial, sans-serif;
    color: var(--text);
}
.container-fluid {
    background: var(--main-bg);
    padding-top: 2.5rem;
    padding-bottom: 2rem;
}
.persons-card {
    background: var(--card-bg);
    border-radius: var(--radius-lg);
    box-shadow: var(--section-shadow);
    padding: 2.5rem 2rem;
    direction: rtl;
    margin-bottom: 2rem;
}
@media (max-width: 768px) {
    .persons-card {padding: 0.7rem;}
}
.section-header {
    background: var(--header-bg);
    color: var(--primary-dark);
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    padding: 1rem 1.4rem;
    font-weight: 700;
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    gap: .6rem;
    box-shadow: none;
    margin-bottom: 2rem;
}
.section-header .section-icon {
    font-size: 1.15em;
    margin-left: .45rem;
    opacity: 0.70;
}
.btn {
    min-width: 90px;
    padding: .54rem 1.15rem;
    font-weight: 600;
    border-radius: var(--radius-md);
    border: none;
    transition: var(--transition);
    font-size: 1.01rem;
    letter-spacing: .03em;
    box-shadow: 0 2px 8px rgba(36,107,253,0.07);
    margin-left: 0.5rem;
}
.btn-primary {
    background: var(--primary);
    color: #fff;
}
.btn-primary:hover, .btn-primary:focus { background: var(--primary-dark);}
.btn-danger {background: var(--danger); color: #fff;}
.btn-danger:hover {background: #ffe5e5; color: var(--danger);}
.btn-warning {background: var(--accent1); color: #fff;}
.btn-warning:hover {background: #25b178;}
.btn-info {background: var(--header-bg); color: var(--primary);}
.btn-info:hover {background: var(--primary); color: #fff;}
.table {
    background: var(--card-bg);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--section-shadow);
}
.table th, .table td {
    border: 1px solid var(--border);
    padding: 0.62rem 1rem;
    vertical-align: middle;
    text-align: center;
    font-size: 1.01rem;
}
.table th {
    background: var(--header-bg);
    color: var(--primary-dark);
    font-weight: bold;
}
.table-striped tbody tr:nth-child(odd) {
    background: #f4f7fa;
}
.table-hover tbody tr:hover {
    background: #e6f1ff;
    color: var(--primary-dark);
}
.alert {
    border-radius: var(--radius-md);
    font-size: 1.01em;
    margin-bottom: 1.5rem;
}
.pagination {
    justify-content: center;
    margin-top: 2rem;
}
.pagination .page-link {
    border-radius: var(--radius-sm)!important;
    color: var(--primary-dark)!important;
    border: 1px solid var(--header-bg)!important;
    margin: 0 3px;
    font-size: 1.01em;
}
.pagination .active .page-link,
.pagination .page-link:hover {
    background: var(--primary)!important;
    color: #fff!important;
    border-color: var(--primary-dark)!important;
}
@media (max-width:600px){
    .persons-card {padding: .45rem;}
    .section-header {padding: .55rem;}
    .table th, .table td {font-size: .92rem; padding: 0.5rem 0.7rem;}
    .btn {font-size: .95rem;}
}
</style>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="persons-card">
                <div class="section-header">
                    <i class="fas fa-users section-icon"></i>
                    لیست اشخاص
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="mb-3 text-start">
                    <a href="{{ route('persons.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        افزودن شخص جدید
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>کد حسابداری</th>
                                <th>نام</th>
                                <th>نام خانوادگی</th>
                                <th>نوع</th>
                                <th>شرکت</th>
                                <th>موبایل</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($persons as $person)
                                <tr>
                                    <td>{{ $person->accounting_code }}</td>
                                    <td>{{ $person->first_name }}</td>
                                    <td>{{ $person->last_name }}</td>
                                    <td>{{ $person->type }}</td>
                                    <td>{{ $person->company_name }}</td>
                                    <td>{{ $person->mobile }}</td>
                                    <td>
                                        <a href="{{ route('persons.show', $person) }}" class="btn btn-info btn-sm" title="نمایش">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('persons.edit', $person) }}" class="btn btn-warning btn-sm" title="ویرایش">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('persons.destroy', $person) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('آیا از حذف این شخص مطمئن هستید؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        هیچ شخصی ثبت نشده است.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $persons->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- فونت آیکون لازم است اگر در لایه اصلی نیست اینجا قرار بده -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection
