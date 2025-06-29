@extends('layouts.app')

@section('title', 'حسابداری شخصی')

@section('styles')
<style>

    
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- هدر صفحه -->
    <div class="dashboard-header">
        <div>
            <h2 class="text-primary fw-bold mb-1">
                <i class="fas fa-wallet"></i>
                حسابداری شخصی
            </h2>
            <p class="text-muted">مدیریت تراکنش‌ها و حساب‌های اشخاص</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importExportModal">
                <i class="fas fa-file-export"></i>
                پشتیبان‌گیری/بازیابی
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPersonModal">
                <i class="fas fa-user-plus"></i>
                افزودن شخص جدید
            </button>
        </div>
    </div>

    <!-- باکس جستجو و فیلتر -->
    <div class="search-box">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" id="searchInput"
                           placeholder="جستجو در اشخاص...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterSelect">
                    <option value="all">همه اشخاص</option>
                    <option value="debtors">بدهکاران</option>
                    <option value="creditors">طلبکاران</option>
                    <option value="settled">تسویه شده</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="sortSelect">
                    <option value="latest">جدیدترین</option>
                    <option value="balance_desc">بیشترین مانده</option>
                    <option value="balance_asc">کمترین مانده</option>
                    <option value="name">نام (الفبا)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- لیست اشخاص -->
    <div class="row" id="peopleList">
        @forelse($people as $person)
            @php
                $balance = $person->transactions->reduce(function($carry, $trx) {
                    if (in_array($trx->type, ['income','receive','credit']))
                        return $carry + $trx->amount;
                    return $carry - $trx->amount;
                }, 0);

                $status = $balance > 0 ? 'creditor' : ($balance < 0 ? 'debtor' : 'settled');
                $statusText = $balance > 0 ? 'بستانکار' : ($balance < 0 ? 'بدهکار' : 'تسویه');
            @endphp

            <div class="col-md-6 col-lg-4 person-item"
                 data-balance="{{ $balance }}"
                 data-name="{{ $person->first_name }} {{ $person->last_name }}"
                 data-status="{{ $status }}">
                <div class="person-card">
                    <div class="person-header">
                        @if($person->avatar)
                            <img src="{{ asset('storage/' . $person->avatar) }}" alt="تصویر {{ $person->first_name }}"
                                 class="person-avatar">
                        @else
                            <div class="person-avatar">
                                {{ substr($person->first_name, 0, 1) }}
                            </div>
                        @endif
                        <div class="person-info">
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="person-name">{{ $person->first_name }} {{ $person->last_name }}</h5>
                                @if($person->transactions->count() > 0)
                                    <span class="transaction-count">
                                        {{ $person->transactions->count() }} تراکنش
                                    </span>
                                @endif
                            </div>
                            <div class="person-contact">
                                <i class="fas fa-phone text-muted"></i>
                                {{ $person->mobile ?: 'بدون شماره تماس' }}
                            </div>
                        </div>
                    </div>

                    <div class="person-stats">
                        <div class="stat-item">
                            <div class="stat-value {{ $balance > 0 ? 'positive' : ($balance < 0 ? 'negative' : '') }}">
                                {{ number_format(abs($balance)) }}
                            </div>
                            <div class="stat-label">مانده حساب</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">
                                <span class="status-badge {{ $status }}">{{ $statusText }}</span>
                            </div>
                            <div class="stat-label">وضعیت</div>
                        </div>
                    </div>

                    <div class="person-actions">
                        <button class="btn btn-warning btn-sm" onclick="showReminderModal({{ $person->id }})">
                            <i class="fas fa-bell"></i>
                            یادآور
                        </button>
                        <a href="{{ route('personal_accounting.person', $person->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-folder-open"></i>
                            مشاهده حساب
                        </a>
                        <form method="POST" action="{{ route('personal_accounting.remove_person', $person->id) }}"
                              class="d-inline"
                              onsubmit="return confirm('آیا مطمئن هستید؟ این کار تراکنش‌ها را حذف نمی‌کند.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h4>هیچ شخصی یافت نشد</h4>
                <p class="text-muted">برای شروع، یک شخص جدید اضافه کنید</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPersonModal">
                    <i class="fas fa-user-plus"></i>
                    افزودن شخص جدید
                </button>
            </div>
        @endforelse
    </div>
</div>

<!-- مودال افزودن شخص -->
<div class="modal fade" id="addPersonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن شخص به حسابداری شخصی</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="search-person-input" class="form-control mb-3"
                       placeholder="جستجوی نام یا شماره موبایل...">
                <div id="person-list">
                    <div class="text-center text-muted">در حال بارگذاری...</div>
                </div>
                <div id="add-person-message" class="mt-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- مودال پشتیبان‌گیری -->
