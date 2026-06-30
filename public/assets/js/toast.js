/**
 * Toast Notification — Vanilla JS, reusable di semua dashboard (Owner, Admin, Kasir)
 *
 * Cara pakai:
 *   showToast('Pengaturan berhasil disimpan');                  // default: success
 *   showToast('Gagal menghapus produk', 'error');
 *   showToast('Periksa kembali input kamu', 'warning');
 *   showToast('Sedang memproses...', 'info');
 *
 * Cukup include file ini sekali di layout (sebelum </body> atau di partial header/footer),
 * lalu panggil showToast(...) menggantikan alert(...) di mana pun dibutuhkan.
 */

(function () {
    const TOAST_CONTAINER_ID = 'toastContainer';
    const TOAST_DURATION = 3500; // ms

    const TOAST_STYLES = {
        success: {
            bg: 'bg-white',
            border: 'border-green-200',
            iconBg: 'bg-green-100',
            iconColor: 'text-green-600',
            icon: '✓',
        },
        error: {
            bg: 'bg-white',
            border: 'border-red-200',
            iconBg: 'bg-red-100',
            iconColor: 'text-red-600',
            icon: '✕',
        },
        warning: {
            bg: 'bg-white',
            border: 'border-amber-200',
            iconBg: 'bg-amber-100',
            iconColor: 'text-amber-700',
            icon: '!',
        },
        info: {
            bg: 'bg-white',
            border: 'border-blue-200',
            iconBg: 'bg-blue-100',
            iconColor: 'text-blue-600',
            icon: 'i',
        },
    };

    function ensureContainer() {
        let container = document.getElementById(TOAST_CONTAINER_ID);

        if (!container) {
            container = document.createElement('div');
            container.id = TOAST_CONTAINER_ID;
            container.className = 'fixed top-4 right-4 z-[9999] flex flex-col gap-3 w-full max-w-sm pointer-events-none';
            document.body.appendChild(container);
        }

        return container;
    }

    function ensureStyles() {
        if (document.getElementById('toastStyles')) return;

        const style = document.createElement('style');
        style.id = 'toastStyles';
        style.textContent = `
            @keyframes toastSlideIn {
                from { opacity: 0; transform: translateX(100%); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes toastSlideOut {
                from { opacity: 1; transform: translateX(0); }
                to { opacity: 0; transform: translateX(100%); }
            }
            .toast-item {
                animation: toastSlideIn 0.3s ease forwards;
            }
            .toast-item.toast-leaving {
                animation: toastSlideOut 0.25s ease forwards;
            }
        `;
        document.head.appendChild(style);
    }

    function showToast(message, type = 'success', duration = TOAST_DURATION) {
        ensureStyles();
        const container = ensureContainer();
        const config = TOAST_STYLES[type] || TOAST_STYLES.success;

        const toast = document.createElement('div');
        toast.className = `toast-item pointer-events-auto ${config.bg} border ${config.border} rounded-2xl shadow-lg p-4 flex items-start gap-3`;

        toast.innerHTML = `
            <div class="w-8 h-8 rounded-full ${config.iconBg} ${config.iconColor} flex items-center justify-center flex-shrink-0 font-bold text-sm">
                ${config.icon}
            </div>
            <p class="flex-1 text-sm text-stone-700 leading-snug pt-1">${message}</p>
            <button type="button" class="text-stone-400 hover:text-stone-600 flex-shrink-0 text-lg leading-none" aria-label="Tutup">
                &times;
            </button>
        `;

        const closeButton = toast.querySelector('button');
        const removeToast = () => {
            toast.classList.add('toast-leaving');
            toast.addEventListener('animationend', () => toast.remove(), { once: true });
        };

        closeButton.addEventListener('click', removeToast);

        container.appendChild(toast);

        if (duration > 0) {
            setTimeout(removeToast, duration);
        }

        return toast;
    }

    // Expose global supaya bisa dipanggil dari mana saja (inline onclick, fetch handler, dll)
    window.showToast = showToast;
})();