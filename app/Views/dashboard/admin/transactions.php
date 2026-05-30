<div id="page-transactions" class="page-content hidden">
    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="font-semibold text-stone-800">Riwayat Transaksi</h3>
            <div class="flex gap-3">
                <input type="date" id="transactionDateFilter" onchange="changeTransactionFilter()"
                       class="px-4 py-2 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                <button type="button" onclick="clearTransactionFilter()"
                        class="bg-amber-700 hover:bg-amber-800 text-white px-4 py-2 rounded-xl font-medium transition-colors">
                    Semua
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200">
                    <th class="pb-4 font-medium">ID Transaksi</th>
                    <th class="pb-4 font-medium">Tanggal</th>
                    <th class="pb-4 font-medium">Pesanan</th>
                    <th class="pb-4 font-medium">Metode</th>
                    <th class="pb-4 font-medium">Item</th>
                    <th class="pb-4 font-medium">Total</th>
                    <th class="pb-4 font-medium">Bayar</th>
                    <th class="pb-4 font-medium">Kembalian</th>
                    <th class="pb-4 font-medium">Status</th>
                </tr>
                </thead>
                <tbody id="transactionsTable">
                <tr>
                    <td colspan="9" class="py-4 text-center text-stone-500">Memuat data...</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="transactionsPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>
</div>

<script>
    let currentTransactionsPage = 1;

    function paymentMethodLabel(method) {
        const labels = {
            cash: 'Tunai',
            qris: 'QRIS',
            transfer: 'Transfer'
        };

        return labels[method] || method;
    }

    function transactionStatusBadge(status) {
        const labels = {
            success: 'Berhasil',
            pending: 'Pending',
            failed: 'Gagal'
        };
        const classes = {
            success: 'bg-green-100 text-green-700',
            pending: 'bg-yellow-100 text-yellow-700',
            failed: 'bg-red-100 text-red-700'
        };

        return `<span class="px-3 py-1 ${classes[status] || 'bg-stone-100 text-stone-700'} rounded-full text-sm font-medium">${labels[status] || status}</span>`;
    }

    function formatTransactionTime(value) {
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

    function changeTransactionsPage(page) {
        if (page < 1) return;
        currentTransactionsPage = page;
        renderTransactions(page);
    }

    function changeTransactionFilter() {
        currentTransactionsPage = 1;
        renderTransactions();
    }

    async function renderTransactions(page = currentTransactionsPage) {
        const tableBody = document.getElementById('transactionsTable');

        if (!tableBody) {
            return;
        }

        currentTransactionsPage = page;
        const date = document.getElementById('transactionDateFilter').value;
        const query = new URLSearchParams({page: currentTransactionsPage, per_page: 10});
        if (date) query.set('date', date);
        const response = await fetch(`/admin-transactions?${query.toString()}`);
        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            tableBody.innerHTML = '<tr><td colspan="9" class="py-4 text-center text-red-600">Gagal memuat transaksi.</td></tr>';
            return;
        }

        const transactions = result.data;
        renderPagination('transactionsPagination', result.pagination, 'changeTransactionsPage');

        if (!transactions.length) {
            tableBody.innerHTML = '<tr><td colspan="9" class="py-4 text-center text-stone-500">Belum ada transaksi.</td></tr>';
            return;
        }

        tableBody.innerHTML = transactions.map(transaction => `
            <tr class="table-row border-b border-stone-100">
                <td class="py-4 font-medium">#${transaction.code}</td>
                <td class="py-4">${formatTransactionTime(transaction.paid_at || transaction.created_at)}</td>
                <td class="py-4">${transaction.order_code ? '#' + transaction.order_code : '-'}</td>
                <td class="py-4">${transaction.payment_method_name || paymentMethodLabel(transaction.payment_method)}</td>
                <td class="py-4">${transaction.item_count || 0} item</td>
                <td class="py-4 font-semibold text-amber-700">${formatRupiah(Number(transaction.total))}</td>
                <td class="py-4">${formatRupiah(Number(transaction.payment || transaction.total))}</td>
                <td class="py-4">${formatRupiah(Number(transaction.change_amount || 0))}</td>
                <td class="py-4">${transactionStatusBadge(transaction.status)}</td>
            </tr>
        `).join('');
    }

    function clearTransactionFilter() {
        document.getElementById('transactionDateFilter').value = '';
        currentTransactionsPage = 1;
        renderTransactions();
    }
</script>
