<?php

namespace App\Models;

use CodeIgniter\Model;

class Product extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
 
    protected $allowedFields = [
        'category_id',
        'name',
        'price',
        'stock',
        'status',
    ];
 
    // Pengganti public $timestamps = false
    // Tabel products hanya punya created_at, tidak ada updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // protected $updatedField  = false;
    protected $updatedField = '';
 
    // -------------------------------------------------------
    // Pengganti belongsTo(Category::class)
    // -------------------------------------------------------
    public function findWithCategory(int $id): array|null
    {
        return $this->db->table('products')
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.id', $id)
            ->get()
            ->getRowArray();
    }
 
    public function getAllWithCategory(): array
    {
        return $this->db->table('products')
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->orderBy('categories.name')
            ->orderBy('products.name')
            ->get()
            ->getResultArray();
    }
 
    // -------------------------------------------------------
    // Pengganti hasMany(StockLog::class)
    // -------------------------------------------------------
    public function getStockLogs(int $productId): array
    {
        return $this->db->table('stock_logs')
            ->select('stock_logs.*, users.name as user_name')
            ->join('users', 'users.id = stock_logs.user_id', 'left')
            ->where('stock_logs.product_id', $productId)
            ->orderBy('stock_logs.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
 
    // -------------------------------------------------------
    // Helper umum untuk POS
    // -------------------------------------------------------
 
    // Ambil semua produk aktif (status = 1)
    public function getActiveProducts(): array
    {
        return $this->db->table('products')
            ->select('products.*, categories.name as category_name')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('products.status', 1)
            ->orderBy('categories.name')
            ->orderBy('products.name')
            ->get()
            ->getResultArray();
    }
 
    // Kurangi stok — dipakai saat transaksi penjualan
    public function decrementStock(int $productId, int $qty): bool
    {
        return $this->db->table('products')
            ->where('id', $productId)
            ->set('stock', "stock - {$qty}", false)
            ->update();
    }
 
    // Tambah stok — dipakai saat restock
    public function incrementStock(int $productId, int $qty): bool
    {
        return $this->db->table('products')
            ->where('id', $productId)
            ->set('stock', "stock + {$qty}", false)
            ->update();
    }
}