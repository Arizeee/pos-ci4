<?= $this->extend('template/layouts/kasir') ?>

<?= $this->section('content') ?>

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?= $this->include('template/partials/kasir/sidebar') ?>

    <!-- Main Content -->
    <main class="flex-1 lg:ml-64 overflow-hidden">
        <?= $this->include('template/partials/kasir/header') ?>

        <div class="h-[calc(100vh-73px)] overflow-hidden">
            <?= $this->include('dashboard/kasir/kasir') ?>
            <?= $this->include('dashboard/kasir/menu') ?>
            <?= $this->include('dashboard/kasir/riwayat') ?>
            <?= $this->include('dashboard/kasir/reports') ?>
            <?= $this->include('dashboard/kasir/settings') ?>
        </div>
    </main>
</div>

<!-- ===================== PAYMENT MODAL ===================== -->
<div id="paymentModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-stone-800">Pembayaran</h2>
            <button onclick="closePaymentModal()" class="text-stone-400 hover:text-stone-600 text-2xl">&times;</button>
        </div>
        <div class="p-6">
            <div class="text-center mb-6">
                <p class="text-stone-500">Total Pembayaran</p>
                <p class="text-4xl font-bold text-amber-700" id="modalTotal">Rp 0</p>
                <p class="text-sm text-stone-500 mt-1" id="paymentItemCount">0 item pesanan</p>
            </div>
            <div class="mb-6 bg-stone-50 border border-stone-200 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-sm text-stone-600"><span>Subtotal</span><span id="modalSubtotal">Rp 0</span></div>
                <div class="flex justify-between text-sm text-stone-600"><span id="modalTaxLabel">Pajak</span><span id="modalTax">Rp 0</span></div>
                <div class="flex justify-between text-sm text-stone-600"><span>Pesanan</span><span id="modalOrderSummary">-</span></div>
            </div>
            <div class="mb-6">
                <label class="block text-stone-700 font-medium mb-2">Metode Pembayaran</label>
                <div id="paymentMethods" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div class="col-span-full text-center text-stone-500 py-4">Memuat metode pembayaran...</div>
                </div>
            </div>
            <div id="cashPayment" class="mb-6">
                <label class="block text-stone-700 font-medium mb-2">Uang Diterima</label>
                <input type="number" id="cashReceived" placeholder="Masukkan jumlah uang"
                       class="w-full px-4 py-3 border-2 border-stone-200 rounded-xl focus:border-amber-700 focus:outline-none"
                       oninput="calculateChange()">
                <div class="mt-2 text-right">
                    <p class="text-stone-500">Kembalian</p>
                    <p class="text-2xl font-bold text-green-600" id="change">Rp 0</p>
                </div>
            </div>
            <button onclick="processPayment()" id="confirmPaymentBtn"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-medium transition-colors">
                Konfirmasi Pembayaran
            </button>
        </div>
    </div>
</div>

<!-- ===================== SUCCESS MODAL ===================== -->
<div id="successModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 p-8 text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-4xl">✓</span>
        </div>
        <h2 class="text-2xl font-bold text-stone-800 mb-2">Pembayaran Berhasil!</h2>
        <p class="text-stone-500 mb-6" id="successPaymentSummary">Terima kasih atas pembayarannya</p>
        <div class="space-y-3">
            <button onclick="printReceipt()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-medium transition-colors">Cetak Struk</button>
            <button onclick="closeSuccessModal()" class="w-full bg-amber-700 hover:bg-amber-800 text-white py-3 rounded-xl font-medium transition-colors">Transaksi Baru</button>
        </div>
    </div>
</div>

<!-- ===================== PRODUCT MODAL ===================== -->
<div id="productModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-stone-800" id="productModalTitle">Tambah Produk</h2>
            <button onclick="closeProductModal()" class="text-stone-400 hover:text-stone-600 text-2xl">&times;</button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-stone-700 font-medium mb-2">Nama Produk</label>
                <input type="text" id="productName" placeholder="Masukkan nama produk"
                       class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
            </div>
            <div>
                <label class="block text-stone-700 font-medium mb-2">Kategori</label>
                <select id="productCategoryId" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <option value="1">☕ Kopi</option>
                    <option value="2">🥤 Non-Kopi</option>
                    <option value="3">🍜 Makanan</option>
                    <option value="4">🍪 Snack</option>
                </select>
            </div>
            <div>
                <label class="block text-stone-700 font-medium mb-2">Harga</label>
                <input type="number" id="productPrice" placeholder="Masukkan harga"
                       class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
            </div>
            <div>
                <label class="block text-stone-700 font-medium mb-2">Stok</label>
                <input type="number" id="productStock" placeholder="Masukkan stok" min="0"
                       class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
            </div>
            <div>
                <label class="block text-stone-700 font-medium mb-2">Status</label>
                <select id="productStatus" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <option value="1">Aktif</option>
                    <option value="0">Non-Aktif</option>
                </select>
            </div>
        </div>
        <div class="p-6 border-t border-stone-200 flex gap-3 justify-end">
            <button onclick="closeProductModal()" class="px-6 py-3 border border-stone-200 rounded-xl font-medium hover:bg-stone-50 transition-colors">Batal</button>
            <button onclick="saveProduct()" id="saveProductBtn" class="px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white rounded-xl font-medium transition-colors">Simpan</button>
        </div>
    </div>
</div>

<!-- ===================== TRANSACTION DETAIL MODAL ===================== -->
<div id="transactionDetailModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-stone-800">Detail Transaksi</h2>
            <button onclick="closeTransactionDetailModal()" class="text-stone-400 hover:text-stone-600 text-2xl">&times;</button>
        </div>
        <div class="p-6" id="transactionDetailContent"></div>
        <div class="p-6 border-t border-stone-200">
            <button onclick="closeTransactionDetailModal()" class="w-full bg-amber-700 hover:bg-amber-800 text-white py-3 rounded-xl font-medium transition-colors">Tutup</button>
        </div>
    </div>
</div>

<script>
// ============================================================
// STATE
// ============================================================
let products              = [];
let cart                  = [];
let currentCategory       = 'all';
let paymentMethod         = null;
let selectedPaymentMethod = null;
let paymentMethods        = [];
let dailyTotalAmount      = 0;
let currentReportPeriod   = 'today';
let lastReceiptData       = null;
let kasirSettingsCache    = {};
let currentMenuProductsPage   = 1;
let filteredMenuProducts      = [];
let currentKasirTransactionsPage = 1;
let transactions          = [];
let editingProductId      = null;

