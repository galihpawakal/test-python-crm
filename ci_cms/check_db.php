<?php
require 'public/index.php';
$db = \Config\Database::connect();
echo "TRANSACTIONS SCHEMA:\n";
print_r($db->getFieldNames('transactions'));
echo "TRANSACTION DETAILS SCHEMA:\n";
print_r($db->getFieldNames('transaction_details'));
