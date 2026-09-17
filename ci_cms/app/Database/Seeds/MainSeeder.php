<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // Seed Categories
        $categories = [
            ['name' => 'Elektronik'],
            ['name' => 'Pakaian'],
            ['name' => 'Makanan'],
        ];
        
        $this->db->table('categories')->ignore(true)->insertBatch($categories);
        
        // Seed Products if less than 10
        $productCount = $this->db->table('products')->countAllResults();
        
        if ($productCount < 10) {
            $products = [];
            $categoriesDb = $this->db->table('categories')->get()->getResultArray();
            $catIds = array_column($categoriesDb, 'id');
            
            if (!empty($catIds)) {
                for ($i = 1; $i <= 10; $i++) {
                    $products[] = [
                        'category_id'  => $catIds[array_rand($catIds)],
                        'product_name' => 'Dummy Product ' . $i,
                        'description'  => 'Ini adalah deskripsi untuk produk dummy ' . $i,
                        'qty_in_stock' => rand(10, 100),
                        'price'        => rand(100, 1000) * 1000,
                        'image'        => 'https://via.placeholder.com/300x200?text=Product+' . $i,
                        'created_at'   => date('Y-m-d H:i:s'),
                        'updated_at'   => date('Y-m-d H:i:s'),
                    ];
                }
                $this->db->table('products')->insertBatch($products);
            }
        }
    }
}
