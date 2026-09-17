<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTransactionsTable extends Migration
{
    public function up()
    {
        // 1. Tambah kolom baru di transactions
        $fields = [
            'transaction_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'transaction_id',
            ],
            'total_price' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'payment_method',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Success',
                'after'      => 'total_price',
            ],
            'date' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'status',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('transactions', $fields);

        // Beri default value pada baris lama
        $this->db->query("UPDATE transactions SET transaction_code = CONCAT('TRX-', transaction_id, '-', UNIX_TIMESTAMP()) WHERE transaction_code IS NULL");
        $this->db->query("UPDATE transactions SET date = CURRENT_TIMESTAMP WHERE date IS NULL");

        // Set NOT NULL jika diperlukan, tapi kita biarkan nullable untuk mempermudah jika butuh rollback
    }

    public function down()
    {
        $this->forge->dropColumn('transactions', ['transaction_code', 'total_price', 'status', 'date', 'created_at', 'updated_at']);
    }
}
