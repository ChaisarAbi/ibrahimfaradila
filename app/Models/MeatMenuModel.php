<?php namespace App\Models;

use CodeIgniter\Model;

class MeatMenuModel extends Model
{
    protected $table = 'meat_menus';
    protected $primaryKey = 'id_meat';
    protected $allowedFields = ['name'];
    protected $useTimestamps = false;
}