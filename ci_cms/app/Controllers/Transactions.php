<?php

namespace App\Controllers;

use App\Services\TransactionService;
use App\Services\UserService;
use App\Services\ProductService;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\UserModel;
use App\Models\ProductModel;

class Transactions extends BaseController
{
    protected $transactionService;
    protected $userService;
    protected $productService;

    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        // Dependency Injection
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        
        $this->transactionService = new TransactionService(
            $this->transactionModel,
            $this->transactionDetailModel,
            new ProductModel()
        );
        $this->userService = new UserService(new UserModel());
        $this->productService = new ProductService(new ProductModel());
    }

    public function index()
    {
        $data['transactions'] = $this->transactionService->getAllTransactions();
        return view('transactions/index', $data);
    }

    public function show($id)
    {
        $transaction = $this->transactionModel->find($id);
        if (!$transaction) return redirect()->to('/transactions')->with('error', 'Transaction not found');
        
        $db = \Config\Database::connect();
        
        // Get user info
        $user = $db->table('users')->where('user_id', $transaction['user_id'])->get()->getRowArray();
        $transaction['user_name'] = $user ? $user['name'] : 'Unknown';

        // Get details
        $builder = $db->table('transaction_details');
        $builder->select('transaction_details.*, products.product_name');
        $builder->join('products', 'products.product_id = transaction_details.product_id');
        $builder->where('transaction_details.transaction_id', $id);
        $details = $builder->get()->getResultArray();

        return view('transactions/show', [
            'transaction' => $transaction,
            'details'     => $details
        ]);
    }

    public function create()
    {
        $data['users'] = $this->userService->getAllUsers();
        $data['products'] = $this->productService->getAvailableProducts();
        return view('transactions/create', $data);
    }

    public function store()
    {
        $rules = [
            'user_id' => 'required|is_natural_no_zero',
            'product_id.*' => 'required|is_natural_no_zero',
            'qty.*' => 'required|is_natural_no_zero',
            'payment_method' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productIds = $this->request->getPost('product_id');
        $qtys = $this->request->getPost('qty');

        if (empty($productIds) || !is_array($productIds)) {
            return redirect()->back()->withInput()->with('error', 'You must add at least one product.');
        }

        $items = [];
        foreach ($productIds as $index => $pid) {
            $items[] = [
                'product_id' => $pid,
                'qty'        => $qtys[$index]
            ];
        }

        $data = [
            'user_id'        => $this->request->getPost('user_id'),
            'items'          => $items,
            'payment_method' => $this->request->getPost('payment_method')
        ];

        $result = $this->transactionService->createPendingTransaction($data);

        if (!$result['status']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        return redirect()->to('/transactions/processing/' . $result['transaction_id']);
    }

    public function processing($id)
    {
        $this->transactionService->updateStatus($id, 'processing');
        return view('transactions/processing', ['id' => $id]);
    }

    public function payment($id)
    {
        $transaction = $this->transactionModel->find($id);
        if (!$transaction) return redirect()->to('/transactions')->with('error', 'Transaction not found');
        
        $details = $this->transactionDetailModel->where('transaction_id', $id)->findAll();
        
        $data = [
            'transaction' => $transaction,
            'details'     => $details
        ];
        return view('transactions/payment', $data);
    }

    public function confirmPayment($id)
    {
        $isSuccess = $this->request->getPost('is_success') === '1';
        $result = $this->transactionService->confirmPayment($id, $isSuccess);

        if (!$result['status']) {
            // Stock deduction might have failed (insufficient stock)
            return redirect()->to("/transactions/result/{$id}")->with('error', $result['message']);
        }

        return redirect()->to("/transactions/result/{$id}");
    }

    public function result($id)
    {
        $transaction = $this->transactionModel->find($id);
        if (!$transaction) return redirect()->to('/transactions');
        
        return view('transactions/result', ['transaction' => $transaction]);
    }

    public function receipt($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transactions');
        $builder->select('transactions.*, users.name as user_name');
        $builder->join('users', 'users.user_id = transactions.user_id');
        $builder->where('transactions.transaction_id', $id);
        $transaction = $builder->get()->getRowArray();
        
        if (!$transaction) return redirect()->to('/transactions');
        
        $builder = $db->table('transaction_details');
        $builder->select('transaction_details.*, products.product_name');
        $builder->join('products', 'products.product_id = transaction_details.product_id');
        $builder->where('transaction_details.transaction_id', $id);
        $details = $builder->get()->getResultArray();
        
        return view('transactions/receipt', [
            'transaction' => $transaction,
            'details'     => $details
        ]);
    }
}
