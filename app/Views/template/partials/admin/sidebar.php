<aside
    class="w-64 bg-stone-900 text-white flex-shrink-0 hidden lg:flex flex-col fixed left-0 top-0 bottom-0 z-40">
    <div class="p-6 border-b border-stone-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-600 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-lg">WP</span>
            </div>
            <div>
                <h2 class="font-semibold text-lg">Warkop Pos</h2>
                <p class="text-stone-400 text-sm">Admin Panel</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 p-4 overflow-y-auto custom-scrollbar">
        <ul class="space-y-1">
            <li>
                <a href="#" onclick="showPage('dashboard')"
                   class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                    <span>📊</span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="showPage('accounts')"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                    <span>👤</span>
                    <span>Akun</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="showPage('products')"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                    <span>📦</span>
                    <span>Produk</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="showPage('orders')"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                    <span>📋</span>
                    <span>Pesanan</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="showPage('transactions')"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                    <span>💰</span>
                    <span>Transaksi</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="showPage('payments')"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                    <span>💳</span>
                    <span>Payment</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="showPage('reports')"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                    <span>📈</span>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="showPage('settings')"
                   class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
                    <span>⚙️</span>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="p-4 border-t border-stone-800">
        <a href="#" onclick="handleLogout(event)"
        class="flex items-center gap-3 px-4 py-3 rounded-lg text-stone-300 hover:bg-stone-800 transition-all">
            <span>🚪</span>
            <span>Keluar</span>
        </a>
    </div>
</aside>
