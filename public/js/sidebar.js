document.addEventListener('DOMContentLoaded', function() {
    // متغیرهای مورد نیاز
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const submenuItems = document.querySelectorAll('.has-submenu');

    // باز و بسته کردن سایدبار
    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    }

    // بازیابی وضعیت قبلی سایدبار
    const sidebarState = localStorage.getItem('sidebarCollapsed');
    if (sidebarState === 'true') {
        sidebar.classList.add('collapsed');
    }

    // مدیریت زیرمنوها
    submenuItems.forEach(item => {
        const link = item.querySelector('.nav-link');
        link.addEventListener('click', (e) => {
            e.preventDefault();

            // بستن سایر زیرمنوهای باز
            submenuItems.forEach(other => {
                if (other !== item && other.classList.contains('open')) {
                    other.classList.remove('open');
                }
            });

            item.classList.toggle('open');
        });
    });

// جدید: فقط اگر روی فضای خالی سایدبار (نه روی آیتم منو) یا روی overlay کلیک شد، زیرمنوها را ببند.
// و فقط در حالت موبایل (زیر 900px) این کار را انجام بده.
document.addEventListener('click', (e) => {
    const sidebar = document.getElementById('sidebar');
    const isMobile = window.innerWidth < 900;
    if (isMobile) {
        // اگر کلیک روی سایدبار یا overlay نبود، زیرمنوها را ببند
        if (!e.target.closest('#sidebar') && !e.target.closest('#sidebarOverlay')) {
            submenuItems.forEach(item => {
                item.classList.remove('open');
            });
            // سایدبار را هم ببند (اختیاری)
            sidebar.classList.add('collapsed');
        }
    }
});

    // نمایش منوی فعال
    const currentPath = window.location.pathname;
    const menuLinks = document.querySelectorAll('.nav-link, .submenu a');

    menuLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            const parentItem = link.closest('.has-submenu');
            if (parentItem) {
                parentItem.classList.add('open');
            }
        }
    });

    // پشتیبانی از تاچ
    let touchStartX = 0;
    let touchEndX = 0;

    sidebar.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, false);

    sidebar.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, false);

    function handleSwipe() {
        const SWIPE_THRESHOLD = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > SWIPE_THRESHOLD) {
            if (diff > 0) {
                // کشیدن به چپ
                sidebar.classList.add('collapsed');
            } else {
                // کشیدن به راست
                sidebar.classList.remove('collapsed');
            }
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
    }

    // پشتیبانی از کلیدهای میانبر
    document.addEventListener('keydown', (e) => {
        // Alt + S برای باز/بسته کردن سایدبار
        if (e.altKey && e.key === 's') {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('userMenuToggle');
        const menu = document.getElementById('userMenuDropdown');
        if (toggle && menu) {
            // کلاس position:fixed به منو بده
            menu.classList.add('user-menu-fixed');
            function openMenu() {
                // مختصات دکمه را پیدا کن
                const rect = toggle.getBoundingClientRect();
                // موقعیت منو درست زیر دکمه قرار بگیرد
                menu.style.display = 'block';
                menu.style.top = (window.scrollY + rect.bottom + 6) + 'px';
                menu.style.right = (window.innerWidth - rect.right) + 'px';
                toggle.classList.add('open');
            }
            function closeMenu() {
                menu.style.display = 'none';
                toggle.classList.remove('open');
            }
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                if (menu.style.display === 'block') {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
            // اگر بیرون منو کلیک شد، منو بسته شود
            document.addEventListener('click', function(e) {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    closeMenu();
                }
            });
            // موقع اسکرول یا resize موقعیت منو را رفرش کن
            window.addEventListener('scroll', function(){
                if(menu.style.display === 'block') openMenu();
            });
            window.addEventListener('resize', function(){
                if(menu.style.display === 'block') openMenu();
            });
        }
    });
});
