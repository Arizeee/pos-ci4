<div id="page-inventory" class="page-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <p class="text-stone-500 text-sm">Total Item</p>
            <p id="ownerInventoryTotal" class="text-3xl font-bold text-stone-800">0</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <p class="text-stone-500 text-sm">Stok Rendah</p>
            <p id="ownerInventoryLow" class="text-3xl font-bold text-amber-600">0</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <p class="text-stone-500 text-sm">Stok Habis</p>
            <p id="ownerInventoryOut" class="text-3xl font-bold text-red-600">0</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <p class="text-stone-500 text-sm">Nilai Inventory</p>
            <p id="ownerInventoryValue" class="text-3xl font-bold text-stone-800">Rp 0</p>
        </div>
    </div>

    <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-6">
        <h3 class="font-bold text-red-800 mb-4">Peringatan Stok Rendah</h3>
        <div id="ownerInventoryLowItems" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <p class="text-sm text-red-700">Memuat stok...</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <h3 class="font-bold text-lg text-stone-800 mb-6">Daftar Inventory</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                    <th class="px-6 py-4 font-medium">Item</th>
                    <th class="px-6 py-4 font-medium">Kategori</th>
                    <th class="px-6 py-4 font-medium">Stok</th>
                    <th class="px-6 py-4 font-medium">Min. Stok</th>
                    <th class="px-6 py-4 font-medium">Nilai</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium">Update</th>
                </tr>
                </thead>
                <tbody id="ownerInventoryTable">
                <tr><td colspan="7" class="px-6 py-8 text-center text-stone-500">Memuat inventory...</td></tr>
                </tbody>
            </table>
        </div>
        <div id="ownerInventoryPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200 mt-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h3 class="font-bold text-lg text-stone-800">Riwayat Stok</h3>
                <p class="text-sm text-stone-500 mt-1">Pergerakan stok masuk, keluar, dan penyesuaian</p>
            </div>
            <span class="text-sm text-stone-500">10 log per halaman</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                    <th class="px-6 py-4 font-medium">Waktu</th>
                    <th class="px-6 py-4 font-medium">Produk</th>
                    <th class="px-6 py-4 font-medium">Tipe</th>
                    <th class="px-6 py-4 font-medium">Jumlah</th>
                    <th class="px-6 py-4 font-medium">Sebelum</th>
                    <th class="px-6 py-4 font-medium">Sesudah</th>
                    <th class="px-6 py-4 font-medium">Catatan</th>
                    <th class="px-6 py-4 font-medium">User</th>
                </tr>
                </thead>
                <tbody id="ownerStockLogsTable">
                <tr><td colspan="8" class="px-6 py-8 text-center text-stone-500">Memuat riwayat stok...</td></tr>
                </tbody>
            </table>
        </div>
        <div id="ownerStockLogsPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>
</div>
