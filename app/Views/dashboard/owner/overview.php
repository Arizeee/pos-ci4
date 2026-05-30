<div id="page-overview" class="page-content">
    <!-- Alert Banner -->
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">🎉</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg" id="ownerOverviewAlertTitle">Memuat overview...</h3>
                    <p class="text-amber-100" id="ownerOverviewAlertText">Data owner sedang disiapkan dari database</p>
                </div>
            </div>
            <button onclick="viewDetails()"
                    class="bg-white text-amber-600 px-6 py-2 rounded-xl font-semibold hover:bg-amber-50 transition-colors">
                Lihat Detail
            </button>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Revenue -->
        <div class="stat-card bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-2xl">💰</span>
                </div>
                <span id="ownerRevenueChange" class="bg-stone-100 text-stone-700 px-3 py-1 rounded-full text-sm font-semibold">0%</span>
            </div>
            <h3 class="text-stone-500 text-sm mb-1">Total Pendapatan</h3>
            <p class="text-3xl font-bold text-stone-800" id="ownerTotalRevenue">Rp 0</p>
            <p class="text-stone-400 text-sm mt-1" id="ownerPreviousRevenue">vs Rp 0 periode lalu</p>
        </div>

        <!-- Total Orders -->
        <div class="stat-card bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-2xl">📋</span>
                </div>
                <span id="ownerOrderChange" class="bg-stone-100 text-stone-700 px-3 py-1 rounded-full text-sm font-semibold">0%</span>
            </div>
            <h3 class="text-stone-500 text-sm mb-1">Total Pesanan</h3>
            <p class="text-3xl font-bold text-stone-800" id="ownerTotalOrders">0</p>
            <p class="text-stone-400 text-sm mt-1" id="ownerPreviousOrders">vs 0 periode lalu</p>
        </div>

        <!-- Average Order -->
        <div class="stat-card bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-2xl">🛒</span>
                </div>
                <span id="ownerAverageOrderChange" class="bg-stone-100 text-stone-700 px-3 py-1 rounded-full text-sm font-semibold">0%</span>
            </div>
            <h3 class="text-stone-500 text-sm mb-1">Rata-rata Order</h3>
            <p class="text-3xl font-bold text-stone-800" id="ownerAverageOrder">Rp 0</p>
            <p class="text-stone-400 text-sm mt-1" id="ownerPreviousAverageOrder">vs Rp 0 periode lalu</p>
        </div>

        <!-- Profit Margin -->
        <div class="stat-card bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-2xl">📈</span>
                </div>
                <span id="ownerActiveProductPercent" class="bg-stone-100 text-stone-700 px-3 py-1 rounded-full text-sm font-semibold">0%</span>
            </div>
            <h3 class="text-stone-500 text-sm mb-1">Produk Aktif</h3>
            <p class="text-3xl font-bold text-stone-800" id="ownerActiveProducts">0</p>
            <p class="text-stone-400 text-sm mt-1" id="ownerTotalProducts">dari 0 produk</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-lg text-stone-800">Tren Pendapatan</h3>
                    <p class="text-stone-500 text-sm" id="ownerRevenueTrendSubtitle">7 hari terakhir</p>
                </div>
                <select id="ownerRevenueTrendRange" onchange="setOwnerTrend(this.value)"
                    class="px-4 py-2 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-amber-700">
                    <option value="7d">7 Hari</option>
                    <option value="30d">30 Hari</option>
                    <option value="12m">12 Bulan</option>
                </select>
            </div>
            <div id="ownerRevenueTrend" class="h-72 flex items-end justify-between gap-3 px-2">
                <div class="w-full text-center text-stone-500">Memuat tren pendapatan...</div>
            </div>
        </div>

        <!-- Revenue by Category -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-6">Pendapatan per Kategori</h3>
            <div class="space-y-5" id="ownerCategoryRevenue">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-stone-700">☕ Kopi</span>
                        <span class="font-semibold text-amber-700">45%</span>
                    </div>
                    <div class="h-3 bg-stone-100 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-amber-400 to-amber-600 rounded-full progress-bar"
                            style="width: 45%"></div>
                    </div>
                    <p class="text-sm text-stone-500 mt-1">Rp 20.6M</p>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-stone-700">🥤 Non-Kopi</span>
                        <span class="font-semibold text-blue-600">25%</span>
                    </div>
                    <div class="h-3 bg-stone-100 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full progress-bar"
                            style="width: 25%"></div>
                    </div>
                    <p class="text-sm text-stone-500 mt-1">Rp 11.5M</p>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-stone-700">🍜 Makanan</span>
                        <span class="font-semibold text-orange-600">20%</span>
                    </div>
                    <div class="h-3 bg-stone-100 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-orange-400 to-orange-600 rounded-full progress-bar"
                            style="width: 20%"></div>
                    </div>
                    <p class="text-sm text-stone-500 mt-1">Rp 9.2M</p>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-stone-700">🍪 Snack</span>
                        <span class="font-semibold text-purple-600">10%</span>
                    </div>
                    <div class="h-3 bg-stone-100 rounded-full overflow-hidden">
                        <div
                            class="h-full bg-gradient-to-r from-purple-400 to-purple-600 rounded-full progress-bar"
                            style="width: 10%"></div>
                    </div>
                    <p class="text-sm text-stone-500 mt-1">Rp 4.6M</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Top Products -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-lg text-stone-800">Produk Terlaris</h3>
                <a href="#" onclick="showPage('products')"
                   class="text-amber-700 hover:text-amber-800 text-sm font-medium">Lihat Semua →</a>
            </div>
            <div class="space-y-4" id="ownerTopProducts">
                <div class="flex items-center gap-4 p-3 bg-stone-50 rounded-xl">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">☕</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-stone-800">Kopi Susu</h4>
                        <p class="text-sm text-stone-500">456 terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-amber-700">Rp 5.5M</p>
                        <p class="text-xs text-green-600">↑ 12%</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-3 bg-stone-50 rounded-xl">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🥤</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-stone-800">Matcha Latte</h4>
                        <p class="text-sm text-stone-500">324 terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-amber-700">Rp 4.9M</p>
                        <p class="text-xs text-green-600">↑ 18%</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-3 bg-stone-50 rounded-xl">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🍜</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-stone-800">Mie Goreng</h4>
                        <p class="text-sm text-stone-500">298 terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-amber-700">Rp 4.5M</p>
                        <p class="text-xs text-green-600">↑ 8%</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-3 bg-stone-50 rounded-xl">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🥤</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-stone-800">Avocado Juice</h4>
                        <p class="text-sm text-stone-500">267 terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-amber-700">Rp 4.8M</p>
                        <p class="text-xs text-red-600">↓ 3%</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-3 bg-stone-50 rounded-xl">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">🍪</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-stone-800">Dimsum</h4>
                        <p class="text-sm text-stone-500">234 terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-amber-700">Rp 2.8M</p>
                        <p class="text-xs text-green-600">↑ 15%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-lg text-stone-800">Aktivitas Terbaru</h3>
                <a href="#" class="text-amber-700 hover:text-amber-800 text-sm font-medium">Lihat Semua
                    →</a>
            </div>
            <div class="space-y-4" id="ownerRecentActivities">
                <div class="flex items-start gap-4">
                    <div
                        class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-lg">💰</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-stone-800"><span class="font-semibold">Pesanan #TRX-2847</span>
                            selesai</p>
                        <p class="text-sm text-stone-500">Total Rp 45.000 • 2 menit lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div
                        class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-lg">📦</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-stone-800"><span class="font-semibold">Stok Kopi Susu</span>
                            di-restock</p>
                        <p class="text-sm text-stone-500">+50 unit • 15 menit lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div
                        class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-lg">👥</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-stone-800"><span class="font-semibold">Pelanggan baru</span>
                            terdaftar</p>
                        <p class="text-sm text-stone-500">Siti Rahayu • 30 menit lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div
                        class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-lg">⚠️</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-stone-800"><span class="font-semibold">Stok Rendah:</span>
                            Indomie Rebus</p>
                        <p class="text-sm text-stone-500">Tersisa 5 unit • 1 jam lalu</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div
                        class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-lg">💵</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-stone-800"><span class="font-semibold">Setoran harian</span>
                            tercatat</p>
                        <p class="text-sm text-stone-500">Rp 2.450.000 • 2 jam lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
