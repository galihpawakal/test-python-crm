<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transaction_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'product_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'qty' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'unit_price' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'subtotal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('transaction_id', 'transactions', 'transaction_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'product_id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('transaction_details');

        // Migrate existing data from transactions to transaction_details
        $db = \Config\Database::connect();
        $transactions = $db->table('transactions')->get()->getResult();
        foreach ($transactions as $t) {
            // Get product price
            $product = $db->table('products')->where('product_id', $t->product_id)->get()->getRow();
            $price = $product ? $product->price : 0;
            $subtotal = $price * $t->qty;

            $db->table('transaction_details')->insert([
                'transaction_id' => $t->transaction_id,
                'product_id'     => $t->product_id,
                'qty'            => $t->qty,
                'unit_price'     => $price,
                'subtotal'       => $subtotal,
            ]);

            // Update total price on transactions
            $db->table('transactions')->where('transaction_id', $t->transaction_id)->update(['total_price' => $subtotal]);
        }

        // Drop old foreign key & columns from transactions table
        $this->db->query('ALTER TABLE `transactions` DROP FOREIGN KEY `transactions_product_id_foreign`');
        $this->forge->dropColumn('transactions', ['product_id', 'qty']);
    }

    public function down()
    {
        // Re-add columns to transactions
        $fields = [
            'product_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'qty' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('transactions', $fields);

        // This is simplified down method, we drop the details table
        $this->forge->dropTable('transaction_details');
    }
}
