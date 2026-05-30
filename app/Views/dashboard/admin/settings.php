<div id="page-settings" class="page-content hidden">
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Store Settings -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-semibold text-stone-800 mb-6">Pengaturan Toko</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Nama Toko</label>
                    <input type="text" value="Warkop Pos"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Alamat</label>
                    <textarea
                        class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"
                        rows="3">Jl. Pendidikan No. 123, Dekat Kampus, Kota Pelajar</textarea>
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">No. Telepon</label>
                    <input type="text" value="+62 812-3456-7890"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Pajak (%)</label>
                    <input type="number" value="10"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
            </div>
        </div>

        <!-- Opening Hours -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-semibold text-stone-800 mb-6">Jam Operasional</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="font-medium">Senin - Jumat</span>
                    <div class="flex gap-2">
                        <input type="time" value="08:00"
                               class="px-3 py-2 border border-stone-200 rounded-lg">
                        <span>-</span>
                        <input type="time" value="23:00"
                               class="px-3 py-2 border border-stone-200 rounded-lg">
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="font-medium">Sabtu - Minggu</span>
                    <div class="flex gap-2">
                        <input type="time" value="09:00"
                               class="px-3 py-2 border border-stone-200 rounded-lg">
                        <span>-</span>
                        <input type="time" value="00:00"
                               class="px-3 py-2 border border-stone-200 rounded-lg">
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-semibold text-stone-800 mb-6">Metode Pembayaran</h3>
            <div class="space-y-4">
                <label
                    class="flex items-center justify-between p-4 border border-stone-200 rounded-xl cursor-pointer hover:bg-stone-50">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">💵</span>
                        <span class="font-medium">Tunai</span>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 accent-amber-700">
                </label>
                <label
                    class="flex items-center justify-between p-4 border border-stone-200 rounded-xl cursor-pointer hover:bg-stone-50">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">📱</span>
                        <span class="font-medium">QRIS</span>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 accent-amber-700">
                </label>
                <label
                    class="flex items-center justify-between p-4 border border-stone-200 rounded-xl cursor-pointer hover:bg-stone-50">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">🏦</span>
                        <span class="font-medium">Transfer Bank</span>
                    </div>
                    <input type="checkbox" checked class="w-5 h-5 accent-amber-700">
                </label>
            </div>
        </div>

        <!-- Save Button -->
        <div class="bg-white rounded-2xl p-6 border border-stone-200 flex items-center justify-end">
            <button
                class="bg-amber-700 hover:bg-amber-800 text-white px-8 py-3 rounded-xl font-medium transition-colors">
                Simpan Pengaturan
            </button>
        </div>
    </div>
</div>
