document.addEventListener('DOMContentLoaded', function () {
    // باز و بسته کردن سایدبار
    document.getElementById('sidebar-toggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('collapsed');
        localStorage.setItem('sidebar-collapsed', document.getElementById('sidebar').classList.contains('collapsed'));
    });
    // حفظ حالت بین رفرش
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        document.getElementById('sidebar').classList.add('collapsed');
    }

    // باز و بسته شدن زیرمنو
    document.querySelectorAll('.has-submenu > .sidebar-link').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('open');
        });
    });

    // جستجو
    const searchInput = document.getElementById('sidebar-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('#sidebar-menu-list > li').forEach(function (item) {
                if (!q) {
                    item.style.display = '';
                    return;
                }
                const text = item.innerText.trim().toLowerCase();
                item.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }
});
