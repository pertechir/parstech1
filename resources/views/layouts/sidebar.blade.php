<aside class="main-sidebar" id="sidebar" dir="rtl">
    <!-- Overlay for mobile/tablet -->
    <div class="sidebar-overlay d-none" id="sidebarOverlay"></div>
    <!-- Toggle button -->
    <div class="sidebar-toggle">
        <button id="sidebarCollapse" type="button" aria-label="باز و بسته کردن سایدبار">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Logo and Brand -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('img/logo.png') }}" alt="لوگو" class="brand-image">
        <span class="brand-text">سیستم حسابداری و فروش</span>
    </a>

    <!-- Sidebar Content -->
    <div class="sidebar-content">
        <div class="sidebar-content-inner">
            @auth
            <!-- User Panel -->
            <div class="user-panel">
                <button class="user-info user-info-btn" id="userMenuToggle" aria-label="باز و بسته کردن منوی کاربر" type="button">
                    <img src="{{ asset('img/user.png') }}" class="user-image" alt="تصویر کاربر">
                    <div class="user-details">
                        <div class="user-name">{{ Auth::user()->name ?? 'کاربر' }}</div>
                        <div class="user-role">مدیر سیستم</div>
                    </div>
                    <i class="fa fa-angle-down ms-auto user-menu-arrow"></i>
                </button>
                <div class="user-menu" id="userMenuDropdown" style="display: none;">
                    <a href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-edit text-primary"></i>
                        <span>ویرایش پروفایل</span>
                    </a>
                    <a href="{{ route('settings.company') }}">
                        <i class="fas fa-cog text-secondary"></i>
                        <span>تنظیمات</span>
                    </a>
                    <a href="#" onclick="event.preventDefault();document.getElementById('logout-form-sidebar').submit();">
                        <i class="fas fa-sign-out-alt text-danger"></i>
                        <span>خروج</span>
                    </a>
                </div>
            </div>
            @endauth

            <!-- Main Navigation -->
            <nav class="sidebar-nav" aria-label="منوی اصلی">
                <ul class="nav-menu">

                    <!-- داشبورد -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt text-info"></i>
                            <span>داشبورد</span>
                        </a>
                    </li>

                    <!-- کالا و خدمات -->
                    <li class="nav-item has-submenu {{ (request()->is('products*') || request()->is('stocks*') || request()->is('categories*') || request()->is('services*')) ? 'open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-warehouse text-warning"></i>
                            <span>کالا و خدمات</span>
                            <i class="submenu-arrow fas fa-angle-left"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('products.create') }}" class="{{ request()->routeIs('products.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus text-success"></i>
                                    <span>افزودن محصول</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                                    <i class="fas fa-box text-warning"></i>
                                    <span>لیست محصولات</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services.create') }}" class="{{ request()->routeIs('services.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus text-info"></i>
                                    <span>افزودن خدمات</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.index') ? 'active' : '' }}">
                                    <i class="fas fa-list text-info"></i>
                                    <span>لیست خدمات</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stocks.in') }}" class="{{ request()->routeIs('stocks.in') ? 'active' : '' }}">
                                    <i class="fas fa-arrow-down text-primary"></i>
                                    <span>ورود کالا</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stocks.out') }}" class="{{ request()->routeIs('stocks.out') ? 'active' : '' }}">
                                    <i class="fas fa-arrow-up text-danger"></i>
                                    <span>خروج کالا</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('stocks.transfer') }}" class="{{ request()->routeIs('stocks.transfer') ? 'active' : '' }}">
                                    <i class="fas fa-exchange-alt text-success"></i>
                                    <span>انتقال بین انبارها</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories.create') }}" class="{{ request()->routeIs('categories.create') ? 'active' : '' }}">
                                    <i class="fas fa-tags text-purple"></i>
                                    <span>افزودن دسته‌بندی</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                    <i class="fas fa-list-ul text-purple"></i>
                                    <span>لیست دسته‌بندی</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- اشخاص -->
                    <li class="nav-item has-submenu {{ request()->is('persons*') ? 'open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-users text-primary"></i>
                            <span>اشخاص</span>
                            <i class="submenu-arrow fas fa-angle-left"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('persons.create') }}" class="{{ request()->routeIs('persons.create') ? 'active' : '' }}">
                                    <i class="fas fa-user-plus text-success"></i>
                                    <span>شخص جدید</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('persons.index') }}" class="{{ request()->routeIs('persons.index') ? 'active' : '' }}">
                                    <i class="fas fa-list text-primary"></i>
                                    <span>لیست اشخاص</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('shareholders.index') }}" class="{{ request()->routeIs('shareholders.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-shield text-info"></i>
                                    <span>سهامداران</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sellers.create') }}" class="{{ request()->routeIs('sellers.create') ? 'active' : '' }}">
                                    <i class="fas fa-store text-warning"></i>
                                    <span>فروشنده جدید</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sellers.index') }}" class="{{ request()->routeIs('sellers.index') ? 'active' : '' }}">
                                    <i class="fas fa-user-tie text-warning"></i>
                                    <span>لیست فروشندگان</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('persons.suppliers') }}" class="{{ request()->routeIs('persons.suppliers') ? 'active' : '' }}">
                                    <i class="fas fa-truck text-danger"></i>
                                    <span>تامین‌کنندگان</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- فروش -->
                    <li class="nav-item has-submenu {{ request()->is('sales*') ? 'open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-shopping-cart text-success"></i>
                            <span>فروش</span>
                            <i class="submenu-arrow fas fa-angle-left"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('sales.create') }}" class="{{ request()->routeIs('sales.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus text-success"></i>
                                    <span>ثبت فروش جدید</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.index') ? 'active' : '' }}">
                                    <i class="fas fa-list text-info"></i>
                                    <span>لیست فاکتورها</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sales.quick') }}" class="{{ request()->routeIs('sales.quick') ? 'active' : '' }}">
                                    <i class="fas fa-bolt text-warning"></i>
                                    <span>فروش سریع</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('sales.returns.index') }}" class="{{ request()->routeIs('sales.returns.*') ? 'active' : '' }}">
                                    <i class="fas fa-undo text-danger"></i>
                                    <span>برگشت از فروش</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- حسابداری -->
                    <li class="nav-item has-submenu {{ request()->is('accounting*') ? 'open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-calculator text-purple"></i>
                            <span>حسابداری</span>
                            <i class="submenu-arrow fas fa-angle-left"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('accounting.journal') }}" class="{{ request()->routeIs('accounting.journal') ? 'active' : '' }}">
                                    <i class="fas fa-book text-info"></i>
                                    <span>دفتر روزنامه</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('accounting.ledger') }}" class="{{ request()->routeIs('accounting.ledger') ? 'active' : '' }}">
                                    <i class="fas fa-book-open text-warning"></i>
                                    <span>دفتر کل</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('accounting.balance') }}" class="{{ request()->routeIs('accounting.balance') ? 'active' : '' }}">
                                    <i class="fas fa-balance-scale text-danger"></i>
                                    <span>تراز آزمایشی</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- امور مالی -->
                    <li class="nav-item has-submenu {{ request()->is('financial*') ? 'open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-coins text-danger"></i>
                            <span>امور مالی</span>
                            <i class="submenu-arrow fas fa-angle-left"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('financial.incomes.index') }}" class="{{ request()->routeIs('financial.incomes.index') ? 'active' : '' }}">
                                    <i class="fas fa-arrow-down text-success"></i>
                                    <span>درآمدها</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('financial.expenses') }}" class="{{ request()->routeIs('financial.expenses') ? 'active' : '' }}">
                                    <i class="fas fa-arrow-up text-danger"></i>
                                    <span>هزینه‌ها</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('financial.banking') }}" class="{{ request()->routeIs('financial.banking') ? 'active' : '' }}">
                                    <i class="fas fa-university text-info"></i>
                                    <span>عملیات بانکی</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('financial.cheques') }}" class="{{ request()->routeIs('financial.cheques') ? 'active' : '' }}">
                                    <i class="fas fa-money-check-alt text-primary"></i>
                                    <span>مدیریت چک‌ها</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- گزارشات -->
                    <li class="nav-item has-submenu {{ request()->is('reports*') ? 'open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-bar text-info"></i>
                            <span>گزارشات</span>
                            <i class="submenu-arrow fas fa-angle-left"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('reports.sales') }}" class="{{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line text-success"></i>
                                    <span>گزارش فروش</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.inventory') }}" class="{{ request()->routeIs('reports.inventory') ? 'active' : '' }}">
                                    <i class="fas fa-boxes text-warning"></i>
                                    <span>گزارش موجودی</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.financial') }}" class="{{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                                    <i class="fas fa-money-bill-wave text-info"></i>
                                    <span>گزارش مالی</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- تنظیمات -->
                    <li class="nav-item has-submenu {{ request()->is('settings*') ? 'open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-cog text-secondary"></i>
                            <span>تنظیمات</span>
                            <i class="submenu-arrow fas fa-angle-left"></i>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('settings.company') }}" class="{{ request()->routeIs('settings.company') ? 'active' : '' }}">
                                    <i class="fas fa-building text-primary"></i>
                                    <span>اطلاعات شرکت</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('settings.users') }}" class="{{ request()->routeIs('settings.users') ? 'active' : '' }}">
                                    <i class="fas fa-users-cog text-warning"></i>
                                    <span>مدیریت کاربران</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="nav-link" id="openCurrencyModal">
                                    <i class="fas fa-money-bill-wave text-success"></i>
                                    <span>مدیریت واحدهای پول</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- فرم خروج -->
    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</aside>

