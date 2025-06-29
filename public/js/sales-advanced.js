// نام‌گذاری متغیرهای سراسری
const CONSTANTS = {
    API_ENDPOINTS: {
        PRODUCTS: '/api/products',
        CUSTOMERS: '/api/customers',
        SALES: '/api/sales',
        TOP_PRODUCTS: '/api/products/top-selling'
    },
    SELECTORS: {
        INVOICE_NUMBER: '#invoice_number',
        INVOICE_AUTO: '#invoice_auto',
        INVOICE_DATE: '#invoice_date',
        SELLER_ID: '#seller_id',
        CUSTOMER_SEARCH: '#customer_search',
        CUSTOMER_ID: '#customer_id',
        PRODUCT_SEARCH: '#product_search',
        CATEGORY_FILTER: '#category_filter',
        SORT_FILTER: '#sort_filter',
        PRODUCTS_GRID: '#products_grid',
        INVOICE_ITEMS: '#invoice_items',
        INVOICE_TOTAL: '#invoice_total',
        TOP_PRODUCTS: '#top_products'
    }
};

// مدیریت محصولات
const ProductManager = {
    page: 1,
    loading: false,
    items: [],

    init() {
        this.loadTopProducts();
        this.setupEventListeners();
        this.loadProducts();
    },

    setupEventListeners() {
        // جستجوی محصولات
        document.querySelector(CONSTANTS.SELECTORS.PRODUCT_SEARCH)
            ?.addEventListener('input', this.debounce(() => {
                this.page = 1;
                this.loadProducts(true);
            }, 500));

        // فیلتر دسته‌بندی
        document.querySelector(CONSTANTS.SELECTORS.CATEGORY_FILTER)
            ?.addEventListener('change', () => {
                this.page = 1;
                this.loadProducts(true);
            });

        // مرتب‌سازی
        document.querySelector(CONSTANTS.SELECTORS.SORT_FILTER)
            ?.addEventListener('change', () => {
                this.page = 1;
                this.loadProducts(true);
            });

        // دکمه نمایش بیشتر
        document.querySelector('#load_more')?.addEventListener('click', () => {
            this.page++;
            this.loadProducts();
        });
    },

    async loadTopProducts() {
        try {
            const response = await fetch(CONSTANTS.API_ENDPOINTS.TOP_PRODUCTS);
            const data = await response.json();
            this.renderTopProducts(data);
        } catch (error) {
            console.error('Error loading top products:', error);
            this.showNotification('خطا در بارگذاری محصولات پرفروش', 'error');
        }
    },

    async loadProducts(reset = false) {
        if (this.loading) return;
        this.loading = true;

        const searchQuery = document.querySelector(CONSTANTS.SELECTORS.PRODUCT_SEARCH)?.value || '';
        const categoryId = document.querySelector(CONSTANTS.SELECTORS.CATEGORY_FILTER)?.value || '';
        const sortBy = document.querySelector(CONSTANTS.SELECTORS.SORT_FILTER)?.value || '';

        try {
            const response = await fetch(`${CONSTANTS.API_ENDPOINTS.PRODUCTS}?page=${this.page}&search=${searchQuery}&category=${categoryId}&sort=${sortBy}`);
            const data = await response.json();

            if (reset) {
                document.querySelector(CONSTANTS.SELECTORS.PRODUCTS_GRID).innerHTML = '';
            }

            this.renderProducts(data.data);

            // نمایش/مخفی کردن دکمه نمایش بیشتر
            document.querySelector('#load_more').style.display = data.next_page_url ? 'block' : 'none';
        } catch (error) {
            console.error('Error loading products:', error);
            this.showNotification('خطا در بارگذاری محصولات', 'error');
        } finally {
            this.loading = false;
        }
    },

    renderTopProducts(products) {
        const container = document.querySelector(CONSTANTS.SELECTORS.TOP_PRODUCTS);
        if (!container) return;

        container.innerHTML = products.map(product => `
            <div class="product-card" data-id="${product.id}">
                <img src="${product.image || '/images/no-image.png'}" class="product-image" alt="${product.name}">
                <div class="product-info">
                    <h3 class="product-title">${product.name}</h3>
                    <div class="product-price">${this.formatPrice(product.price)} تومان</div>
                    <div class="product-stock">موجودی: ${product.stock}</div>
                    <button class="btn btn-sm btn-primary mt-2 w-100 add-to-invoice" data-id="${product.id}">
                        <i class="fas fa-plus"></i>
                        افزودن به فاکتور
                    </button>
                </div>
            </div>
        `).join('');

        // اضافه کردن event listener برای دکمه‌های افزودن به فاکتور
        container.querySelectorAll('.add-to-invoice').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = e.target.closest('.product-card').dataset.id;
                InvoiceManager.addItem(productId);
            });
        });
    },

    renderProducts(products) {
        const container = document.querySelector(CONSTANTS.SELECTORS.PRODUCTS_GRID);
        if (!container) return;

        const html = products.map(product => `
            <div class="product-card" data-id="${product.id}">
                <img src="${product.image || '/images/no-image.png'}" class="product-image" alt="${product.name}">
                <div class="product-info">
                    <h3 class="product-title">${product.name}</h3>
                    <div class="product-price">${this.formatPrice(product.price)} تومان</div>
                    <div class="product-stock">موجودی: ${product.stock}</div>
                    <button class="btn btn-sm btn-primary mt-2 w-100 add-to-invoice" data-id="${product.id}">
                        <i class="fas fa-plus"></i>
                        افزودن به فاکتور
                    </button>
                </div>
            </div>
        `).join('');

        if (this.page === 1) {
            container.innerHTML = html;
        } else {
            container.insertAdjacentHTML('beforeend', html);
        }

        // اضافه کردن event listener برای دکمه‌های جدید
        container.querySelectorAll('.add-to-invoice').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = e.target.closest('.product-card').dataset.id;
                InvoiceManager.addItem(productId);
            });
        });
    },

    formatPrice(price) {
        return new Intl.NumberFormat('fa-IR').format(price);
    },

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    showNotification(message, type = 'info') {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
};

