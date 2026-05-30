<div id="page-kasir" class="page-content h-full flex flex-col lg:flex-row">
    <!-- Product Section -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Category Filter -->
        <div class="bg-white border-b border-stone-200 px-6 py-4">
            <div class="flex gap-3 overflow-x-auto pb-2">
                <button onclick="filterCategory('all')"
                        class="category-btn active px-6 py-2 rounded-full border-2 border-amber-700 text-amber-700 whitespace-nowrap font-medium transition-all hover:bg-amber-700 hover:text-white">
                    Semua
                </button>
                <button onclick="filterCategory('kopi')"
                        class="category-btn px-6 py-2 rounded-full border-2 border-amber-700 text-amber-700 whitespace-nowrap font-medium transition-all hover:bg-amber-700 hover:text-white">
                    ☕ Kopi
                </button>
                <button onclick="filterCategory('non-kopi')"
                        class="category-btn px-6 py-2 rounded-full border-2 border-amber-700 text-amber-700 whitespace-nowrap font-medium transition-all hover:bg-amber-700 hover:text-white">
                    🥤 Non-Kopi
                </button>
                <button onclick="filterCategory('makanan')"
                        class="category-btn px-6 py-2 rounded-full border-2 border-amber-700 text-amber-700 whitespace-nowrap font-medium transition-all hover:bg-amber-700 hover:text-white">
                    🍜 Makanan
                </button>
                <button onclick="filterCategory('snack')"
                        class="category-btn px-6 py-2 rounded-full border-2 border-amber-700 text-amber-700 whitespace-nowrap font-medium transition-all hover:bg-amber-700 hover:text-white">
                    🍪 Snack
                </button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="productGrid">
                <!-- Products will be rendered here -->
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <aside class="w-full lg:w-96 bg-white border-l border-stone-200 flex flex-col h-64 lg:h-auto">
        <!-- Cart Header -->
        <div class="p-4 border-b border-stone-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-stone-800">Keranjang</h2>
                <button onclick="clearCart()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                    Hapus Semua
                </button>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto cart-scroll p-4" id="cartItems">
            <div class="text-center text-stone-400 py-12" id="emptyCart">
                <div class="text-5xl mb-4">🛒</div>
                <p>Keranjang kosong</p>
                <p class="text-sm">Pilih produk untuk ditambahkan</p>
            </div>
        </div>

        <!-- Cart Footer -->
        <div class="p-4 border-t border-stone-200 space-y-4">
            <div class="space-y-2">
                <div class="flex justify-between text-stone-600">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="flex justify-between text-stone-600">
                    <span id="taxLabel">Pajak (10%)</span>
                    <span id="tax">Rp 0</span>
                </div>
                <div
                    class="flex justify-between text-xl font-bold text-stone-800 pt-2 border-t border-stone-200">
                    <span>Total</span>
                    <span id="total" class="text-amber-700">Rp 0</span>
                </div>
            </div>

            <div class="space-y-2">
                <button onclick="openPaymentModal()" id="payButton" disabled
                        class="w-full bg-amber-700 hover:bg-amber-800 disabled:bg-stone-300 disabled:cursor-not-allowed text-white py-3 rounded-xl font-medium transition-colors">
                    Bayar Sekarang
                </button>
                <button onclick="printReceipt()" id="printButton" disabled
                        class="w-full bg-stone-200 hover:bg-stone-300 disabled:bg-stone-100 disabled:text-stone-400 text-stone-700 py-3 rounded-xl font-medium transition-colors">
                    🖨️ Cetak Struk
                </button>
            </div>
        </div>
    </aside>
</div>

<script>
function parseRupiah(text) {
    // "Rp 15.000" → 15000
    return parseInt((text || '0').replace(/[^\d]/g, '')) || 0;
}

function filterCategory(category) {
    currentCategory = category;
    document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    renderProducts();
}

