<?php

namespace App\Models;

use CodeIgniter\Model;

class Category extends Model
{
    protected $table      = 'categories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
 
    protected $allowedFields = ['name'];
 
    // Tabel punya created_at tapi tidak ada updated_at
    // Laravel model set $timestamps = false — ikuti DB aslinya
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = false;
 
    // -------------------------------------------------------
    // Pengganti hasMany(Product::class)
    // -------------------------------------------------------
    public function getProducts(int $categoryId): array
    {
        return $this->db->table('products')
            ->where('category_id', $categoryId)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }
 
    public function getActiveProducts(int $categoryId): array
    {
        return $this->db->table('products')
            ->where('category_id', $categoryId)
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }
 
    // Ambil semua kategori beserta jumlah produknya
    public function getAllWithProductCount(): array
    {
        return $this->db->table('categories')
            ->select('categories.*, COUNT(products.id) as product_count')
            ->join('products', 'products.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->orderBy('categories.name', 'ASC')
            ->get()
            ->getResultArray();
    }
}