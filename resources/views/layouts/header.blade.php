<nav id="main-header" class="main-header navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm sticky-top" style="z-index:1030;">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">داشبورد</a>
        <div class="d-flex align-items-center ms-auto">
            <!-- دکمه کسب و کارها -->
            <button id="businesses-btn" class="btn btn-outline-primary mx-2 d-flex align-items-center" type="button">
                <i class="fas fa-briefcase me-1"></i>
                کسب‌وکارها
            </button>
            <!-- فروش روزانه -->
            <div class="sales-summary position-relative mx-2" style="cursor:pointer;">
                <span class="badge bg-gradient-primary sales-badge">
                    فروش امروز: {{ number_format($dailySales) }} تومان
                </span>
                <span class="badge bg-secondary ms-1 sales-badge">
                    دیروز: {{ number_format($yesterdaySales) }} تومان
                </span>
                <span class="badge bg-success ms-1 sales-badge">
                    ماه جاری: {{ number_format($monthlySales) }} تومان
                </span>
            </div>
            @auth
            <div class="dropdown ms-2">
                <a href="#" class="d-flex align-items-center nav-link dropdown-toggle p-0" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : asset('img/user.png') }}"
                         alt="پروفایل"
                         class="rounded-circle"
                         width="38"
                         height="38"
                         style="object-fit:cover;">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2"></i> پروفایل من
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('settings.company') }}">
                            <i class="fas fa-cog me-2"></i> تنظیمات
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> خروج
                        </a>
                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>
    </div>
    <div id="business-modal-container"></div>
</nav>

<!-- لود پاپ‌آپ کسب‌وکارها و استایل و اسکریپت مرتبط -->
@include('businesses.modal')
<link rel="stylesheet" href="{{ asset('css/businesses-modal.css') }}">
<script src="{{ asset('js/businesses-modal.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        document.getElementById('businesses-btn').addEventListener('click', function(){
            // اگر قبلاً لود نشده
            if (!document.getElementById('businesses-modal')) {
                fetch('/businesses/modal')
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('business-modal-container').innerHTML = html;
                    });
            } else {
                document.getElementById('businesses-modal').style.display = 'block';
            }
        });
        // بستن مودال (این اگر در modal.blade.php نیست، اضافه کن)
        document.addEventListener('click', function(e){
            if(e.target.id === 'close-businesses-modal' || e.target.className === 'businesses-modal-bg'){
                document.getElementById('businesses-modal').style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    });
    </script>