function renderProducts() {
    const grid = document.getElementById('productGrid');
    const activeProducts = products.filter(p => p.status === 'active');
    const filteredProducts = currentCategory === 'all'
        ? activeProducts
        : activeProducts.filter(p => p.category === currentCategory);

    grid.innerHTML = filteredProducts.map(product => `
        <div class="product-card bg-white rounded-xl p-4 transition-all duration-300 border ${product.stock > 0 ? 'cursor-pointer border-stone-200 hover:border-amber-700' : 'cursor-not-allowed border-red-100 opacity-60 bg-stone-50'}"
            ${product.stock > 0 ? `onclick="addToCart(${product.id})"` : ''}>
            <div class="flex items-start justify-between gap-2 mb-3">
                <div class="text-4xl">${product.emoji}</div>
                <span class="px-2 py-1 rounded-full text-xs font-semibold ${product.stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                    ${product.stock > 0 ? `Stok ${product.stock}` : 'Habis'}
                </span>
            </div>
            <h3 class="font-semibold text-stone-800 mb-1">${product.name}</h3>
            <p class="${product.stock > 0 ? 'text-amber-700' : 'text-stone-400'} font-bold">${formatRupiah(product.price)}</p>
        </div>
    `).join('');
}

function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product || product.stock <= 0) { alert('Stok produk ini habis.'); return; }

    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        if (existingItem.quantity >= product.stock) { alert(`Stok ${product.name} hanya tersisa ${product.stock}.`); return; }
        existingItem.quantity += 1;
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    renderCart();
}

function updateQuantity(productId, change) {
    const item    = cart.find(i => i.id === productId);
    const product = products.find(p => p.id === productId);
    if (!item) return;

    if (change > 0 && product && item.quantity >= product.stock) {
        alert(`Stok ${item.name} hanya tersisa ${product.stock}.`);
        return;
    }
    item.quantity += change;
    if (item.quantity <= 0) cart = cart.filter(i => i.id !== productId);
    renderCart();
}

function removeFromCart(productId) { cart = cart.filter(i => i.id !== productId); renderCart(); }

function clearCart() {
    if (!cart.length) return;
    if (confirm('Hapus semua item dari keranjang?')) { cart = []; renderCart(); }
}

function renderCart() {
    const cartItems   = document.getElementById('cartItems');
    const payButton   = document.getElementById('payButton');
    const printButton = document.getElementById('printButton');

    if (!cart.length) {
        cartItems.innerHTML = `
            <div class="text-center text-stone-400 py-12">
                <div class="text-5xl mb-4">🛒</div>
                <p>Keranjang kosong</p>
                <p class="text-sm">Pilih produk untuk ditambahkan</p>
            </div>`;
        payButton.disabled   = true;
        printButton.disabled = !lastReceiptData;
    } else {
        cartItems.innerHTML = cart.map(item => `
            <div class="flex items-center gap-3 mb-3 p-3 bg-stone-50 rounded-xl">
                <div class="text-2xl">${item.emoji}</div>
                <div class="flex-1">
                    <h4 class="font-medium text-stone-800 text-sm">${item.name}</h4>
                    <p class="text-amber-700 text-sm font-semibold">${formatRupiah(item.price)}</p>
                    <p class="text-xs text-stone-500">Stok tersedia: ${item.stock}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateQuantity(${item.id}, -1)" class="w-8 h-8 bg-stone-200 hover:bg-stone-300 rounded-full font-bold">-</button>
                    <span class="w-8 text-center font-medium">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, 1)" ${item.quantity >= item.stock ? 'disabled' : ''} class="w-8 h-8 bg-stone-200 hover:bg-stone-300 disabled:bg-stone-100 disabled:text-stone-400 disabled:cursor-not-allowed rounded-full font-bold">+</button>
                </div>
            </div>`).join('');
        payButton.disabled   = false;
        printButton.disabled = !lastReceiptData;
    }
    updateTotals();
}

