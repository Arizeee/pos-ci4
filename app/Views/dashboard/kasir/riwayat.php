<div id="page-riwayat" class="page-content hidden h-full flex flex-col">
    <header class="bg-white border-b border-stone-200 px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-stone-800">Riwayat Transaksi</h1>
                <p class="text-stone-500 text-sm">Lihat semua transaksi yang telah terjadi</p>
            </div>
            <button onclick="exportTransactions()"
                    class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-2 rounded-xl font-medium transition-colors">
                📥 Export Data
            </button>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6">
        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <p class="text-stone-500 text-sm mb-1">Total Transaksi</p>
                <p class="text-2xl font-bold text-stone-800" id="totalTransactions">156</p>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <p class="text-stone-500 text-sm mb-1">Total Pendapatan</p>
                <p class="text-2xl font-bold text-amber-700" id="totalRevenue">Rp 2.450.000</p>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <p class="text-stone-500 text-sm mb-1">Rata-rata Transaksi</p>
                <p class="text-2xl font-bold text-stone-800" id="averageTransaction">Rp 0</p>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <p class="text-stone-500 text-sm mb-1">Produk Terjual</p>
                <p class="text-2xl font-bold text-stone-800" id="totalItemsSold">482</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl p-4 border border-stone-200 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-stone-600 text-sm mb-1">Dari Tanggal</label>
                    <input type="date" id="filterDateFrom"
                           class="w-full px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div class="flex-1">
                    <label class="block text-stone-600 text-sm mb-1">Sampai Tanggal</label>
                    <input type="date" id="filterDateTo"
                           class="w-full px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div class="flex-1">
                    <label class="block text-stone-600 text-sm mb-1">Metode Pembayaran</label>
                    <select id="filterPaymentMethod"
                            class="w-full px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                        <option value="">Semua Metode</option>
                        <option value="cash">💵 Tunai</option>
                        <option value="qr">📱 QRIS</option>
                        <option value="transfer">🏦 Transfer</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="applyTransactionFilters()"
                            class="w-full bg-amber-700 hover:bg-amber-800 text-white px-6 py-2 rounded-xl font-medium transition-colors">
                        Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                        <th class="px-6 py-4 font-medium">ID Transaksi</th>
                        <th class="px-6 py-4 font-medium">Waktu</th>
                        <th class="px-6 py-4 font-medium">Items</th>
                        <th class="px-6 py-4 font-medium">Total</th>
                        <th class="px-6 py-4 font-medium">Bayar</th>
                        <th class="px-6 py-4 font-medium">Kembalian</th>
                        <th class="px-6 py-4 font-medium">Metode</th>
                        <th class="px-6 py-4 font-medium">Aksi</th>
                    </tr>
                    </thead>
                    <tbody id="transactionsTable">
                    <!-- Transactions will be rendered here -->
                    </tbody>
                </table>
            </div>
            <div id="kasirTransactionsPagination" class="hidden px-6 py-4 border-t border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4"></div>
        </div>
    </div>
</div>