// Mapping category_id → slug (sesuai DB)
const categoryIdToSlug = { 1: 'kopi', 2: 'non-kopi', 3: 'makanan', 4: 'snack' };
const categorySlugToId = { 'kopi': 1, 'non-kopi': 2, 'makanan': 3, 'snack': 4 };
const categoryEmojiMap = { kopi: '☕', 'non-kopi': '🥤', makanan: '🍜', snack: '🍪' };

// ============================================================
// INIT
// ============================================================
document.addEventListener('DOMContentLoaded', () => {
    updateDate();
    setInterval(updateDate, 60000);
    loadCategories();
    loadProducts();
    loadPaymentMethods();
    renderTransactions();
    loadKasirReport();
    loadKasirSettings();
});

function updateDate() {
    const el = document.getElementById('currentDate');
    if (el) el.textContent = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
}

// ============================================================
// NAVIGATION
// ============================================================
function showPage(pageName) {
    document.querySelectorAll('.page-content').forEach(p => p.classList.add('hidden'));
    document.getElementById(`page-${pageName}`)?.classList.remove('hidden');

    document.querySelectorAll('.nav-link').forEach(l => {
        l.classList.remove('active');
        l.classList.add('text-stone-300');
    });
    event.target.closest('.nav-link')?.classList.add('active');
    event.target.closest('.nav-link')?.classList.remove('text-stone-300');

    const titles = { kasir: 'Kasir', menu: 'Manajemen Menu', riwayat: 'Riwayat Transaksi', laporan: 'Laporan', pengaturan: 'Pengaturan' };
    const titleEl = document.getElementById('pageTitle');
    if (titleEl) titleEl.textContent = titles[pageName] || 'Kasir';

    if (pageName === 'laporan')     loadKasirReport();
    if (pageName === 'pengaturan')  loadKasirSettings();
    if (pageName === 'riwayat')     renderTransactions();
}

// ============================================================
// HELPERS
// ============================================================
function formatRupiah(number) {
    return 'Rp ' + Number(number || 0).toLocaleString('id-ID');
}

function getTaxPercent() {
    return Number(kasirSettingsCache.tax_percent ?? 10);
}

function csrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

function renderPagination(containerId, pagination, onPageChange) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (!pagination || Number(pagination.total || 0) === 0) {
        container.classList.add('hidden');
        container.innerHTML = '';
        return;
    }

    const current = Number(pagination.current_page || 1);
    const last    = Number(pagination.last_page || 1);
    const start   = Math.max(1, current - 2);
    const end     = Math.min(last, current + 2);
    let buttons   = '';

    for (let page = start; page <= end; page++) {
        buttons += `<button type="button" onclick="${onPageChange}(${page})"
            class="w-10 h-10 rounded-xl text-sm font-semibold ${page === current ? 'bg-amber-700 text-white' : 'border border-stone-200 text-stone-700 hover:bg-stone-50'}">${page}</button>`;
    }

    container.classList.remove('hidden');
    container.innerHTML = `
        <p class="text-sm text-stone-500">Menampilkan ${pagination.from || 0}–${pagination.to || 0} dari ${pagination.total || 0} data</p>
        <div class="flex items-center gap-2">
            <button type="button" onclick="${onPageChange}(${current - 1})" ${current <= 1 ? 'disabled' : ''}
                class="px-4 py-2 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed">Sebelumnya</button>
            ${buttons}
            <button type="button" onclick="${onPageChange}(${current + 1})" ${current >= last ? 'disabled' : ''}
                class="px-4 py-2 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed">Berikutnya</button>
        </div>`;
}

// ============================================================
// CATEGORIES – load from API to populate product modal select
// ============================================================
async function loadCategories() {
    try {
        const res    = await fetch('/kasir-categories', { headers: { 'Accept': 'application/json' } });
        const result = await res.json();
        if (!res.ok || !result.status) return;

        const select = document.getElementById('productCategoryId');
        if (!select) return;

        select.innerHTML = result.data.map(c => `<option value="${c.id}">${c.name}</option>`).join('');

        // Update emoji map
        result.data.forEach(c => {
            const slug = normalizeCategory(c.name);
            categoryIdToSlug[c.id] = slug;
            categorySlugToId[slug] = c.id;
        });
    } catch (e) {
        console.error('loadCategories error:', e);
    }
}

// ============================================================
// PRODUCTS
// ============================================================
function normalizeCategory(categoryName) {
    const n = (categoryName || '').trim().toLowerCase();
    if (n.includes('non') && n.includes('kopi')) return 'non-kopi';
    if (n.includes('kopi'))   return 'kopi';
    if (n.includes('makan'))  return 'makanan';
    if (n.includes('snack') || n.includes('cemilan')) return 'snack';
    return n.replace(/\s+/g, '-');
}

function normalizeProduct(p) {
    const category = normalizeCategory(p.category_name);
    return {
        id:           Number(p.id),
        name:         p.name || 'Produk Tanpa Nama',
        category,
        categoryName: p.category_name || 'Tanpa Kategori',
        categoryId:   categorySlugToId[category] || 1,
        price:        Number(p.price || 0),
        stock:        Number(p.stock || 0),
        status:       Number(p.status) === 1 ? 'active' : 'inactive',
        emoji:        categoryEmojiMap[category] || '📦',
    };
}

async function loadProducts() {
    const grid      = document.getElementById('productGrid');
    const tableBody = document.getElementById('menuProductsTable');

    if (grid)      grid.innerHTML = '<div class="col-span-full text-center text-stone-500 py-12">Memuat produk...</div>';
    if (tableBody) tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-stone-500">Memuat produk...</td></tr>';

    try {
        const res    = await fetch('/kasir-products', { headers: { 'Accept': 'application/json' } });
        const result = await res.json();

        if (!res.ok || !result.status) throw new Error(result.message || 'Gagal mengambil data produk');

        products = result.data.map(normalizeProduct);
        renderProducts();
        renderMenuProducts();
    } catch (e) {
        console.error(e);
        if (grid)      grid.innerHTML      = '<div class="col-span-full text-center text-red-600 py-12">Gagal memuat produk.</div>';
        if (tableBody) tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-red-600">Gagal memuat produk.</td></tr>';
    }
}

