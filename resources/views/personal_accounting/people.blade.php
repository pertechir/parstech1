@extends('layouts.app')

@section('title', 'مدیریت اشخاص حسابداری شخصی')

@section('styles')
    <style>
        .modal-backdrop { z-index: 1040 !important; }
        .modal-content { z-index: 1050 !important; }
    </style>
@endsection

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">
            <i class="fas fa-users"></i>
            مدیریت اشخاص حسابداری شخصی
        </h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPersonModal">
            <i class="fas fa-user-plus"></i>
            افزودن شخص به حسابداری
        </button>
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
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($people as $person)
                        <tr>
                            <td>{{ $person->first_name }} {{ $person->last_name }}</td>
                            <td>{{ $person->mobile }}</td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm disabled">مشاهده حسابداری (در مراحل بعد)</a>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">هیچ شخصی ثبت نشده است.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal افزودن شخص -->
<div class="modal fade" id="addPersonModal" tabindex="-1" aria-labelledby="addPersonModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPersonModalLabel">افزودن شخص (فروشنده/سهامدار)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="search-person-input" class="form-control mb-3" placeholder="جستجوی نام یا نام خانوادگی...">
        <div id="person-list">
            <div class="text-center text-muted">در حال بارگذاری...</div>
        </div>
        <div id="add-person-message" class="mt-2"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
let searchTimeout = null;
let selectedPersonId = null;

// Ajax برای گرفتن لیست اولیه (۱۰ نفر اول)
function loadInitialPeopleList() {
    let listBox = document.getElementById('person-list');
    listBox.innerHTML = '<div class="text-center text-muted">در حال بارگذاری...</div>';
    fetch("{{ route('personal_accounting.ajax_search_person') }}")
        .then(res => res.json())
        .then(data => renderPeopleList(data));
}

// نمایش لیست اشخاص جستجو شده
function renderPeopleList(data) {
    let listBox = document.getElementById('person-list');
    if (!data.length) {
        listBox.innerHTML = "<div class='text-center text-muted'>موردی یافت نشد.</div>";
        return;
    }
    listBox.innerHTML = data.map(person => `
        <div class="list-group-item d-flex justify-content-between align-items-center">
            <span>${person.first_name} ${person.last_name} <small class="text-muted">${person.mobile || ''}</small></span>
            <button class="btn btn-outline-primary btn-sm" onclick="selectPersonToAdd(${person.id}, this)">انتخاب</button>
        </div>
    `).join('');
}

// جستجو با تایپ کاربر
document.getElementById('search-person-input').addEventListener('input', function() {
    let q = this.value.trim();
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('personal_accounting.ajax_search_person') }}?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => renderPeopleList(data));
    }, 350);
});

// انتخاب و افزودن شخص
function selectPersonToAdd(id, btn) {
    btn.disabled = true;
    btn.innerHTML = 'در حال افزودن...';
    fetch("{{ route('personal_accounting.add_person') }}", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({person_id:id})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            document.getElementById('add-person-message').innerHTML = '<span class="text-success">شخص با موفقیت به لیست اضافه شد.</span>';
            setTimeout(()=>{ location.reload(); }, 1200);
        } else {
            btn.disabled = false;
            btn.innerHTML = 'انتخاب';
            document.getElementById('add-person-message').innerHTML = '<span class="text-danger">خطا در افزودن شخص!</span>';
        }
    });
}

document.getElementById('addPersonModal').addEventListener('show.bs.modal', function () {
    document.getElementById('add-person-message').innerHTML = '';
    document.getElementById('search-person-input').value = '';
    loadInitialPeopleList();
});
</script>
@endsection
