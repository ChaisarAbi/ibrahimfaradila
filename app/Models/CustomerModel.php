<?php namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id_customer';
    protected $allowedFields = ['name', 'child_name', 'gender', 'birth_date', 'phone', 'address'];
    protected $useTimestamps = false;
}