// ---- Kasir grid ----
function filterCategory(category) {
    currentCategory = category;
    document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    renderProducts();
}

function renderProducts() {
    const grid   = document.getElementById('productGrid');
    const active = products.filter(p => p.status === 'active');
    const list   = currentCategory === 'all' ? active : active.filter(p => p.category === currentCategory);

    grid.innerHTML = list.map(p => `
        <div class="product-card bg-white rounded-xl p-4 transition-all duration-300 border ${p.stock > 0 ? 'cursor-pointer border-stone-200 hover:border-amber-700' : 'cursor-not-allowed border-red-100 opacity-60 bg-stone-50'}"
             ${p.stock > 0 ? `onclick="addToCart(${p.id})"` : ''}>
            <div class="flex items-start justify-between gap-2 mb-3">
                <div class="text-4xl">${p.emoji}</div>
                <span class="px-2 py-1 rounded-full text-xs font-semibold ${p.stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                    ${p.stock > 0 ? `Stok ${p.stock}` : 'Habis'}
                </span>
            </div>
            <h3 class="font-semibold text-stone-800 mb-1">${p.name}</h3>
            <p class="${p.stock > 0 ? 'text-amber-700' : 'text-stone-400'} font-bold">${formatRupiah(p.price)}</p>
        </div>`).join('');
}

// ---- Menu table ----
function renderMenuProducts() {
    filteredMenuProducts  = [...products];
    currentMenuProductsPage = 1;
    renderMenuProductsPage();

    document.getElementById('totalProducts').textContent    = products.length;
    document.getElementById('activeProducts').textContent   = products.filter(p => p.status === 'active').length;
    document.getElementById('inactiveProducts').textContent = products.filter(p => p.status === 'inactive').length;
}

function filterMenuProducts() {
    const search   = document.getElementById('menuSearch').value.toLowerCase();
    const category = document.getElementById('menuCategoryFilter').value;
    const status   = document.getElementById('menuStatusFilter').value;

    filteredMenuProducts = products.filter(p => {
        const matchSearch   = p.name.toLowerCase().includes(search) || p.categoryName.toLowerCase().includes(search);
        const matchCategory = !category || p.category === category;
        const matchStatus   = !status   || p.status === status;
        return matchSearch && matchCategory && matchStatus;
    });

    currentMenuProductsPage = 1;
    renderMenuProductsPage();
}

function paginateArray(items, page = 1, perPage = 10) {
    const total       = items.length;
    const lastPage    = Math.max(1, Math.ceil(total / perPage));
    const currentPage = Math.max(1, Math.min(page, lastPage));
    const from        = total ? (currentPage - 1) * perPage + 1 : null;
    const to          = total ? Math.min(currentPage * perPage, total) : null;
    return { data: items.slice((currentPage - 1) * perPage, currentPage * perPage), pagination: { current_page: currentPage, last_page: lastPage, per_page: perPage, total, from, to } };
}

function changeMenuProductsPage(page) { currentMenuProductsPage = page; renderMenuProductsPage(); }

function renderMenuProductsPage() {
    const tableBody = document.getElementById('menuProductsTable');
    const paged     = paginateArray(filteredMenuProducts, currentMenuProductsPage, 10);
    currentMenuProductsPage = paged.pagination.current_page;

    tableBody.innerHTML = paged.data.length
        ? paged.data.map(p => `
            <tr class="border-b border-stone-100 hover:bg-stone-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-xl">${p.emoji}</div>
                        <span class="font-medium">${p.name}</span>
                    </div>
                </td>
                <td class="px-6 py-4 capitalize">${p.categoryName}</td>
                <td class="px-6 py-4 font-semibold text-amber-700">${formatRupiah(p.price)}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 ${p.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} rounded-full text-sm font-medium">
                        ${p.status === 'active' ? 'Aktif' : 'Non-Aktif'}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <button onclick="editProduct(${p.id})" class="px-3 py-1 text-sm text-blue-600 hover:bg-blue-50 rounded-lg border border-blue-200">Edit</button>
                        <button onclick="toggleProductStatus(${p.id})" class="px-3 py-1 text-sm ${p.status === 'active' ? 'text-red-600 hover:bg-red-50 border-red-200' : 'text-green-600 hover:bg-green-50 border-green-200'} rounded-lg border">
                            ${p.status === 'active' ? 'Nonaktif' : 'Aktifkan'}
                        </button>
                        <button onclick="deleteProduct(${p.id})" class="px-3 py-1 text-sm text-red-600 hover:bg-red-50 rounded-lg border border-red-200">Hapus</button>
                    </div>
                </td>
            </tr>`).join('')
        : '<tr><td colspan="5" class="px-6 py-8 text-center text-stone-500">Belum ada produk.</td></tr>';

    renderPagination('menuProductsPagination', paged.pagination, 'changeMenuProductsPage');
}

// ---- Product Modal ----
function openProductModal(productId = null) {
    editingProductId = productId;
    document.getElementById('productModalTitle').textContent = productId ? 'Edit Produk' : 'Tambah Produk';

    if (productId) {
        const p = products.find(x => x.id === productId);
        if (!p) return;
        document.getElementById('productName').value       = p.name;
        document.getElementById('productCategoryId').value = p.categoryId || categorySlugToId[p.category] || 1;
        document.getElementById('productPrice').value      = p.price;
        document.getElementById('productStock').value      = p.stock;
        document.getElementById('productStatus').value     = p.status === 'active' ? '1' : '0';
    } else {
        document.getElementById('productName').value       = '';
        document.getElementById('productCategoryId').value = '1';
        document.getElementById('productPrice').value      = '';
        document.getElementById('productStock').value      = '0';
        document.getElementById('productStatus').value     = '1';
    }

    document.getElementById('productModal').classList.remove('hidden');
}

function closeProductModal() {
    document.getElementById('productModal').classList.add('hidden');
    editingProductId = null;
}

