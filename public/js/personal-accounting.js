// داده اولیه برای نمونه (در عمل باید از سرور لود شود)
let transactions = JSON.parse(localStorage.getItem('personal_transactions') || '[]');

// Utility: تبدیل عدد به فرمت پولی فارسی
function toFaMoney(num) {
    return new Intl.NumberFormat('fa-IR').format(num);
}

// به‌روزرسانی داشبورد
function updateDashboard() {
    let income = transactions.filter(t => t.type === 'income').reduce((a, b) => a + Number(b.amount), 0);
    let expense = transactions.filter(t => t.type === 'expense').reduce((a, b) => a + Number(b.amount), 0);
    document.getElementById('total-income').textContent = toFaMoney(income) + ' تومان';
    document.getElementById('total-expense').textContent = toFaMoney(expense) + ' تومان';
    document.getElementById('balance').textContent = toFaMoney(income - expense) + ' تومان';
}

// رندر جدول تراکنش‌ها
function renderTransactions() {
    let tbody = document.querySelector('#transactions-table tbody');
    let search = document.getElementById('search-transaction').value.trim();
    let cat = document.getElementById('category-filter').value;
    let type = document.getElementById('type-filter').value;
    let filtered = transactions.filter(t => {
        return (!search || t.description.includes(search)) &&
               (!cat || t.category === cat) &&
               (!type || t.type === type)
    });
    tbody.innerHTML = '';
    filtered.forEach(t => {
        let badge = t.type === 'income'
            ? '<span class="badge badge-income">درآمد</span>'
            : '<span class="badge badge-expense">هزینه</span>';
        tbody.innerHTML += `
            <tr>
                <td>${t.date}</td>
                <td>${categoryName(t.category)}</td>
                <td>${t.description || '-'}</td>
                <td>${toFaMoney(t.amount)}</td>
                <td>${badge}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editTransaction(${t.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="deleteTransaction(${t.id})"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
    document.getElementById('no-transactions').style.display = filtered.length ? "none" : "block";
}

// دسته‌بندی فارسی
function categoryName(key) {
    switch(key) {
        case 'food': return 'غذا';
        case 'transport': return 'حمل و نقل';
        case 'home': return 'خانه';
        case 'salary': return 'درآمد حقوق';
        case 'other': return 'سایر';
        default: return '-';
    }
}

// فرم افزودن/ویرایش
document.getElementById('add-transaction-btn').onclick = function() {
    showTransactionForm();
};
document.getElementById('close-transaction-form').onclick = function() {
    document.getElementById('transaction-form-card').style.display = 'none';
};
document.getElementById('transaction-form').onsubmit = function(e) {
    e.preventDefault();
    let id = document.getElementById('transaction-id').value;
    let data = {
        id: id ? Number(id) : (Date.now()),
        date: document.getElementById('transaction-date').value,
        amount: document.getElementById('transaction-amount').value,
        description: document.getElementById('transaction-description').value,
        category: document.getElementById('transaction-category').value,
        type: document.getElementById('transaction-type').value
    };
    if (id) {
        // ویرایش
        let idx = transactions.findIndex(t => t.id == id);
        transactions[idx] = data;
    } else {
        transactions.push(data);
    }
    localStorage.setItem('personal_transactions', JSON.stringify(transactions));
    document.getElementById('transaction-form-card').style.display = 'none';
    renderTransactions();
    updateDashboard();
    renderChart();
    renderCategorySummary();
};

// دکمه‌های فیلتر
document.getElementById('search-transaction').oninput =
document.getElementById('category-filter').onchange =
document.getElementById('type-filter').onchange = function() {
    renderTransactions();
};

// ویرایش تراکنش
window.editTransaction = function(id) {
    let t = transactions.find(t => t.id == id);
    showTransactionForm(t);
};
function showTransactionForm(t = null) {
    document.getElementById('transaction-form-card').style.display = 'block';
    document.getElementById('transaction-form-title').textContent = t ? 'ویرایش تراکنش' : 'افزودن تراکنش جدید';
    document.getElementById('transaction-id').value = t?.id || '';
    document.getElementById('transaction-date').value = t?.date || (new Date().toISOString().slice(0,10));
    document.getElementById('transaction-amount').value = t?.amount || '';
    document.getElementById('transaction-description').value = t?.description || '';
    document.getElementById('transaction-category').value = t?.category || '';
    document.getElementById('transaction-type').value = t?.type || 'expense';
}

// حذف تراکنش
window.deleteTransaction = function(id) {
    if (!confirm('آیا از حذف تراکنش مطمئن هستید؟')) return;
    transactions = transactions.filter(t => t.id != id);
    localStorage.setItem('personal_transactions', JSON.stringify(transactions));
    renderTransactions();
    updateDashboard();
    renderChart();
    renderCategorySummary();
};

// خلاصه هزینه بر اساس دسته‌بندی
function renderCategorySummary() {
    let cats = {};
    transactions.filter(t => t.type === 'expense').forEach(t => {
        cats[t.category] = (cats[t.category] || 0) + Number(t.amount);
    });
    let ul = document.getElementById('category-summary-list');
    ul.innerHTML = '';
    Object.entries(cats).forEach(([cat, sum]) => {
        ul.innerHTML += `<li class="list-group-item d-flex justify-content-between">
            <span>${categoryName(cat)}</span>
            <span>${toFaMoney(sum)} تومان</span>
        </li>`;
    });
    if (!Object.keys(cats).length) {
        ul.innerHTML = '<li class="list-group-item text-muted">هنوز هزینه‌ای ثبت نشده است.</li>';
    }
}

// نمودار با Chart.js
let chart = null;
function renderChart() {
    let ctx = document.getElementById('finance-chart').getContext('2d');
    let dates = [];
    let incomeData = [];
    let expenseData = [];

    let range = document.getElementById('report-range').value;
    let now = new Date();
    let filtered = transactions.filter(t => {
        if (range === 'month') {
            let d = new Date(t.date);
            return d.getFullYear() === now.getFullYear() && d.getMonth() === now.getMonth();
        }
        if (range === 'year') {
            let d = new Date(t.date);
            return d.getFullYear() === now.getFullYear();
        }
        return true;
    });

    // جمع بر اساس روز
    let days = {};
    filtered.forEach(t => {
        days[t.date] = days[t.date] || {income: 0, expense: 0};
        days[t.date][t.type] += Number(t.amount);
    });
    dates = Object.keys(days).sort();
    incomeData = dates.map(d => days[d].income);
    expenseData = dates.map(d => days[d].expense);

    if (chart) chart.destroy();
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [
                {
                    label: 'درآمد',
                    data: incomeData,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.1)',
                    fill: true,
                },
                {
                    label: 'هزینه',
                    data: expenseData,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220,38,38,0.1)',
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { rtl: true, labels: { font: { family: 'Vazirmatn, Tahoma' } } }
            },
            scales: {
                x: { display: true, title: { display: false } },
                y: { display: true, beginAtZero: true }
            }
        }
    });
}
document.getElementById('report-range').onchange = renderChart;

// شروع اولیه
updateDashboard();
renderTransactions();
renderChart();
renderCategorySummary();
