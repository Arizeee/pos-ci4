<div id="page-accounts" class="page-content hidden">
    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h3 class="font-semibold text-stone-800">Manajemen Akun</h3>
            <button type="button" onclick="openAccountModal()"
                    class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-2 rounded-xl font-medium transition-colors whitespace-nowrap">
                + Tambah Akun
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="text-left text-stone-500 text-sm border-b border-stone-200">
                    <th class="pb-4 font-medium">Nama</th>
                    <th class="pb-4 font-medium">Username</th>
                    <th class="pb-4 font-medium">Email</th>
                    <th class="pb-4 font-medium">Role</th>
                    <th class="pb-4 font-medium">Jam Kerja</th>
                    <th class="pb-4 font-medium">Status</th>
                    <th class="pb-4 font-medium">Dibuat</th>
                    <th class="pb-4 font-medium">Aksi</th>
                </tr>
                </thead>
                <tbody id="accountsTable">
                <tr>
                    <td colspan="8" class="py-4 text-center text-stone-500">Memuat data...</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div id="accountsPagination" class="hidden mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-stone-100 pt-4"></div>
    </div>
</div>

<div id="accountModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-stone-800" id="accountModalTitle">Tambah Akun</h2>
                <button type="button" onclick="closeAccountModal()" class="text-stone-400 hover:text-stone-600 text-2xl">
                    &times;
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Nama</label>
                    <input type="text" id="accountName" placeholder="Masukkan nama"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Username</label>
                    <input type="text" id="accountUsername" placeholder="contoh: kasir_1"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Email</label>
                    <input type="email" id="accountEmail" placeholder="Masukkan email"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Password</label>
                    <input type="password" id="accountPassword" placeholder="Masukkan password"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <p id="accountPasswordHelp" class="text-xs text-stone-500 mt-2 hidden">Kosongkan jika password tidak ingin diubah.</p>
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Role</label>
                    <select id="accountRole"
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                        <option value="">Memuat role...</option>
                    </select>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-stone-700 font-medium mb-2">Jam Kerja</label>
                        <input type="number" id="accountWorkHours" min="0" value="0"
                               class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    </div>
                    <div>
                        <label class="block text-stone-700 font-medium mb-2">Status</label>
                        <select id="accountStatus"
                                class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                            <option value="offline">Offline</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-stone-200 flex gap-3 justify-end">
            <button type="button" onclick="closeAccountModal()"
                    class="px-6 py-3 border border-stone-200 rounded-xl font-medium hover:bg-stone-50 transition-colors">
                Batal
            </button>
            <button type="button" onclick="saveAccount(event)"
                    class="px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white rounded-xl font-medium transition-colors">
                Simpan
            </button>
        </div>
    </div>
</div>

