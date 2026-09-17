<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterProductsTable extends Migration
{
    public function up()
    {
        $fields = [
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'product_id',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'product_name',
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'price',
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

        $this->forge->addColumn('products', $fields);

        // Add foreign key manually via query since CodeIgniter's Forge addForeignKey on existing table 
        // can be tricky depending on DB driver. MySQL handles it fine if we run it natively.
        $this->db->query('ALTER TABLE `products` ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `products` DROP FOREIGN KEY `fk_product_category`');
        $this->forge->dropColumn('products', ['category_id', 'description', 'image', 'created_at', 'updated_at']);
    }
}
