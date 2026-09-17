<?php

namespace App\Services;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ProductModel;

class TransactionService
{
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $productModel;

    public function __construct(
        TransactionModel $transactionModel,
        TransactionDetailModel $transactionDetailModel,
        ProductModel $productModel
    ) {
        $this->transactionModel = $transactionModel;
        $this->transactionDetailModel = $transactionDetailModel;
        $this->productModel = $productModel;
    }

    public function getAllTransactions()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transactions');
        $builder->select('transactions.*, users.name as user_name');
        $builder->join('users', 'users.user_id = transactions.user_id');
        $builder->orderBy('transactions.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function createPendingTransaction(array $data)
    {
        $items = $data['items'];
        $totalTransactionPrice = 0;

        // 1. Business Validation: Cek Stok sementara sebelum lanjut (Belum dipotong)
        $validatedItems = [];
        foreach ($items as $item) {
            $product = $this->productModel->find($item['product_id']);
            if (!$product) {
                return ['status' => false, 'message' => 'Product not found for ID: ' . $item['product_id']];
            }
            if ($product['qty_in_stock'] < $item['qty']) {
                return ['status' => false, 'message' => "Insufficient stock for {$product['product_name']}. Available: {$product['qty_in_stock']}."];
            }
            $itemSubtotal = $product['price'] * $item['qty'];
            $totalTransactionPrice += $itemSubtotal;
            
            $validatedItems[] = [
                'product_id' => $item['product_id'],
                'qty'        => $item['qty'],
                'price'      => $product['price'],
                'subtotal'   => $itemSubtotal,
            ];
        }

        // 2. Database Transaction Start
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insert Header
            $transactionId = $this->transactionModel->insert([
                'user_id'          => $data['user_id'],
                'transaction_code' => 'TRX-' . time() . '-' . rand(100, 999),
                'payment_method'   => $data['payment_method'],
                'total_price'      => $totalTransactionPrice,
                'status'           => 'pending',
                'date'             => date('Y-m-d H:i:s')
            ]);

            if (!$transactionId) {
                throw new \Exception('Failed to insert Header. Model Errors: ' . json_encode($this->transactionModel->errors()));
            }

            // Insert Details (Do NOT Deduct Stock Yet)
            foreach ($validatedItems as $vItem) {
                $detailInserted = $this->transactionDetailModel->insert([
                    'transaction_id' => $transactionId,
                    'product_id'     => $vItem['product_id'],
                    'qty'            => $vItem['qty'],
                    'unit_price'     => $vItem['price'],
                    'subtotal'       => $vItem['subtotal']
                ]);

                if (!$detailInserted) {
                    throw new \Exception('Failed to insert Detail. Model Errors: ' . json_encode($this->transactionDetailModel->errors()));
                }
            }

            // 3. Database Transaction Complete
            $db->transComplete();

            if ($db->transStatus() === false) {
                return ['status' => false, 'message' => 'Failed to create pending transaction.'];
            }

            return ['status' => true, 'transaction_id' => $transactionId];
            
        } catch (\Exception $e) {
            log_message('error', 'Transaction Error: ' . $e->getMessage());
            return ['status' => false, 'message' => 'System error: ' . $e->getMessage()];
        }
    }

    public function confirmPayment($transactionId, $isSuccess)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $transaction = $this->transactionModel->find($transactionId);
            if (!$transaction) {
                throw new \Exception("Transaction not found.");
            }

            if ($transaction['status'] !== 'processing' && $transaction['status'] !== 'pending') {
                return ['status' => false, 'message' => 'Transaction has already been processed.'];
            }

            if (!$isSuccess) {
                // Update to failed
                $this->transactionModel->update($transactionId, ['status' => 'failed']);
                $db->transComplete();
                return ['status' => true, 'message' => 'Payment failed updated.'];
            }

            // Update to paid
            $this->transactionModel->update($transactionId, [
                'status'  => 'paid',
                'paid_at' => date('Y-m-d H:i:s')
            ]);

            // Deduct stock
            $details = $this->transactionDetailModel->where('transaction_id', $transactionId)->findAll();
            foreach ($details as $detail) {
                $product = $this->productModel->find($detail['product_id']);
                
                // Final Stock Validation
                if ($product['qty_in_stock'] < $detail['qty']) {
                    throw new \Exception("Stock for {$product['product_name']} is no longer sufficient.");
                }

                $this->productModel->update($product['product_id'], [
                    'qty_in_stock' => $product['qty_in_stock'] - $detail['qty']
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return ['status' => false, 'message' => 'Database error during payment confirmation.'];
            }

            return ['status' => true, 'message' => 'Payment successful!'];

        } catch (\Exception $e) {
            $db->transRollback();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateStatus($transactionId, $status)
    {
        return $this->transactionModel->update($transactionId, ['status' => $status]);
    }


    public function getTransactionCount()
    {
        return $this->transactionModel->countAllResults();
    }
}
