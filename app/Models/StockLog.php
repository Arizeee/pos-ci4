<?php

namespace App\Models;

use CodeIgniter\Model;

class StockLog extends Model
{
     protected $table      = 'stock_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
 
    protected $allowedFields = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'before_stock',
        'after_stock',
        'note',
        // Kolom 'qty' ada di DB tapi tampaknya duplikat dari 'quantity'
        // Tetap disertakan agar tidak error saat ada data lama
        'qty',
    ];
 
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
 
    // -------------------------------------------------------
    // Pengganti belongsTo(Product::class) + belongsTo(User::class)
    // CI4 tidak punya Eloquent relationship — join manual
    // -------------------------------------------------------
 
    public function findWithRelations(int $id): array|null
    {
        return $this->db->table('stock_logs')
            ->select('
                stock_logs.*,
                products.name  as product_name,
                products.stock as product_stock,
                users.name     as user_name,
                users.username as user_username
            ')
            ->join('products', 'products.id = stock_logs.product_id', 'left')
            ->join('users',    'users.id = stock_logs.user_id',       'left')
            ->where('stock_logs.id', $id)
            ->get()
            ->getRowArray();
    }
 
    public function getAllWithRelations(): array
    {
        return $this->db->table('stock_logs')
            ->select('
                stock_logs.*,
                products.name  as product_name,
                products.stock as product_stock,
                users.name     as user_name,
                users.username as user_username
            ')
            ->join('products', 'products.id = stock_logs.product_id', 'left')
            ->join('users',    'users.id = stock_logs.user_id',       'left')
            ->orderBy('stock_logs.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
 
    public function getByProduct(int $productId): array
    {
        return $this->db->table('stock_logs')
            ->select('
                stock_logs.*,
                users.name     as user_name,
                users.username as user_username
            ')
            ->join('users', 'users.id = stock_logs.user_id', 'left')
            ->where('stock_logs.product_id', $productId)
            ->orderBy('stock_logs.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
