@extends('layouts.app')

@section('title', 'دسته‌بندی‌ها')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link href="{{ asset('css/category-list.css') }}" rel="stylesheet">

<div class="container my-4 px-2 px-md-4">
    <div class="bg-white shadow-lg rounded-4 overflow-hidden p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h2 class="mb-0 fs-3 fw-bold text-gradient">دسته‌بندی‌ها (جدول درختی)</h2>
            <a href="{{ route('categories.create') }}" class="btn btn-success fw-bold px-4 py-2">
                <i class="bi bi-plus-circle me-1"></i>افزودن دسته جدید
            </a>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="table-responsive">
            <table class="category-table table table-hover align-middle text-center w-100 min-w-700" id="categories-table">
                <thead>
                    <tr>
                        <th>نام دسته</th>
                        <th>کد</th>
                        <th>نوع</th>
                        <th>زیر دسته‌ها</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        @include('categories.partials.tree-table-row', ['category' => $cat, 'level' => 0])
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($categories->count() == 0)
            <div class="text-center text-secondary py-4">دسته‌ای وجود ندارد.</div>
        @endif
    </div>
</div>

<!-- استایل سفارشی برای جدول درختی -->
<style>
    .tree-toggle-btn {
        background: none;
        border: none;
        color: #1565c0;
        font-size: 1.25rem;
        cursor: pointer;
        vertical-align: middle;
        margin-left: 6px;
        margin-right: 2px;
        transition: color 0.15s;
    }
    .tree-toggle-btn:focus { outline: none; }
    .tree-level-0 td { font-weight: bold; }

    .tree-indent {
        display: inline-block;
        width: 1.7em;
    }
</style>

<!-- اسکریپت برای Expand/Collapse -->
<script src="{{ asset('js/category-tree.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tree-toggle-btn').forEach(function(btn){
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const parentId = this.dataset.id;
            const icon = this.querySelector('i');
            const rows = document.querySelectorAll('tr[data-parent="'+parentId+'"]');
            const isOpen = this.classList.toggle('opened');
            if(icon) icon.classList.toggle('bi-caret-down-fill', isOpen), icon.classList.toggle('bi-caret-left-fill', !isOpen);
            rows.forEach(function(row){
                if(isOpen) {
                    row.style.display = '';
                } else {
                    hideTreeChildren(row);
                    row.style.display = 'none';
                }
            });
        });
    });

    function hideTreeChildren(row) {
        const id = row.dataset.id;
        document.querySelectorAll('tr[data-parent="'+id+'"]').forEach(function(child){
            child.style.display = 'none';
            child.querySelectorAll('.tree-toggle-btn.opened').forEach(function(openBtn){
                openBtn.classList.remove('opened');
                let icon = openBtn.querySelector('i');
                if(icon) icon.classList.remove('bi-caret-down-fill'), icon.classList.add('bi-caret-left-fill');
            });
            hideTreeChildren(child);
        });
    }
});
</script>
@endsection
