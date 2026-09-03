<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeliveryTimeToOrders extends Migration
{
    public function up()
    {
        $this->forge->addColumn('orders', [
            'delivery_time' => [
                'type'       => 'TIME',
                'null'       => false,
                'default'    => null,
                'after'      => 'delivery_date',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'delivery_time');
    }
}
