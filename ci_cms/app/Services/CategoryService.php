<?php

namespace App\Services;

use App\Models\CategoryModel;

class CategoryService
{
    protected $categoryModel;

    public function __construct(CategoryModel $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    public function getAllCategories()
    {
        return $this->categoryModel->findAll();
    }

    public function findOrCreate(string $name)
    {
        $name = trim($name);
        if (empty($name)) {
            return null;
        }

        // Case-insensitive search (MySQL defaults to case-insensitive for VARCHAR depending on collation, but we can enforce it)
        $existing = $this->categoryModel->where('LOWER(name)', strtolower($name))->first();
        
        if ($existing) {
            return $existing['id'];
        }

        $this->categoryModel->insert(['name' => $name]);
        return $this->categoryModel->getInsertID();
    }
}
