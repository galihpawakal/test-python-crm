<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaidAtToTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ];
        
        $this->forge->addColumn('transactions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', 'paid_at');
    }
}
