<?= $this->extend('template/layouts/admin') ?>

<?= $this->section('content') ?>

<div class="flex min-h-screen">

    <?= $this->include('template/partials/admin/sidebar') ?>

    <main class="flex-1 lg:ml-64 overflow-hidden">

        <?= $this->include('template/partials/admin/header') ?>

        <div class="overflow-y-auto p-6 h-[calc(100vh-73px)] custom-scrollbar">

            <?= $this->include('dashboard/admin/dashboard') ?>
            <?= $this->include('dashboard/admin/products') ?>
            <?= $this->include('dashboard/admin/akun') ?>
            <?= $this->include('dashboard/admin/orders') ?>
            <?= $this->include('dashboard/admin/transactions') ?>
            <?= $this->include('dashboard/admin/payments') ?>
            <?= $this->include('dashboard/admin/reports') ?>

            <div class="bg-white rounded-2xl p-6 border border-stone-200 mt-6">
                <h3 class="font-semibold text-stone-800 mb-6">Export Laporan</h3>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <button class="flex items-center justify-center gap-2 p-4 border border-stone-200 rounded-xl hover:bg-stone-50 transition-colors">
                        <span class="text-xl">📊</span><span>Excel</span>
                    </button>
                    <button class="flex items-center justify-center gap-2 p-4 border border-stone-200 rounded-xl hover:bg-stone-50 transition-colors">
                        <span class="text-xl">📄</span><span>PDF</span>
                    </button>
                    <button class="flex items-center justify-center gap-2 p-4 border border-stone-200 rounded-xl hover:bg-stone-50 transition-colors">
                        <span class="text-xl">📑</span><span>Print</span>
                    </button>
                    <button class="flex items-center justify-center gap-2 p-4 border border-stone-200 rounded-xl hover:bg-stone-50 transition-colors">
                        <span class="text-xl">📧</span><span>Email</span>
                    </button>
                </div>
            </div>

        </div>

        <?= $this->include('dashboard/admin/settings') ?>

    </main>

</div>

<!-- Product Modal -->
<div id="productModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-stone-800" id="productModalTitle">Tambah Produk</h2>
                <button onclick="closeProductModal()" class="text-stone-400 hover:text-stone-600 text-2xl">&times;</button>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Nama Produk</label>
                    <input type="text" id="productName" placeholder="Masukkan nama produk"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Kategori</label>
                    <select id="productCategory"
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                        <option value="">Memuat kategori...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Harga</label>
                    <input type="number" id="productPrice" placeholder="Masukkan harga"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Stok</label>
                    <input type="number" id="productStock" placeholder="Masukkan stok"
                           class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                </div>
                <div>
                    <label class="block text-stone-700 font-medium mb-2">Status</label>
                    <select id="productStatus"
                            class="w-full px-4 py-3 border border-stone-200 rounded-xl focus:outline-none focus:border-amber-700">
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6 border-t border-stone-200 flex gap-3 justify-end">
            <button onclick="closeProductModal()"
                    class="px-6 py-3 border border-stone-200 rounded-xl font-medium hover:bg-stone-50 transition-colors">
                Batal
            </button>
            <button onclick="saveProduct()"
                    class="px-6 py-3 bg-amber-700 hover:bg-amber-800 text-white rounded-xl font-medium transition-colors">
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteProductModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="p-6 border-b border-stone-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-stone-800">Hapus Produk</h2>
                <button onclick="closeDeleteProductModal()" class="text-stone-400 hover:text-stone-600 text-2xl">&times;</button>
            </div>
        </div>
        <div class="p-6">
            <p class="text-stone-600">
                Apakah Anda yakin ingin menghapus
                <span id="deleteProductName" class="font-semibold text-stone-800"></span>?
            </p>
        </div>
        <div class="p-6 border-t border-stone-200 flex gap-3 justify-end">
            <button onclick="closeDeleteProductModal()"
                    class="px-6 py-3 border border-stone-200 rounded-xl font-medium hover:bg-stone-50 transition-colors">
                Batal
            </button>
            <button onclick="confirmDeleteProduct()"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors">
                Hapus
            </button>
        </div>
    </div>
</div>

<script src="/assets/js/admin.js"></script>
<script src="<?= base_url('assets/js/toast.js') ?>"></script>
<?= $this->endSection() ?>