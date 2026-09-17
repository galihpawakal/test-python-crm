<?php

namespace App\Services;

use App\Models\ProductModel;

class ProductService
{
    protected $productModel;

    public function __construct(ProductModel $productModel)
    {
        $this->productModel = $productModel;
    }

    public function getAllProductsWithCategory()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('products');
        $builder->select('products.*, categories.name as category_name');
        $builder->join('categories', 'categories.id = products.category_id', 'left');
        return $builder->get()->getResultArray();
    }
    
    public function getAvailableProducts()
    {
        return $this->productModel->where('qty_in_stock >', 0)->findAll();
    }

    public function getProductById($id)
    {
        return $this->productModel->find($id);
    }

    public function createProduct(array $data)
    {
        $data['image'] = empty($data['image']) ? 'https://via.placeholder.com/300x200?text=No+Image' : $data['image'];
        return $this->productModel->save($data);
    }

    public function updateProduct($id, array $data)
    {
        $data['image'] = empty($data['image']) ? 'https://via.placeholder.com/300x200?text=No+Image' : $data['image'];
        return $this->productModel->update($id, $data);
    }

    public function deleteProduct($id)
    {
        return $this->productModel->delete($id);
    }

    public function getProductCount()
    {
        return $this->productModel->countAllResults();
    }
}
