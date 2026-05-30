<?php

namespace App\Models;

use CodeIgniter\Model;

class Role extends Model
{
    protected $table      = 'roles';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
 
    // Pengganti PHP Attribute #[Fillable(['name'])]
    protected $allowedFields = ['name'];
 
    // Tabel roles tidak punya kolom timestamps
    protected $useTimestamps = false;
 
    // -------------------------------------------------------
    // Pengganti hasMany(User::class)
    // CI4 tidak punya Eloquent relationship — join manual
    // -------------------------------------------------------
 
    public function getUsersByRole(int $roleId): array
    {
        return $this->db->table('users')
            ->select('users.*')
            ->where('users.role_id', $roleId)
            ->get()
            ->getResultArray();
    }
 
    public function getAllWithUserCount(): array
    {
        return $this->db->table('roles')
            ->select('roles.*, COUNT(users.id) as user_count')
            ->join('users', 'users.role_id = roles.id', 'left')
            ->groupBy('roles.id')
            ->get()
            ->getResultArray();
    }
}
 
