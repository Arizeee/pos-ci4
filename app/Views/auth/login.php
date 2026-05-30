<?= $this->extend('template/layouts/auth') ?>

<?= $this->section('content') ?>
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-amber-500 to-amber-700 rounded-2xl shadow-lg mb-4">
                <span class="text-3xl font-bold text-white">WP</span>
            </div>
            <h1 class="text-3xl font-bold text-stone-800">Warkop POS</h1>
            <p class="text-stone-500 mt-2">Masuk untuk melanjutkan ke dashboard</p>
        </div>

        <div class="login-card rounded-3xl shadow-2xl p-8 border border-white/50">
            <form id="loginForm" onsubmit="handleLogin(event)" class="space-y-5">
                <?= csrf_field() ?>
                <div>
                    <label for="username" class="block text-stone-700 font-medium mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-semibold">ID</span>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            required
                            autocomplete="username"
                            placeholder="Masukkan username"
                            class="input-focus w-full pl-12 pr-4 py-3 border-2 border-stone-200 rounded-xl focus:outline-none transition-all text-stone-800 placeholder-stone-400"
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-stone-700 font-medium mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 font-semibold">PW</span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="input-focus w-full pl-12 pr-12 py-3 border-2 border-stone-200 rounded-xl focus:outline-none transition-all text-stone-800 placeholder-stone-400"
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-stone-500 hover:text-stone-700 transition-colors"
                            aria-label="Tampilkan password"
                        >
                            <span id="eyeIcon">Lihat</span>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="remember" class="w-4 h-4 accent-amber-600 rounded">
                    <span class="text-sm text-stone-600">Ingat saya</span>
                </label>

                <button
                    type="submit"
                    id="loginButton"
                    class="btn-hover w-full bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300"
                >
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-stone-500 mt-6">
                Gunakan username yang dibuat oleh admin.
            </p>
        </div>

        <div class="text-center mt-8 text-sm text-stone-400">
            <p>Warkop POS</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = 'Tutup';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = 'Lihat';
            }
        }

        async function handleLogin(event) {
            event.preventDefault();

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const loginButton = document.getElementById('loginButton');

            if (!username || !password) {
                showNotification('error', 'Mohon lengkapi username dan password.');
                return;
            }

            loginButton.disabled = true;
            loginButton.textContent = 'Memproses...';

            try {
                const response = await fetch('/auth', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({username, password})
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    showNotification('error', result.message || 'Login gagal.');
                    return;
                }

                const redirects = {
                    owner: '/owner-dashboard',
                    admin: '/admin-dashboard',
                    kasir: '/kasir-dashboard'
                };

                if (!redirects[result.role]) {
                    showNotification('error', 'Role tidak valid.');
                    return;
                }

                showNotification('success', 'Login berhasil. Mengalihkan...');
                setTimeout(() => {
                    window.location.href = redirects[result.role];
                }, 700);
            } catch (error) {
                console.error(error);
                showNotification('error', 'Terjadi kesalahan server.');
            } finally {
                loginButton.disabled = false;
                loginButton.textContent = 'Masuk';
            }
        }

        function showNotification(type, message) {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-4 rounded-xl shadow-lg z-50 transition-all transform translate-x-full ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            toast.innerHTML = `
                <div class="flex items-center gap-3">
                    <span class="font-bold">${type === 'success' ? 'OK' : '!'}</span>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-full'), 10);
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
<?= $this->endSection() ?>
