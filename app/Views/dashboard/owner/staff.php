<div id="page-staff" class="page-content hidden">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-sm font-bold text-blue-700">ST</span>
                </div>
                <div>
                    <p class="text-stone-500 text-sm">Total Karyawan</p>
                    <p id="ownerStaffTotal" class="text-3xl font-bold text-stone-800">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-sm font-bold text-green-700">ON</span>
                </div>
                <div>
                    <p class="text-stone-500 text-sm">Online Hari Ini</p>
                    <p id="ownerStaffOnline" class="text-3xl font-bold text-stone-800">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <span class="text-sm font-bold text-amber-700">SK</span>
                </div>
                <div>
                    <p class="text-stone-500 text-sm">Skor Rata-rata</p>
                    <p id="ownerStaffAverageScore" class="text-3xl font-bold text-stone-800">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <span class="text-sm font-bold text-purple-700">TR</span>
                </div>
                <div>
                    <p class="text-stone-500 text-sm">Transaksi Kasir Hari Ini</p>
                    <p id="ownerStaffTodayTransactions" class="text-3xl font-bold text-stone-800">0</p>
                </div>
            </div>
        </div>
    </div>

    <div id="ownerStaffRoleTables" class="space-y-6 mb-6">
        <div class="bg-white rounded-2xl p-6 border border-stone-200">
            <h3 class="font-bold text-lg text-stone-800 mb-1">Kinerja Karyawan</h3>
            <p class="text-sm text-stone-500">Memuat data karyawan...</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-stone-200">
        <h3 class="font-bold text-lg text-stone-800 mb-1">Jadwal Minggu Ini</h3>
        <p class="text-sm text-stone-500 mb-6">Tampilan jadwal operasional karyawan</p>
        <div id="ownerStaffSchedule" class="grid grid-cols-8 gap-2 text-center text-sm min-w-[760px] overflow-x-auto">
            <div class="col-span-8 text-stone-500 py-6">Memuat jadwal...</div>
        </div>
    </div>
</div>
