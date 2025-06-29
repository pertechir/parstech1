@extends('layouts.app')

@section('title', 'دسته‌بندی‌ها – حالت درختی جدولی')

@section('content')
<link rel="stylesheet" href="{{ asset('css/category-list.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="category-list-container">
    <div class="category-list-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="mb-0 fs-4 fw-bold">دسته‌بندی‌ها (ساختار درختی-جدولی)</h2>
        <a href="{{ route('categories.create') }}" class="btn btn-success fw-bold px-4 py-2">
            <i class="bi bi-plus-circle me-1"></i>افزودن دسته جدید
        </a>
    </div>
    <div class="category-list-body">
        <div class="table-responsive">
            <table class="table table-hover category-table align-middle text-center w-100 min-w-700" id="categories-table">
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
                    @foreach($categories as $category)
                        @component('categories.partials.table-tree-row', ['category' => $category]) @endcomponent
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($categories->count() == 0)
            <div class="text-center text-secondary py-4">دسته‌ای وجود ندارد.</div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.tree-toggle').forEach(function(btn){
            btn.addEventListener('click', function(){
                const target = this.getAttribute('data-bs-target');
                document.querySelectorAll(target).forEach(function(row){
                    row.classList.toggle('show');
                });
                this.classList.toggle('collapsed');
                const icon = this.querySelector('i');
                if(icon) icon.classList.toggle('bi-caret-left-fill'), icon.classList.toggle('bi-caret-down-fill');
            });
        });
    });
</script>
@endsection
