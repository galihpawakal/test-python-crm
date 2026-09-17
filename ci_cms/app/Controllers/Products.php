<?php

namespace App\Controllers;

use App\Services\ProductService;
use App\Services\CategoryService;
use App\Models\ProductModel;
use App\Models\CategoryModel;

class Products extends BaseController
{
    protected $productService;
    protected $categoryService;

    public function __construct()
    {
        // Dependency Injection Manual untuk Controller
        $this->productService = new ProductService(new ProductModel());
        $this->categoryService = new CategoryService(new CategoryModel());
    }

    public function index()
    {
        $data['products'] = $this->productService->getAllProductsWithCategory();
        return view('products/index', $data);
    }

    public function create()
    {
        $data['categories'] = $this->categoryService->getAllCategories();
        return view('products/create', $data);
    }

    public function store()
    {
        // HTTP Input Validation
        $rules = [
            'product_name' => 'required|min_length[3]|max_length[100]',
            'qty_in_stock' => 'required|is_natural',
            'price'        => 'required|numeric',
            'image'        => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        $categoryId = $this->request->getPost('category_id');
        if ($categoryId === 'new') {
            $rules['new_category_name'] = 'required|min_length[2]|max_length[100]';
        } else {
            $rules['category_id'] = 'required|is_natural_no_zero';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($categoryId === 'new') {
            $categoryId = $this->categoryService->findOrCreate($this->request->getPost('new_category_name'));
        }

        // Handle Image Upload
        $imageFile = $this->request->getFile('image');
        $imagePath = 'https://via.placeholder.com/300x200?text=No+Image'; // Default

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'uploads/products', $newName);
            $imagePath = '/uploads/products/' . $newName;
        } elseif ($this->request->getPost('image_url')) {
            $imagePath = $this->request->getPost('image_url');
        }

        // Pass to Service
        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'category_id'  => $categoryId,
            'description'  => $this->request->getPost('description'),
            'qty_in_stock' => $this->request->getPost('qty_in_stock'),
            'price'        => $this->request->getPost('price'),
            'image'        => $imagePath
        ];

        $this->productService->createProduct($data);
        
        return redirect()->to('/products')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $data['product'] = $this->productService->getProductById($id);
        if (!$data['product']) return redirect()->to('/products')->with('error', 'Product not found.');
        
        $data['categories'] = $this->categoryService->getAllCategories();
        return view('products/edit', $data);
    }

    public function update($id)
    {
        // HTTP Input Validation
        $rules = [
            'product_name' => 'required|min_length[3]|max_length[100]',
            'qty_in_stock' => 'required|is_natural',
            'price'        => 'required|numeric',
            'image'        => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        $categoryId = $this->request->getPost('category_id');
        if ($categoryId === 'new') {
            $rules['new_category_name'] = 'required|min_length[2]|max_length[100]';
        } else {
            $rules['category_id'] = 'required|is_natural_no_zero';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ($categoryId === 'new') {
            $categoryId = $this->categoryService->findOrCreate($this->request->getPost('new_category_name'));
        }

        // Handle Image Upload
        $imageFile = $this->request->getFile('image');
        $imagePath = $this->request->getPost('old_image');

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imageFile->move(FCPATH . 'uploads/products', $newName);
            $imagePath = '/uploads/products/' . $newName;

            // Delete old local file if replaced
            $oldImagePost = $this->request->getPost('old_image');
            if ($oldImagePost && strpos($oldImagePost, '/uploads/') === 0) {
                $oldPath = FCPATH . ltrim($oldImagePost, '/');
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        } elseif ($this->request->getPost('image_url')) {
            $imagePath = $this->request->getPost('image_url');
        }

        // Pass to Service
        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'category_id'  => $categoryId,
            'description'  => $this->request->getPost('description'),
            'qty_in_stock' => $this->request->getPost('qty_in_stock'),
            'price'        => $this->request->getPost('price'),
            'image'        => $imagePath
        ];

        $this->productService->updateProduct($id, $data);
        
        return redirect()->to('/products')->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        $this->productService->deleteProduct($id);
        return redirect()->to('/products')->with('success', 'Product deleted successfully.');
    }
}
