<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($landingSettings['business_name']) ?> - Warung Kopi dekat Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1e3a2f 0%, #2d5a47 50%, #3d7a5f 100%);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .menu-carousel {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .menu-carousel::-webkit-scrollbar {
            display: none;
        }

        .fade-up {
            animation: fadeUp 0.7s ease both;
        }

        .menu-panel {
            animation: panelIn 0.35s ease both;
        }

        .menu-card {
            animation: cardIn 0.45s ease both;
        }

        .carousel-btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .carousel-btn:hover {
            transform: translateY(-50%) scale(1.08);
            box-shadow: 0 14px 28px rgba(120, 53, 15, 0.18);
        }

        .carousel-btn:active {
            transform: translateY(-50%) scale(0.94);
        }

        .menu-card.slide-focus {
            animation: slideFocus 0.38s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes panelIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateX(18px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes slideFocus {
            0% {
                transform: translateX(14px) scale(0.98);
            }
            60% {
                transform: translateX(0) scale(1.03);
            }
            100% {
                transform: translateX(0) scale(1);
            }
        }
    </style>
</head>
<body class="bg-stone-50 min-h-screen">
<!-- Navigation -->
<nav class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm shadow-sm z-50">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-700 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-lg"><?= esc(strtoupper(substr($landingSettings['business_name'], 0, 2))) ?></span>
            </div>
            <span class="font-semibold text-xl text-stone-800"><?= esc($landingSettings['business_name']) ?></span>
        </div>
        <div class="hidden md:flex gap-8">
            <a href="#home" class="text-stone-600 hover:text-amber-700 transition-colors">Beranda</a>
            <a href="#menu" class="text-stone-600 hover:text-amber-700 transition-colors">Menu</a>
            <a href="#fasilitas" class="text-stone-600 hover:text-amber-700 transition-colors">Fasilitas</a>
            <a href="#lokasi" class="text-stone-600 hover:text-amber-700 transition-colors">Lokasi</a>
        </div>
        <a href="#lokasi" class="bg-amber-700 text-white px-5 py-2 rounded-full hover:bg-amber-800 transition-colors">
            Kunjungi Kami
        </a>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero-gradient min-h-screen flex items-center pt-20">
    <div class="max-w-6xl mx-auto px-4 py-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <span class="inline-block bg-amber-600/30 text-amber-200 px-4 py-1 rounded-full text-sm mb-6">
                    <?= esc($landingSettings['hero_location']) ?>
                </span>
                <span class="hidden bg-amber-600/30 text-amber-200 px-4 py-1 rounded-full text-sm mb-6">
                    📍 Tapos, Depok
                </span>
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    <?= esc($landingSettings['hero_title']) ?><br>
                    <span class="text-amber-400"><?= esc($landingSettings['hero_highlight']) ?></span>
                </h1>
                <p class="text-lg text-stone-200 mb-8 leading-relaxed">
                    <?= esc($landingSettings['hero_description']) ?>
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#menu"
                       class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-full font-medium transition-all transform hover:scale-105">
                        Lihat Menu
                    </a>
                    <a href="#lokasi"
                       class="border-2 border-white/50 hover:bg-white/10 text-white px-8 py-3 rounded-full font-medium transition-all">
                        Cek Lokasi
                    </a>
                </div>
            </div>
            <div class="hidden md:flex justify-center">
                <div class="relative">
                    <div class="w-72 h-72 bg-amber-500/20 rounded-full absolute -top-4 -left-4"></div>
                    <div class="w-72 h-72 bg-amber-600/30 rounded-full relative flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-8xl mb-4">☕</div>
                            <p class="text-white text-xl font-medium"><?= esc($landingSettings['hero_highlight']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Section -->
<section id="menu" class="py-20 bg-white overflow-hidden">
    <div class="max-w-6xl mx-auto px-4">
        <div class="fade-up flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-12">
            <div>
                <span class="text-amber-700 font-medium">Menu Kami</span>
                <h2 class="text-3xl md:text-4xl font-bold text-stone-900 mt-2">Katalog Menu</h2>
                <p class="text-stone-500 mt-4 max-w-2xl">
                    <?= esc($landingSettings['menu_description']) ?>
                </p>
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-2xl px-5 py-4">
                <p class="text-stone-500 text-sm">Kategori</p>
                <p class="text-3xl font-bold text-amber-700"><?= count($menuGroups) ?></p>
            </div>
        </div>

        <?php if (!empty($menuGroups)): ?>
            <div class="flex gap-3 overflow-x-auto pb-4 mb-8 snap-x snap-mandatory">
                <?php $i = 0; foreach ($menuGroups as $group): ?>
                    <?php
                        $category = strtolower($group['name']);
                        $icon = str_contains($category, 'non') && str_contains($category, 'kopi')
                            ? 'NK'
                            : (str_contains($category, 'kopi') ? 'KP' : (str_contains($category, 'makan') ? 'MK' : 'MN'));
                    ?>
                    <button
                        type="button"
                        onclick="showMenuCategory(<?= $i ?>)"
                        id="menuTab<?= $i ?>"
                        class="menu-tab snap-start shrink-0 flex items-center gap-3 px-5 py-3 rounded-2xl border border-stone-200 bg-stone-50 text-stone-700 hover:border-amber-300 hover:bg-amber-50 transition-colors <?= $i === 0 ? 'active border-amber-700 bg-amber-700 text-white' : '' ?>">
                        <span class="w-9 h-9 rounded-xl <?= $i === 0 ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800' ?> flex items-center justify-center text-sm font-bold"><?= esc($icon) ?></span>
                        <span class="font-semibold whitespace-nowrap"><?= esc($group['name']) ?></span>
                    </button>
                <?php $i++; endforeach ?>
            </div>
        <?php endif ?>

        <div>
            <?php if (!empty($menuGroups)): ?>
                <?php $i = 0; foreach ($menuGroups as $group): ?>
                    <?php
                        $category = strtolower($group['name']);
                        $icon = str_contains($category, 'non') && str_contains($category, 'kopi')
                            ? 'NK'
                            : (str_contains($category, 'kopi') ? 'KP' : (str_contains($category, 'makan') ? 'MK' : 'MN'));
                        $availableCount = count(array_filter($group['products'], fn($p) => (int)$p['stock'] > 0));
                        $totalCount     = count($group['products']);
                        $minPrice       = $group['min_price'];
                    ?>
                    <div id="menuPanel<?= $i ?>" class="menu-panel <?= $i === 0 ? '' : 'hidden' ?>">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold">
                                    <?= esc($icon) ?>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-stone-900"><?= esc($group['name']) ?></h3>
                                    <p class="text-stone-500 text-sm"><?= $availableCount ?> dari <?= $totalCount ?> menu tersedia</p>
                                </div>
                            </div>
                            <p class="hidden sm:block text-amber-700 font-semibold whitespace-nowrap">Mulai Rp <?= number_format($minPrice, 0, ',', '.') ?></p>
                        </div>
                        <div class="relative">
                            <button
                                type="button"
                                onclick="scrollMenuCarousel(<?= $i ?>, -1)"
                                class="carousel-btn flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 sm:-translate-x-4 z-10 w-10 h-10 rounded-full bg-white border border-stone-200 shadow-md items-center justify-center text-stone-700 hover:bg-amber-50 hover:text-amber-700">
                                &lt;
                            </button>
                            <div id="menuCarousel<?= $i ?>" class="menu-carousel flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4 px-10 sm:px-0">
                                <?php $j = 0; foreach ($group['products'] as $product): ?>
                                    <?php $isAvailable = (int) $product['stock'] > 0; ?>
                                    <div class="menu-card snap-start shrink-0 w-64 sm:w-72 bg-stone-50 rounded-2xl border <?= $isAvailable ? 'border-stone-200' : 'border-red-100 opacity-70' ?> p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1" style="animation-delay: <?= $j * 60 ?>ms">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0">
                                                <h4 class="font-semibold text-stone-900 truncate"><?= esc($product['name']) ?></h4>
                                                <p class="text-sm <?= $isAvailable ? 'text-stone-500' : 'text-red-600' ?> mt-1">
                                                    <?= $isAvailable ? 'Stok tersedia' : 'Stok habis' ?>
                                                </p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $isAvailable ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                                <?= $isAvailable ? 'Ready' : 'Habis' ?>
                                            </span>
                                        </div>
                                        <div class="mt-8 flex items-end justify-between gap-3">
                                            <p class="text-amber-700 font-bold text-xl">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                                            <p class="text-xs text-stone-400">Stok <?= (int) $product['stock'] ?></p>
                                        </div>
                                    </div>
                                <?php $j++; endforeach ?>
                            </div>
                            <button
                                type="button"
                                onclick="scrollMenuCarousel(<?= $i ?>, 1)"
                                class="carousel-btn flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 sm:translate-x-4 z-10 w-10 h-10 rounded-full bg-white border border-stone-200 shadow-md items-center justify-center text-stone-700 hover:bg-amber-50 hover:text-amber-700">
                                &gt;
                            </button>
                        </div>
                    </div>
                <?php $i++; endforeach ?>
            <?php else: ?>
                <div class="bg-stone-50 rounded-2xl p-8 text-center border border-stone-200">
                    <h3 class="text-xl font-semibold text-stone-800 mb-2">Menu belum tersedia</h3>
                    <p class="text-stone-500">Silakan tambahkan produk dari dashboard admin/kasir.</p>
                </div>
            <?php endif ?>
        </div>
    </div>
</section>

<!-- Fasilitas Section -->
<section id="fasilitas" class="py-20 bg-stone-100">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-amber-700 font-medium"><?= esc($landingSettings['facilities_subtitle']) ?></span>
            <h2 class="text-3xl md:text-4xl font-bold text-stone-800 mt-2"><?= esc($landingSettings['facilities_title']) ?></h2>
        </div>

        <div class="grid md:grid-cols-4 gap-6">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">📶</span>
                </div>
                <h3 class="font-semibold text-stone-800 mb-2"><?= esc($landingSettings['facility_1_title']) ?></h3>
                <p class="text-stone-500 text-sm"><?= esc($landingSettings['facility_1_description']) ?></p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🪑</span>
                </div>
                <h3 class="font-semibold text-stone-800 mb-2"><?= esc($landingSettings['facility_2_title']) ?></h3>
                <p class="text-stone-500 text-sm"><?= esc($landingSettings['facility_2_description']) ?></p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🔌</span>
                </div>
                <h3 class="font-semibold text-stone-800 mb-2"><?= esc($landingSettings['facility_3_title']) ?></h3>
                <p class="text-stone-500 text-sm"><?= esc($landingSettings['facility_3_description']) ?></p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">💰</span>
                </div>
                <h3 class="font-semibold text-stone-800 mb-2"><?= esc($landingSettings['facility_4_title']) ?></h3>
                <p class="text-stone-500 text-sm"><?= esc($landingSettings['facility_4_description']) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Lokasi Section -->
<section id="lokasi" class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-amber-700 font-medium"><?= esc($landingSettings['location_subtitle']) ?></span>
            <h2 class="text-3xl md:text-4xl font-bold text-stone-800 mt-2"><?= esc($landingSettings['location_title']) ?></h2>
            <p class="text-stone-500 mt-4"><?= esc($landingSettings['location_description']) ?></p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 items-start">
            <!-- Map -->
            <div class="rounded-2xl overflow-hidden shadow-lg">
                <iframe
                    src="<?= esc($landingSettings['maps_embed_url']) ?>"
                    width="600"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <!-- Info -->
            <div class="space-y-6">
                <div class="bg-stone-50 rounded-2xl p-6">
                    <h3 class="font-semibold text-stone-800 mb-4 flex items-center gap-2">
                        <span>📍</span> Alamat Lengkap
                    </h3>
                    <p class="text-stone-600"><?= esc($landingSettings['business_name']) ?><br><?= esc($landingSettings['business_address']) ?></p>
                </div>

                <div class="bg-stone-50 rounded-2xl p-6">
                    <h3 class="font-semibold text-stone-800 mb-4 flex items-center gap-2">
                        <span>🕐</span> Jam Buka
                    </h3>
                    <ul class="text-stone-600 space-y-2">
                        <li>Senin - Jumat: <?= esc($landingSettings['weekday_open']) ?> - <?= esc($landingSettings['weekday_close']) ?></li>
                        <li>Sabtu - Minggu: <?= esc($landingSettings['weekend_open']) ?> - <?= esc($landingSettings['weekend_close']) ?></li>
                    </ul>
                </div>

                <a
                    href="<?= esc($landingSettings['maps_url']) ?>"
                    target="_blank"
                    class="inline-flex items-center gap-2 bg-amber-700 hover:bg-amber-800 text-white px-6 py-3 rounded-full font-medium transition-all"
                >
                    <span>🗺️</span>
                    Buka di Google Maps
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-stone-800 text-white py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-amber-700 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-lg"><?= esc(strtoupper(substr($landingSettings['business_name'], 0, 2))) ?></span>
                    </div>
                    <span class="font-semibold text-xl"><?= esc($landingSettings['business_name']) ?></span>
                </div>
                <p class="text-stone-400"><?= esc($landingSettings['footer_description']) ?></p>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Menu</h4>
                <ul class="space-y-2 text-stone-400">
                    <?php if (!empty($menuGroups)): ?>
                        <?php foreach ($menuGroups as $group): ?>
                            <li><?= esc($group['name']) ?></li>
                        <?php endforeach ?>
                    <?php else: ?>
                        <li>Menu belum tersedia</li>
                    <?php endif ?>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Hubungi Kami</h4>
                <ul class="space-y-2 text-stone-400">
                    <li><?= esc($landingSettings['business_phone']) ?></li>
                    <li><?= esc($landingSettings['business_email']) ?></li>
                    <li><?= esc($landingSettings['instagram']) ?></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-stone-700 pt-8 text-center text-stone-400">
            <p>&copy; <?= date('Y') ?> <?= esc($landingSettings['business_name']) ?>. Semua hak dilindungi.</p>
        </div>
    </div>
</footer>

<script>
    function showMenuCategory(index) {
        document.querySelectorAll('.menu-panel').forEach((panel, panelIndex) => {
            panel.classList.toggle('hidden', panelIndex !== index);
            if (panelIndex === index) {
                panel.style.animation = 'none';
                panel.offsetHeight;
                panel.style.animation = '';
            }
        });

        document.querySelectorAll('.menu-tab').forEach((tab, tabIndex) => {
            const icon = tab.querySelector('span');
            const isActive = tabIndex === index;

            tab.classList.toggle('active', isActive);
            tab.classList.toggle('border-amber-700', isActive);
            tab.classList.toggle('bg-amber-700', isActive);
            tab.classList.toggle('text-white', isActive);
            tab.classList.toggle('border-stone-200', !isActive);
            tab.classList.toggle('bg-stone-50', !isActive);
            tab.classList.toggle('text-stone-700', !isActive);

            if (icon) {
                icon.classList.toggle('bg-white/20', isActive);
                icon.classList.toggle('text-white', isActive);
                icon.classList.toggle('bg-amber-100', !isActive);
                icon.classList.toggle('text-amber-800', !isActive);
            }
        });
    }

    function scrollMenuCarousel(index, direction) {
        const carousel = document.getElementById(`menuCarousel${index}`);
        if (!carousel) {
            return;
        }

        const firstCard = carousel.querySelector('div');
        const gap = parseFloat(getComputedStyle(carousel).columnGap || getComputedStyle(carousel).gap || 16);
        const cardWidth = firstCard ? firstCard.getBoundingClientRect().width + gap : 288;

        carousel.scrollBy({
            left: direction * cardWidth,
            behavior: 'smooth'
        });

        const cards = Array.from(carousel.querySelectorAll('.menu-card'));
        const currentIndex = Math.round(carousel.scrollLeft / cardWidth);
        const nextIndex = Math.max(0, Math.min(cards.length - 1, currentIndex + direction));
        const nextCard = cards[nextIndex];

        if (nextCard) {
            nextCard.classList.remove('slide-focus');
            nextCard.offsetHeight;
            nextCard.classList.add('slide-focus');
        }
    }

    // Smooth scroll untuk navigation
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
</body>
</html>