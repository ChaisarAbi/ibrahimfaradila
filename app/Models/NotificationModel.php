<?php namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id_notif';
    protected $allowedFields = ['order_id', 'type', 'message', 'is_read'];
    protected $useTimestamps = false;
}