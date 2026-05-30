<div id="page-settings" class="page-content hidden">
    <form id="ownerSettingsForm" class="grid lg:grid-cols-2 gap-6" onsubmit="saveOwnerSettings(event)">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-6">Informasi Toko</h3>
            <div class="space-y-4">
                <input id="settingBusinessName" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="Nama bisnis">
                <textarea id="settingBusinessAddress" class="w-full px-4 py-3 border border-stone-200 rounded-xl" rows="3" placeholder="Alamat"></textarea>
                <input id="settingBusinessPhone" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="No. telepon">
                <input id="settingBusinessEmail" type="email" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="Email bisnis">
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-6">Pengaturan Bisnis</h3>
            <div class="space-y-4">
                <input id="settingTaxPercent" type="number" min="0" max="100" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="Pajak (%)">
                <select id="settingCurrency" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                    <option value="IDR">IDR - Rupiah Indonesia</option>
                    <option value="USD">USD - US Dollar</option>
                </select>
                <input id="settingMonthlyTarget" type="number" min="0" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="Target pendapatan bulanan">
                <input id="settingCustomerTarget" type="number" min="0" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="Target pelanggan baru">
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-6">Notifikasi</h3>
            <div class="space-y-4">
                <label class="flex items-center justify-between p-4 border border-stone-200 rounded-xl"><span>Stok Rendah</span><input id="settingNotifyLowStock" type="checkbox" class="w-5 h-5 accent-amber-700"></label>
                <label class="flex items-center justify-between p-4 border border-stone-200 rounded-xl"><span>Laporan Harian</span><input id="settingNotifyDaily" type="checkbox" class="w-5 h-5 accent-amber-700"></label>
                <label class="flex items-center justify-between p-4 border border-stone-200 rounded-xl"><span>Laporan Mingguan</span><input id="settingNotifyWeekly" type="checkbox" class="w-5 h-5 accent-amber-700"></label>
                <label class="flex items-center justify-between p-4 border border-stone-200 rounded-xl"><span>Karyawan</span><input id="settingNotifyStaff" type="checkbox" class="w-5 h-5 accent-amber-700"></label>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-6">Keamanan</h3>
            <div class="space-y-4">
                <input id="settingCurrentPassword" type="password" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="Password saat ini">
                <input id="settingNewPassword" type="password" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="Password baru">
                <input id="settingNewPasswordConfirmation" type="password" class="w-full px-4 py-3 border border-stone-200 rounded-xl" placeholder="Konfirmasi password baru">
            </div>
        </div>
        <div class="lg:col-span-2 flex justify-end">
            <button type="submit" class="bg-amber-700 hover:bg-amber-800 text-white px-8 py-3 rounded-xl font-medium transition-colors">
                Simpan Semua Pengaturan
            </button>
        </div>
    </form>
</div>
