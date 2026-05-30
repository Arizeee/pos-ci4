<div id="page-dashboard" class="page-content">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card bg-white rounded-2xl p-6 border border-stone-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">P</span>
                </div>
                <span class="text-stone-500 text-sm font-medium">Total</span>
            </div>
            <h3 class="text-stone-500 text-sm mb-1">Total Produk</h3>
            <p id="dashboardTotalProducts" class="text-2xl font-bold text-stone-800">0</p>
        </div>

        <div class="stat-card bg-white rounded-2xl p-6 border border-stone-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">A</span>
                </div>
                <span class="text-green-600 text-sm font-medium">Aktif</span>
            </div>
            <h3 class="text-stone-500 text-sm mb-1">Produk Aktif</h3>
            <p id="dashboardActiveProducts" class="text-2xl font-bold text-stone-800">0</p>
        </div>

        <div class="stat-card bg-white rounded-2xl p-6 border border-stone-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">N</span>
                </div>
                <span class="text-red-600 text-sm font-medium">Non-Aktif</span>
            </div>
            <h3 class="text-stone-500 text-sm mb-1">Produk Non-Aktif</h3>
            <p id="dashboardInactiveProducts" class="text-2xl font-bold text-stone-800">0</p>
        </div>

        <div class="stat-card bg-white rounded-2xl p-6 border border-stone-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">S</span>
                </div>
                <span id="dashboardLowStockProducts" class="text-amber-700 text-sm font-medium">0 stok rendah</span>
            </div>
            <h3 class="text-stone-500 text-sm mb-1">Total Stok</h3>
            <p id="dashboardTotalStock" class="text-2xl font-bold text-stone-800">0</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-stone-500 text-sm">Stok Masuk Hari Ini</h3>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Masuk</span>
            </div>
            <p id="dashboardStockInToday" class="text-2xl font-bold text-stone-800">0</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-stone-500 text-sm">Stok Keluar Hari Ini</h3>
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">Keluar</span>
            </div>
            <p id="dashboardStockOutToday" class="text-2xl font-bold text-stone-800">0</p>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-stone-500 text-sm">Aktivitas Stok Hari Ini</h3>
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Log</span>
            </div>
            <p id="dashboardStockActivitiesToday" class="text-2xl font-bold text-stone-800">0</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-stone-800">Produk per Kategori</h3>
                <span class="text-sm text-stone-500">Data kategori</span>
            </div>
            <div id="dashboardCategoryChart" class="h-64 flex items-end justify-between gap-2 px-4">
                <div class="w-full text-center text-stone-500">Memuat data...</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-semibold text-stone-800 mb-6">Stok Terbanyak</h3>
            <div id="dashboardTopProducts" class="space-y-4">
                <p class="text-stone-500 text-sm">Memuat data...</p>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-stone-800">Stok Rendah</h3>
                <a href="#" onclick="showPage('products')"
                   class="text-amber-700 hover:text-amber-800 text-sm font-medium">Kelola Produk -></a>
            </div>
            <div id="dashboardLowStockItems" class="space-y-4">
                <p class="text-stone-500 text-sm">Memuat data...</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-stone-800">Aktivitas Stok Terbaru</h3>
                <a href="#" onclick="showPage('products')"
                   class="text-amber-700 hover:text-amber-800 text-sm font-medium">Lihat Log -></a>
            </div>
            <div id="dashboardRecentStockLogs" class="space-y-4">
                <p class="text-stone-500 text-sm">Memuat data...</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-stone-800">Produk Terbaru</h3>
            <a href="#" onclick="showPage('products')"
               class="text-amber-700 hover:text-amber-800 text-sm font-medium">Lihat Semua -></a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200">
                    <th class="pb-4 font-medium">Produk</th>
                    <th class="pb-4 font-medium">Kategori</th>
                    <th class="pb-4 font-medium">Harga</th>
                    <th class="pb-4 font-medium">Stok</th>
                    <th class="pb-4 font-medium">Status</th>
                </tr>
                </thead>
                <tbody id="dashboardLatestProducts">
                <tr>
                    <td colspan="5" class="py-4 text-center text-stone-500">Memuat data...</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
