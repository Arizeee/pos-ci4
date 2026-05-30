<?php

namespace App\Models;

use CodeIgniter\Model;

class Order extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
 
    protected $allowedFields = [
        'code',
        'customer_name',
        'status',
        'item_count',
        'total',
        'created_by',
    ];
 
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
 
    // -------------------------------------------------------
    // Pengganti hasMany(OrderItem::class)
    // -------------------------------------------------------
    public function getItems(int $orderId): array
    {
        return $this->db->table('order_items')
            ->where('order_id', $orderId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }
 
    public function getItemsWithProduct(int $orderId): array
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
 
    // -------------------------------------------------------
    // Pengganti hasOne(Transaction::class)
    // -------------------------------------------------------
    public function getTransaction(int $orderId): array|null
    {
        return $this->db->table('transactions')
            ->where('order_id', $orderId)
            ->get()
            ->getRowArray();
    }
 
    // -------------------------------------------------------
    // Helper: ambil order lengkap beserta items + transaction
    // Pengganti Order::with(['items', 'transaction'])->find($id)
    // -------------------------------------------------------
    public function findComplete(int $orderId): array|null
    {
        $order = $this->find($orderId);
 
        if (!$order) {
            return null;
        }
 
        $order['items']       = $this->getItemsWithProduct($orderId);
        $order['transaction'] = $this->getTransaction($orderId);
 
        return $order;
    }
 
    // -------------------------------------------------------
    // Helper umum POS
    // -------------------------------------------------------
 
    // Ambil order beserta nama kasir yang membuat
    public function getAllWithKasir(): array
    {
        return $this->db->table('orders')
            ->select('orders.*, users.name as kasir_name')
            ->join('users', 'users.id = orders.created_by', 'left')
            ->orderBy('orders.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
 
    // Ambil order aktif (pending atau process)
    public function getActiveOrders(): array
    {
        return $this->db->table('orders')
            ->select('orders.*, users.name as kasir_name')
            ->join('users', 'users.id = orders.created_by', 'left')
            ->whereIn('orders.status', ['pending', 'process'])
            ->orderBy('orders.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
 
    // Generate kode order berikutnya, contoh: ORD-0001
    public function generateCode(): string
    {
        $last = $this->db->table('orders')
            ->select('code')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
 
        if (!$last) {
            return 'ORD-0001';
        }
 
        $number = (int) substr($last['code'], 4);
 
        return 'ORD-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
    }
}