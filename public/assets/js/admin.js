let products = [];
let editingProductId = null;
let deletingProductId = null;

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    updateDate();
    setInterval(updateDate, 60000);
    if (typeof renderCategories === 'function') {
        renderCategories();
    }
    if (typeof renderDashboard === 'function') {
        renderDashboard();
    }
    if (typeof renderAccountRoles === 'function') {
        renderAccountRoles();
    }
    if (typeof renderAccounts === 'function') {
        renderAccounts();
    }
    if (typeof renderStockLogs === 'function') {
        renderStockLogs();
    }
    if (typeof renderOrders === 'function') {
        renderOrders();
    }
    if (typeof renderTransactions === 'function') {
        renderTransactions();
    }
    if (typeof renderPayments === 'function') {
        renderPayments();
    }
    if (typeof renderReports === 'function') {
        renderReports();
    }
    renderProducts();
});

// Update Date
function updateDate() {
    const now = new Date();
    const options = {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
}

// Format Currency
function formatRupiah(number) {
    return 'Rp ' + number.toLocaleString('id-ID');
}

function productCategoryIcon(categoryName) {
    const name = String(categoryName || '').toLowerCase();

    if (name === 'kopi') {
        return 'K';
    }

    if (name === 'non-kopi') {
        return 'N';
    }

    if (name === 'makanan') {
        return 'M';
    }

    return 'S';
}

function statusBadge(status) {
    const isActive = Number(status) === 1;

    return `
        <span class="px-3 py-1 ${isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} rounded-full text-sm font-medium">
            ${isActive ? 'Aktif' : 'Non-Aktif'}
        </span>
    `;
}

async function renderDashboard() {
    try {
        const response = await fetch('/admin-dashboard-data');
        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            showToast(result.message || 'Gagal memuat dashboard', 'error');
            return;
        }

        const data = result.data;
        const summary = data.summary;

        document.getElementById('dashboardTotalProducts').textContent = summary.total_products;
        document.getElementById('dashboardActiveProducts').textContent = summary.active_products;
        document.getElementById('dashboardInactiveProducts').textContent = summary.inactive_products;
        document.getElementById('dashboardTotalStock').textContent = summary.total_stock;
        document.getElementById('dashboardLowStockProducts').textContent = `${summary.low_stock_products} stok rendah`;
        document.getElementById('dashboardStockInToday').textContent = summary.stock_in_today;
        document.getElementById('dashboardStockOutToday').textContent = summary.stock_out_today;
        document.getElementById('dashboardStockActivitiesToday').textContent = summary.stock_activities_today;

        renderDashboardCategoryChart(data.category_stats);
        renderDashboardTopProducts(data.top_stock_products);
        renderDashboardLatestProducts(data.latest_products);
        renderDashboardLowStockItems(data.low_stock_items);
        renderDashboardRecentStockLogs(data.recent_stock_logs);
    } catch (error) {
        console.error(error);
        showToast('Dashboard gagal dimuat, periksa koneksi', 'error');
    }
}

function renderDashboardCategoryChart(categoryStats) {
    const chart = document.getElementById('dashboardCategoryChart');

    if (!categoryStats.length) {
        chart.innerHTML = '<div class="w-full text-center text-stone-500">Belum ada kategori.</div>';
        return;
    }

    const maxTotal = Math.max(...categoryStats.map(category => Number(category.total_products)), 1);

    chart.innerHTML = categoryStats.map(category => {
        const total = Number(category.total_products);
        const height = total === 0 ? 8 : Math.max((total / maxTotal) * 100, 12);

        return `
            <div class="flex flex-col items-center gap-2 flex-1 min-w-0">
                <div class="w-full bg-amber-600 rounded-t-lg" style="height: ${height}%"></div>
                <span class="text-xs text-stone-500 text-center truncate w-full">${category.name}</span>
                <span class="text-xs font-semibold text-stone-700">${total}</span>
            </div>
        `;
    }).join('');
}

function renderDashboardTopProducts(topProducts) {
    const container = document.getElementById('dashboardTopProducts');

    if (!topProducts.length) {
        container.innerHTML = '<p class="text-stone-500 text-sm">Belum ada produk.</p>';
        return;
    }

    container.innerHTML = topProducts.map(product => `
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-sm font-semibold text-amber-800">
                ${productCategoryIcon(product.category_name)}
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-medium text-stone-800 truncate">${product.name}</h4>
                <p class="text-sm text-stone-500">${product.stock} stok</p>
            </div>
            <div class="text-right">
                <p class="font-semibold text-amber-700">${formatRupiah(Number(product.price))}</p>
            </div>
        </div>
    `).join('');
}

function renderDashboardLatestProducts(latestProducts) {
    const tableBody = document.getElementById('dashboardLatestProducts');

    if (!latestProducts.length) {
        tableBody.innerHTML = '<tr><td colspan="5" class="py-4 text-center text-stone-500">Belum ada produk.</td></tr>';
        return;
    }

    tableBody.innerHTML = latestProducts.map(product => `
        <tr class="table-row border-b border-stone-100">
            <td class="py-4 font-medium">${product.name}</td>
            <td class="py-4 capitalize">${product.category_name}</td>
            <td class="py-4 font-semibold text-amber-700">${formatRupiah(Number(product.price))}</td>
            <td class="py-4">${product.stock}</td>
            <td class="py-4">${statusBadge(product.status)}</td>
        </tr>
    `).join('');
}

