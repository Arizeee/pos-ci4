<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItem extends Model
{
    protected $table      = 'order_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
 
    protected $allowedFields = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'subtotal',
    ];
 
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
 
    // -------------------------------------------------------
    // Pengganti belongsTo(Order::class)
    // -------------------------------------------------------
    public function findWithOrder(int $id): array|null
    {
        return $this->db->table('order_items')
            ->select('
                order_items.*,
                orders.code          as order_code,
                orders.customer_name as order_customer,
                orders.status        as order_status
            ')
            ->join('orders', 'orders.id = order_items.order_id', 'left')
            ->where('order_items.id', $id)
            ->get()
            ->getRowArray();
    }
 
    // -------------------------------------------------------
    // Pengganti belongsTo(Product::class)
    // -------------------------------------------------------
    public function findWithProduct(int $id): array|null
    {
        return $this->db->table('order_items')
            ->select('
                order_items.*,
                products.stock  as product_stock,
                products.status as product_status
            ')
            ->join('products', 'products.id = order_items.product_id', 'left')
            ->where('order_items.id', $id)
            ->get()
            ->getRowArray();
    }
 
    // -------------------------------------------------------
    // Helper umum
    // -------------------------------------------------------
 
    // Ambil semua item milik satu order (paling sering dipakai di POS)
    public function getByOrder(int $orderId): array
    {
        return $this->where('order_id', $orderId)
            ->orderBy('id', 'ASC')
            ->findAll();
    }
 
    // Ambil item beserta detail produk terkini untuk satu order
    public function getByOrderWithProduct(int $orderId): array
    {
        return $this->db->table('order_items')
            ->select('
                order_items.*,
                products.stock  as product_stock,
                products.status as product_status
            ')
            ->join('products', 'products.id = order_items.product_id', 'left')
            ->where('order_items.order_id', $orderId)
            ->orderBy('order_items.id', 'ASC')
            ->get()
            ->getResultArray();
    }
}
