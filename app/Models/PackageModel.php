<?php namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table = 'packages';
    protected $primaryKey = 'id_package';
    protected $allowedFields = ['name', 'weight_type', 'min_weight', 'max_weight', 'box_count', 'price', 'is_special'];
    protected $useTimestamps = false;
}