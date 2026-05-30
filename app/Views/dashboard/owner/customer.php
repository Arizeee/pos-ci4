<div id="page-customers" class="page-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <p class="text-stone-500 text-sm">Pembeli Hari Ini</p>
            <p id="ownerCustomerToday" class="text-3xl font-bold text-stone-800">0</p>
            <p id="ownerCustomerTodayChange" class="text-green-600 text-sm mt-2">0% dari kemarin</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <p class="text-stone-500 text-sm">Pembeli Bulan Ini</p>
            <p id="ownerCustomerMonth" class="text-3xl font-bold text-stone-800">0</p>
            <p id="ownerCustomerMonthChange" class="text-green-600 text-sm mt-2">0% dari bulan lalu</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <p class="text-stone-500 text-sm">Rata-rata Harian</p>
            <p id="ownerCustomerAverage" class="text-3xl font-bold text-stone-800">0</p>
            <p class="text-stone-400 text-sm mt-2">Pembeli per hari bulan ini</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <p class="text-stone-500 text-sm">Target Bulanan</p>
            <p id="ownerCustomerTarget" class="text-3xl font-bold text-stone-800">0%</p>
            <p id="ownerCustomerTargetDetail" class="text-stone-400 text-sm mt-2">0 / 0 pembeli</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="font-bold text-lg text-stone-800">Pembeli 7 Hari Terakhir</h3>
                <p class="text-sm text-stone-500 mt-1">Satu transaksi sukses dihitung sebagai satu pembeli</p>
            </div>
        </div>
        <div id="ownerCustomerTrend" class="h-72 flex items-end gap-3">
            <div class="w-full text-center text-stone-500">Memuat statistik pelanggan...</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <h3 class="font-bold text-lg text-stone-800 mb-6">Statistik Pembeli Harian</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium">Pembeli</th>
                    <th class="px-6 py-4 font-medium">Pendapatan</th>
                    <th class="px-6 py-4 font-medium">Rata-rata/order</th>
                </tr>
                </thead>
                <tbody id="ownerCustomerDailyTable">
                <tr><td colspan="4" class="px-6 py-8 text-center text-stone-500">Memuat data harian...</td></tr>
                </tbody>
            </table>
        </div>
        <div id="ownerCustomerDailyPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>
</div>
