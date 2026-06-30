<aside
    class="w-72 bg-stone-900 text-white flex-shrink-0 hidden lg:flex flex-col fixed left-0 top-0 bottom-0 z-40">
    <!-- Logo Section -->
    <div class="p-6 border-b border-stone-800">
        <div class="flex items-center gap-3">
            <div
                class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-700 rounded-xl flex items-center justify-center shadow-lg">
                <span class="text-white font-bold text-xl">WP</span>
            </div>
            <div>
                <h2 class="font-bold text-lg">Warkop Pos</h2>
                <p class="text-amber-500 text-sm font-medium">Owner Dashboard</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 overflow-y-auto custom-scrollbar">
        <div class="mb-6">
            <p class="text-stone-500 text-xs font-semibold uppercase tracking-wider mb-3 px-4">Menu Utama</p>
            <ul class="space-y-1">
                <li>
                    <a href="#" onclick="showPage('overview')"
                       class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">📊</span>
                        <span>Overview</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="showPage('revenue')"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">💰</span>
                        <span>Pendapatan</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="showPage('products')"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">📦</span>
                        <span>Analisis Produk</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="showPage('customers')"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">👥</span>
                        <span>Pelanggan</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="showPage('staff')"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">👨‍💼</span>
                        <span>Karyawan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="mb-6">
            <p class="text-stone-500 text-xs font-semibold uppercase tracking-wider mb-3 px-4">Laporan</p>
            <ul class="space-y-1">
                <li>
                    <a href="#" onclick="showPage('financial')"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">📈</span>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="showPage('inventory')"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">📋</span>
                        <span>Stok & Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="showPage('performance')"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">⚡</span>
                        <span>Kinerja</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="mb-6">
            <p class="text-stone-500 text-xs font-semibold uppercase tracking-wider mb-3 px-4">Pengaturan</p>
            <ul class="space-y-1">
                <li>
                    <a href="#" onclick="showPage('settings')"
                       class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                        <span class="text-lg">⚙️</span>
                        <span>Pengaturan</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-stone-800">
        <div class="bg-stone-800 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center">
                    <span class="text-xl">👔</span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold">Pak Budi</p>
                    <p class="text-stone-400 text-sm">Owner</p>
                </div>
                <button class="text-stone-400 hover:text-white transition-colors">
                    <!-- <a href="<?= base_url('logout') ?>" 
                    class="text-stone-400 hover:text-red-400 transition-colors"
                    onclick="return confirm('Yakin ingin logout?')">
                        <span class="text-xl">🚪</span>
                    </a> -->
                    <a href="<?= base_url('logout') ?>" onclick="if(confirm('Yakin ingin logout?')) handleLogout(event); else event.preventDefault();">
                        <span class="text-xl">🚪</span>
                    </a>
                </button>
            </div>
        </div>
    </div>
</aside>
