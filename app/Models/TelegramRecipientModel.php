<?php namespace App\Models;

use CodeIgniter\Model;

class TelegramRecipientModel extends Model
{
    protected $table = 'telegram_recipients';
    protected $primaryKey = 'id_recipient';
    protected $allowedFields = ['chat_id', 'name', 'type', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = false;
}