function dashboardStockLogTypeBadge(type) {
    const labels = {
        in: 'Masuk',
        out: 'Keluar',
        adjustment: 'Penyesuaian'
    };
    const classes = {
        in: 'bg-green-100 text-green-700',
        out: 'bg-red-100 text-red-700',
        adjustment: 'bg-blue-100 text-blue-700'
    };

    return `
        <span class="px-3 py-1 ${classes[type] || 'bg-stone-100 text-stone-700'} rounded-full text-sm font-medium">
            ${labels[type] || type}
        </span>
    `;
}

function formatDashboardTime(value) {
    if (!value) {
        return '-';
    }

    return new Date(value).toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function renderDashboardLowStockItems(items) {
    const container = document.getElementById('dashboardLowStockItems');

    if (!items.length) {
        container.innerHTML = '<p class="text-stone-500 text-sm">Tidak ada produk stok rendah.</p>';
        return;
    }

    container.innerHTML = items.map(product => `
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-sm font-semibold text-red-700">
                ${product.stock}
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-medium text-stone-800 truncate">${product.name}</h4>
                <p class="text-sm text-stone-500 capitalize">${product.category_name}</p>
            </div>
            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-medium">Rendah</span>
        </div>
    `).join('');
}

function renderDashboardRecentStockLogs(logs) {
    const container = document.getElementById('dashboardRecentStockLogs');

    if (!logs.length) {
        container.innerHTML = '<p class="text-stone-500 text-sm">Belum ada aktivitas stok.</p>';
        return;
    }

    container.innerHTML = logs.map(log => `
        <div class="flex items-start gap-4">
            <div class="mt-1">${dashboardStockLogTypeBadge(log.type)}</div>
            <div class="flex-1 min-w-0">
                <h4 class="font-medium text-stone-800 truncate">${log.product_name || 'Produk terhapus'}</h4>
                <p class="text-sm text-stone-500">
                    ${log.quantity} stok, ${log.before_stock} -> ${log.after_stock}
                </p>
                <p class="text-xs text-stone-400">${formatDashboardTime(log.created_at)} oleh ${log.user_name || '-'}</p>
            </div>
        </div>
    `).join('');
}

// Show Page
function showPage(pageName) {
    // Hide all pages
    document.querySelectorAll('.page-content').forEach(page => {
        page.classList.add('hidden');
    });

    // Show selected page
    document.getElementById(`page-${pageName}`).classList.remove('hidden');

    // Update sidebar active state
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.classList.remove('active');
    });
    event.target.closest('.sidebar-link').classList.add('active');

    // Update page title
    const titles = {
        'dashboard': 'Dashboard',
        'products': 'Manajemen Produk',
        'accounts': 'Manajemen Akun',
        'orders': 'Daftar Pesanan',
        'transactions': 'Riwayat Transaksi',
        'payments': 'Manajemen Payment',
        'reports': 'Laporan',
        'settings': 'Pengaturan'
    };
    document.getElementById('pageTitle').textContent = titles[pageName] || 'Dashboard';
}

function validationMessage(result, fallback) {
    if (result.errors) {
        return Object.values(result.errors).flat()[0] || fallback;
    }

    return result.message || fallback;
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
    const last = Number(pagination.last_page || 1);
    const start = Math.max(1, current - 2);
    const end = Math.min(last, current + 2);
    let buttons = '';

    for (let page = start; page <= end; page++) {
        buttons += `
            <button type="button" onclick="${onPageChange}(${page})"
                    class="w-10 h-10 rounded-xl text-sm font-semibold ${page === current ? 'bg-amber-700 text-white' : 'border border-stone-200 text-stone-700 hover:bg-stone-50'}">
                ${page}
            </button>
        `;
    }

    container.classList.remove('hidden');
    container.innerHTML = `
        <p class="text-sm text-stone-500">Menampilkan ${pagination.from || 0}-${pagination.to || 0} dari ${pagination.total || 0} data</p>
        <div class="flex items-center gap-2">
            <button type="button" onclick="${onPageChange}(${current - 1})" ${current <= 1 ? 'disabled' : ''}
                    class="px-4 py-2 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Sebelumnya
            </button>
            ${buttons}
            <button type="button" onclick="${onPageChange}(${current + 1})" ${current >= last ? 'disabled' : ''}
                    class="px-4 py-2 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed">
                Berikutnya
            </button>
        </div>
    `;
}

// Open Product Modal

// Close Product Modal
function closeProductModal() {
    document.getElementById('productModal').classList.add('hidden');
    editingProductId = null;
}

function editProduct(productId) {
    openProductModal(productId);
}

// Delete Product
function deleteProduct(productId) {
    const product = products.find(p => Number(p.id) === Number(productId));

    deletingProductId = productId;
    document.getElementById('deleteProductName').textContent = product ? product.name : 'produk ini';
    document.getElementById('deleteProductModal').classList.remove('hidden');
}

function closeDeleteProductModal() {
    document.getElementById('deleteProductModal').classList.add('hidden');
    deletingProductId = null;
}

async function confirmDeleteProduct() {
    if (!deletingProductId) {
        return;
    }

    try {
        const response = await fetch(`/admin-product/${deletingProductId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            showToast(result.message || 'Gagal menghapus produk', 'error');
            return;
        }

        await renderProducts();
        if (typeof renderStockLogs === 'function') {
            await renderStockLogs();
        }
        if (typeof renderDashboard === 'function') {
            await renderDashboard();
        }
        closeDeleteProductModal();
        showToast(result.message || 'Produk berhasil dihapus', 'success');
    } catch (error) {
        console.error(error);
        showToast('Gagal menghapus produk, periksa koneksi', 'error');
    }
}

async function handleLogout(event) {
    event.preventDefault();

    try {
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
        });

        const result = await response.json();

        if (result.success) {
            window.location.href = '/auth';
        }
    } catch (error) {
        console.error(error);
        window.location.href = '/auth';
    }
}