<div class="modal fade" id="importExportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">پشتیبان‌گیری و بازیابی</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-3">
                    <button class="btn btn-outline-primary" onclick="exportData()">
                        <i class="fas fa-file-export"></i>
                        دریافت فایل پشتیبان
                    </button>
                    <div class="text-center">- یا -</div>
                    <div class="text-center">
                        <input type="file" id="importFile" accept=".json" class="d-none">
                        <button class="btn btn-outline-success" onclick="document.getElementById('importFile').click()">
                            <i class="fas fa-file-import"></i>
                            بازیابی از فایل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال یادآور -->
<div class="modal fade" id="reminderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تنظیم یادآور</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <form id="reminderForm">
                    <input type="hidden" id="reminder_person_id">
                    <div class="mb-3">
                        <label class="form-label">تاریخ یادآوری</label>
                        <input type="text" class="form-control" id="reminderDate">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">متن یادآوری</label>
                        <textarea class="form-control" id="reminderText" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">ثبت یادآور</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let searchTimeout = null;

// دریافت لیست اولیه
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

// جستجوی اشخاص
document.getElementById('search-person-input')?.addEventListener('input', function() {
    let q = this.value.trim();
    if (searchTimeout) clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('personal_accounting.ajax_search_person') }}?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => renderPeopleList(data));
    }, 350);
});

// افزودن شخص
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
            document.getElementById('add-person-message').innerHTML =
                '<div class="alert alert-success">شخص با موفقیت به حسابداری اضافه شد.</div>';
            setTimeout(() => { location.reload(); }, 1200);
        } else {
            btn.disabled = false;
            btn.innerHTML = 'انتخاب';
            document.getElementById('add-person-message').innerHTML =
                '<div class="alert alert-danger">خطا در افزودن شخص!</div>';
        }
    });
}

// جستجو و فیلتر در لیست
function filterAndSortPeople() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filterValue = document.getElementById('filterSelect').value;
    const sortValue = document.getElementById('sortSelect').value;

    let items = Array.from(document.getElementsByClassName('person-item'));

    items.forEach(item => {
        const name = item.getAttribute('data-name').toLowerCase();
        const balance = parseInt(item.getAttribute('data-balance'));
        const status = item.getAttribute('data-status');

        // فیلتر جستجو
        const matchesSearch = name.includes(searchTerm);

        // فیلتر وضعیت
        let matchesFilter = true;
        if (filterValue !== 'all') {
            matchesFilter = status === filterValue;
        }

        item.style.display = matchesSearch && matchesFilter ? '' : 'none';
    });

    // مرتب‌سازی
    items.sort((a, b) => {
        switch(sortValue) {
            case 'balance_desc':
                return parseInt(b.getAttribute('data-balance')) - parseInt(a.getAttribute('data-balance'));
            case 'balance_asc':
                return parseInt(a.getAttribute('data-balance')) - parseInt(b.getAttribute('data-balance'));
            case 'name':
                return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            default: // latest
                return 0; // حفظ ترتیب فعلی
        }
    });

    const container = document.getElementById('peopleList');
    items.forEach(item => container.appendChild(item));
}

document.getElementById('searchInput')?.addEventListener('input', filterAndSortPeople);
document.getElementById('filterSelect')?.addEventListener('change', filterAndSortPeople);
document.getElementById('sortSelect')?.addEventListener('change', filterAndSortPeople);

// پشتیبان‌گیری
function exportData() {
    fetch("{{ route('personal_accounting.export') }}")
        .then(res => res.json())
        .then(data => {
            const blob = new Blob([JSON.stringify(data)], {type: 'application/json'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `personal-accounting-backup-${new Date().toISOString().split('T')[0]}.json`;
            a.click();
        });
}

// بازیابی از فایل
document.getElementById('importFile')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const data = JSON.parse(e.target.result);
            fetch("{{ route('personal_accounting.import') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    alert('اطلاعات با موفقیت بازیابی شد.');
                    location.reload();
                } else {
                    alert('خطا در بازیابی اطلاعات!');
                }
            });
        } catch(e) {
            alert('فایل نامعتبر است!');
        }
    };
    reader.readAsText(file);
});

// مودال یادآور
function showReminderModal(personId) {
    document.getElementById('reminder_person_id').value = personId;
    new bootstrap.Modal(document.getElementById('reminderModal')).show();
}

document.getElementById('reminderForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const data = {
        person_id: document.getElementById('reminder_person_id').value,
        date: document.getElementById('reminderDate').value,
        text: document.getElementById('reminderText').value
    };

    fetch("{{ route('personal_accounting.add_reminder') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('یادآور با موفقیت ثبت شد');
            bootstrap.Modal.getInstance(document.getElementById('reminderModal')).hide();
        } else {
            alert('خطا در ثبت یادآور!');
        }
    });
});

// راه‌اندازی اولیه
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('addPersonModal')?.addEventListener('show.bs.modal', function() {
        document.getElementById('add-person-message').innerHTML = '';
        document.getElementById('search-person-input').value = '';
        loadInitialPeopleList();
    });
});
</script>
@endsection
