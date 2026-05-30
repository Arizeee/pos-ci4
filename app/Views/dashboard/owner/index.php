<?= $this->extend('template/layouts/owner') ?>

<?= $this->section('content') ?>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?= $this->include('template/partials/owner/sidebar') ?>

    <!-- Main Content -->
    <main class="flex-1 lg:ml-72 overflow-hidden">
        <!-- Header -->
        <?= $this->include('template/partials/owner/header') ?>

        <!-- Page Content -->
        <div class="overflow-y-auto p-6 h-[calc(100vh-73px)] custom-scrollbar">

            <?= $this->include('dashboard/owner/overview') ?>
            <?= $this->include('dashboard/owner/revenue') ?>
            <?= $this->include('dashboard/owner/products') ?>
            <?= $this->include('dashboard/owner/customer') ?>
            <?= $this->include('dashboard/owner/staff') ?>
            <?= $this->include('dashboard/owner/financial') ?>
            <?= $this->include('dashboard/owner/inventory') ?>
            <?= $this->include('dashboard/owner/performance') ?>
            <?= $this->include('dashboard/owner/setting') ?>

        </div>
    </main>
</div>

    <script>
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            updateDate();
            setInterval(updateDate, 60000);
            loadOwnerOverview();
            loadOwnerRevenue();
            loadOwnerProducts();
            loadOwnerCustomers();
            loadOwnerStaff();
            loadOwnerFinancial();
            loadOwnerInventory();
            loadOwnerPerformance();
            loadOwnerSettings();
        });

        let ownerCurrentPeriod = 'today';
        let ownerTrendRange = '7d';
        let ownerProductDetailsPage = 1;
        let ownerFinancialRecordsPage = 1;
        let ownerInventoryPage = 1;
        let ownerStockLogsPage = 1;
        let ownerCustomerDailyPage = 1;
        let ownerStaffRolePages = {};

        // Update Date
        function updateDate() {
            const now = new Date();
            const options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
            const dateEl = document.getElementById('currentDate');
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('id-ID', options);
            }
        }

        // Show Page
        function showPage(pageName) {
            // Hide all pages
            document.querySelectorAll('.page-content').forEach(page => {
                page.classList.add('hidden');
            });

            // Show selected page
            document.getElementById(`page-${pageName}`).classList.remove('hidden');

            // Update sidebar active state
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('active');
                link.classList.add('text-stone-300');
            });
            const clickEvent = window.event || null;
            const activeLink = clickEvent?.target?.closest('.sidebar-link');
            if (activeLink) {
                activeLink.classList.add('active');
                activeLink.classList.remove('text-stone-300');
            }

            // Update page title
            const titles = {
                'overview': 'Overview',
                'revenue': 'Pendapatan',
                'products': 'Analisis Produk',
                'customers': 'Pelanggan',
                'staff': 'Karyawan',
                'financial': 'Laporan Keuangan',
                'inventory': 'Stok & Inventory',
                'performance': 'Kinerja',
                'settings': 'Pengaturan'
            };
            document.getElementById('pageTitle').textContent = titles[pageName] || 'Dashboard';

            if (pageName === 'revenue') {
                loadOwnerRevenue();
            }

            if (pageName === 'products') {
                loadOwnerProducts();
            }

            if (pageName === 'customers') {
                loadOwnerCustomers();
            }

            if (pageName === 'staff') {
                loadOwnerStaff();
            }

            if (pageName === 'financial') loadOwnerFinancial();
            if (pageName === 'inventory') loadOwnerInventory();
            if (pageName === 'performance') loadOwnerPerformance();
            if (pageName === 'settings') loadOwnerSettings();
        }

        function renderPagination(containerId, pagination, onPageChange) {
            const container = document.getElementById(containerId);
            if (!container) return;

            if (!pagination || Number(pagination.total || 0) === 0) {
                container.classList.add('hidden');
                container.innerHTML = '';
                return;
            }

            const current = Number(pagination.current_page || 1);
            const last = Number(pagination.last_page || 1);
            const start = Math.max(1, current - 2);
            const end = Math.min(last, current + 2);
            let buttons = '';

            for (let page = start; page <= end; page++) {
                buttons += `
                    <button type="button" onclick="${onPageChange}(${page})"
                            class="w-10 h-10 rounded-xl text-sm font-semibold ${page === current ? 'bg-amber-700 text-white' : 'border border-stone-200 text-stone-700 hover:bg-stone-50'}">
                        ${page}
                    </button>
                `;
            }

            container.classList.remove('hidden');
            container.innerHTML = `
                <p class="text-sm text-stone-500">Menampilkan ${pagination.from || 0}-${pagination.to || 0} dari ${pagination.total || 0} data</p>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="${onPageChange}(${current - 1})" ${current <= 1 ? 'disabled' : ''}
                            class="px-4 py-2 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Sebelumnya
                    </button>
                    ${buttons}
                    <button type="button" onclick="${onPageChange}(${current + 1})" ${current >= last ? 'disabled' : ''}
                            class="px-4 py-2 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Berikutnya
                    </button>
                </div>
            `;
        }

        // Set Period
        function setPeriod(period) {
            ownerCurrentPeriod = period;
            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-amber-700', 'text-white');
                btn.classList.add('text-stone-600');
            });
            event.target.classList.add('active', 'bg-amber-700', 'text-white');
            event.target.classList.remove('text-stone-600');

            loadOwnerOverview();
        }

        function formatRupiah(number) {
            return 'Rp ' + Number(number || 0).toLocaleString('id-ID');
        }

        function formatNumber(number) {
            return Number(number || 0).toLocaleString('id-ID');
        }

        function formatCompactRupiah(number) {
            const value = Number(number || 0);

            if (value >= 1000000000) return 'Rp ' + (value / 1000000000).toFixed(1).replace('.', ',') + 'M';
            if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1).replace('.', ',') + 'Jt';
            if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(1).replace('.', ',') + 'Rb';

            return formatRupiah(value);
        }

        function periodLabel(period = ownerCurrentPeriod) {
            const labels = {
                today: 'hari ini',
                week: 'minggu ini',
                month: 'bulan ini',
                year: 'tahun ini'
            };

            return labels[period] || 'periode ini';
        }

        function changeBadge(elementId, value) {
            const element = document.getElementById(elementId);
            const number = Number(value || 0);
            const positive = number >= 0;

            element.textContent = `${positive ? '+' : ''}${number}%`;
            element.className = `${positive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} px-3 py-1 rounded-full text-sm font-semibold`;
        }

        async function loadOwnerOverview() {
            try {
                const response = await fetch(`/owner-dashboard-data?period=${ownerCurrentPeriod}&trend=${ownerTrendRange}`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (!response.ok || !result.status) {
                    throw new Error(result.message || 'Gagal memuat overview owner');
                }

                renderOwnerOverview(result.data);
            } catch (error) {
                console.error(error);
                document.getElementById('ownerOverviewAlertTitle').textContent = 'Overview gagal dimuat';
                document.getElementById('ownerOverviewAlertText').textContent = 'Periksa koneksi database dan coba refresh halaman';
            }
        }

        function renderOwnerOverview(data) {
            const summary = data.summary;

            document.getElementById('ownerTotalRevenue').textContent = formatCompactRupiah(summary.revenue);
            document.getElementById('ownerPreviousRevenue').textContent = `vs ${formatCompactRupiah(summary.previous_revenue)} periode lalu`;
            document.getElementById('ownerTotalOrders').textContent = formatNumber(summary.orders);
            document.getElementById('ownerPreviousOrders').textContent = `vs ${formatNumber(summary.previous_orders)} periode lalu`;
            document.getElementById('ownerAverageOrder').textContent = formatCompactRupiah(summary.average_order);
            document.getElementById('ownerPreviousAverageOrder').textContent = `vs ${formatCompactRupiah(summary.previous_average_order)} periode lalu`;
            document.getElementById('ownerActiveProducts').textContent = formatNumber(summary.active_products);
            document.getElementById('ownerTotalProducts').textContent = `dari ${formatNumber(summary.total_products)} produk`;
            document.getElementById('ownerActiveProductPercent').textContent = `${summary.active_product_percent}%`;

            changeBadge('ownerRevenueChange', summary.revenue_change);
            changeBadge('ownerOrderChange', summary.order_change);
            changeBadge('ownerAverageOrderChange', summary.average_order_change);

            document.getElementById('ownerOverviewAlertTitle').textContent = `Pendapatan ${periodLabel()} ${formatCompactRupiah(summary.revenue)}`;
            document.getElementById('ownerOverviewAlertText').textContent = `Perubahan ${summary.revenue_change >= 0 ? 'naik' : 'turun'} ${Math.abs(summary.revenue_change)}% dibanding periode lalu`;

            renderOwnerRevenueTrend(data.revenue_trend);
            renderOwnerCategoryRevenue(data.category_revenue);
            renderOwnerTopProducts(data.top_products);
            renderOwnerRecentActivities(data.recent_activities);
        }

        function setOwnerTrend(range) {
            ownerTrendRange = range;
            const subtitles = {
                '7d': '7 hari terakhir',
                '30d': '30 hari terakhir',
                '12m': '12 bulan terakhir'
            };
            const subtitle = document.getElementById('ownerRevenueTrendSubtitle');

            if (subtitle) {
                subtitle.textContent = subtitles[range] || 'Tren pendapatan';
            }

            loadOwnerOverview();
        }

        function renderOwnerRevenueTrend(items) {
            const container = document.getElementById('ownerRevenueTrend');
            const trendItems = Array.isArray(items) ? items : [];

            if (!trendItems.length) {
                container.innerHTML = '<div class="w-full text-center text-stone-500">Belum ada data tren pendapatan.</div>';
                return;
            }

            const maxTotal = Math.max(...trendItems.map(item => Number(item.total || 0)), 1);

            container.innerHTML = trendItems.map((item, index) => {
                const total = Number(item.total || 0);
                const height = total === 0 ? 8 : Math.max((total / maxTotal) * 100, 12);
                const colors = ['#fde68a', '#fcd34d', '#fbbf24', '#f59e0b', '#d97706', '#b45309'];
                const color = colors[Math.min(colors.length - 1, Math.floor(index / 2))];

                return `
                    <div class="flex-1 h-full flex flex-col items-center justify-end gap-2 min-w-0">
                        <div class="w-full rounded-t-lg chart-bar" title="${formatRupiah(total)}" style="height: ${height}%; min-height: 10px; background-color: ${color}"></div>
                        <span class="text-xs text-stone-500 truncate">${item.label}</span>
                    </div>
                `;
            }).join('');
        }

        function renderOwnerCategoryRevenue(items) {
            const container = document.getElementById('ownerCategoryRevenue');
            const total = items.reduce((sum, item) => sum + Number(item.total || 0), 0);

            if (!items.length || total === 0) {
                container.innerHTML = '<p class="text-stone-500 text-sm">Belum ada pendapatan kategori pada periode ini.</p>';
                return;
            }

            container.innerHTML = items.map((item, index) => {
                const percent = total > 0 ? Math.round((Number(item.total) / total) * 100) : 0;
                const colors = [
                    'from-amber-400 to-amber-600 text-amber-700',
                    'from-blue-400 to-blue-600 text-blue-600',
                    'from-orange-400 to-orange-600 text-orange-600',
                    'from-purple-400 to-purple-600 text-purple-600'
                ];
                const color = colors[index % colors.length];
                const [gradient, textColor] = color.split(' text-');

                return `
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium text-stone-700">${item.name || 'Tanpa Kategori'}</span>
                            <span class="font-semibold text-${textColor}">${percent}%</span>
                        </div>
                        <div class="h-3 bg-stone-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r ${gradient} rounded-full progress-bar" style="width: ${percent}%"></div>
                        </div>
                        <p class="text-sm text-stone-500 mt-1">${formatCompactRupiah(item.total)}</p>
                    </div>
                `;
            }).join('');
        }

        function renderOwnerTopProducts(items) {
            const container = document.getElementById('ownerTopProducts');

            if (!items.length) {
                container.innerHTML = '<p class="text-stone-500 text-sm">Belum ada produk terjual pada periode ini.</p>';
                return;
            }

            container.innerHTML = items.map(item => `
                <div class="flex items-center gap-4 p-3 bg-stone-50 rounded-xl">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <span class="text-lg font-semibold text-amber-800">${String(item.category_name || 'P').charAt(0).toUpperCase()}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-stone-800 truncate">${item.name}</h4>
                        <p class="text-sm text-stone-500">${formatNumber(item.quantity)} terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-amber-700">${formatCompactRupiah(item.total)}</p>
                    </div>
                </div>
            `).join('');
        }

        function renderOwnerRecentActivities(items) {
            const container = document.getElementById('ownerRecentActivities');

            if (!items.length) {
                container.innerHTML = '<p class="text-stone-500 text-sm">Belum ada aktivitas terbaru.</p>';
                return;
            }

            container.innerHTML = items.map(item => `
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 ${item.type === 'stock' ? 'bg-amber-100' : 'bg-green-100'} rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-lg">${item.type === 'stock' ? '!' : 'Rp'}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-stone-800"><span class="font-semibold">${item.title}</span></p>
                        <p class="text-sm text-stone-500">${item.description}</p>
                    </div>
                </div>
            `).join('');
        }

        async function loadOwnerRevenue() {
            try {
                const response = await fetch('/owner-revenue-data', {
                    headers: {
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (!response.ok || !result.status) {
                    throw new Error(result.message || 'Gagal memuat data pendapatan');
                }

                renderOwnerRevenuePage(result.data);
            } catch (error) {
                console.error(error);
                const table = document.getElementById('ownerRevenueDetailTable');
                if (table) {
                    table.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-red-600">Data pendapatan gagal dimuat.</td></tr>';
                }
            }
        }

        function renderOwnerRevenuePage(data) {
            const summary = data.summary || {};

            renderOwnerRevenueCard('Today', summary.today);
            renderOwnerRevenueCard('Week', summary.week);
            renderOwnerRevenueCard('Month', summary.month);
            renderOwnerRevenueCard('Year', summary.year);
            renderOwnerSimpleBarChart('ownerDailyRevenueChart', data.daily_revenue || [], 'daily');
            renderOwnerSimpleBarChart('ownerHourlyRevenueChart', data.hourly_revenue || [], 'hourly');
            renderOwnerRevenueDetailTable(data.details || []);
        }

        function renderOwnerRevenueCard(key, item) {
            const data = item || {};
            const valueEl = document.getElementById(`ownerRevenue${key}`);
            const changeEl = document.getElementById(`ownerRevenue${key}Change`);
            const change = Number(data.change || 0);

            if (valueEl) {
                valueEl.textContent = formatCompactRupiah(data.revenue || 0);
            }

            if (changeEl) {
                changeEl.textContent = `${change >= 0 ? '+' : ''}${change}% ${data.comparison || 'dari periode lalu'}`;
            }
        }

        function renderOwnerSimpleBarChart(elementId, items, type = 'daily') {
            const container = document.getElementById(elementId);
            const chartItems = Array.isArray(items) ? items : [];

            if (!container) return;

            if (!chartItems.length) {
                container.innerHTML = '<div class="w-full text-center text-stone-500">Belum ada data pendapatan.</div>';
                return;
            }

            const maxTotal = Math.max(...chartItems.map(item => Number(item.total || 0)), 1);

            container.innerHTML = chartItems.map((item, index) => {
                const total = Number(item.total || 0);
                const height = total === 0 ? 8 : Math.max((total / maxTotal) * 100, 12);
                const label = type === 'hourly' ? `${item.label}` : item.label;
                const barClass = type === 'hourly'
                    ? 'bg-amber-500 rounded-t'
                    : 'bg-gradient-to-t from-amber-600 to-amber-400 rounded-t-lg';
                const gap = type === 'hourly' ? 'gap-1' : 'gap-2';
                const labelClass = type === 'hourly' ? 'text-stone-400' : 'text-stone-500';

                return `
                    <div class="flex-1 h-full flex flex-col items-center justify-end ${gap} min-w-0">
                        <div class="w-full ${barClass}" title="${formatRupiah(total)}" style="height: ${height}%; min-height: 10px"></div>
                        <span class="text-xs ${labelClass} truncate">${label}</span>
                    </div>
                `;
            }).join('');
        }

        function renderOwnerRevenueDetailTable(items) {
            const table = document.getElementById('ownerRevenueDetailTable');
            const rows = Array.isArray(items) ? items : [];

            if (!table) return;

            if (!rows.length) {
                table.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-stone-500">Belum ada detail pendapatan.</td></tr>';
                return;
            }

            table.innerHTML = rows.map((item, index) => {
                const change = Number(item.change || 0);
                const trendClass = change >= 0 ? 'text-green-600' : 'text-red-600';
                const sign = change >= 0 ? '+' : '';
                const borderClass = index === rows.length - 1 ? '' : 'border-b border-stone-100';

                return `
                    <tr class="${borderClass} table-row">
                        <td class="px-6 py-4 font-medium">${item.label}</td>
                        <td class="px-6 py-4">${formatNumber(item.orders)}</td>
                        <td class="px-6 py-4 font-semibold text-amber-700">${formatRupiah(item.revenue)}</td>
                        <td class="px-6 py-4">${formatRupiah(item.average_order)}</td>
                        <td class="px-6 py-4"><span class="${trendClass}">${sign}${change}%</span></td>
                    </tr>
                `;
            }).join('');
        }

        function changeOwnerProductDetailsPage(page) {
            if (page < 1) return;
            ownerProductDetailsPage = page;
            loadOwnerProducts();
        }

        async function loadOwnerProducts() {
            try {
                const response = await fetch(`/owner-products-data?details_page=${ownerProductDetailsPage}&per_page=10`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (!response.ok || !result.status) {
                    throw new Error(result.message || 'Gagal memuat analisis produk');
                }

                renderOwnerProductsPage(result.data);
            } catch (error) {
                console.error(error);
                const table = document.getElementById('ownerProductDetailTable');
                if (table) {
                    table.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-red-600">Analisis produk gagal dimuat.</td></tr>';
                }
            }
        }

        function renderOwnerProductsPage(data) {
            const summary = data.summary || {};

            document.getElementById('ownerProductTotal').textContent = formatNumber(summary.total_products || 0);
            document.getElementById('ownerProductBestSeller').textContent = summary.best_seller || '-';
            document.getElementById('ownerProductBiggestDrop').textContent = summary.biggest_drop || '-';
            document.getElementById('ownerProductNewest').textContent = summary.newest_product || '-';

            renderOwnerProductPerformance(data.performance || []);
            renderOwnerProductCategories(data.categories || []);
            renderOwnerProductDetails(data.details || []);
            renderPagination('ownerProductDetailsPagination', data.details_pagination, 'changeOwnerProductDetailsPage');
        }

        function renderOwnerProductPerformance(items) {
            const container = document.getElementById('ownerProductPerformance');
            const rows = Array.isArray(items) ? items : [];

            if (!container) return;

            if (!rows.length || rows.every(item => Number(item.quantity || 0) === 0)) {
                container.innerHTML = '<p class="text-stone-500 text-sm">Belum ada penjualan produk bulan ini.</p>';
                return;
            }

            const maxQuantity = Math.max(...rows.map(item => Number(item.quantity || 0)), 1);
            const colors = ['bg-amber-500', 'bg-blue-500', 'bg-orange-500', 'bg-green-500', 'bg-purple-500'];

            container.innerHTML = rows.map((item, index) => {
                const quantity = Number(item.quantity || 0);
                const width = quantity === 0 ? 4 : Math.max((quantity / maxQuantity) * 100, 8);
                const change = Number(item.change || 0);
                const trendClass = change >= 0 ? 'text-green-600' : 'text-red-600';
                const sign = change >= 0 ? '+' : '';

                return `
                    <div class="flex items-center gap-4">
                        <div class="w-9 h-9 rounded-lg bg-stone-100 flex items-center justify-center text-xs font-bold text-stone-700 flex-shrink-0">
                            ${String(item.category_name || item.name || 'P').charAt(0).toUpperCase()}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between gap-3 mb-1">
                                <span class="font-medium truncate">${item.name}</span>
                                <span class="text-sm text-stone-500 whitespace-nowrap">${formatNumber(quantity)} terjual</span>
                            </div>
                            <div class="h-2 bg-stone-100 rounded-full overflow-hidden">
                                <div class="h-full ${colors[index % colors.length]} rounded-full" style="width: ${width}%"></div>
                            </div>
                        </div>
                        <span class="${trendClass} text-sm font-semibold whitespace-nowrap">${sign}${change}%</span>
                    </div>
                `;
            }).join('');
        }

        function renderOwnerProductCategories(items) {
            const container = document.getElementById('ownerProductCategories');
            const rows = Array.isArray(items) ? items : [];

            if (!container) return;

            if (!rows.length) {
                container.innerHTML = '<p class="text-stone-500 text-sm col-span-2">Belum ada kategori produk.</p>';
                return;
            }

            const styles = [
                'bg-amber-50 text-amber-700',
                'bg-blue-50 text-blue-700',
                'bg-orange-50 text-orange-700',
                'bg-purple-50 text-purple-700',
                'bg-green-50 text-green-700',
                'bg-red-50 text-red-700'
            ];

            container.innerHTML = rows.map((item, index) => {
                const change = Number(item.change || 0);
                const changeClass = change >= 0 ? 'text-green-600' : 'text-red-600';
                const sign = change >= 0 ? '+' : '';
                const style = styles[index % styles.length];

                return `
                    <div class="${style} rounded-xl p-4 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-white/70 rounded-xl flex items-center justify-center text-sm font-bold">
                            ${String(item.name || 'K').charAt(0).toUpperCase()}
                        </div>
                        <h4 class="font-semibold text-stone-800 truncate">${item.name || 'Tanpa Kategori'}</h4>
                        <p class="text-2xl font-bold my-2">${Number(item.percent || 0)}%</p>
                        <p class="text-sm text-stone-500">${formatCompactRupiah(item.revenue || 0)}</p>
                        <p class="text-xs ${changeClass} mt-1">${sign}${change}%</p>
                    </div>
                `;
            }).join('');
        }

        function renderOwnerProductDetails(items) {
            const table = document.getElementById('ownerProductDetailTable');
            const rows = Array.isArray(items) ? items : [];

            if (!table) return;

            if (!rows.length) {
                table.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-stone-500">Belum ada produk.</td></tr>';
                return;
            }

            table.innerHTML = rows.map((item, index) => {
                const change = Number(item.change || 0);
                const trendClass = change >= 0 ? 'text-green-600' : 'text-red-600';
                const sign = change >= 0 ? '+' : '';
                const borderClass = index === rows.length - 1 ? '' : 'border-b border-stone-100';

                return `
                    <tr class="${borderClass} table-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center text-xs font-bold text-amber-700">
                                    ${String(item.name || 'P').charAt(0).toUpperCase()}
                                </div>
                                <span class="font-medium">${item.name}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">${item.category_name || 'Tanpa Kategori'}</td>
                        <td class="px-6 py-4">${formatNumber(item.stock)}</td>
                        <td class="px-6 py-4">${formatNumber(item.quantity)}</td>
                        <td class="px-6 py-4 font-semibold text-amber-700">${formatRupiah(item.revenue)}</td>
                        <td class="px-6 py-4"><span class="${trendClass}">${sign}${change}%</span></td>
                    </tr>
                `;
            }).join('');
        }

        function changeOwnerCustomerDailyPage(page) {
            if (page < 1) return;
            ownerCustomerDailyPage = page;
            loadOwnerCustomers();
        }

        async function loadOwnerCustomers() {
            try {
                const data = await fetchOwnerJson(`/owner-customers-data?daily_page=${ownerCustomerDailyPage}&per_page=10`);
                renderOwnerCustomersPage(data);
            } catch (error) {
                console.error(error);
                const table = document.getElementById('ownerCustomerDailyTable');
                if (table) {
                    table.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-red-600">Statistik pelanggan gagal dimuat.</td></tr>';
                }
            }
        }

        function customerChangeText(value, comparison) {
            const number = Number(value || 0);
            const sign = number >= 0 ? '+' : '';
            return `${sign}${number}% ${comparison}`;
        }

        function renderOwnerCustomersPage(data) {
            const summary = data.summary || {};
            const todayChange = Number(summary.today_change || 0);
            const monthChange = Number(summary.month_change || 0);

            document.getElementById('ownerCustomerToday').textContent = formatNumber(summary.today_buyers || 0);
            document.getElementById('ownerCustomerTodayChange').textContent = customerChangeText(todayChange, 'dari kemarin');
            document.getElementById('ownerCustomerTodayChange').className = todayChange >= 0 ? 'text-green-600 text-sm mt-2' : 'text-red-600 text-sm mt-2';

            document.getElementById('ownerCustomerMonth').textContent = formatNumber(summary.month_buyers || 0);
            document.getElementById('ownerCustomerMonthChange').textContent = customerChangeText(monthChange, 'dari bulan lalu');
            document.getElementById('ownerCustomerMonthChange').className = monthChange >= 0 ? 'text-green-600 text-sm mt-2' : 'text-red-600 text-sm mt-2';

            document.getElementById('ownerCustomerAverage').textContent = Number(summary.average_daily_buyers || 0).toLocaleString('id-ID');
            document.getElementById('ownerCustomerTarget').textContent = `${Number(summary.target_progress || 0)}%`;
            document.getElementById('ownerCustomerTargetDetail').textContent = `${formatNumber(summary.month_buyers || 0)} / ${formatNumber(summary.target || 0)} pembeli`;

            renderOwnerCustomerTrend(data.trend || []);
            renderOwnerCustomerDailyTable(data.daily || []);
            renderPagination('ownerCustomerDailyPagination', data.daily_pagination, 'changeOwnerCustomerDailyPage');
        }

        function renderOwnerCustomerTrend(items) {
            const container = document.getElementById('ownerCustomerTrend');
            const rows = Array.isArray(items) ? items : [];

            if (!container) return;

            if (!rows.length) {
                container.innerHTML = '<div class="w-full text-center text-stone-500">Belum ada data pembeli.</div>';
                return;
            }

            const max = Math.max(...rows.map(item => Number(item.buyers || 0)), 1);
            container.innerHTML = rows.map(item => {
                const buyers = Number(item.buyers || 0);
                const height = buyers === 0 ? 8 : Math.max((buyers / max) * 100, 12);

                return `
                    <div class="flex-1 h-full flex flex-col items-center justify-end gap-2 min-w-0">
                        <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t-lg transition-all duration-500" title="${formatNumber(buyers)} pembeli" style="height:${height}%; min-height:10px"></div>
                        <span class="text-xs text-stone-500 truncate">${item.label}</span>
                        <span class="text-xs font-semibold text-stone-700">${formatNumber(buyers)}</span>
                    </div>
                `;
            }).join('');
        }

        function renderOwnerCustomerDailyTable(items) {
            const table = document.getElementById('ownerCustomerDailyTable');
            const rows = Array.isArray(items) ? items : [];

            if (!table) return;

            if (!rows.length) {
                table.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-stone-500">Belum ada statistik pembeli.</td></tr>';
                return;
            }

            table.innerHTML = rows.map(item => `
                <tr class="border-b border-stone-100 table-row">
                    <td class="px-6 py-4 font-medium">${item.date ? new Date(item.date).toLocaleDateString('id-ID') : '-'}</td>
                    <td class="px-6 py-4">${formatNumber(item.buyers || 0)} orang</td>
                    <td class="px-6 py-4 font-semibold text-amber-700">${formatRupiah(item.revenue || 0)}</td>
                    <td class="px-6 py-4">${formatRupiah(item.average_order || 0)}</td>
                </tr>
            `).join('');
        }

        function changeOwnerStaffRolePage(pageParam, page) {
            if (page < 1) return;
            ownerStaffRolePages[pageParam] = page;
            loadOwnerStaff();
        }

        async function loadOwnerStaff() {
            try {
                const query = new URLSearchParams({per_page: 10});
                Object.entries(ownerStaffRolePages).forEach(([key, value]) => query.set(key, value));
                const response = await fetch(`/owner-staff-data?${query.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });
                const result = await response.json();

                if (!response.ok || !result.status) {
                    throw new Error(result.message || 'Gagal memuat data karyawan');
                }

                renderOwnerStaffPage(result.data);
            } catch (error) {
                console.error(error);
                const container = document.getElementById('ownerStaffRoleTables');
                if (container) {
                    container.innerHTML = `
                        <div class="bg-white rounded-2xl p-6 border border-stone-200">
                            <p class="text-red-600">Data karyawan gagal dimuat.</p>
                        </div>
                    `;
                }
            }
        }

        function renderOwnerStaffPage(data) {
            const summary = data.summary || {};

            document.getElementById('ownerStaffTotal').textContent = formatNumber(summary.total_staff || 0);
            document.getElementById('ownerStaffOnline').textContent = formatNumber(summary.online_today || 0);
            document.getElementById('ownerStaffAverageScore').textContent = Number(summary.average_score || 0).toFixed(1);
            document.getElementById('ownerStaffTodayTransactions').textContent = formatNumber(summary.today_transactions || 0);

            renderOwnerStaffRoleTables(data.role_groups || []);
            renderOwnerStaffSchedule(data.schedule || []);
        }

        function renderOwnerStaffRoleTables(groups) {
            const container = document.getElementById('ownerStaffRoleTables');
            const roleGroups = Array.isArray(groups) ? groups : [];

            if (!container) return;

            if (!roleGroups.length) {
                container.innerHTML = `
                    <div class="bg-white rounded-2xl p-6 border border-stone-200">
                        <p class="text-stone-500 text-sm">Belum ada akun karyawan.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = roleGroups.map(group => renderOwnerStaffRoleSection(group)).join('');
            roleGroups.forEach(group => {
                renderPagination(
                    `ownerStaffPagination-${group.page_param}`,
                    group.pagination,
                    `changeOwnerStaffRolePage.bind(null, '${group.page_param}')`
                );
            });
        }

        function renderOwnerStaffRoleSection(group) {
            const rows = Array.isArray(group.staff) ? group.staff : [];
            const isCashier = group.is_cashier === true;
            const title = `Role ${capitalizeText(group.role || 'Karyawan')}`;
            const subtitle = isCashier
                ? 'Bulan ini berdasarkan transaksi kasir yang tercatat'
                : 'Data akun dan status pengguna, tanpa perhitungan pendapatan';

            const header = isCashier
                ? `
                    <th class="px-6 py-4 font-medium">Karyawan</th>
                    <th class="px-6 py-4 font-medium">Jam Kerja</th>
                    <th class="px-6 py-4 font-medium">Transaksi</th>
                    <th class="px-6 py-4 font-medium">Pendapatan</th>
                    <th class="px-6 py-4 font-medium">Rata-rata/order</th>
                    <th class="px-6 py-4 font-medium">Skor</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                `
                : `
                    <th class="px-6 py-4 font-medium">Karyawan</th>
                    <th class="px-6 py-4 font-medium">Username</th>
                    <th class="px-6 py-4 font-medium">Jam Kerja</th>
                    <th class="px-6 py-4 font-medium">Skor</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                `;

            const body = rows.length
                ? rows.map((item, index) => renderOwnerStaffRow(item, index, rows.length, isCashier)).join('')
                : `<tr><td colspan="${isCashier ? 7 : 5}" class="px-6 py-8 text-center text-stone-500">Belum ada akun pada role ini.</td></tr>`;

            return `
                <div class="bg-white rounded-2xl p-6 border border-stone-200">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="font-bold text-lg text-stone-800">${title}</h3>
                            <p class="text-sm text-stone-500">${subtitle}</p>
                        </div>
                        <span class="px-3 py-1 bg-stone-100 text-stone-600 rounded-full text-sm font-medium">${formatNumber(rows.length)} orang</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                            <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                                ${header}
                            </tr>
                            </thead>
                            <tbody>${body}</tbody>
                        </table>
                    </div>
                    <div id="ownerStaffPagination-${group.page_param}" class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
                </div>
            `;
        }

        function renderOwnerStaffRow(item, index, totalRows, isCashier) {
            const colors = [
                'from-pink-400 to-pink-600',
                'from-blue-400 to-blue-600',
                'from-green-400 to-green-600',
                'from-purple-400 to-purple-600',
                'from-amber-400 to-amber-600',
                'from-orange-400 to-orange-600'
            ];
            const online = item.status === 'Online';
            const borderClass = index === totalRows - 1 ? '' : 'border-b border-stone-100';
            const statusClass = online ? 'bg-green-100 text-green-700' : 'bg-stone-100 text-stone-600';
            const identityCell = `
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br ${colors[index % colors.length]} rounded-full flex items-center justify-center text-white font-bold text-sm">
                            ${item.initials || 'ST'}
                        </div>
                        <div>
                            <p class="font-medium">${item.name}</p>
                            <p class="text-sm text-stone-500">${item.role_name || 'Karyawan'}</p>
                        </div>
                    </div>
                </td>
            `;
            const statusCell = `
                <td class="px-6 py-4">
                    <span class="px-3 py-1 ${statusClass} rounded-full text-sm font-medium">${item.status}</span>
                </td>
            `;

            if (isCashier) {
                return `
                    <tr class="${borderClass} table-row">
                        ${identityCell}
                        <td class="px-6 py-4">${formatNumber(item.work_hours)} jam</td>
                        <td class="px-6 py-4">${formatNumber(item.transactions)}</td>
                        <td class="px-6 py-4 font-semibold text-amber-700">${formatCompactRupiah(item.revenue)}</td>
                        <td class="px-6 py-4">${formatRupiah(item.average_order)}</td>
                        <td class="px-6 py-4">${Number(item.score || 0).toFixed(1)}</td>
                        ${statusCell}
                    </tr>
                `;
            }

            return `
                <tr class="${borderClass} table-row">
                    ${identityCell}
                    <td class="px-6 py-4">${item.username || '-'}</td>
                    <td class="px-6 py-4">${formatNumber(item.work_hours)} jam</td>
                    <td class="px-6 py-4">${Number(item.score || 0).toFixed(1)}</td>
                    ${statusCell}
                </tr>
            `;
        }

        function capitalizeText(text) {
            return String(text || '')
                .split(' ')
                .filter(Boolean)
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }

        function renderOwnerStaffSchedule(items) {
            const container = document.getElementById('ownerStaffSchedule');
            const rows = Array.isArray(items) ? items : [];
            const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

            if (!container) return;

            if (!rows.length) {
                container.innerHTML = '<div class="col-span-8 text-stone-500 py-6">Belum ada jadwal karyawan.</div>';
                return;
            }

            const header = `
                <div class="font-semibold text-stone-600">Karyawan</div>
                ${days.map(day => `<div class="font-semibold text-stone-600">${day}</div>`).join('')}
            `;

            const body = rows.map(item => `
                <div class="font-medium truncate">${item.name}</div>
                ${(item.schedule || []).map(shift => {
                    const isOff = shift === 'Off';
                    return `<div class="${isOff ? 'bg-stone-100 text-stone-400' : 'bg-amber-100 text-amber-800'} rounded-lg py-2 px-1 whitespace-nowrap">${shift}</div>`;
                }).join('')}
            `).join('');

            container.innerHTML = header + body;
        }

        async function fetchOwnerJson(url) {
            const response = await fetch(url, {headers: {'Accept': 'application/json'}});
            const result = await response.json();
            if (!response.ok || !result.status) throw new Error(result.message || 'Data gagal dimuat');
            return result.data;
        }

        function changeOwnerFinancialRecordsPage(page) {
            if (page < 1) return;
            ownerFinancialRecordsPage = page;
            loadOwnerFinancial();
        }

        async function loadOwnerFinancial() {
            try {
                const data = await fetchOwnerJson(`/owner-financial-data?records_page=${ownerFinancialRecordsPage}&per_page=10`);
                document.getElementById('ownerFinancialRevenue').textContent = formatCompactRupiah(data.summary.revenue);
                document.getElementById('ownerFinancialExpense').textContent = formatCompactRupiah(data.summary.expense);
                document.getElementById('ownerFinancialProfit').textContent = formatCompactRupiah(data.summary.net_profit);
                document.getElementById('ownerFinancialMargin').textContent = `${data.summary.margin}% margin`;
                document.getElementById('ownerFinancialYearProfit').textContent = formatCompactRupiah(data.summary.year_profit);
                renderFinancialExpenses(data.expenses || []);
                renderFinancialMonthlyProfit(data.monthly_profit || []);
                renderFinancialRecords(data.records || []);
                renderPagination('ownerFinancialRecordsPagination', data.records_pagination, 'changeOwnerFinancialRecordsPage');
            } catch (error) {
                console.error(error);
            }
        }

        function renderFinancialExpenses(items) {
            const container = document.getElementById('ownerFinancialExpenses');
            const total = items.reduce((sum, item) => sum + Number(item.amount || 0), 0) || 1;
            container.innerHTML = items.map(item => {
                const percent = Math.round((Number(item.amount || 0) / total) * 100);
                return `
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">${item.category}</span>
                            <span class="font-semibold text-red-600">${formatCompactRupiah(item.amount)}</span>
                        </div>
                        <div class="h-3 bg-stone-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-red-400 to-red-600 rounded-full" style="width:${percent}%"></div>
                        </div>
                        <p class="text-xs text-stone-500 mt-1">${item.description || ''}</p>
                    </div>
                `;
            }).join('');
        }

        function renderFinancialMonthlyProfit(items) {
            const container = document.getElementById('ownerFinancialMonthlyProfit');
            const max = Math.max(...items.map(item => Number(item.profit || 0)), 1);
            container.innerHTML = items.map(item => {
                const width = Math.max((Number(item.profit || 0) / max) * 100, 4);
                return `
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-stone-600 w-24">${item.label}</span>
                        <div class="flex-1 h-2 bg-stone-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-600 rounded-full" style="width:${width}%"></div>
                        </div>
                        <span class="font-semibold text-amber-700">${formatCompactRupiah(item.profit)}</span>
                    </div>
                `;
            }).join('');
        }

        function renderFinancialRecords(items) {
            const table = document.getElementById('ownerFinancialRecords');
            if (!items.length) {
                table.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-stone-500">Belum ada riwayat keuangan.</td></tr>';
                return;
            }
            table.innerHTML = items.map(item => {
                const masuk = item.type === 'Masuk';
                return `
                    <tr class="border-b border-stone-100 table-row">
                        <td class="px-6 py-4">${new Date(item.date).toLocaleDateString('id-ID')}</td>
                        <td class="px-6 py-4">${item.category}</td>
                        <td class="px-6 py-4">${item.description}</td>
                        <td class="px-6 py-4"><span class="px-3 py-1 ${masuk ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} rounded-full text-sm font-medium">${item.type}</span></td>
                        <td class="px-6 py-4 font-semibold ${masuk ? 'text-green-600' : 'text-red-600'}">${masuk ? '+' : '-'}${formatRupiah(item.amount)}</td>
                    </tr>
                `;
            }).join('');
        }

        function changeOwnerInventoryPage(page) {
            if (page < 1) return;
            ownerInventoryPage = page;
            loadOwnerInventory();
        }

        function changeOwnerStockLogsPage(page) {
            if (page < 1) return;
            ownerStockLogsPage = page;
            loadOwnerInventory();
        }

        async function loadOwnerInventory() {
            try {
                const data = await fetchOwnerJson(`/owner-inventory-data?items_page=${ownerInventoryPage}&logs_page=${ownerStockLogsPage}&per_page=10`);
                document.getElementById('ownerInventoryTotal').textContent = formatNumber(data.summary.total_items);
                document.getElementById('ownerInventoryLow').textContent = formatNumber(data.summary.low_stock);
                document.getElementById('ownerInventoryOut').textContent = formatNumber(data.summary.out_of_stock);
                document.getElementById('ownerInventoryValue').textContent = formatCompactRupiah(data.summary.inventory_value);
                renderInventoryLowItems(data.low_stock_items || []);
                renderInventoryTable(data.items || []);
                renderPagination('ownerInventoryPagination', data.items_pagination, 'changeOwnerInventoryPage');
                renderOwnerStockLogs(data.logs || []);
                renderPagination('ownerStockLogsPagination', data.logs_pagination, 'changeOwnerStockLogsPage');
            } catch (error) {
                console.error(error);
            }
        }

        function renderInventoryLowItems(items) {
            const container = document.getElementById('ownerInventoryLowItems');
            if (!items.length) {
                container.innerHTML = '<p class="text-sm text-green-700">Semua stok masih aman.</p>';
                return;
            }
            container.innerHTML = items.map(item => `
                <div class="bg-white rounded-xl p-4 border border-red-200">
                    <p class="font-semibold text-stone-800">${item.name}</p>
                    <p class="text-red-600 font-bold">Tersisa ${formatNumber(item.stock)}</p>
                </div>
            `).join('');
        }

        function inventoryBadge(status) {
            const classes = {
                Aman: 'bg-green-100 text-green-700',
                Rendah: 'bg-amber-100 text-amber-700',
                Habis: 'bg-red-100 text-red-700'
            };
            return `<span class="px-3 py-1 ${classes[status] || 'bg-stone-100 text-stone-700'} rounded-full text-sm font-medium">${status}</span>`;
        }

        function renderInventoryTable(items) {
            const table = document.getElementById('ownerInventoryTable');
            if (!items.length) {
                table.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-stone-500">Belum ada produk.</td></tr>';
                return;
            }
            table.innerHTML = items.map(item => `
                <tr class="border-b border-stone-100 table-row">
                    <td class="px-6 py-4 font-medium">${item.name}</td>
                    <td class="px-6 py-4">${item.category_name}</td>
                    <td class="px-6 py-4">${formatNumber(item.stock)}</td>
                    <td class="px-6 py-4">${formatNumber(item.min_stock)}</td>
                    <td class="px-6 py-4 font-semibold text-amber-700">${formatCompactRupiah(item.value)}</td>
                    <td class="px-6 py-4">${inventoryBadge(item.status)}</td>
                    <td class="px-6 py-4 text-stone-500">${item.updated_at ? new Date(item.updated_at).toLocaleDateString('id-ID') : '-'}</td>
                </tr>
            `).join('');
        }

        function stockLogTypeBadge(type) {
            const labels = {
                in: 'Masuk',
                out: 'Keluar',
                adjustment: 'Penyesuaian'
            };
            const classes = {
                in: 'bg-green-100 text-green-700',
                out: 'bg-red-100 text-red-700',
                adjustment: 'bg-blue-100 text-blue-700'
            };

            return `<span class="px-3 py-1 ${classes[type] || 'bg-stone-100 text-stone-700'} rounded-full text-sm font-medium">${labels[type] || type || '-'}</span>`;
        }

        function renderOwnerStockLogs(items) {
            const table = document.getElementById('ownerStockLogsTable');
            const rows = Array.isArray(items) ? items : [];

            if (!table) return;

            if (!rows.length) {
                table.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-stone-500">Belum ada riwayat stok.</td></tr>';
                return;
            }

            table.innerHTML = rows.map(item => `
                <tr class="border-b border-stone-100 table-row">
                    <td class="px-6 py-4 text-stone-500 whitespace-nowrap">${item.created_at ? new Date(item.created_at).toLocaleString('id-ID') : '-'}</td>
                    <td class="px-6 py-4 font-medium">${item.product_name || 'Produk terhapus'}</td>
                    <td class="px-6 py-4">${stockLogTypeBadge(item.type)}</td>
                    <td class="px-6 py-4">${formatNumber(item.quantity || 0)}</td>
                    <td class="px-6 py-4">${formatNumber(item.before_stock || 0)}</td>
                    <td class="px-6 py-4">${formatNumber(item.after_stock || 0)}</td>
                    <td class="px-6 py-4">${item.note || '-'}</td>
                    <td class="px-6 py-4">${item.user_name || '-'}</td>
                </tr>
            `).join('');
        }

        async function loadOwnerPerformance() {
            try {
                const data = await fetchOwnerJson('/owner-performance-data');
                document.getElementById('ownerPerformanceSpeed').textContent = `${data.summary.service_speed} min`;
                document.getElementById('ownerPerformanceRating').textContent = `${data.summary.rating}/5`;
                document.getElementById('ownerPerformanceRepeat').textContent = `${data.summary.repeat_rate}%`;
                document.getElementById('ownerPerformanceGrowth').textContent = `${data.summary.growth_rate >= 0 ? '+' : ''}${data.summary.growth_rate}%`;
                renderPerformanceOperational(data.operational || []);
                renderPerformanceKpis(data.kpis || []);
                renderPerformanceInsights(data.insights || []);
            } catch (error) {
                console.error(error);
            }
        }

        function renderPerformanceOperational(items) {
            document.getElementById('ownerPerformanceOperational').innerHTML = items.map(item => `
                <div class="flex items-center justify-between p-4 bg-stone-50 rounded-xl gap-4">
                    <div><p class="font-medium">${item.label}</p><p class="text-sm text-stone-500">${item.description}</p></div>
                    <span class="text-xl font-bold text-amber-700">${item.value}</span>
                </div>
            `).join('');
        }

        function renderPerformanceKpis(items) {
            document.getElementById('ownerPerformanceKpis').innerHTML = items.map(item => {
                const percent = Math.min(Number(item.percent || 0), 100);
                return `
                    <div>
                        <div class="flex justify-between mb-2"><span class="font-medium">${item.label}</span><span class="text-green-600 font-semibold">${item.percent}%</span></div>
                        <div class="h-3 bg-stone-100 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full" style="width:${percent}%"></div></div>
                        <p class="text-sm text-stone-500 mt-1">${item.detail}</p>
                    </div>
                `;
            }).join('');
        }

        function renderPerformanceInsights(items) {
            document.getElementById('ownerPerformanceInsights').innerHTML = items.map(item => `
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <h4 class="font-semibold text-amber-800 mb-1">${item.type}</h4>
                    <p class="text-sm text-amber-700">${item.message}</p>
                </div>
            `).join('');
        }

        async function loadOwnerSettings() {
            try {
                const data = await fetchOwnerJson('/owner-settings-data');
                document.getElementById('settingBusinessName').value = data.business_name || '';
                document.getElementById('settingBusinessAddress').value = data.business_address || '';
                document.getElementById('settingBusinessPhone').value = data.business_phone || '';
                document.getElementById('settingBusinessEmail').value = data.business_email || '';
                document.getElementById('settingTaxPercent').value = data.tax_percent || 0;
                document.getElementById('settingCurrency').value = data.currency || 'IDR';
                document.getElementById('settingMonthlyTarget').value = data.monthly_revenue_target || 0;
                document.getElementById('settingCustomerTarget').value = data.new_customer_target || 0;
                document.getElementById('settingNotifyLowStock').checked = String(data.notify_low_stock) === '1';
                document.getElementById('settingNotifyDaily').checked = String(data.notify_daily_report) === '1';
                document.getElementById('settingNotifyWeekly').checked = String(data.notify_weekly_report) === '1';
                document.getElementById('settingNotifyStaff').checked = String(data.notify_staff) === '1';
            } catch (error) {
                console.error(error);
            }
        }

        async function saveOwnerSettings(event) {
            event.preventDefault();
            const payload = {
                business_name: document.getElementById('settingBusinessName').value,
                business_address: document.getElementById('settingBusinessAddress').value,
                business_phone: document.getElementById('settingBusinessPhone').value,
                business_email: document.getElementById('settingBusinessEmail').value,
                tax_percent: document.getElementById('settingTaxPercent').value,
                currency: document.getElementById('settingCurrency').value,
                monthly_revenue_target: document.getElementById('settingMonthlyTarget').value,
                new_customer_target: document.getElementById('settingCustomerTarget').value,
                notify_low_stock: document.getElementById('settingNotifyLowStock').checked ? 1 : 0,
                notify_daily_report: document.getElementById('settingNotifyDaily').checked ? 1 : 0,
                notify_weekly_report: document.getElementById('settingNotifyWeekly').checked ? 1 : 0,
                notify_staff: document.getElementById('settingNotifyStaff').checked ? 1 : 0,
                current_password: document.getElementById('settingCurrentPassword').value,
                new_password: document.getElementById('settingNewPassword').value,
                new_password_confirmation: document.getElementById('settingNewPasswordConfirmation').value,
            };
            try {
                const response = await fetch('/owner-settings-data', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                    body: JSON.stringify(payload)
                });
                const result = await response.json();
                if (!response.ok || !result.status) throw new Error(result.message || 'Gagal menyimpan pengaturan');
                alert(result.message || 'Pengaturan tersimpan');
                ['settingCurrentPassword', 'settingNewPassword', 'settingNewPasswordConfirmation'].forEach(id => document.getElementById(id).value = '');
            } catch (error) {
                alert(error.message);
            }
        }

        // Toggle Quick Actions Menu
        function toggleQuickActions() {
            const menu = document.getElementById('quickActionsMenu');
            menu.classList.toggle('hidden');
        }

        // Close quick actions when clicking outside
        document.addEventListener('click', (e) => {
            const menu = document.getElementById('quickActionsMenu');
            const button = e.target.closest('button');
            if (!menu.contains(e.target) && (!button || !button.onclick?.toString().includes('toggleQuickActions'))) {
                menu.classList.add('hidden');
            }
        });

        // Quick Actions
        function exportData() {
            alert('Fitur export data akan tersedia setelah integrasi dengan backend');
        }

        function generateReport() {
            alert('Fitur generate laporan akan tersedia setelah integrasi dengan backend');
        }

        function viewAnalytics() {
            showPage('performance');
        }

        function refreshData() {
            alert('Data berhasil di-refresh!');
        }

        function viewDetails() {
            showPage('revenue');
        }

        function saveAllSettings() {
            alert('Pengaturan berhasil disimpan!');
        }
    </script>
<?= $this->endSection() ?>
