<div id="page-menu" class="page-content hidden h-full flex flex-col">
    <header class="bg-white border-b border-stone-200 px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-stone-800">Manajemen Menu</h1>
                <p class="text-stone-500 text-sm">Kelola daftar produk dan harga</p>
            </div>
            <button onclick="openProductModal()"
                    class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-2 rounded-xl font-medium transition-colors">
                + Tambah Produk
            </button>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">📦</span>
                    </div>
                    <div>
                        <p class="text-stone-500 text-sm">Total Produk</p>
                        <p class="text-2xl font-bold text-stone-800" id="totalProducts">28</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div>
                        <p class="text-stone-500 text-sm">Produk Aktif</p>
                        <p class="text-2xl font-bold text-stone-800" id="activeProducts">28</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <span class="text-2xl">❌</span>
                    </div>
                    <div>
                        <p class="text-stone-500 text-sm">Non-Aktif</p>
                        <p class="text-2xl font-bold text-stone-800" id="inactiveProducts">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-xl p-4 border border-stone-200 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="menuSearch" placeholder="Cari produk..."
                           class="w-full px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"
                           oninput="filterMenuProducts()">
                </div>
                <select id="menuCategoryFilter"
                        class="px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"
                        onchange="filterMenuProducts()">
                    <option value="">Semua Kategori</option>
                    <option value="kopi">☕ Kopi</option>
                    <option value="non-kopi">🥤 Non-Kopi</option>
                    <option value="makanan">🍜 Makanan</option>
                    <option value="snack">🍪 Snack</option>
                </select>
                <select id="menuStatusFilter"
                        class="px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"
                        onchange="filterMenuProducts()">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Non-Aktif</option>
                </select>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl border border-stone-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left text-stone-500 text-sm border-b border-stone-200 bg-stone-50">
                        <th class="px-6 py-4 font-medium">Produk</th>
                        <th class="px-6 py-4 font-medium">Kategori</th>
                        <th class="px-6 py-4 font-medium">Harga</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Aksi</th>
                    </tr>
                    </thead>
                    <tbody id="menuProductsTable">
                    <!-- Products will be rendered here -->
                    </tbody>
                </table>
            </div>
            <div id="menuProductsPagination" class="hidden px-6 py-4 border-t border-stone-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4"></div>
        </div>
    </div>
</div>
