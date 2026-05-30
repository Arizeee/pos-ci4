<div id="page-revenue" class="page-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
            <p class="text-green-100 text-sm mb-1">Pendapatan Hari Ini</p>
            <p id="ownerRevenueToday" class="text-3xl font-bold">Rp 0</p>
            <p id="ownerRevenueTodayChange" class="text-green-100 text-sm mt-2">0% dari kemarin</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <p class="text-blue-100 text-sm mb-1">Pendapatan Minggu Ini</p>
            <p id="ownerRevenueWeek" class="text-3xl font-bold">Rp 0</p>
            <p id="ownerRevenueWeekChange" class="text-blue-100 text-sm mt-2">0% dari minggu lalu</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
            <p class="text-purple-100 text-sm mb-1">Pendapatan Bulan Ini</p>
            <p id="ownerRevenueMonth" class="text-3xl font-bold">Rp 0</p>
            <p id="ownerRevenueMonthChange" class="text-purple-100 text-sm mt-2">0% dari bulan lalu</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-6 text-white">
            <p class="text-amber-100 text-sm mb-1">Pendapatan Tahun Ini</p>
            <p id="ownerRevenueYear" class="text-3xl font-bold">Rp 0</p>
            <p id="ownerRevenueYearChange" class="text-amber-100 text-sm mt-2">0% dari tahun lalu</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-1">Pendapatan Harian</h3>
            <p class="text-sm text-stone-500 mb-6">7 hari terakhir</p>
            <div id="ownerDailyRevenueChart" class="h-64 flex items-end justify-between gap-2 px-2">
                <div class="w-full text-center text-stone-500">Memuat data pendapatan...</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-1">Pendapatan per Jam</h3>
            <p class="text-sm text-stone-500 mb-6">Hari ini</p>
            <div id="ownerHourlyRevenueChart" class="h-64 flex items-end justify-between gap-1 px-2">
                <div class="w-full text-center text-stone-500">Memuat data pendapatan...</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-lg text-stone-800">Detail Pendapatan</h3>
            <button
                class="bg-amber-700 hover:bg-amber-800 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">
                Export Excel
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                    <th class="px-6 py-4 font-medium">Periode</th>
                    <th class="px-6 py-4 font-medium">Pesanan</th>
                    <th class="px-6 py-4 font-medium">Total Pendapatan</th>
                    <th class="px-6 py-4 font-medium">Rata-rata Order</th>
                    <th class="px-6 py-4 font-medium">Trend</th>
                </tr>
                </thead>
                <tbody id="ownerRevenueDetailTable">
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-stone-500">Memuat detail pendapatan...</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
