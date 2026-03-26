<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectUnitModel extends Model
{
    protected $table            = 'project_units';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'company_id',
        'project_id',
        'unit_name',
        'bedrooms',
        'bathrooms',
        'area',
        'price',
        'description',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