function updateTotals() {
    const subtotal   = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const taxPercent = getTaxPercent();
    const tax        = Math.round(subtotal * taxPercent / 100);
    const total      = subtotal + tax;
    const itemCount  = cart.reduce((sum, item) => sum + item.quantity, 0);

    document.getElementById('subtotal').textContent      = formatRupiah(subtotal);
    document.getElementById('taxLabel').textContent      = `Pajak (${taxPercent}%)`;
    document.getElementById('tax').textContent           = formatRupiah(tax);
    document.getElementById('total').textContent         = formatRupiah(total);
    document.getElementById('modalTotal').textContent    = formatRupiah(total);
    document.getElementById('modalSubtotal').textContent = formatRupiah(subtotal);
    document.getElementById('modalTaxLabel').textContent = `Pajak (${taxPercent}%)`;
    document.getElementById('modalTax').textContent      = formatRupiah(tax);
    document.getElementById('paymentItemCount').textContent  = `${itemCount} item pesanan`;
    document.getElementById('modalOrderSummary').textContent = cart.length
        ? cart.map(i => `${i.quantity}x ${i.name}`).join(', ')
        : '-';
}

function openPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('cashReceived').value = '';
    document.getElementById('change').textContent = 'Rp 0';
    if (!selectedPaymentMethod && paymentMethods.length > 0) selectPaymentMethod(paymentMethods[0].id);
}

function closePaymentModal() { document.getElementById('paymentModal').classList.add('hidden'); }

function selectPaymentMethod(methodId) {
    selectedPaymentMethod = paymentMethods.find(m => m.id === Number(methodId));
    if (!selectedPaymentMethod) { paymentMethod = null; return; }

    paymentMethod = selectedPaymentMethod.code;

    document.querySelectorAll('.payment-method').forEach(btn => {
        btn.classList.remove('active', 'border-amber-700', 'bg-amber-50');
        btn.classList.add('border-stone-200');
    });
    const activeBtn = document.querySelector(`[data-method-id="${selectedPaymentMethod.id}"]`);
    if (activeBtn) {
        activeBtn.classList.add('active', 'border-amber-700', 'bg-amber-50');
        activeBtn.classList.remove('border-stone-200');
    }

    const cashSection = document.getElementById('cashPayment');
    cashSection.style.display = paymentMethod === 'cash' ? 'block' : 'none';
    if (paymentMethod !== 'cash') {
        document.getElementById('cashReceived').value = '';
        document.getElementById('change').textContent = 'Rp 0';
    }
}

function calculateChange() {
    const cashReceived = parseInt(document.getElementById('cashReceived').value) || 0;
    // ✅ Fix: ambil dari state cart, bukan parse teks
    const subtotal   = cart.reduce((s, i) => s + i.price * i.quantity, 0);
    const taxPercent = getTaxPercent();
    const total      = subtotal + Math.round(subtotal * taxPercent / 100);
    const change     = cashReceived - total;

    const el = document.getElementById('change');
    if (change >= 0) {
        el.textContent = formatRupiah(change);
        el.className   = 'text-2xl font-bold text-green-600';
    } else {
        el.textContent = 'Kurang ' + formatRupiah(Math.abs(change));
        el.className   = 'text-2xl font-bold text-red-600';
    }
}

