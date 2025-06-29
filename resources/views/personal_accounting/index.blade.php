@extends('layouts.app')

@section('title', 'حسابداری شخصی')

@section('styles')
    <style>
        .person-item { border-bottom: 1px solid #eee; padding:18px 0; display:flex; align-items:center; justify-content:space-between;}
        .person-info { display:flex; flex-direction:column;}
        .person-balance { font-weight:bold; }
        .person-balance.positive { color:#16a34a; }
        .person-balance.negative { color:#dc2626; }
        .person-balance.zero { color:#666; }
        .action-btns { display: flex; gap: 8px; }
        .add-person-btn { min-width:150px; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-primary fw-bold">
            <i class="fas fa-wallet"></i>
            حسابداری شخصی
        </h2>
        <button class="btn btn-success add-person-btn" data-bs-toggle="modal" data-bs-target="#addPersonModal">
            <i class="fas fa-user-plus"></i>
            افزودن شخص
        </button>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <b>لیست اشخاص حسابداری</b>
        </div>
        <div class="card-body p-0">
            @if(count($people))
                @foreach($people as $person)
                    <div class="person-item">
                        <div class="person-info">
                            <b>{{ $person->first_name }} {{ $person->last_name }}</b>
                            <small class="text-muted">{{ $person->mobile }}</small>
                        </div>
                        <div class="action-btns">
                            <a href="{{ route('personal_accounting.person', $person->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-folder-open"></i> مشاهده حساب‌ها
                            </a>
                            <form method="POST" action="{{ route('personal_accounting.remove_person', $person->id) }}" class="d-inline" onsubmit="return confirm('آیا مطمئن هستید که این شخص از حسابداری شخصی حذف شود؟ این کار تراکنش‌هایش را حذف نمی‌کند.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-4 text-center text-muted">
                    هیچ شخصی به حسابداری اضافه نشده است.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal افزودن شخص (مثل قبل) -->
<div class="modal fade" id="addPersonModal" tabindex="-1" aria-labelledby="addPersonModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPersonModalLabel">افزودن شخص (فروشنده یا سهامدار)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="search-person-input" class="form-control mb-3" placeholder="جستجو نام یا نام خانوادگی...">
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

// دریافت لیست اولیه (۱۰ نفر اول فروشنده‌ها و سهامداران)
function loadInitialPeopleList() {
    let listBox = document.getElementById('person-list');
    listBox.innerHTML = '<div class="text-center text-muted">در حال بارگذاری...</div>';
    fetch("/personal-accounting/ajax-search-person")
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
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('search-person-input').addEventListener('input', function() {
        let q = this.value.trim();
        if (searchTimeout) clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetch(`/personal-accounting/ajax-search-person?q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(data => renderPeopleList(data));
        }, 350);
    });
    document.getElementById('addPersonModal').addEventListener('show.bs.modal', function () {
        document.getElementById('add-person-message').innerHTML = '';
        document.getElementById('search-person-input').value = '';
        loadInitialPeopleList();
    });
});

// انتخاب و افزودن شخص
function selectPersonToAdd(id, btn) {
    btn.disabled = true;
    btn.innerHTML = 'در حال افزودن...';
    fetch("/personal-accounting/add-person", {
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
            document.getElementById('add-person-message').innerHTML = '<span class="text-success">شخص با موفقیت اضافه شد.</span>';
            setTimeout(()=>{ location.reload(); }, 1200);
        } else {
            btn.disabled = false;
            btn.innerHTML = 'انتخاب';
            document.getElementById('add-person-message').innerHTML = '<span class="text-danger">خطا در افزودن شخص!</span>';
        }
    });
}
</script>
@endsection
