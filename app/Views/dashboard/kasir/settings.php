<div id="page-pengaturan" class="page-content hidden h-full flex flex-col">
    <header class="bg-white border-b border-stone-200 px-6 py-4">
        <h1 class="text-2xl font-bold text-stone-800">Pengaturan</h1>
        <p class="text-stone-500 text-sm">Profil kasir, toko, struk, dan printer</p>
    </header>

    <form id="kasirSettingsForm" onsubmit="saveSettings(event)" class="flex-1 overflow-y-auto p-6">
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-6">Profil Kasir</h3>
                <div class="space-y-4">
                    <input id="kasirProfileName" type="text" disabled class="w-full px-4 py-3 bg-stone-100 border border-stone-200 rounded-xl">
                    <input id="kasirProfileUsername" type="text" disabled class="w-full px-4 py-3 bg-stone-100 border border-stone-200 rounded-xl">
                    <input id="kasirProfileStatus" type="text" disabled class="w-full px-4 py-3 bg-stone-100 border border-stone-200 rounded-xl">
                    <input id="kasirProfileWorkHours" type="text" disabled class="w-full px-4 py-3 bg-stone-100 border border-stone-200 rounded-xl">
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-6">Informasi Toko</h3>
                <div class="space-y-4">
                    <input id="kasirStoreName" type="text" placeholder="Nama toko" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <textarea id="kasirStoreAddress" rows="3" placeholder="Alamat toko" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"></textarea>
                    <input id="kasirStorePhone" type="text" placeholder="No. telepon" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <input id="kasirStoreEmail" type="email" placeholder="Email toko" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <textarea id="kasirReceiptFooter" rows="2" placeholder="Footer struk" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"></textarea>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-stone-200 lg:col-span-2">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div>
                        <h3 class="font-semibold text-stone-800">Info Landing Page</h3>
                        <p class="text-sm text-stone-500 mt-1">Konten ini tampil di halaman depan pelanggan.</p>
                    </div>
                    <a href="/" target="_blank" class="text-sm font-medium text-amber-700 hover:text-amber-800">Lihat landing</a>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <input id="kasirHeroLocation" type="text" placeholder="Label lokasi hero" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <input id="kasirHeroTitle" type="text" placeholder="Judul hero" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <input id="kasirHeroHighlight" type="text" placeholder="Highlight judul hero" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <input id="kasirFacilitiesSubtitle" type="text" placeholder="Subjudul fasilitas" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <input id="kasirFacilitiesTitle" type="text" placeholder="Judul fasilitas" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <input id="kasirLocationSubtitle" type="text" placeholder="Subjudul lokasi" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <input id="kasirLocationTitle" type="text" placeholder="Judul lokasi" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <textarea id="kasirHeroDescription" rows="3" placeholder="Deskripsi hero" class="md:col-span-2 w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"></textarea>
                    <textarea id="kasirMenuDescription" rows="2" placeholder="Deskripsi katalog menu" class="md:col-span-2 w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"></textarea>
                    <textarea id="kasirLocationDescription" rows="2" placeholder="Deskripsi lokasi" class="md:col-span-2 w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"></textarea>
                    <input id="kasirMapsUrl" type="url" placeholder="Link Google Maps" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <input id="kasirInstagram" type="text" placeholder="Instagram" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <textarea id="kasirMapsEmbedUrl" rows="2" placeholder="Google Maps embed URL" class="md:col-span-2 w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"></textarea>
                    <textarea id="kasirFooterDescription" rows="2" placeholder="Deskripsi footer" class="md:col-span-2 w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700"></textarea>
                </div>

                <div class="mt-6">
                    <h4 class="font-medium text-stone-800 mb-3">Fasilitas</h4>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-3 p-4 bg-stone-50 rounded-xl">
                            <input id="kasirFacility1Title" type="text" placeholder="Fasilitas 1" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                            <textarea id="kasirFacility1Description" rows="2" placeholder="Deskripsi fasilitas 1" class="w-full px-4 py-3 border border-stone-200 rounded-xl"></textarea>
                        </div>
                        <div class="space-y-3 p-4 bg-stone-50 rounded-xl">
                            <input id="kasirFacility2Title" type="text" placeholder="Fasilitas 2" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                            <textarea id="kasirFacility2Description" rows="2" placeholder="Deskripsi fasilitas 2" class="w-full px-4 py-3 border border-stone-200 rounded-xl"></textarea>
                        </div>
                        <div class="space-y-3 p-4 bg-stone-50 rounded-xl">
                            <input id="kasirFacility3Title" type="text" placeholder="Fasilitas 3" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                            <textarea id="kasirFacility3Description" rows="2" placeholder="Deskripsi fasilitas 3" class="w-full px-4 py-3 border border-stone-200 rounded-xl"></textarea>
                        </div>
                        <div class="space-y-3 p-4 bg-stone-50 rounded-xl">
                            <input id="kasirFacility4Title" type="text" placeholder="Fasilitas 4" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                            <textarea id="kasirFacility4Description" rows="2" placeholder="Deskripsi fasilitas 4" class="w-full px-4 py-3 border border-stone-200 rounded-xl"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-6">Pajak & Pembayaran</h3>
                <div class="space-y-4">
                    <input id="kasirTaxPercent" type="number" min="0" max="100" placeholder="Pajak (%)" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                    <select id="kasirCurrency" class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                        <option value="IDR">IDR - Rupiah</option>
                        <option value="USD">USD - Dollar</option>
                    </select>
                    <div>
                        <p class="block text-stone-700 font-medium mb-3">Metode Pembayaran Aktif</p>
                        <div id="kasirPaymentMethodsPreview" class="space-y-3">
                            <p class="text-sm text-stone-500">Memuat metode pembayaran...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-6">Jam Operasional</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center p-4 bg-stone-50 rounded-xl">
                        <span class="font-medium">Senin - Jumat</span>
                        <input id="kasirWeekdayOpen" type="time" class="px-3 py-2 border border-stone-200 rounded-lg">
                        <input id="kasirWeekdayClose" type="time" class="px-3 py-2 border border-stone-200 rounded-lg">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center p-4 bg-stone-50 rounded-xl">
                        <span class="font-medium">Sabtu - Minggu</span>
                        <input id="kasirWeekendOpen" type="time" class="px-3 py-2 border border-stone-200 rounded-lg">
                        <input id="kasirWeekendClose" type="time" class="px-3 py-2 border border-stone-200 rounded-lg">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-6">Pengaturan Printer</h3>
                <div class="space-y-4">
                    <select id="kasirPaperSize" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                        <option value="58mm">58mm</option>
                        <option value="80mm">80mm</option>
                    </select>
                    <input id="kasirReceiptCopies" type="number" min="1" max="10" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-stone-200">
                <h3 class="font-semibold text-stone-800 mb-6">Keamanan</h3>
                <div class="space-y-4">
                    <input id="kasirCurrentPassword" type="password" placeholder="Password saat ini" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                    <input id="kasirNewPassword" type="password" placeholder="Password baru" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                    <input id="kasirNewPasswordConfirmation" type="password" placeholder="Konfirmasi password baru" class="w-full px-4 py-3 border border-stone-200 rounded-xl">
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-amber-700 hover:bg-amber-800 text-white px-8 py-3 rounded-xl font-medium transition-colors">
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