async function saveProduct() {
    const name       = document.getElementById('productName').value.trim();
    const categoryId = parseInt(document.getElementById('productCategoryId').value);
    const price      = parseFloat(document.getElementById('productPrice').value);
    const stock      = parseInt(document.getElementById('productStock').value) || 0;
    const status     = parseInt(document.getElementById('productStatus').value);

    if (!name || !price || isNaN(price)) {
        alert('Mohon lengkapi nama dan harga produk!');
        return;
    }

    const btn = document.getElementById('saveProductBtn');
    btn.disabled    = true;
    btn.textContent = 'Menyimpan...';

    const url  = editingProductId ? `/kasir-product/${editingProductId}` : '/kasir-product/add';
    const body = JSON.stringify({ name, category_id: categoryId, price, stock, status });

    try {
        const res    = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() }, body });
        const result = await res.json();

        if (!res.ok || !result.status) {
            const errMsg = result.errors ? Object.values(result.errors).flat()[0] : result.message;
            alert(errMsg || 'Gagal menyimpan produk');
            return;
        }

        closeProductModal();
        await loadProducts();
        alert(result.message);
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan server');
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Simpan';
    }
}

function editProduct(id) { openProductModal(id); }

async function toggleProductStatus(id) {
    try {
        const res    = await fetch(`/kasir-product/${id}/toggle`, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() } });
        const result = await res.json();
        if (!res.ok || !result.status) { alert(result.message || 'Gagal mengubah status'); return; }
        await loadProducts();
    } catch (e) { console.error(e); alert('Terjadi kesalahan server'); }
}

