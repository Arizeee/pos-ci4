<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;

class HomeController extends BaseController
{
 public function index()
    {
        $menuGroups      = [];
        $landingSettings = $this->landingSettings();
        $db              = Database::connect();
 
        if ($db->tableExists('products')) {
            $builder = $db->table('products')
                ->select('products.id, products.name, products.price, products.stock, categories.name as category_name')
                ->join('categories', 'categories.id = products.category_id', 'left')
                ->orderBy('categories.name')
                ->orderBy('products.name');
 
            if ($db->fieldExists('status', 'products')) {
                $builder->where('products.status', 1);
            }
 
            $products = $builder->get()->getResultArray();
 
            // Grouping manual — pengganti ->groupBy() Laravel Collection
            $grouped = [];
            foreach ($products as $product) {
                $categoryName = $product['category_name'] ?: 'Tanpa Kategori';
                $grouped[$categoryName][] = $product;
            }
 
            // Map — pengganti ->map() Laravel Collection
            foreach ($grouped as $categoryName => $items) {
                $prices = array_column($items, 'price');
 
                $menuGroups[] = [
                    'name'      => $categoryName,
                    'min_price' => (float) min($prices),
                    'products'  => $items,
                ];
            }
        }
 
        return view('landing', [
            'menuGroups'      => $menuGroups,
            'landingSettings' => $landingSettings,
        ]);
    }
 
    private function landingSettings(): array
    {
        $db       = Database::connect();
        $settings = [];
 
        if ($db->tableExists('owner_settings')) {
            $rows = $db->table('owner_settings')->get()->getResultArray();
 
            // Pengganti ->pluck('value', 'key')->toArray()
            foreach ($rows as $row) {
                $settings[$row['key']] = $row['value'];
            }
        }
 
        return array_merge([
            'business_name'          => 'Warkop POS',
            'business_address'       => 'Jl. Pendidikan No. 123, Dekat Kampus, Kota Pelajar',
            'business_phone'         => '+62 812-3456-7890',
            'business_email'         => 'info@warkoppos.com',
            'hero_location'          => 'Tapos, Depok',
            'hero_title'             => 'Nongkrong Nyaman',
            'hero_highlight'         => 'Kopi Enak',
            'hero_description'       => 'Warkop Pos adalah tempat nongkrong favorit mahasiswa. Nikmati berbagai menu kopi dan makanan dengan harga mahasiswa di tempat yang nyaman dan asik.',
            'menu_description'       => 'Geser tab kategori, lalu pilih kategori untuk melihat menu terbaru dari database.',
            'facilities_subtitle'    => 'Kenapa Warkop Pos?',
            'facilities_title'       => 'Fasilitas Kami',
            'facility_1_title'       => 'WiFi Gratis',
            'facility_1_description' => 'Koneksi cepat buat tugas dan nugas',
            'facility_2_title'       => 'Tempat Nyaman',
            'facility_2_description' => 'Kursi nyaman buat nongkrong',
            'facility_3_title'       => 'Colokan Banyak',
            'facility_3_description' => 'Bisa charge laptop dan HP',
            'facility_4_title'       => 'Harga Mahasiswa',
            'facility_4_description' => 'Terjangkau di kantong mahasiswa',
            'location_subtitle'      => 'Temukan Kami',
            'location_title'         => 'Lokasi Warkop Pos',
            'location_description'   => 'Parkiran luas, dan mudah dijangkau',
            'weekday_open'           => '08:00',
            'weekday_close'          => '23:00',
            'weekend_open'           => '09:00',
            'weekend_close'          => '00:00',
            'maps_url'               => 'https://maps.app.goo.gl/kZ5c7HtT4Ljeejdt9',
            'maps_embed_url'         => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d236.83781425737686!2d106.88016649203074!3d-6.402241092000892!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69eb004be11e3b%3A0x2881133719049df2!2sKedai%20Revis!5e1!3m2!1sid!2sid!4v1778100164000!5m2!1sid!2sid',
            'instagram'              => '@warkoppos',
            'footer_description'     => 'Tempat nongkrong nyaman dengan kopi enak dan harga terjangkau untuk mahasiswa.',
        ], $settings);
    }
}
