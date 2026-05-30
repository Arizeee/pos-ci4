<?php

namespace App\Models;

use CodeIgniter\Model;

class Transaction extends Model
{
    
    protected $table      = 'transactions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
 
    protected $allowedFields = [
        'invoice_code',
        'user_id',
        'payment_method_id',
        'total',
        'payment',
        'change_amount',
    ];
 
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = false;
 
    public function findWithOrder(int $id): array|null
    {
        return $this->db->table('transactions')
            ->select('
                transactions.*,
                orders.code        as order_code,
                orders.customer_name,
                orders.status      as order_status,
                orders.item_count
            ')
            ->join('orders', 'orders.id = transactions.order_id', 'left')
            ->where('transactions.id', $id)
            ->get()
            ->getRowArray();
    }
 
    public function getAllWithOrder(): array
    {
        return $this->db->table('transactions')
            ->select('
                transactions.*,
                orders.code        as order_code,
                orders.customer_name,
                orders.status      as order_status,
                orders.item_count
            ')
            ->join('orders', 'orders.id = transactions.order_id', 'left')
            ->orderBy('transactions.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }
}
