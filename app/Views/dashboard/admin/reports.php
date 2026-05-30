<div id="page-reports" class="page-content hidden">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="text-stone-500 text-sm mb-1">Total Pendapatan</h3>
            <p id="reportTotalRevenue" class="text-2xl font-bold text-stone-800">Rp 0</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="text-stone-500 text-sm mb-1">Pendapatan Bulan Ini</h3>
            <p id="reportMonthRevenue" class="text-2xl font-bold text-stone-800">Rp 0</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="text-stone-500 text-sm mb-1">Total Pesanan</h3>
            <p id="reportTotalOrders" class="text-2xl font-bold text-stone-800">0</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="text-stone-500 text-sm mb-1">Transaksi Berhasil</h3>
            <p id="reportTotalTransactions" class="text-2xl font-bold text-stone-800">0</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-semibold text-stone-800 mb-6">Laporan Penjualan Bulanan</h3>
            <div id="reportWeeklySales" class="space-y-4">
                <p class="text-stone-500 text-sm">Memuat data...</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-semibold text-stone-800 mb-6">Penjualan per Kategori</h3>
            <div id="reportCategorySales" class="space-y-4">
                <p class="text-stone-500 text-sm">Memuat data...</p>
            </div>
        </div>
    </div>
</div>

<script>
    async function renderReports() {
        const response = await fetch('/admin-reports-data');
        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            return;
        }

        const data = result.data;
        document.getElementById('reportTotalRevenue').textContent = formatRupiah(Number(data.summary.total_revenue));
        document.getElementById('reportMonthRevenue').textContent = formatRupiah(Number(data.summary.month_revenue));
        document.getElementById('reportTotalOrders').textContent = data.summary.total_orders;
        document.getElementById('reportTotalTransactions').textContent = data.summary.total_transactions;

        renderReportWeeklySales(data.weekly_sales);
        renderReportCategorySales(data.category_sales);
    }

    function renderReportWeeklySales(items) {
        const container = document.getElementById('reportWeeklySales');
        const maxTotal = Math.max(...items.map(item => Number(item.total)), 1);

        container.innerHTML = items.map(item => {
            const total = Number(item.total);
            const width = total === 0 ? 4 : Math.max((total / maxTotal) * 100, 8);

            return `
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-stone-600">${item.label}</span>
                        <span class="font-medium">${formatRupiah(total)}</span>
                    </div>
                    <div class="h-2 bg-stone-100 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-600 rounded-full" style="width: ${width}%"></div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function renderReportCategorySales(items) {
        const container = document.getElementById('reportCategorySales');

        if (!items.length) {
            container.innerHTML = '<p class="text-stone-500 text-sm">Belum ada data kategori.</p>';
            return;
        }

        const totalSales = items.reduce((sum, item) => sum + Number(item.total), 0);

        container.innerHTML = items.map(item => {
            const total = Number(item.total);
            const percent = totalSales === 0 ? 0 : Math.round((total / totalSales) * 100);
            const width = percent === 0 ? 4 : percent;

            return `
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-sm font-semibold text-amber-800">
                        ${productCategoryIcon(item.name)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between mb-1 gap-4">
                            <span class="font-medium truncate">${item.name}</span>
                            <span class="font-medium text-amber-700">${percent}%</span>
                        </div>
                        <div class="h-2 bg-stone-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-600 rounded-full" style="width: ${width}%"></div>
                        </div>
                        <p class="text-xs text-stone-500 mt-1">${formatRupiah(total)}</p>
                    </div>
                </div>
            `;
        }).join('');
    }
</script>
