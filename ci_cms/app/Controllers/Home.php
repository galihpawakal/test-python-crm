<?php

namespace App\Controllers;

use App\Services\ProductService;
use App\Services\TransactionService;
use App\Services\UserService;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\UserModel;

class Home extends BaseController
{
    public function index(): string
    {
        $productService = new ProductService(new ProductModel());
        $transactionService = new TransactionService(new TransactionModel(), new TransactionDetailModel(), new ProductModel());
        $userService = new UserService(new UserModel());

        $data['product_count'] = $productService->getProductCount();
        $data['transaction_count'] = $transactionService->getTransactionCount();
        $data['user_count'] = $userService->getUserCount();

        return view('home', $data);
    }
}
