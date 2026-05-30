<div id="page-financial" class="page-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
            <p class="text-green-100 text-sm mb-1">Total Pendapatan</p>
            <p id="ownerFinancialRevenue" class="text-3xl font-bold">Rp 0</p>
            <p class="text-green-100 text-sm mt-2">Bulan ini</p>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white">
            <p class="text-red-100 text-sm mb-1">Total Pengeluaran</p>
            <p id="ownerFinancialExpense" class="text-3xl font-bold">Rp 0</p>
            <p class="text-red-100 text-sm mt-2">Estimasi bulan ini</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <p class="text-blue-100 text-sm mb-1">Laba Bersih</p>
            <p id="ownerFinancialProfit" class="text-3xl font-bold">Rp 0</p>
            <p id="ownerFinancialMargin" class="text-blue-100 text-sm mt-2">0% margin</p>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
            <p class="text-purple-100 text-sm mb-1">Profit Tahun Ini</p>
            <p id="ownerFinancialYearProfit" class="text-3xl font-bold">Rp 0</p>
            <p class="text-purple-100 text-sm mt-2">Akumulasi estimasi</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-6">Komponen Pengeluaran</h3>
            <div id="ownerFinancialExpenses" class="space-y-4">
                <p class="text-stone-500 text-sm">Memuat data...</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-6">Profit Bulanan</h3>
            <div id="ownerFinancialMonthlyProfit" class="space-y-4">
                <p class="text-stone-500 text-sm">Memuat data...</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <h3 class="font-bold text-lg text-stone-800 mb-6">Riwayat Keuangan</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium">Kategori</th>
                    <th class="px-6 py-4 font-medium">Deskripsi</th>
                    <th class="px-6 py-4 font-medium">Tipe</th>
                    <th class="px-6 py-4 font-medium">Jumlah</th>
                </tr>
                </thead>
                <tbody id="ownerFinancialRecords">
                <tr><td colspan="5" class="px-6 py-8 text-center text-stone-500">Memuat riwayat...</td></tr>
                </tbody>
            </table>
        </div>
        <div id="ownerFinancialRecordsPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>
</div>
