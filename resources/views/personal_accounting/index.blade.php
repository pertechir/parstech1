@extends('layouts.app')

@section('title', 'حسابداری شخصی')

@section('styles')
    <style>
        .stats-card { transition: all 0.3s; border-right: 4px solid; }
        .stats-card:hover { transform: translateY(-5px); }
        .stats-card.income { border-right-color: #16a34a; }
        .stats-card.expense { border-right-color: #dc2626; }
        .stats-card.debt { border-right-color: #0ea5e9; }
        .stats-card.budget { border-right-color: #8b5cf6; }

        .person-item { border-bottom: 1px solid #eee; padding:18px 0; display:flex; align-items:center; justify-content:space-between;}
        .person-info { display:flex; flex-direction:column;}
        .person-balance { font-weight:bold; }
        .person-balance.positive { color:#16a34a; }
        .person-balance.negative { color:#dc2626; }
        .person-balance.zero { color:#666; }
        .action-btns { display: flex; gap: 8px; }
        .add-person-btn { min-width:150px; }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .quick-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-radius: 8px;
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .quick-action-btn i {
            font-size: 1.5rem;
            margin-left: 0.75rem;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">
            <i class="fas fa-wallet"></i>
            حسابداری شخصی
        </h2>
        <div>
            <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#importExportModal">
                <i class="fas fa-file-export"></i>
                پشتیبان‌گیری/بازیابی
            </button>
            <button class="btn btn-success add-person-btn" data-bs-toggle="modal" data-bs-target="#addPersonModal">
                <i class="fas fa-user-plus"></i>
                افزودن شخص
            </button>
        </div>
    </div>

    <!-- آمار کلی -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card income">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">کل دریافتی‌ها</h6>
                    <h3 class="card-title text-success">
                        {{ number_format($totalIncome) }}
                        <small>تومان</small>
                    </h3>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-arrow-up text-success"></i>
                            {{ $thisMonthIncome }} تومان این ماه
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card expense">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">کل هزینه‌ها</h6>
                    <h3 class="card-title text-danger">
                        {{ number_format($totalExpense) }}
                        <small>تومان</small>
                    </h3>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-arrow-down text-danger"></i>
                            {{ $thisMonthExpense }} تومان این ماه
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card debt">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">مجموع بدهی‌ها</h6>
                    <h3 class="card-title text-info">
                        {{ number_format($totalDebt) }}
                        <small>تومان</small>
                    </h3>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-users"></i>
                            {{ $debtorsCount }} نفر بدهکار
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stats-card budget">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">مانده کل</h6>
                    <h3 class="card-title {{ $totalBalance > 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format(abs($totalBalance)) }}
                        <small>تومان</small>
                    </h3>
                    <div class="text-muted">
                        <small>
                            <i class="fas fa-chart-line"></i>
                            {{ $totalBalance > 0 ? 'مثبت' : 'منفی' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- دکمه‌های دسترسی سریع -->
    <div class="quick-actions mb-4">
        <a href="{{ route('personal_accounting.categories') }}" class="quick-action-btn">
            <i class="fas fa-tags text-primary"></i>
            <span>دسته‌بندی هزینه‌ها</span>
        </a>
        <a href="{{ route('personal_accounting.budgets') }}" class="quick-action-btn">
            <i class="fas fa-chart-pie text-success"></i>
            <span>مدیریت بودجه</span>
        </a>
        <a href="{{ route('personal_accounting.reports') }}" class="quick-action-btn">
            <i class="fas fa-chart-bar text-warning"></i>
            <span>گزارشات</span>
        </a>
        <a href="{{ route('personal_accounting.reminders') }}" class="quick-action-btn">
            <i class="fas fa-bell text-danger"></i>
            <span>یادآورها</span>
        </a>
        <a href="{{ route('personal_accounting.accounts') }}" class="quick-action-btn">
            <i class="fas fa-university text-info"></i>
            <span>حساب‌های بانکی</span>
        </a>
    </div>

    <!-- لیست اشخاص -->
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <b>لیست اشخاص حسابداری</b>
            <div class="d-flex gap-2">
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="جستجو در اشخاص...">
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select class="form-select" style="width: 200px;" id="filterSelect">
                    <option value="all">همه</option>
                    <option value="debtors">بدهکاران</option>
                    <option value="creditors">طلبکاران</option>
                    <option value="settled">تسویه شده</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            @if(count($people))
                @foreach($people as $person)
                    <div class="person-item">
                        <div class="person-info">
                            <div class="d-flex align-items-center gap-2">
                                <b>{{ $person->first_name }} {{ $person->last_name }}</b>
                                @if($person->transactions->count() > 0)
                                    <span class="badge bg-secondary">{{ $person->transactions->count() }} تراکنش</span>
                                @endif
                            </div>
                            <small class="text-muted">{{ $person->mobile ?: 'بدون شماره تماس' }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $balance = $person->transactions->reduce(function($carry, $trx){
                                    if (in_array($trx->type, ['income','receive','credit'])) return $carry + $trx->amount;
                                    else return $carry - $trx->amount;
                                }, 0);
                            @endphp
                            <div class="text-nowrap">
                                <span class="person-balance {{ $balance > 0 ? 'positive' : ($balance < 0 ? 'negative' : 'zero') }}">
                                    {{ number_format(abs($balance)) }} تومان
                                </span>
                                <small class="text-muted d-block text-center">
                                    {{ $balance > 0 ? 'بستانکار' : ($balance < 0 ? 'بدهکار' : 'تسویه') }}
                                </small>
                            </div>
                            <div class="action-btns">
                                <a href="{{ route('personal_accounting.person', $person->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-folder-open"></i>
                                    مشاهده حساب‌ها
                                </a>
                                <button class="btn btn-warning btn-sm" onclick="showReminderModal({{ $person->id }})">
                                    <i class="fas fa-bell"></i>
                                    یادآور
                                </button>
                                <form method="POST" action="{{ route('personal_accounting.remove_person', $person->id) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('آیا مطمئن هستید؟ این کار تراکنش‌ها را حذف نمی‌کند.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                        حذف
                                    </button>
                                </form>
                            </div>
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

<!-- مودال افزودن شخص -->
<div class="modal fade" id="addPersonModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن شخص به حسابداری شخصی</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="search-person-input" class="form-control mb-3" placeholder="جستجوی نام یا شماره موبایل...">
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
document.getElementById('search-person-input').addEventListener('input', function() {
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

// جستجو در لیست
document.getElementById('searchInput').addEventListener('input', function() {
    let searchTerm = this.value.toLowerCase();
    document.querySelectorAll('.person-item').forEach(item => {
        let name = item.querySelector('.person-info b').textContent.toLowerCase();
        let mobile = item.querySelector('.person-info small').textContent.toLowerCase();
        if (name.includes(searchTerm) || mobile.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
});

// فیلتر لیست
document.getElementById('filterSelect').addEventListener('change', function() {
    let filter = this.value;
    document.querySelectorAll('.person-item').forEach(item => {
        let balance = item.querySelector('.person-balance').classList;
        switch(filter) {
            case 'debtors':
                item.style.display = balance.contains('negative') ? '' : 'none';
                break;
            case 'creditors':
                item.style.display = balance.contains('positive') ? '' : 'none';
                break;
            case 'settled':
                item.style.display = balance.contains('zero') ? '' : 'none';
                break;
            default:
                item.style.display = '';
        }
    });
});

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
document.getElementById('importFile').addEventListener('change', function(e) {
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

document.getElementById('reminderForm').addEventListener('submit', function(e) {
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
    document.getElementById('addPersonModal').addEventListener('show.bs.modal', function() {
        document.getElementById('add-person-message').innerHTML = '';
        document.getElementById('search-person-input').value = '';
        loadInitialPeopleList();
    });
});
</script>
@endsection
