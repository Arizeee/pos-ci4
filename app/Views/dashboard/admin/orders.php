<div id="page-orders" class="page-content hidden">
    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="font-semibold text-stone-800">Daftar Pesanan</h3>
            <div class="flex gap-3">
                <select id="orderStatusFilter" onchange="changeOrdersPeriod()" class="px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <option value="all">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="process">Proses</option>
                    <option value="completed">Selesai</option>
                    <option value="canceled">Dibatalkan</option>
                </select>
                <select id="orderPeriodFilter" onchange="changeOrdersPeriod()" class="px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <option value="all">Semua Waktu</option>
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                </select>
            </div>
        </div>

        <div id="ordersList" class="space-y-4">
            <p class="text-stone-500 text-sm">Memuat data...</p>
        </div>

        <div id="ordersPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4">
            <p id="ordersPaginationInfo" class="text-sm text-stone-500">Menampilkan 0 pesanan</p>
            <div class="flex items-center gap-2">
                <button onclick="changeOrdersPage(currentOrdersPage - 1)" id="ordersPrevBtn"
                        class="px-4 py-2 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Sebelumnya
                </button>
                <div id="ordersPageButtons" class="flex items-center gap-2"></div>
                <button onclick="changeOrdersPage(currentOrdersPage + 1)" id="ordersNextBtn"
                        class="px-4 py-2 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 hover:bg-stone-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Berikutnya
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let orders = [];
    let currentOrdersPage = 1;
    let ordersPagination = null;

    function formatOrderTime(value) {
        return new Date(value).toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function changeOrdersPeriod() {
        currentOrdersPage = 1;
        renderOrders();
    }

    function changeOrdersPage(page) {
        if (!ordersPagination) {
            return;
        }

        if (page < 1 || page > ordersPagination.last_page || page === currentOrdersPage) {
            return;
        }

        currentOrdersPage = page;
        renderOrders();
    }

    async function renderOrders(page = currentOrdersPage) {
        const container = document.getElementById('ordersList');

        if (!container) {
            return;
        }

        currentOrdersPage = page;
        container.innerHTML = '<p class="text-stone-500 text-sm">Memuat data...</p>';

        const period = document.getElementById('orderPeriodFilter').value;
        const status = document.getElementById('orderStatusFilter').value;
        const response = await fetch(`/admin-orders?period=${period}&status=${status}&page=${currentOrdersPage}&per_page=10`);
        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            container.innerHTML = '<p class="text-red-600 text-sm">Gagal memuat pesanan.</p>';
            document.getElementById('ordersPagination').classList.add('hidden');
            return;
        }

        orders = result.data;
        ordersPagination = result.pagination;

        if (!orders.length) {
            container.innerHTML = '<p class="text-stone-500 text-sm">Belum ada pesanan.</p>';
            document.getElementById('ordersPagination').classList.add('hidden');
            return;
        }

        container.innerHTML = orders.map(order => `
            <div class="border border-stone-200 rounded-xl p-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                    <div>
                        <h4 class="font-semibold text-stone-800">#${order.code}</h4>
                        <p class="text-sm text-stone-500">${formatOrderTime(order.created_at)} WIB - ${order.customer_name}</p>
                    </div>
                </div>
                <div class="border-t border-stone-100 pt-4">
                    <div class="space-y-2 mb-4">
                        ${order.items.map(item => `
                            <div class="flex justify-between text-sm">
                                <span>${item.quantity}x ${item.product_name}</span>
                                <span>${formatRupiah(Number(item.subtotal))}</span>
                            </div>
                        `).join('')}
                    </div>
                    <div class="flex justify-between font-semibold">
                        <span>Total</span>
                        <span class="text-amber-700">${formatRupiah(Number(order.total))}</span>
                    </div>
                </div>
            </div>
        `).join('');

        renderOrdersPagination();
    }

    function renderOrdersPagination() {
        const wrapper = document.getElementById('ordersPagination');
        const info = document.getElementById('ordersPaginationInfo');
        const prevBtn = document.getElementById('ordersPrevBtn');
        const nextBtn = document.getElementById('ordersNextBtn');
        const pageButtons = document.getElementById('ordersPageButtons');

        if (!ordersPagination || ordersPagination.last_page <= 1) {
            wrapper.classList.toggle('hidden', !ordersPagination || ordersPagination.total === 0);
        } else {
            wrapper.classList.remove('hidden');
        }

        info.textContent = `Menampilkan ${ordersPagination.from || 0}-${ordersPagination.to || 0} dari ${ordersPagination.total || 0} pesanan`;
        prevBtn.disabled = currentOrdersPage <= 1;
        nextBtn.disabled = currentOrdersPage >= ordersPagination.last_page;

        const start = Math.max(1, currentOrdersPage - 2);
        const end = Math.min(ordersPagination.last_page, currentOrdersPage + 2);
        const buttons = [];

        for (let page = start; page <= end; page++) {
            buttons.push(`
                <button onclick="changeOrdersPage(${page})"
                        class="w-10 h-10 rounded-xl text-sm font-semibold ${page === currentOrdersPage ? 'bg-amber-700 text-white' : 'border border-stone-200 text-stone-700 hover:bg-stone-50'}">
                    ${page}
                </button>
            `);
        }

        pageButtons.innerHTML = buttons.join('');
    }

</script>