// مدیریت فاکتور
const InvoiceManager = {
    items: [],

    init() {
        this.setupEventListeners();
    },

    setupEventListeners() {
        // دکمه ذخیره فاکتور
        document.querySelector('#save_invoice')?.addEventListener('click', () => {
            this.saveInvoice();
        });
    },

    async addItem(productId) {
        try {
            const response = await fetch(`${CONSTANTS.API_ENDPOINTS.PRODUCTS}/${productId}`);
            const product = await response.json();

            const existingItem = this.items.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                this.items.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1
                });
            }

            this.renderItems();
            this.updateTotal();
            this.showNotification('محصول به فاکتور اضافه شد', 'success');
        } catch (error) {
            console.error('Error adding product to invoice:', error);
            this.showNotification('خطا در افزودن محصول به فاکتور', 'error');
        }
    },

    removeItem(productId) {
        this.items = this.items.filter(item => item.id !== productId);
        this.renderItems();
        this.updateTotal();
    },

    updateQuantity(productId, quantity) {
        const item = this.items.find(item => item.id === productId);
        if (item) {
            item.quantity = Math.max(1, quantity);
            this.renderItems();
            this.updateTotal();
        }
    },

    renderItems() {
        const container = document.querySelector(CONSTANTS.SELECTORS.INVOICE_ITEMS);
        if (!container) return;

        container.innerHTML = this.items.map((item, index) => `
            <tr>
                <td>${item.name}</td>
                <td>
                    <input type="number" class="form-control form-control-sm quantity-input"
                           value="${item.quantity}" min="1" data-id="${item.id}">
                </td>
                <td class="text-left">${this.formatPrice(item.price * item.quantity)}</td>
                <td>
                    <button class="btn btn-sm btn-danger remove-item" data-id="${item.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        // اضافه کردن event listeners
        container.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const id = e.target.dataset.id;
                const quantity = parseInt(e.target.value);
                this.updateQuantity(id, quantity);
            });
        });

        container.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.closest('.remove-item').dataset.id;
                this.removeItem(id);
            });
        });
    },

    updateTotal() {
        const total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const totalElement = document.querySelector(CONSTANTS.SELECTORS.INVOICE_TOTAL);
        if (totalElement) {
            totalElement.textContent = `${this.formatPrice(total)} تومان`;
        }
    },

    async saveInvoice() {
        if (this.items.length === 0) {
            this.showNotification('لطفاً حداقل یک محصول به فاکتور اضافه کنید', 'warning');
            return;
        }

        const customerId = document.querySelector(CONSTANTS.SELECTORS.CUSTOMER_ID).value;
        if (!customerId) {
            this.showNotification('لطفاً مشتری را انتخاب کنید', 'warning');
            return;
        }

        const sellerId = document.querySelector(CONSTANTS.SELECTORS.SELLER_ID).value;
        if (!sellerId) {
            this.showNotification('لطفاً فروشنده را انتخاب کنید', 'warning');
            return;
        }

        try {
            const invoiceData = {
                customer_id: customerId,
                seller_id: sellerId,
                invoice_number: document.querySelector(CONSTANTS.SELECTORS.INVOICE_NUMBER).value,
                items: this.items.map(item => ({
                    product_id: item.id,
                    quantity: item.quantity,
                    price: item.price
                }))
            };

            const response = await fetch(CONSTANTS.API_ENDPOINTS.SALES, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(invoiceData)
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('فاکتور با موفقیت ثبت شد', 'success');
                // پاک کردن فاکتور
                this.items = [];
                this.renderItems();
                this.updateTotal();

                // ریدایرکت به صفحه جزئیات فاکتور
                setTimeout(() => {
                    window.location.href = `/sales/${result.sale_id}`;
                }, 1500);
            } else {
                throw new Error(result.message || 'خطا در ثبت فاکتور');
            }
        } catch (error) {
            console.error('Error saving invoice:', error);
            this.showNotification(error.message || 'خطا در ثبت فاکتور', 'error');
        }
    },

    formatPrice(price) {
        return new Intl.NumberFormat('fa-IR').format(price);
    },

    showNotification(message, type = 'info') {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
};

// مدیریت مشتری
const CustomerManager = {
    init() {
        this.setupEventListeners();
    },

    setupEventListeners() {
        const searchInput = document.querySelector(CONSTANTS.SELECTORS.CUSTOMER_SEARCH);
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(() => {
                this.searchCustomers(searchInput.value);
            }, 300));
        }

        // دکمه افزودن مشتری جدید
        document.querySelector('#add_customer')?.addEventListener('click', () => {
            $('#add_customer_modal').modal('show');
        });

        // دکمه ذخیره مشتری جدید
        document.querySelector('#save_customer')?.addEventListener('click', () => {
            this.saveNewCustomer();
        });
    },

    async searchCustomers(query) {
        if (query.length < 2) return;

        try {
            const response = await fetch(`${CONSTANTS.API_ENDPOINTS.CUSTOMERS}/search?q=${query}`);
            const customers = await response.json();
            this.renderSearchResults(customers);
        } catch (error) {
            console.error('Error searching customers:', error);
            this.showNotification('خطا در جستجوی مشتریان', 'error');
        }
    },

    renderSearchResults(customers) {
        const container = document.querySelector('#customer_search_results');
        if (!container) return;

        if (customers.length === 0) {
            container.innerHTML = '<div class="search-result-item text-muted">موردی یافت نشد</div>';
            container.classList.add('show');
            return;
        }

        container.innerHTML = customers.map(customer => `
            <div class="search-result-item" data-id="${customer.id}" data-name="${customer.name}">
                ${customer.name}
                ${customer.mobile ? `<small class="text-muted">${customer.mobile}</small>` : ''}
            </div>
        `).join('');

        container.classList.add('show');

        // اضافه کردن event listener برای انتخاب مشتری
        container.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', () => {
                this.selectCustomer(item.dataset.id, item.dataset.name);
            });
        });
    },

    selectCustomer(id, name) {
        document.querySelector(CONSTANTS.SELECTORS.CUSTOMER_SEARCH).value = name;
        document.querySelector(CONSTANTS.SELECTORS.CUSTOMER_ID).value = id;
        document.querySelector('#customer_search_results').classList.remove('show');
    },

    async saveNewCustomer() {
        const form = document.querySelector('#customer_form');
        const formData = new FormData(form);

        try {
            const response = await fetch(CONSTANTS.API_ENDPOINTS.CUSTOMERS, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('مشتری جدید با موفقیت ثبت شد', 'success');
                this.selectCustomer(result.customer.id, result.customer.name);
                $('#add_customer_modal').modal('hide');
                form.reset();
            } else {
                throw new Error(result.message || 'خطا در ثبت مشتری');
            }
        } catch (error) {
            console.error('Error saving customer:', error);
            this.showNotification(error.message || 'خطا در ثبت مشتری', 'error');
        }
    },

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    showNotification(message, type = 'info') {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
};

// راه‌اندازی اولیه
document.addEventListener('DOMContentLoaded', function() {
    ProductManager.init();
    InvoiceManager.init();
    CustomerManager.init();

    // راه‌اندازی تاریخ شمسی
    if (typeof $ !== 'undefined' && $.fn.persianDatepicker) {
        $('#invoice_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true
        });
    }
});
