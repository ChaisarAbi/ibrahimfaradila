<?php namespace App\Models;

use CodeIgniter\Model;

class OrderDetailModel extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'id_detail';
    protected $allowedFields = ['order_id', 'bone_menu_id', 'meat_menu_id', 'box_type', 'jumlah_box'];
    protected $useTimestamps = false;
}