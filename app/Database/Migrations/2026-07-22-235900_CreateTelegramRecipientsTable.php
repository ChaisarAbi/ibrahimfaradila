<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTelegramRecipientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_recipient' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'chat_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['personal', 'group'],
                'default'    => 'personal',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_recipient', true);
        $this->forge->createTable('telegram_recipients', true);
    }

    public function down()
    {
        $this->forge->dropTable('telegram_recipients', true);
    }
}