<div id="page-payments" class="page-content hidden">
    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="font-semibold text-stone-800">Manajemen Payment</h3>
                <p class="text-sm text-stone-500 mt-1">Kelola metode pembayaran yang muncul di kasir</p>
            </div>
            <button type="button" onclick="openPaymentAdminModal()"
                    class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-2 rounded-xl font-medium transition-colors whitespace-nowrap">
                + Tambah Payment
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200">
                    <th class="pb-4 font-medium">ID</th>
                    <th class="pb-4 font-medium">Nama Payment</th>
                    <th class="pb-4 font-medium">Preview di Kasir</th>
                    <th class="pb-4 font-medium">Aksi</th>
                </tr>
                </thead>
                <tbody id="paymentsTable">
                <tr>
                    <td colspan="4" class="py-4 text-center text-stone-500">Memuat data...</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="paymentsPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>
</div>

<div id="paymentAdminModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-stone-800" id="paymentAdminModalTitle">Tambah Payment</h2>
                <button type="button" onclick="closePaymentAdminModal()" class="text-stone-400 hover:text-stone-600 text-2xl">
                    &times;
                </button>
            </div>
        </div>

        <div class="p-6">
            <label class="block text-stone-700 font-medium mb-2">Nama Payment</label>
            <input type="text" id="paymentAdminName" placeholder="Contoh: Tunai, QRIS, Transfer BCA"
                   class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
        </div>

        <div class="p-6 border-t border-stone-200 flex gap-3 justify-end">
            <button type="button" onclick="closePaymentAdminModal()"
                    class="px-6 py-3 border border-stone-200 rounded-xl font-medium hover:bg-stone-50 transition-colors">
                Batal
            </button>
            <button type="button" onclick="savePaymentAdmin(event)"
                    class="px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white rounded-xl font-medium transition-colors">
                Simpan
            </button>
        </div>
    </div>
</div>

<div id="deletePaymentAdminModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-stone-800">Hapus Payment</h2>
                <button type="button" onclick="closeDeletePaymentAdminModal()" class="text-stone-400 hover:text-stone-600 text-2xl">
                    &times;
                </button>
            </div>
        </div>

        <div class="p-6">
            <p class="text-stone-600">
                Apakah Anda yakin ingin menghapus metode pembayaran
                <span id="deletePaymentAdminName" class="font-semibold text-stone-800"></span>?
            </p>
            <p class="text-sm text-stone-500 mt-3">Payment yang sudah digunakan transaksi tidak bisa dihapus.</p>
        </div>

        <div class="p-6 border-t border-stone-200 flex gap-3 justify-end">
            <button type="button" onclick="closeDeletePaymentAdminModal()"
                    class="px-6 py-3 border border-stone-200 rounded-xl font-medium hover:bg-stone-50 transition-colors">
                Batal
            </button>
            <button type="button" onclick="confirmDeletePaymentAdmin()"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
    let payments = [];
    let editingPaymentId = null;
    let deletingPaymentId = null;
    let currentPaymentsPage = 1;

    function paymentAdminIcon(name) {
        const normalized = String(name || '').toLowerCase();

        if (normalized.includes('tunai') || normalized.includes('cash')) return '💵';
        if (normalized.includes('qris') || normalized.includes('qr')) return '📱';
        if (normalized.includes('transfer') || normalized.includes('bank')) return '🏦';

        return '💳';
    }

    function changePaymentsPage(page) {
        if (page < 1) return;
        currentPaymentsPage = page;
        renderPayments(page);
    }

    async function renderPayments(page = currentPaymentsPage) {
        const tableBody = document.getElementById('paymentsTable');

        if (!tableBody) {
            return;
        }

        try {
            currentPaymentsPage = page;
            const response = await fetch(`/admin-payments?page=${currentPaymentsPage}&per_page=10`);
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal memuat payment');
            }

            payments = result.data;
            renderPagination('paymentsPagination', result.pagination, 'changePaymentsPage');

            if (!payments.length) {
                tableBody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-stone-500">Belum ada payment.</td></tr>';
                return;
            }

            tableBody.innerHTML = payments.map(payment => `
                <tr class="table-row border-b border-stone-100">
                    <td class="py-4 font-medium">#${payment.id}</td>
                    <td class="py-4">${payment.name}</td>
                    <td class="py-4">
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-stone-100 text-stone-700 rounded-full text-sm font-medium">
                            <span>${paymentAdminIcon(payment.name)}</span>
                            <span>${payment.name}</span>
                        </span>
                    </td>
                    <td class="py-4">
                        <div class="flex gap-2">
                            <button type="button" onclick="editPaymentAdmin(${payment.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                Edit
                            </button>
                            <button type="button" onclick="deletePaymentAdmin(${payment.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            console.error(error);
            tableBody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-red-600">Gagal memuat payment.</td></tr>';
        }
    }

    function openPaymentAdminModal(paymentId = null) {
        editingPaymentId = paymentId;

        const title = document.getElementById('paymentAdminModalTitle');
        const nameInput = document.getElementById('paymentAdminName');

        if (paymentId) {
            const payment = payments.find(item => item.id === paymentId);
            title.textContent = 'Edit Payment';
            nameInput.value = payment ? payment.name : '';
        } else {
            title.textContent = 'Tambah Payment';
            nameInput.value = '';
        }

        document.getElementById('paymentAdminModal').classList.remove('hidden');
        nameInput.focus();
    }

    function closePaymentAdminModal() {
        document.getElementById('paymentAdminModal').classList.add('hidden');
        editingPaymentId = null;
    }

    function editPaymentAdmin(paymentId) {
        openPaymentAdminModal(paymentId);
    }

    async function savePaymentAdmin(event) {
        if (event) {
            event.preventDefault();
        }

        const name = document.getElementById('paymentAdminName').value.trim();

        if (!name) {
            alert('Nama payment wajib diisi!');
            return;
        }

        const url = editingPaymentId
            ? `/admin-payment/${editingPaymentId}`
            : '/admin-payments';
        const method = editingPaymentId ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({name})
        });

        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            alert(validationMessage(result, 'Gagal menyimpan payment'));
            return;
        }

        await renderPayments();
        closePaymentAdminModal();
    }

    function deletePaymentAdmin(paymentId) {
        const payment = payments.find(item => item.id === paymentId);

        deletingPaymentId = paymentId;
        document.getElementById('deletePaymentAdminName').textContent = payment ? payment.name : 'payment ini';
        document.getElementById('deletePaymentAdminModal').classList.remove('hidden');
    }

    function closeDeletePaymentAdminModal() {
        document.getElementById('deletePaymentAdminModal').classList.add('hidden');
        deletingPaymentId = null;
    }

    async function confirmDeletePaymentAdmin() {
        if (!deletingPaymentId) {
            return;
        }

        const response = await fetch(`/admin-payment/${deletingPaymentId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            alert(validationMessage(result, 'Gagal menghapus payment'));
            return;
        }

        await renderPayments();
        closeDeletePaymentAdminModal();
    }
</script>
