<?php namespace App\Models;

use CodeIgniter\Model;

class TelegramRecipientModel extends Model
{
    protected $table = 'telegram_recipients';
    protected $primaryKey = 'id_recipient';
    protected $allowedFields = ['chat_id', 'name', 'type', 'created_at'];
    protected $useTimestamps = false;
}