async function deleteProduct(id) {
    if (!confirm('Yakin ingin menghapus produk ini?')) return;
    try {
        const res    = await fetch(`/kasir-product/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() } });
        const result = await res.json();
        if (!res.ok || !result.status) { alert(result.message || 'Gagal menghapus produk'); return; }
        await loadProducts();
    } catch (e) { console.error(e); alert('Terjadi kesalahan server'); }
}

// ============================================================
// CART
// ============================================================
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product || product.stock <= 0) { alert('Stok produk ini habis.'); return; }

    const existing = cart.find(i => i.id === productId);
    if (existing) {
        if (existing.quantity >= product.stock) { alert(`Stok ${product.name} hanya tersisa ${product.stock}.`); return; }
        existing.quantity++;
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    renderCart();
}

function updateQuantity(productId, change) {
    const item    = cart.find(i => i.id === productId);
    const product = products.find(p => p.id === productId);
    if (!item) return;

    if (change > 0 && product && item.quantity >= product.stock) {
        alert(`Stok ${item.name} hanya tersisa ${product.stock}.`);
        return;
    }

    item.quantity += change;
    if (item.quantity <= 0) cart = cart.filter(i => i.id !== productId);
    renderCart();
}

function removeFromCart(productId) { cart = cart.filter(i => i.id !== productId); renderCart(); }

function clearCart() {
    if (!cart.length) return;
    if (confirm('Hapus semua item dari keranjang?')) { cart = []; renderCart(); }
}

function renderCart() {
    const cartItems  = document.getElementById('cartItems');
    const payButton  = document.getElementById('payButton');
    const printButton = document.getElementById('printButton');

    if (!cart.length) {
        cartItems.innerHTML = `<div class="text-center text-stone-400 py-12"><div class="text-5xl mb-4">🛒</div><p>Keranjang kosong</p><p class="text-sm">Pilih produk untuk ditambahkan</p></div>`;
        payButton.disabled   = true;
        printButton.disabled = !lastReceiptData;
    } else {
        cartItems.innerHTML = cart.map(item => `
            <div class="flex items-center gap-3 mb-3 p-3 bg-stone-50 rounded-xl">
                <div class="text-2xl">${item.emoji}</div>
                <div class="flex-1">
                    <h4 class="font-medium text-stone-800 text-sm">${item.name}</h4>
                    <p class="text-amber-700 text-sm font-semibold">${formatRupiah(item.price)}</p>
                    <p class="text-xs text-stone-500">Stok: ${item.stock}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateQuantity(${item.id}, -1)" class="w-8 h-8 bg-stone-200 hover:bg-stone-300 rounded-full font-bold">-</button>
                    <span class="w-8 text-center font-medium">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, 1)" ${item.quantity >= item.stock ? 'disabled' : ''} class="w-8 h-8 bg-stone-200 hover:bg-stone-300 disabled:bg-stone-100 disabled:text-stone-400 disabled:cursor-not-allowed rounded-full font-bold">+</button>
                </div>
            </div>`).join('');
        payButton.disabled   = false;
        printButton.disabled = !lastReceiptData;
    }

    updateTotals();
}

function updateTotals() {
    const subtotal   = cart.reduce((s, i) => s + i.price * i.quantity, 0);
    const taxPercent = getTaxPercent();
    const tax        = Math.round(subtotal * taxPercent / 100);
    const total      = subtotal + tax;
    const itemCount  = cart.reduce((s, i) => s + i.quantity, 0);

    document.getElementById('subtotal').textContent      = formatRupiah(subtotal);
    document.getElementById('taxLabel').textContent      = `Pajak (${taxPercent}%)`;
    document.getElementById('tax').textContent           = formatRupiah(tax);
    document.getElementById('total').textContent         = formatRupiah(total);
    document.getElementById('modalTotal').textContent    = formatRupiah(total);
    document.getElementById('modalSubtotal').textContent = formatRupiah(subtotal);
    document.getElementById('modalTaxLabel').textContent = `Pajak (${taxPercent}%)`;
    document.getElementById('modalTax').textContent      = formatRupiah(tax);
    document.getElementById('paymentItemCount').textContent = `${itemCount} item pesanan`;
    document.getElementById('modalOrderSummary').textContent = cart.length ? cart.map(i => `${i.quantity}x ${i.name}`).join(', ') : '-';
}

// ============================================================
// PAYMENT MODAL
// ============================================================
function normalizePayment(p) {
    const name       = p.name || 'Pembayaran';
    const normalized = name.toLowerCase();
    let code = normalized.replace(/\s+/g, '_');
    let emoji = '💳';
    if (normalized.includes('tunai') || normalized.includes('cash'))  { code = 'cash'; emoji = '💵'; }
    else if (normalized.includes('qris') || normalized.includes('qr')) { code = 'qris'; emoji = '📱'; }
    else if (normalized.includes('transfer'))                          { code = 'transfer'; emoji = '🏦'; }
    return { id: Number(p.id), name, code, emoji };
}

async function loadPaymentMethods() {
    try {
        const res    = await fetch('/kasir-payments', { headers: { 'Accept': 'application/json' } });
        const result = await res.json();
        if (!res.ok || !result.status) throw new Error(result.message);
        paymentMethods = result.data.map(normalizePayment);
        renderPaymentMethods();
        if (paymentMethods.length > 0) selectPaymentMethod(paymentMethods[0].id);
    } catch (e) {
        console.error(e);
        document.getElementById('paymentMethods').innerHTML = '<div class="col-span-full text-center text-red-600 py-4">Gagal memuat metode pembayaran.</div>';
    }
}

function renderPaymentMethods() {
    const el = document.getElementById('paymentMethods');
    el.innerHTML = paymentMethods.length
        ? paymentMethods.map(m => `
            <button onclick="selectPaymentMethod(${m.id})" data-method-id="${m.id}"
                class="payment-method p-4 border-2 border-stone-200 rounded-xl text-center hover:border-amber-700">
                <div class="text-2xl mb-1">${m.emoji}</div>
                <p class="text-sm font-medium">${m.name}</p>
            </button>`).join('')
        : '<div class="col-span-full text-center text-stone-500 py-4">Belum ada metode pembayaran.</div>';
}

function selectPaymentMethod(methodId) {
    selectedPaymentMethod = paymentMethods.find(m => m.id === Number(methodId));
    if (!selectedPaymentMethod) { paymentMethod = null; return; }

    paymentMethod = selectedPaymentMethod.code;

    document.querySelectorAll('.payment-method').forEach(b => {
        b.classList.remove('active', 'border-amber-700', 'bg-amber-50');
        b.classList.add('border-stone-200');
    });
    const btn = document.querySelector(`[data-method-id="${selectedPaymentMethod.id}"]`);
    if (btn) { btn.classList.add('active', 'border-amber-700', 'bg-amber-50'); btn.classList.remove('border-stone-200'); }

    const cashSection = document.getElementById('cashPayment');
    cashSection.style.display = paymentMethod === 'cash' ? 'block' : 'none';
    if (paymentMethod !== 'cash') {
        document.getElementById('cashReceived').value = '';
        document.getElementById('change').textContent = 'Rp 0';
    }
}

function openPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('cashReceived').value = '';
    document.getElementById('change').textContent  = 'Rp 0';
    if (!selectedPaymentMethod && paymentMethods.length > 0) selectPaymentMethod(paymentMethods[0].id);
}

function closePaymentModal() { document.getElementById('paymentModal').classList.add('hidden'); }

function calculateChange() {
    const cashReceived = parseInt(document.getElementById('cashReceived').value) || 0;
    const totalText    = document.getElementById('total').textContent.replace(/[^\d]/g, '');
    const total        = parseInt(totalText);
    const change       = cashReceived - total;
    const el           = document.getElementById('change');

    if (change >= 0) {
        el.textContent = formatRupiah(change);
        el.className   = 'text-2xl font-bold text-green-600';
    } else {
        el.textContent = 'Kurang ' + formatRupiah(Math.abs(change));
        el.className   = 'text-2xl font-bold text-red-600';
    }
}

async function processPayment() {
    const totalText = document.getElementById('total').textContent.replace(/[^\d]/g, '');
    const total     = parseInt(totalText);

    if (!selectedPaymentMethod) { alert('Pilih metode pembayaran terlebih dahulu!'); return; }

    if (paymentMethod === 'cash') {
        const cashReceived = parseInt(document.getElementById('cashReceived').value) || 0;
        if (cashReceived < total) { alert('Uang yang diterima kurang!'); return; }
    }

    const paymentAmount = paymentMethod === 'cash'
        ? parseInt(document.getElementById('cashReceived').value) || 0
        : total;

    const btn = document.getElementById('confirmPaymentBtn');
    btn.disabled    = true;
    btn.textContent = 'Memproses...';

    try {
        const res = await fetch('/kasir-checkout', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify({
                payment_method_id: selectedPaymentMethod.id,
                payment: paymentAmount,
                items: cart.map(i => ({ id: i.id, name: i.name, quantity: i.quantity, price: i.price }))
            })
        });

        const result = await res.json();

        if (!res.ok || !result.status) {
            const errMsg = result.errors ? Object.values(result.errors).flat()[0] : result.message;
            alert(errMsg || 'Gagal memproses pembayaran');
            loadProducts();
            return;
        }

        const transaction = result.data.transaction;
        lastReceiptData = {
            id:           transaction.code,
            date:         new Date().toLocaleString('id-ID'),
            items:        cart.map(i => ({ name: i.name, qty: i.quantity, price: i.price })),
            subtotal:     transaction.subtotal,
            tax_amount:   transaction.tax_amount,
            tax_percent:  transaction.tax_percent,
            total:        transaction.total,
            payment:      transaction.payment,
            change_amount: transaction.change_amount,
            method:       selectedPaymentMethod.name
        };

        dailyTotalAmount += Number(transaction.total);
        const dailyEl = document.getElementById('dailyTotal');
        if (dailyEl) dailyEl.textContent = formatRupiah(dailyTotalAmount);

        closePaymentModal();
        document.getElementById('successPaymentSummary').textContent = `${transaction.code} – ${selectedPaymentMethod.name} – ${formatRupiah(transaction.total)}`;
        document.getElementById('successModal').classList.remove('hidden');
        document.getElementById('printButton').disabled = false;
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan server');
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Konfirmasi Pembayaran';
    }
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
    cart = [];
    renderCart();
    loadProducts();
}

function printReceipt() {
    const draftSubtotal = cart.reduce((s, i) => s + i.price * i.quantity, 0);
    const receipt = lastReceiptData || {
        id:           'DRAFT',
        date:         new Date().toLocaleString('id-ID'),
        items:        cart.map(i => ({ name: i.name, qty: i.quantity, price: i.price })),
        subtotal:     draftSubtotal,
        tax_amount:   Math.round(draftSubtotal * getTaxPercent() / 100),
        tax_percent:  getTaxPercent(),
        total:        draftSubtotal + Math.round(draftSubtotal * getTaxPercent() / 100),
        payment:      null,
        change_amount: null,
        method:       selectedPaymentMethod ? selectedPaymentMethod.name : '-'
    };

    if (!receipt.items.length) return;

    const isCash      = (receipt.method || '').toLowerCase().includes('tunai') || (receipt.method || '').toLowerCase().includes('cash');
    const storeName   = kasirSettingsCache.store_name || kasirSettingsCache.business_name || 'Warkop POS';
    const storeAddr   = kasirSettingsCache.store_address || kasirSettingsCache.business_address || '';
    const footer      = kasirSettingsCache.receipt_footer || 'Terima Kasih!';

    let content = `====================================\n           ${storeName.toUpperCase()}\n====================================\n${storeAddr}\n${receipt.date}\nNo: ${receipt.id}\n------------------------------------\n\n`;
    receipt.items.forEach(i => { content += `${i.name}\n  ${i.qty} x ${formatRupiah(i.price)} = ${formatRupiah(i.price * i.qty)}\n`; });
    content += `\n------------------------------------\nSubtotal    : ${formatRupiah(receipt.subtotal)}\nPajak (${receipt.tax_percent}%) : ${formatRupiah(receipt.tax_amount)}\n------------------------------------\nTOTAL       : ${formatRupiah(receipt.total)}\nMetode      : ${receipt.method}\n`;
    if (isCash) content += `Dibayar     : ${formatRupiah(receipt.payment)}\nKembalian   : ${formatRupiah(receipt.change_amount)}\n`;
    content += `====================================\n     ${footer}\n====================================\n`;

    const w = window.open('', '_blank');
    w.document.write(`<pre style="font-family:monospace;font-size:12px;">${content}</pre>`);
    w.document.close();
    w.print();
}

// ============================================================
// TRANSACTIONS (Riwayat)
// ============================================================
function paymentMethodBadge(method) {
    const n = (method || '').toLowerCase();
    let cls = 'bg-purple-100 text-purple-700', icon = '💳';
    if (n.includes('tunai') || n.includes('cash'))  { cls = 'bg-green-100 text-green-700'; icon = '💵'; }
    else if (n.includes('qris') || n.includes('qr')) { cls = 'bg-blue-100 text-blue-700';  icon = '📱'; }
    else if (n.includes('transfer'))                 { cls = 'bg-purple-100 text-purple-700'; icon = '🏦'; }
    return `<span class="px-3 py-1 ${cls} rounded-full text-sm font-medium">${icon} ${method}</span>`;
}

function isCashMethod(method) {
    const n = (method || '').toLowerCase();
    return n.includes('tunai') || n.includes('cash');
}

function formatTransactionTime(value) {
    if (!value) return '-';
    return new Date(value).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function changeKasirTransactionsPage(page) {
    if (page < 1) return;
    currentKasirTransactionsPage = page;
    renderTransactions(page);
}

async function renderTransactions(page = currentKasirTransactionsPage) {
    const tableBody = document.getElementById('transactionsTable');
    if (!tableBody) return;

    tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-stone-500">Memuat transaksi...</td></tr>';
    currentKasirTransactionsPage = page;

    try {
        const res    = await fetch(`/kasir-transactions?page=${page}&per_page=10`, { headers: { 'Accept': 'application/json' } });
        const result = await res.json();

        if (!res.ok || !result.status) { tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-red-600">Gagal memuat transaksi.</td></tr>'; return; }

        transactions = result.data;
        renderPagination('kasirTransactionsPagination', result.pagination, 'changeKasirTransactionsPage');

        if (!transactions.length) {
            tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-stone-500">Belum ada transaksi.</td></tr>';
            updateTransactionSummary();
            return;
        }

        tableBody.innerHTML = transactions.map(trx => `
            <tr class="border-b border-stone-100 hover:bg-stone-50">
                <td class="px-6 py-4 font-medium">${trx.code}</td>
                <td class="px-6 py-4 text-sm">${formatTransactionTime(trx.paid_at || trx.created_at)}</td>
                <td class="px-6 py-4">${trx.item_count || 0} item</td>
                <td class="px-6 py-4 font-semibold text-amber-700">${formatRupiah(trx.total)}</td>
                <td class="px-6 py-4">${isCashMethod(trx.payment_method_name) ? formatRupiah(trx.payment || trx.total) : '-'}</td>
                <td class="px-6 py-4">${isCashMethod(trx.payment_method_name) ? formatRupiah(trx.change_amount || 0) : '-'}</td>
                <td class="px-6 py-4">${paymentMethodBadge(trx.payment_method_name)}</td>
                <td class="px-6 py-4">
                    <button onclick="viewTransactionDetail(${trx.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">👁️</button>
                </td>
            </tr>`).join('');

        updateTransactionSummary();
    } catch (e) {
        console.error(e);
        tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-red-600">Gagal memuat transaksi.</td></tr>';
    }
}

function updateTransactionSummary() {
    const totalRevenue = transactions.reduce((s, t) => s + Number(t.total || 0), 0);
    const totalItems   = transactions.reduce((s, t) => s + Number(t.item_count || 0), 0);
    const average      = transactions.length ? totalRevenue / transactions.length : 0;

    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    set('totalTransactions', transactions.length);
    set('totalRevenue', formatRupiah(totalRevenue));
    set('totalItemsSold', totalItems);
    set('averageTransaction', formatRupiah(average));
}

function viewTransactionDetail(trxId) {
    const trx     = transactions.find(t => Number(t.id) === Number(trxId));
    if (!trx) return;
    const content = document.getElementById('transactionDetailContent');

    content.innerHTML = `
        <div class="mb-4"><p class="text-stone-500 text-sm">ID Transaksi</p><p class="font-bold">${trx.code}</p></div>
        <div class="mb-4"><p class="text-stone-500 text-sm">Waktu</p><p>${formatTransactionTime(trx.paid_at || trx.created_at)}</p></div>
        <div class="mb-4"><p class="text-stone-500 text-sm">Metode Pembayaran</p><p>${trx.payment_method_name}</p></div>
        <div class="mb-4 grid grid-cols-2 gap-3">
            <div class="bg-stone-50 rounded-lg p-4">
                <p class="text-stone-500 text-sm">${isCashMethod(trx.payment_method_name) ? 'Uang Diterima' : 'Pembayaran'}</p>
                <p class="font-semibold">${isCashMethod(trx.payment_method_name) ? formatRupiah(trx.payment || trx.total) : trx.payment_method_name}</p>
            </div>
            <div class="bg-stone-50 rounded-lg p-4">
                <p class="text-stone-500 text-sm">Kembalian</p>
                <p class="font-semibold">${isCashMethod(trx.payment_method_name) ? formatRupiah(trx.change_amount || 0) : '-'}</p>
            </div>
        </div>
        <div class="flex justify-between font-bold text-xl pt-4 border-t border-stone-200">
            <span>Total</span><span class="text-amber-700">${formatRupiah(trx.total)}</span>
        </div>`;

    document.getElementById('transactionDetailModal').classList.remove('hidden');
}

function closeTransactionDetailModal() { document.getElementById('transactionDetailModal').classList.add('hidden'); }

function applyTransactionFilters() { renderTransactions(1); }
function exportTransactions() { alert('Fitur export data akan segera tersedia!'); }

// ============================================================
// LAPORAN
// ============================================================
async function loadKasirReport() {
    try {
        const res    = await fetch(`/kasir-reports-data?period=${currentReportPeriod}`, { headers: { 'Accept': 'application/json' } });
        const result = await res.json();
        if (!res.ok || !result.status) { console.error(result); return; }
        renderKasirReport(result.data);
    } catch (e) { console.error(e); }
}

function setReportPeriod(period, btn = null) {
    currentReportPeriod = period;
    document.querySelectorAll('.report-period-btn').forEach(b => {
        b.classList.remove('active', 'border-amber-700', 'bg-amber-700', 'text-white');
        b.classList.add('border-stone-200', 'text-stone-600');
    });
    const target = btn || event?.target;
    if (target) { target.classList.add('active', 'border-amber-700', 'bg-amber-700', 'text-white'); target.classList.remove('border-stone-200', 'text-stone-600'); }
    loadKasirReport();
}

function reportChangeText(value) { const n = Number(value || 0); return `${n >= 0 ? '+' : ''}${n}% dari periode lalu`; }
function reportChangeClass(value) { return Number(value || 0) >= 0 ? 'text-green-600 text-sm' : 'text-red-600 text-sm'; }

function renderKasirReport(data) {
    const s = data.summary;
    document.getElementById('reportRevenue').textContent      = formatRupiah(s.revenue || 0);
    document.getElementById('reportTransactions').textContent = Number(s.transactions || 0).toLocaleString('id-ID');
    document.getElementById('reportItems').textContent        = Number(s.items || 0).toLocaleString('id-ID');
    document.getElementById('reportAverage').textContent      = formatRupiah(s.average_order || 0);

    [['reportRevenueChange', s.revenue_change], ['reportTransactionsChange', s.transactions_change],
     ['reportItemsChange', s.items_change], ['reportAverageChange', s.average_order_change]].forEach(([id, val]) => {
        const el = document.getElementById(id);
        el.textContent = reportChangeText(val);
        el.className   = reportChangeClass(val);
    });

    renderReportSalesChart(data.sales_chart || []);
    renderReportTopProducts(data.top_products || []);
    renderReportCategories(data.categories || []);
}

function renderReportSalesChart(items) {
    const container = document.getElementById('reportSalesChart');
    const max       = Math.max(...items.map(i => Number(i.total || 0)), 1);
    container.innerHTML = items.length
        ? items.map(i => { const h = Number(i.total) === 0 ? 8 : Math.max((Number(i.total) / max) * 100, 12); return `<div class="flex-1 h-full flex flex-col items-center justify-end gap-2 min-w-0"><div class="w-full bg-amber-600 rounded-t-lg" title="${formatRupiah(i.total)}" style="height:${h}%;min-height:10px"></div><span class="text-xs text-stone-500 truncate">${i.label}</span></div>`; }).join('')
        : '<div class="w-full text-center text-stone-500">Belum ada data penjualan.</div>';
}

function renderReportTopProducts(items) {
    const c = document.getElementById('reportTopProducts');
    c.innerHTML = items.length
        ? items.map(i => `<div class="flex items-center gap-4"><div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-sm font-bold text-amber-800">${String(i.category_name || i.name || 'P').charAt(0).toUpperCase()}</div><div class="flex-1 min-w-0"><h4 class="font-medium text-stone-800 truncate">${i.name}</h4><p class="text-sm text-stone-500">${Number(i.quantity || 0).toLocaleString('id-ID')} terjual</p></div><div class="text-right"><p class="font-semibold text-amber-700">${formatRupiah(i.total || 0)}</p></div></div>`).join('')
        : '<p class="text-sm text-stone-500">Belum ada produk terjual pada periode ini.</p>';
}

function renderReportCategories(items) {
    const c = document.getElementById('reportCategories');
    c.innerHTML = items.length
        ? items.map(i => `<div class="text-center bg-stone-50 rounded-xl p-5"><div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3 text-lg font-bold text-amber-800">${String(i.name || 'K').charAt(0).toUpperCase()}</div><h4 class="font-semibold text-stone-800">${i.name}</h4><p class="text-2xl font-bold text-amber-700">${Number(i.percent || 0)}%</p><p class="text-stone-500 text-sm">${formatRupiah(i.total || 0)}</p></div>`).join('')
        : '<p class="text-sm text-stone-500">Belum ada penjualan kategori pada periode ini.</p>';
}

function printReport() { window.print(); }

// PENGATURAN
async function loadKasirSettings() {
    const form = document.getElementById('kasirSettingsForm');
    if (!form) return;

    try {
        const res    = await fetch('/kasir-settings-data', { headers: { 'Accept': 'application/json' } });
        const result = await res.json();
        if (!res.ok || !result.status) throw new Error(result.message || 'Gagal memuat pengaturan');

        const settings = result.data.settings || {};
        kasirSettingsCache = settings;
        if (typeof updateTotals === 'function') updateTotals();

        const user = result.data.user || {};
        const setVal = (id, val) => { const el = document.getElementById(id); if (el) el.value = val; };

        setVal('kasirProfileName',       `Nama: ${user.name || '-'}`);
        setVal('kasirProfileUsername',   `Username: ${user.username || '-'}`);
        setVal('kasirProfileStatus',     `Status: ${user.status || '-'}`);
        setVal('kasirProfileWorkHours',  `Jam kerja: ${Number(user.work_hours || 0).toLocaleString('id-ID')} jam`);
        setVal('kasirStoreName',         settings.store_name || settings.business_name || 'Warkop POS');
        setVal('kasirStoreAddress',      settings.store_address || settings.business_address || '');
        setVal('kasirStorePhone',        settings.store_phone || settings.business_phone || '');
        setVal('kasirStoreEmail',        settings.store_email || settings.business_email || '');
        setVal('kasirReceiptFooter',     settings.receipt_footer || 'Terima kasih sudah berkunjung!');
        setVal('kasirHeroLocation',      settings.hero_location || '');
        setVal('kasirHeroTitle',         settings.hero_title || '');
        setVal('kasirHeroHighlight',     settings.hero_highlight || '');
        setVal('kasirHeroDescription',   settings.hero_description || '');
        setVal('kasirMenuDescription',   settings.menu_description || '');
        setVal('kasirFacilitiesSubtitle',settings.facilities_subtitle || '');
        setVal('kasirFacilitiesTitle',   settings.facilities_title || '');
        setVal('kasirLocationSubtitle',  settings.location_subtitle || '');
        setVal('kasirLocationTitle',     settings.location_title || '');
        setVal('kasirLocationDescription',settings.location_description || '');
        setVal('kasirMapsUrl',           settings.maps_url || '');
        setVal('kasirMapsEmbedUrl',      settings.maps_embed_url || '');
        setVal('kasirInstagram',         settings.instagram || '');
        setVal('kasirFooterDescription', settings.footer_description || '');
        setVal('kasirFacility1Title',    settings.facility_1_title || '');
        setVal('kasirFacility1Description', settings.facility_1_description || '');
        setVal('kasirFacility2Title',    settings.facility_2_title || '');
        setVal('kasirFacility2Description', settings.facility_2_description || '');
        setVal('kasirFacility3Title',    settings.facility_3_title || '');
        setVal('kasirFacility3Description', settings.facility_3_description || '');
        setVal('kasirFacility4Title',    settings.facility_4_title || '');
        setVal('kasirFacility4Description', settings.facility_4_description || '');
        setVal('kasirTaxPercent',        settings.tax_percent || 10);
        setVal('kasirCurrency',          settings.currency || 'IDR');
        setVal('kasirWeekdayOpen',       settings.weekday_open || '08:00');
        setVal('kasirWeekdayClose',      settings.weekday_close || '23:00');
        setVal('kasirWeekendOpen',       settings.weekend_open || '09:00');
        setVal('kasirWeekendClose',      settings.weekend_close || '00:00');
        setVal('kasirPaperSize',         settings.paper_size || '58mm');
        setVal('kasirReceiptCopies',     settings.receipt_copies || 1);

        renderKasirPaymentPreview(result.data.payments || []);
    } catch (e) { console.error(e); alert(e.message); }
}

function renderKasirPaymentPreview(payments) {
    const c = document.getElementById('kasirPaymentMethodsPreview');
    if (!c) return;
    c.innerHTML = payments.length
        ? payments.map(p => `<div class="flex items-center justify-between p-4 border border-stone-200 rounded-xl"><span class="font-medium">${p.name}</span><span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Aktif</span></div>`).join('')
        : '<p class="text-sm text-stone-500">Belum ada metode pembayaran.</p>';
}

async function saveSettings(event = null) {
    if (event) event.preventDefault();

    const getVal = id => { const el = document.getElementById(id); return el ? el.value : ''; };

    const payload = {
        store_name: getVal('kasirStoreName'), store_address: getVal('kasirStoreAddress'),
        store_phone: getVal('kasirStorePhone'), store_email: getVal('kasirStoreEmail'),
        receipt_footer: getVal('kasirReceiptFooter'), hero_location: getVal('kasirHeroLocation'),
        hero_title: getVal('kasirHeroTitle'), hero_highlight: getVal('kasirHeroHighlight'),
        hero_description: getVal('kasirHeroDescription'), menu_description: getVal('kasirMenuDescription'),
        facilities_subtitle: getVal('kasirFacilitiesSubtitle'), facilities_title: getVal('kasirFacilitiesTitle'),
        location_subtitle: getVal('kasirLocationSubtitle'), location_title: getVal('kasirLocationTitle'),
        location_description: getVal('kasirLocationDescription'), maps_url: getVal('kasirMapsUrl'),
        maps_embed_url: getVal('kasirMapsEmbedUrl'), instagram: getVal('kasirInstagram'),
        footer_description: getVal('kasirFooterDescription'),
        facility_1_title: getVal('kasirFacility1Title'), facility_1_description: getVal('kasirFacility1Description'),
        facility_2_title: getVal('kasirFacility2Title'), facility_2_description: getVal('kasirFacility2Description'),
        facility_3_title: getVal('kasirFacility3Title'), facility_3_description: getVal('kasirFacility3Description'),
        facility_4_title: getVal('kasirFacility4Title'), facility_4_description: getVal('kasirFacility4Description'),
        tax_percent: getVal('kasirTaxPercent'), currency: getVal('kasirCurrency'),
        weekday_open: getVal('kasirWeekdayOpen'), weekday_close: getVal('kasirWeekdayClose'),
        weekend_open: getVal('kasirWeekendOpen'), weekend_close: getVal('kasirWeekendClose'),
        paper_size: getVal('kasirPaperSize'), receipt_copies: getVal('kasirReceiptCopies'),
        current_password: getVal('kasirCurrentPassword'),
        new_password: getVal('kasirNewPassword'),
        new_password_confirmation: getVal('kasirNewPasswordConfirmation'),
    };

    try {
        const res    = await fetch('/kasir-settings-data', { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf() }, body: JSON.stringify(payload) });
        const result = await res.json();
        if (!res.ok || !result.status) throw new Error(result.message || Object.values(result.errors || {}).flat()[0] || 'Gagal menyimpan pengaturan');

        ['kasirCurrentPassword', 'kasirNewPassword', 'kasirNewPasswordConfirmation'].forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
        alert(result.message || 'Pengaturan berhasil disimpan');
        loadKasirSettings();
    } catch (e) { console.error(e); alert(e.message); }
}

// LOGOUT
async function logoutAction() {
    try {
        const res    = await fetch('/logout', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() } });
        const result = await res.json();
        if (result.success) window.location.href = '/auth';
        else alert(result.message || 'Logout gagal');
    } catch (e) { console.error(e); alert('Terjadi kesalahan server'); }
}
</script>

<?= $this->endSection() ?>