<script>
    // Overlay موبایل
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        function handleOverlay() {
            if(window.innerWidth < 900) {
                if(!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('show');
                    overlay.classList.remove('d-none');
                    overlay.classList.add('active');
                } else {
                    sidebar.classList.remove('show');
                    overlay.classList.add('d-none');
                    overlay.classList.remove('active');
                }
            } else {
                sidebar.classList.remove('show');
                overlay.classList.add('d-none');
            }
        }
        document.getElementById('sidebarCollapse').addEventListener('click', handleOverlay);
        overlay.addEventListener('click', function(){
            sidebar.classList.add('collapsed');
            sidebar.classList.remove('show');
            overlay.classList.add('d-none');
            overlay.classList.remove('active');
        });
        window.addEventListener('resize', handleOverlay);
    });
    document.addEventListener('DOMContentLoaded', function() {
        // مدیریت باز/بسته شدن منوی کاربر
        const toggle = document.getElementById('userMenuToggle');
        const menu = document.getElementById('userMenuDropdown');
        if (toggle && menu) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.style.display = (menu.style.display === 'none' || menu.style.display === '') ? 'block' : 'none';
                toggle.classList.toggle('open', menu.style.display === 'block');
            });
            document.addEventListener('click', function(e) {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.style.display = 'none';
                    toggle.classList.remove('open');
                }
            });
        }
    });

</script>
