<?php namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'stocks';
    protected $primaryKey = 'id_stock';
    protected $allowedFields = ['item_name', 'category', 'quantity', 'min_threshold', 'unit'];
    protected $useTimestamps = false;
}