async function processPayment() {
    // ✅ Fix: hitung dari cart state, bukan parse dari teks DOM
    const subtotal   = cart.reduce((s, i) => s + i.price * i.quantity, 0);
    const taxPercent = getTaxPercent();
    const total      = subtotal + Math.round(subtotal * taxPercent / 100);

    if (!selectedPaymentMethod) { alert('Pilih metode pembayaran terlebih dahulu!'); return; }

    if (paymentMethod === 'cash') {
        const cashReceived = parseInt(document.getElementById('cashReceived').value) || 0;
        if (cashReceived < total) { alert('Uang yang diterima kurang!'); return; }
    }

    const paymentAmount = paymentMethod === 'cash'
        ? parseInt(document.getElementById('cashReceived').value) || 0
        : total;

    const btn = document.getElementById('confirmPaymentBtn');
    btn.disabled    = true;
    btn.textContent = 'Memproses...';

    try {
        const response = await fetch('/kasir-checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                payment_method_id: selectedPaymentMethod.id,
                payment: paymentAmount,
                items: cart.map(item => ({
                    id: item.id,
                    name: item.name,
                    quantity: item.quantity,
                    price: item.price
                }))
            })
        });

        const result = await response.json();

        if (!response.ok || !result.status) {
            const errMsg = result.errors ? Object.values(result.errors).flat()[0] : result.message;
            alert(errMsg || 'Gagal memproses pembayaran');
            loadProducts();
            return;
        }

        const transaction = result.data.transaction;
        lastReceiptData = {
            id:            transaction.code,
            date:          new Date().toLocaleString('id-ID'),
            items:         cart.map(i => ({ name: i.name, qty: i.quantity, price: i.price })),
            subtotal:      transaction.subtotal,
            tax_amount:    transaction.tax_amount,
            tax_percent:   transaction.tax_percent,
            total:         transaction.total,
            payment:       transaction.payment,
            change_amount: transaction.change_amount,
            method:        selectedPaymentMethod.name
        };

        dailyTotalAmount += Number(transaction.total);
        const dailyEl = document.getElementById('dailyTotal');
        if (dailyEl) dailyEl.textContent = formatRupiah(dailyTotalAmount);

        closePaymentModal();
        document.getElementById('successPaymentSummary').textContent =
            `${transaction.code} – ${selectedPaymentMethod.name} – ${formatRupiah(transaction.total)}`;
        document.getElementById('successModal').classList.remove('hidden');
        document.getElementById('printButton').disabled = false;
    } catch (e) {
        console.error(e);
        alert('Terjadi kesalahan server');
    } finally {
        btn.disabled    = false;
        btn.textContent = 'Konfirmasi Pembayaran';
    }
}

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
    cart = [];
    renderCart();
    loadProducts();
}

function printReceipt() {
    const draftSubtotal = cart.reduce((s, i) => s + i.price * i.quantity, 0);
    const receipt = lastReceiptData || {
        id:            'DRAFT',
        date:          new Date().toLocaleString('id-ID'),
        items:         cart.map(i => ({ name: i.name, qty: i.quantity, price: i.price })),
        subtotal:      draftSubtotal,
        tax_amount:    Math.round(draftSubtotal * getTaxPercent() / 100),
        tax_percent:   getTaxPercent(),
        total:         draftSubtotal + Math.round(draftSubtotal * getTaxPercent() / 100),
        payment:       null,
        change_amount: null,
        method:        selectedPaymentMethod ? selectedPaymentMethod.name : '-'
    };

    if (!receipt.items.length) return;

    const isCash      = (receipt.method || '').toLowerCase().match(/tunai|cash/);
    const storeName   = kasirSettingsCache.store_name || kasirSettingsCache.business_name || 'Warkop POS';
    const storeAddr   = kasirSettingsCache.store_address || kasirSettingsCache.business_address || '';
    const footer      = kasirSettingsCache.receipt_footer || 'Terima Kasih!';

    let content = `====================================\n           ${storeName.toUpperCase()}\n====================================\n${storeAddr}\n${receipt.date}\nNo: ${receipt.id}\n------------------------------------\n\n`;
    receipt.items.forEach(i => { content += `${i.name}\n  ${i.qty} x ${formatRupiah(i.price)} = ${formatRupiah(i.price * i.qty)}\n`; });
    content += `\n------------------------------------\nSubtotal    : ${formatRupiah(receipt.subtotal)}\nPajak (${receipt.tax_percent}%) : ${formatRupiah(receipt.tax_amount)}\n------------------------------------\nTOTAL       : ${formatRupiah(receipt.total)}\nMetode      : ${receipt.method}\n`;
    if (isCash) content += `Dibayar     : ${formatRupiah(receipt.payment || 0)}\nKembalian   : ${formatRupiah(receipt.change_amount || 0)}\n`;
    content += `====================================\n     ${footer}\n====================================\n`;

    const w = window.open('', '_blank');
    w.document.write(`<pre style="font-family:monospace;font-size:12px;">${content}</pre>`);
    w.document.close();
    w.print();
}

</script>
