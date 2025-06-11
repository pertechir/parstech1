class ThemeSwitcher {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.icons = {
            light: {
                dashboard: 'sun',
                products: 'box',
                persons: 'users',
                sales: 'shopping-cart',
                accounting: 'calculator',
                financial: 'coins',
                reports: 'chart-bar',
                settings: 'cog'
            },
            dark: {
                dashboard: 'moon',
                products: 'boxes',
                persons: 'user-friends',
                sales: 'cart-plus',
                accounting: 'book',
                financial: 'wallet',
                reports: 'chart-line',
                settings: 'cogs'
            }
        };
        this.init();
    }

    init() {
        document.documentElement.setAttribute('data-theme', this.theme);
        this.updateIcons();
        this.setupEventListeners();
    }

    updateIcons() {
        const currentIcons = this.icons[this.theme];
        document.querySelectorAll('[data-icon]').forEach(element => {
            const iconType = element.getAttribute('data-icon');
            if (currentIcons[iconType]) {
                element.className = `fas fa-${currentIcons[iconType]} sidebar-icon`;
            }
        });
    }

    toggleTheme() {
        this.theme = this.theme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', this.theme);
        document.documentElement.setAttribute('data-theme', this.theme);
        this.updateIcons();
    }

    setupEventListeners() {
        // Add theme toggle button event listener if exists
        const themeToggle = document.getElementById('themeToggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }
}

class Sidebar {
    constructor() {
        this.sidebar = document.querySelector('.custom-sidebar');
        this.toggleBtn = document.getElementById('sidebarToggleBtn');
        this.submenuItems = document.querySelectorAll('.has-submenu > a');
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupSubmenuHandlers();
        this.checkScreenSize();
        this.loadSidebarState();
    }

    setupEventListeners() {
        // Toggle sidebar
        this.toggleBtn?.addEventListener('click', () => this.toggleSidebar());

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 768) {
                if (!this.sidebar.contains(e.target) && !this.toggleBtn.contains(e.target)) {
                    this.sidebar.classList.remove('sidebar-visible');
                }
            }
        });

        // Window resize handler
        window.addEventListener('resize', () => this.checkScreenSize());
    }

    setupSubmenuHandlers() {
        this.submenuItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const parent = item.parentElement;
                const isOpen = parent.classList.contains('open');

                // Close all other open submenus
                this.submenuItems.forEach(other => {
                    if (other !== item) {
                        other.parentElement.classList.remove('open');
                    }
                });

                // Toggle current submenu
                parent.classList.toggle('open', !isOpen);
            });
        });
    }

    toggleSidebar() {
        document.body.classList.toggle('sidebar-collapsed');
        this.saveSidebarState();

        // Close all submenus when collapsing
        if (document.body.classList.contains('sidebar-collapsed')) {
            this.submenuItems.forEach(item => {
                item.parentElement.classList.remove('open');
            });
        }
    }

    checkScreenSize() {
        if (window.innerWidth <= 768) {
            document.body.classList.remove('sidebar-collapsed');
            this.sidebar.classList.add('mobile');
        } else {
            this.sidebar.classList.remove('mobile');
            this.loadSidebarState();
        }
    }

    saveSidebarState() {
        localStorage.setItem('sidebarCollapsed',
            document.body.classList.contains('sidebar-collapsed'));
    }

    loadSidebarState() {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        document.body.classList.toggle('sidebar-collapsed', isCollapsed);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ThemeSwitcher();
    new Sidebar();
});
