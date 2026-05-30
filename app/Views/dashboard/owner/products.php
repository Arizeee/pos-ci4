<div id="page-products" class="page-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <span class="text-sm font-bold text-amber-700">PR</span>
                </div>
                <div>
                    <p class="text-stone-500 text-sm">Total Produk</p>
                    <p id="ownerProductTotal" class="text-2xl font-bold text-stone-800">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-sm font-bold text-green-700">TOP</span>
                </div>
                <div class="min-w-0">
                    <p class="text-stone-500 text-sm">Produk Terlaris</p>
                    <p id="ownerProductBestSeller" class="text-xl font-bold text-stone-800 truncate">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <span class="text-sm font-bold text-red-700">MIN</span>
                </div>
                <div class="min-w-0">
                    <p class="text-stone-500 text-sm">Penurunan Terbesar</p>
                    <p id="ownerProductBiggestDrop" class="text-xl font-bold text-stone-800 truncate">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-sm font-bold text-blue-700">NEW</span>
                </div>
                <div class="min-w-0">
                    <p class="text-stone-500 text-sm">Produk Baru</p>
                    <p id="ownerProductNewest" class="text-xl font-bold text-stone-800 truncate">-</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-1">Performa Produk</h3>
            <p class="text-sm text-stone-500 mb-6">Bulan ini dibanding bulan lalu</p>
            <div id="ownerProductPerformance" class="space-y-4">
                <p class="text-stone-500 text-sm">Memuat performa produk...</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-1">Analisis Kategori</h3>
            <p class="text-sm text-stone-500 mb-6">Kontribusi pendapatan bulan ini</p>
            <div id="ownerProductCategories" class="grid grid-cols-2 gap-4">
                <p class="text-stone-500 text-sm col-span-2">Memuat kategori...</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <h3 class="font-bold text-lg text-stone-800 mb-6">Detail Produk</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                    <th class="px-6 py-4 font-medium">Produk</th>
                    <th class="px-6 py-4 font-medium">Kategori</th>
                    <th class="px-6 py-4 font-medium">Stok</th>
                    <th class="px-6 py-4 font-medium">Terjual</th>
                    <th class="px-6 py-4 font-medium">Pendapatan</th>
                    <th class="px-6 py-4 font-medium">Trend</th>
                </tr>
                </thead>
                <tbody id="ownerProductDetailTable">
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-stone-500">Memuat detail produk...</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="ownerProductDetailsPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>
</div>
