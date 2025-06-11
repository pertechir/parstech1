document.addEventListener('DOMContentLoaded', function() {
    // نمایش چارت فروش
    const salesSummary = document.querySelector('.sales-summary');
    const salesChartPopup = document.getElementById('sales-chart-popup');
    let salesChart = null;

    if(salesSummary && salesChartPopup) {
        let popupTimeout;

        salesSummary.addEventListener('mouseenter', function() {
            clearTimeout(popupTimeout);
            salesChartPopup.style.display = 'block';
            if(!salesChart) {
                updateSalesChart();
            }
        });

        salesSummary.addEventListener('mouseleave', function() {
            popupTimeout = setTimeout(() => {
                salesChartPopup.style.display = 'none';
            }, 300);
        });

        salesChartPopup.addEventListener('mouseenter', function() {
            clearTimeout(popupTimeout);
        });

        salesChartPopup.addEventListener('mouseleave', function() {
            popupTimeout = setTimeout(() => {
                salesChartPopup.style.display = 'none';
            }, 300);
        });
    }

    // رسم چارت فروش
    function updateSalesChart() {
        if (!window.hourlySales) return;

        const isDark = document.body.classList.contains('dark-theme');
        const hours = window.hourlySales.map(item => item.hour);
        const sales = window.hourlySales.map(item => item.total);

        const options = {
            series: [{
                name: 'فروش',
                data: sales
            }],
            chart: {
                type: 'area',
                height: 210,
                width: 330,
                toolbar: { show: false },
                fontFamily: 'inherit',
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function(value) {
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' تومان';
                    }
                }
            },
            xaxis: {
                categories: hours,
                labels: {
                    style: {
                        colors: isDark ? '#a0aec0' : '#666'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: isDark ? '#a0aec0' : '#666'
                    },
                    formatter: function(value) {
                        return value >= 1000000 ?
                            (value/1000000).toFixed(1) + 'M' :
                            value >= 1000 ?
                                (value/1000).toFixed(0) + 'K' : value;
                    }
                }
            },
            grid: {
                borderColor: isDark ? '#2d3748' : '#e2e8f0',
                strokeDashArray: 4
            },
            colors: ['#4CAF50'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 }
        };

        salesChart = new ApexCharts(document.getElementById('sales-hourly-chart'), options);
        salesChart.render();
    }

    // مدیریت نوتیفیکیشن‌ها
    const notifDropdown = document.getElementById('notif-dropdown');
    const notifBadge = document.getElementById('notif-badge');
    const markSeenBtn = document.getElementById('notif-mark-seen-btn');

    if(notifDropdown && notifBadge && markSeenBtn) {
        const productItems = notifDropdown.querySelectorAll('.notif-product-item');

        if(productItems.length > 0) {
            notifBadge.style.display = 'block';
            notifBadge.textContent = productItems.length;
            markSeenBtn.style.display = 'block';
        }

        markSeenBtn.addEventListener('click', function() {
            notifBadge.style.display = 'none';
            markSeenBtn.style.display = 'none';

            const seenProducts = Array.from(productItems).map(item =>
                item.dataset.productId
            );
            localStorage.setItem('seenLowStockProducts', JSON.stringify(seenProducts));
        });

        // بررسی محصولات دیده شده
        const seenProducts = JSON.parse(localStorage.getItem('seenLowStockProducts') || '[]');
        if(seenProducts.length > 0) {
            let hasUnseenProducts = false;
            productItems.forEach(item => {
                if(!seenProducts.includes(item.dataset.productId)) {
                    hasUnseenProducts = true;
                }
            });

            if(!hasUnseenProducts) {
                notifBadge.style.display = 'none';
                markSeenBtn.style.display = 'none';
            }
        }
    }

    // مدیریت تغییر تم
    const themeToggle = document.getElementById('theme-toggle');
    if(themeToggle) {
        // بررسی و اعمال تم ذخیره شده
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.classList.toggle('dark-theme', savedTheme === 'dark');

        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-theme');
            const isDark = document.body.classList.contains('dark-theme');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');

            // بروزرسانی چارت در صورت نمایش
            if(salesChart && salesChartPopup.style.display === 'block') {
                salesChart.destroy();
                salesChart = null;
                updateSalesChart();
            }

            // انتشار رویداد تغییر تم
            document.dispatchEvent(new CustomEvent('themeChanged', {
                detail: { theme: isDark ? 'dark' : 'light' }
            }));
        });
    }

    // مدیریت ریسپانسیو
    function handleResize() {
        if(salesChart) {
            const width = window.innerWidth <= 768 ? 280 : 330;
            const height = window.innerWidth <= 768 ? 180 : 210;
            salesChart.updateOptions({
                chart: {
                    width: width,
                    height: height
                }
            });
        }
    }

    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(handleResize, 250);
    });
});
