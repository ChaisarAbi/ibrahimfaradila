<?php namespace App\Models;

use CodeIgniter\Model;

class BoneMenuModel extends Model
{
    protected $table = 'bone_menus';
    protected $primaryKey = 'id_bone';
    protected $allowedFields = ['name'];
    protected $useTimestamps = false;
}