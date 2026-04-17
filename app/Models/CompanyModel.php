<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table            = 'companies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'company_name', 
        'logo', 
        'about', 
        'contact_number', 
        'email', 
        'address', 
        'status', 
        'password_hash',
        'is_deleted'
    ];

    // Override finding methods to filter by is_deleted
    public function findAll(?int $limit = null, int $offset = 0)
    {
        return $this->where('is_deleted', 0)->findAll($limit, $offset);
    }

    public function find($id = null)
    {
        return $this->where('is_deleted', 0)->find($id);
    }

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'company_name'   => 'required|min_length[3]|max_length[255]',
        'email'          => 'required|valid_email|is_unique[companies.email]',
        'password_hash'  => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
