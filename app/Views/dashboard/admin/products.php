<div id="page-products" class="page-content hidden">
    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="font-semibold text-stone-800">Manajemen Produk</h3>
            <div class="flex gap-3 w-full sm:w-auto">
                <input type="text" placeholder="Cari produk..."
                       class="flex-1 sm:w-64 px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                <button onclick="openProductModal()"
                        class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-2 rounded-xl font-medium transition-colors whitespace-nowrap">
                    + Tambah Produk
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200">
                    <th class="pb-4 font-medium">Produk</th>
                    <th class="pb-4 font-medium">Kategori</th>
                    <th class="pb-4 font-medium">Harga</th>
                    <th class="pb-4 font-medium">Stok</th>
                    <th class="pb-4 font-medium">Status</th>
                    <th class="pb-4 font-medium">Aksi</th>
                </tr>
                </thead>
                <tbody id="productsTable">
                <!-- Products will be rendered here -->
                <script>
                    let currentProductsPage = 1;

                    function changeProductsPage(page) {
                        if (page < 1) return;
                        currentProductsPage = page;
                        renderProducts(page);
                    }

                    async function renderProducts(page = currentProductsPage) {
                        try {
                            currentProductsPage = page;
                            const response = await fetch(`/admin-products?page=${currentProductsPage}&per_page=10`);
                            const result = await response.json();

                            products = result.data;
                            renderPagination('productsPagination', result.pagination, 'changeProductsPage');

                            const tableBody = document.getElementById('productsTable');

                            tableBody.innerHTML = products.map(product => `
            <tr class="table-row border-b border-stone-100">
                <td class="py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-xl">
                            ${
                                product.category_name === 'kopi'
                                    ? '☕'
                                    : product.category_name === 'non-kopi'
                                        ? '🥤'
                                        : product.category_name === 'makanan'
                                            ? '🍜'
                                            : '🍪'
                            }
                        </div>

                        <span class="font-medium">
                            ${product.name}
                        </span>
                    </div>
                </td>

                <td class="py-4 capitalize">
                    ${product.category_name}
                </td>

                <td class="py-4 font-semibold text-amber-700">
                    ${formatRupiah(product.price)}
                </td>

                <td class="py-4">
                    ${product.stock}
                </td>

                <td class="py-4">
                    <span class="px-3 py-1 ${
                                Number(product.status) === 1
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700'
                            } rounded-full text-sm font-medium">
                        ${
                                Number(product.status) === 1
                                    ? 'Aktif'
                                    : 'Non-Aktif'
                            }
                    </span>
                </td>

                <td class="py-4">
                    <div class="flex gap-2">
                        <button
                            onclick="editProduct(${product.id})"
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"
                        >
                            ✏️
                        </button>

                        <button
                            onclick="deleteProduct(${product.id})"
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
                        >
                            🗑️
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

                        } catch (error) {
                            console.error(error);
                        }
                    }

                </script>
                </tbody>
            </table>
        </div>
        <div id="productsPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200 mt-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-stone-800">Riwayat Stok</h3>
            <span class="text-sm text-stone-500">10 log per halaman</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200">
                    <th class="pb-4 font-medium">Waktu</th>
                    <th class="pb-4 font-medium">Produk</th>
                    <th class="pb-4 font-medium">Tipe</th>
                    <th class="pb-4 font-medium">Jumlah</th>
                    <th class="pb-4 font-medium">Sebelum</th>
                    <th class="pb-4 font-medium">Sesudah</th>
                    <th class="pb-4 font-medium">Catatan</th>
                    <th class="pb-4 font-medium">User</th>
                </tr>
                </thead>
                <tbody id="stockLogsTable">
                <tr>
                    <td colspan="8" class="py-4 text-center text-stone-500">Memuat data...</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="stockLogsPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>
</div>
<script>
    let currentStockLogsPage = 1;

    async function renderCategories() {
        const select = document.getElementById('productCategory');

        try {
            const response = await fetch('/admin-categories');
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal memuat kategori');
            }

            select.innerHTML = result.data.map(category => `
                <option value="${category.id}">${category.name}</option>
            `).join('');
        } catch (error) {
            console.error(error);
            select.innerHTML = '<option value="">Kategori gagal dimuat</option>';
        }
    }

    function openProductModal(productId = null) {
        editingProductId = productId;
        const modalTitle = document.getElementById('productModalTitle');

        if (productId) {
            const product = products.find(p => p.id === productId);
            modalTitle.textContent = 'Edit Produk';
            document.getElementById('productName').value = product.name;
            document.getElementById('productCategory').value = product.category_id;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productStock').value = product.stock;
            document.getElementById('productStatus').value = String(product.status);
        } else {
            modalTitle.textContent = 'Tambah Produk';
            document.getElementById('productName').value = '';
            document.getElementById('productCategory').selectedIndex = 0;
            document.getElementById('productPrice').value = '';
            document.getElementById('productStock').value = '';
            document.getElementById('productStatus').value = '1';
        }

        document.getElementById('productModal').classList.remove('hidden');
    }

    async function saveProduct() {
        const name = document.getElementById('productName').value;
        const category_id = document.getElementById('productCategory').value;
        const price = parseInt(document.getElementById('productPrice').value);
        const stock = parseInt(document.getElementById('productStock').value);
        const status = document.getElementById('productStatus').value;

        if (!name || !category_id || Number.isNaN(price) || Number.isNaN(stock)) {
            alert('Mohon lengkapi semua field!');
            return;
        }

        const url = editingProductId
            ? `/admin-product/${editingProductId}`
            : '/admin-product/add';
        const method = editingProductId ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name,
                category_id,
                price,
                stock,
                status
            })
        });

        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            alert(result.message || 'Gagal menyimpan produk');
            return;
        }

        await renderProducts();
        if (typeof renderStockLogs === 'function') {
            await renderStockLogs();
        }
        if (typeof renderDashboard === 'function') {
            await renderDashboard();
        }
        closeProductModal();
    }

    function stockLogTypeBadge(type) {
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

    function formatStockLogTime(value) {
        if (!value) {
            return '-';
        }

        return new Date(value).toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function changeStockLogsPage(page) {
        if (page < 1) return;
        currentStockLogsPage = page;
        renderStockLogs(page);
    }

    async function renderStockLogs(page = currentStockLogsPage) {
        const tableBody = document.getElementById('stockLogsTable');

        if (!tableBody) {
            return;
        }

        try {
            currentStockLogsPage = page;
            const response = await fetch(`/admin-stock-logs?page=${currentStockLogsPage}&per_page=10`);
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal memuat riwayat stok');
            }

            const logs = result.data;
            renderPagination('stockLogsPagination', result.pagination, 'changeStockLogsPage');

            if (!logs.length) {
                tableBody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-stone-500">Belum ada riwayat stok.</td></tr>';
                return;
            }

            tableBody.innerHTML = logs.map(log => `
                <tr class="table-row border-b border-stone-100">
                    <td class="py-4 text-stone-500 whitespace-nowrap">${formatStockLogTime(log.created_at)}</td>
                    <td class="py-4 font-medium">${log.product_name || 'Produk terhapus'}</td>
                    <td class="py-4">${stockLogTypeBadge(log.type)}</td>
                    <td class="py-4">${log.quantity}</td>
                    <td class="py-4">${log.before_stock}</td>
                    <td class="py-4">${log.after_stock}</td>
                    <td class="py-4">${log.note || '-'}</td>
                    <td class="py-4">${log.user_name || '-'}</td>
                </tr>
            `).join('');
        } catch (error) {
            console.error(error);
            tableBody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-red-600">Gagal memuat riwayat stok.</td></tr>';
        }
    }


</script>


