<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;

class Home extends BaseController
{
    public function index(): string
    {
        // Fetch categories with their products
        $categoryModel = new Category();
        $productModel = new Product();
        
        $categories = $categoryModel->findAll();
        
        $menuGroups = [];
        foreach ($categories as $category) {
            $products = $productModel->where('category_id', $category['id'])->findAll();
            
            $minPrice = 0;
            if (!empty($products)) {
                $prices = array_map(fn($p) => (int)$p['price'] ?? 0, $products);
                $minPrice = min($prices);
            }
            
            $menuGroups[] = [
                'name' => $category['name'] ?? 'Menu',
                'products' => $products,
                'min_price' => $minPrice,
            ];
        }
        
        // Default landing settings
        $landingSettings = [
            'business_name' => 'Warkop Bakat',
            'hero_location' => '📍 Tapos, Depok',
            'hero_title' => 'Kopi Premium',
            'hero_highlight' => 'Kualitas Terbaik',
            'hero_description' => 'Menikmati kopi terbaik dengan suasana yang nyaman dan harga terjangkau untuk mahasiswa.',
            'menu_description' => 'Koleksi lengkap kopi specialty dan makanan ringan yang sempurna untuk menemani hari-hari Anda.',
            'facilities_subtitle' => 'Apa yang kami tawarkan',
            'facilities_title' => 'Fasilitas Kami',
            'facility_1_title' => 'WiFi Gratis',
            'facility_1_description' => 'Koneksi internet super cepat untuk belajar dan bekerja.',
            'facility_2_title' => 'Kursi Nyaman',
            'facility_2_description' => 'Kursi dan meja yang dirancang untuk kenyamanan maksimal.',
            'facility_3_title' => 'Stop Kontak',
            'facility_3_description' => 'Banyak stop kontak untuk mengisi daya perangkat Anda.',
            'facility_4_title' => 'Harga Terjangkau',
            'facility_4_description' => 'Harga special untuk mahasiswa dengan kualitas terjamin.',
            'location_subtitle' => 'Kunjungi kami',
            'location_title' => 'Lokasi Kami',
            'location_description' => 'Temukan kami di lokasi strategis dekat kampus dengan akses mudah.',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.0189904267226!2d106.81833507520477!3d-6.385556893696146!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ef43b7b7b7b7%3A0x1234567890abcdef!2sDepok!5e0!3m2!1sid!2sid!4v1234567890123',
            'maps_url' => 'https://maps.google.com',
            'business_address' => 'Jl. Merdeka No. 123, Tapos, Depok 16351',
            'business_phone' => '0812-3456-7890',
            'business_email' => 'info@warkopbakat.com',
            'instagram' => '@warkopbakat',
            'weekday_open' => '07:00',
            'weekday_close' => '22:00',
            'weekend_open' => '08:00',
            'weekend_close' => '23:00',
            'footer_description' => 'Warkop Bakat adalah tempat favorit mahasiswa untuk menikmati kopi berkualitas dengan suasana yang nyaman.',
        ];
        
        return view('home', [
            'menuGroups' => $menuGroups,
            'landingSettings' => $landingSettings,
        ]);
    }
}
