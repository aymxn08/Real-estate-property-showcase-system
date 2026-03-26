<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectTypeFieldModel extends Model
{
    protected $table            = 'project_type_fields';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'project_type_id', 
        'field_name', 
        'field_type', 
        'is_mandatory', 
        'options_json'
    ];
}
