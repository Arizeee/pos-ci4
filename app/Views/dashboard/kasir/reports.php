<div id="page-laporan" class="page-content hidden h-full flex flex-col">
    <header class="bg-white border-b border-stone-200 px-6 py-4">
        <h1 class="text-2xl font-bold text-stone-800">Laporan</h1>
        <p class="text-stone-500 text-sm">Analisis penjualan dari transaksi kasir</p>
    </header>

    <div class="flex-1 overflow-y-auto p-6">
        <div class="bg-white rounded-xl p-4 border border-stone-200 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex gap-2">
                    <button onclick="setReportPeriod('today', this)" class="report-period-btn active px-4 py-2 rounded-lg border border-amber-700 bg-amber-700 text-white font-medium transition-colors">Hari Ini</button>
                    <button onclick="setReportPeriod('week', this)" class="report-period-btn px-4 py-2 rounded-lg border border-stone-200 text-stone-600 font-medium hover:border-amber-700 transition-colors">Minggu Ini</button>
                    <button onclick="setReportPeriod('month', this)" class="report-period-btn px-4 py-2 rounded-lg border border-stone-200 text-stone-600 font-medium hover:border-amber-700 transition-colors">Bulan Ini</button>
                </div>
                <button onclick="printReport()" class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-2 rounded-xl font-medium transition-colors">
                    Cetak Laporan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <p class="text-stone-500 text-sm">Total Pendapatan</p>
                <p id="reportRevenue" class="text-2xl font-bold text-stone-800">Rp 0</p>
                <p id="reportRevenueChange" class="text-sm text-stone-500">0%</p>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <p class="text-stone-500 text-sm">Jumlah Transaksi</p>
                <p id="reportTransactions" class="text-2xl font-bold text-stone-800">0</p>
                <p id="reportTransactionsChange" class="text-sm text-stone-500">0%</p>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <p class="text-stone-500 text-sm">Item Terjual</p>
                <p id="reportItems" class="text-2xl font-bold text-stone-800">0</p>
                <p id="reportItemsChange" class="text-sm text-stone-500">0%</p>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <p class="text-stone-500 text-sm">Rata-rata Order</p>
                <p id="reportAverage" class="text-2xl font-bold text-stone-800">Rp 0</p>
                <p id="reportAverageChange" class="text-sm text-stone-500">0%</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-6">Grafik Penjualan</h3>
                <div id="reportSalesChart" class="h-64 flex items-end justify-between gap-2 px-2"></div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-6">Produk Terlaris</h3>
                <div id="reportTopProducts" class="space-y-4"></div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-stone-200">
            <h3 class="font-semibold text-stone-800 mb-6">Penjualan per Kategori</h3>
            <div id="reportCategories" class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6"></div>
        </div>
    </div>
</div>
