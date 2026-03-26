<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectFieldValueModel extends Model
{
    protected $table            = 'project_field_values';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'project_id',
        'project_type_field_id',
        'value'
    ];

    protected $useTimestamps = false;
}