<div id="deleteAccountModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-stone-800">Hapus Akun</h2>
                <button type="button" onclick="closeDeleteAccountModal()" class="text-stone-400 hover:text-stone-600 text-2xl">
                    &times;
                </button>
            </div>
        </div>

        <div class="p-6">
            <p class="text-stone-600">
                Apakah Anda yakin ingin menghapus akun
                <span id="deleteAccountName" class="font-semibold text-stone-800"></span>?
            </p>
        </div>

        <div class="p-6 border-t border-stone-200 flex gap-3 justify-end">
            <button type="button" onclick="closeDeleteAccountModal()"
                    class="px-6 py-3 border border-stone-200 rounded-xl font-medium hover:bg-stone-50 transition-colors">
                Batal
            </button>
            <button type="button" onclick="confirmDeleteAccount()"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
    let accounts = [];
    let accountRoles = [];
    let editingAccountId = null;
    let deletingAccountId = null;
    let currentAccountsPage = 1;

    function roleLabel(roleName) {
        const labels = {
            owner: 'Owner',
            admin: 'Admin',
            kasir: 'Kasir'
        };

        return labels[roleName] || roleName || '-';
    }

    function formatAccountDate(value) {
        if (!value) {
            return '-';
        }

        return new Date(value).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function accountValidationMessage(result, fallback) {
        if (result.errors) {
            return Object.values(result.errors).flat()[0] || fallback;
        }

        return result.message || fallback;
    }

    async function renderAccountRoles() {
        const select = document.getElementById('accountRole');

        if (!select) {
            return;
        }

        try {
            const response = await fetch('/admin-roles');
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal memuat role');
            }

            accountRoles = result.data;
            select.innerHTML = accountRoles.map(role => `
                <option value="${role.id}">${roleLabel(role.name)}</option>
            `).join('');
        } catch (error) {
            console.error(error);
            select.innerHTML = '<option value="">Role gagal dimuat</option>';
        }
    }

    function changeAccountsPage(page) {
        if (page < 1) return;
        currentAccountsPage = page;
        renderAccounts(page);
    }

    async function renderAccounts(page = currentAccountsPage) {
        const tableBody = document.getElementById('accountsTable');

        if (!tableBody) {
            return;
        }

        try {
            currentAccountsPage = page;
            const response = await fetch(`/admin-accounts?page=${currentAccountsPage}&per_page=10`);
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || 'Gagal memuat akun');
            }

            accounts = result.data;
            renderPagination('accountsPagination', result.pagination, 'changeAccountsPage');

            if (!accounts.length) {
                tableBody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-stone-500">Belum ada akun.</td></tr>';
                return;
            }

            tableBody.innerHTML = accounts.map(account => `
                <tr class="table-row border-b border-stone-100">
                    <td class="py-4 font-medium">${account.name}</td>
                    <td class="py-4">${account.username || '-'}</td>
                    <td class="py-4">${account.email}</td>
                    <td class="py-4">
                        <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">
                            ${roleLabel(account.role_name)}
                        </span>
                    </td>
                    <td class="py-4">${Number(account.work_hours || 0).toLocaleString('id-ID')} jam</td>
                    <td class="py-4">
                        <span class="px-3 py-1 ${account.status === 'online' ? 'bg-green-100 text-green-700' : 'bg-stone-100 text-stone-600'} rounded-full text-sm font-medium">
                            ${account.status === 'online' ? 'Online' : 'Offline'}
                        </span>
                    </td>
                    <td class="py-4 text-stone-500">${formatAccountDate(account.created_at)}</td>
                    <td class="py-4">
                        <div class="flex gap-2">
                            <button type="button" onclick="editAccount(${account.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                Edit
                            </button>
                            <button type="button" onclick="deleteAccount(${account.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            console.error(error);
            tableBody.innerHTML = '<tr><td colspan="8" class="py-4 text-center text-red-600">Gagal memuat akun.</td></tr>';
        }
    }
    

    async function openAccountModal(accountId = null) {
    editingAccountId = accountId;

    const modalTitle  = document.getElementById('accountModalTitle');
    const passwordInput = document.getElementById('accountPassword');
    const passwordHelp  = document.getElementById('accountPasswordHelp');

    if (accountId) {
        // Cari di array lokal dulu, fallback ke fetch ulang seluruh halaman
        let account = accounts.find(item => Number(item.id) === Number(accountId));

        // Kalau tidak ketemu (misal pindah halaman), fetch ulang dulu
        if (!account) {
            await renderAccounts(currentAccountsPage);
            account = accounts.find(item => Number(item.id) === Number(accountId));
        }

        // Kalau masih tidak ketemu, stop
        if (!account) {
            alert('Data akun tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        modalTitle.textContent = 'Edit Akun';
        document.getElementById('accountName').value      = account.name      ?? '';
        document.getElementById('accountUsername').value  = account.username  ?? '';
        document.getElementById('accountEmail').value     = account.email     ?? '';
        document.getElementById('accountRole').value      = account.role_id;
        document.getElementById('accountWorkHours').value = account.work_hours ?? 0;
        document.getElementById('accountStatus').value    = account.status    ?? 'offline';
        passwordInput.value       = '';
        passwordInput.placeholder = 'Kosongkan jika tidak diubah';
        passwordHelp.classList.remove('hidden');
    } else {
        modalTitle.textContent = 'Tambah Akun';
        document.getElementById('accountName').value      = '';
        document.getElementById('accountUsername').value  = '';
        document.getElementById('accountEmail').value     = '';
        document.getElementById('accountRole').selectedIndex = 0;
        document.getElementById('accountWorkHours').value = 0;
        document.getElementById('accountStatus').value    = 'offline';
        passwordInput.value       = '';
        passwordInput.placeholder = 'Masukkan password';
        passwordHelp.classList.add('hidden');
    }

    document.getElementById('accountModal').classList.remove('hidden');
}

    function closeAccountModal() {
        document.getElementById('accountModal').classList.add('hidden');
        editingAccountId = null;
    }

    function editAccount(accountId) {
        openAccountModal(accountId);
    }

    async function saveAccount(event) {
        if (event) {
            event.preventDefault();
        }

        const name = document.getElementById('accountName').value;
        const username = document.getElementById('accountUsername').value;
        const email = document.getElementById('accountEmail').value;
        const password = document.getElementById('accountPassword').value;
        const role_id = document.getElementById('accountRole').value;
        const work_hours = document.getElementById('accountWorkHours').value;
        const status = document.getElementById('accountStatus').value;

        if (!name || !username || !email || !role_id || (!editingAccountId && !password)) {
            alert('Mohon lengkapi semua field!');
            return;
        }

        const url = editingAccountId
            ? `/admin-account/${editingAccountId}`
            : '/admin-accounts';
        const method = editingAccountId ? 'PUT' : 'POST';
        const payload = {name, username, email, role_id, work_hours, status};

        if (password) {
            payload.password = password;
        }

        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            alert(accountValidationMessage(result, 'Gagal menyimpan akun'));
            return;
        }

        await renderAccounts();
        closeAccountModal();
    }

    function deleteAccount(accountId) {
        const account = accounts.find(item => item.id === accountId);

        deletingAccountId = accountId;
        document.getElementById('deleteAccountName').textContent = account ? account.name : 'akun ini';
        document.getElementById('deleteAccountModal').classList.remove('hidden');
    }

    function closeDeleteAccountModal() {
        document.getElementById('deleteAccountModal').classList.add('hidden');
        deletingAccountId = null;
    }

    async function confirmDeleteAccount() {
        if (!deletingAccountId) {
            return;
        }

        const response = await fetch(`/admin-account/${deletingAccountId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (!response.ok) {
            console.error(result);
            alert(accountValidationMessage(result, 'Gagal menghapus akun'));
            return;
        }

        await renderAccounts();
        closeDeleteAccountModal();
    }
</script>
