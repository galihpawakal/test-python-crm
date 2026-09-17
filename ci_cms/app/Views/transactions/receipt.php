<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - <?= esc($transaction['transaction_code']) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&display=swap');
        
        body {
            background-color: #f0f0f0;
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .receipt {
            background-color: #fff;
            width: 300px; /* Thermal printer approx */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            position: relative;
        }

        .receipt::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 10px;
            background-image: radial-gradient(circle at 5px 10px, transparent 6px, #fff 6px);
            background-size: 10px 20px;
            background-repeat: repeat-x;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: 700; }
        
        .header h2 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0 0; font-size: 10px; }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 4px 0; vertical-align: top; }
        th { border-bottom: 1px dashed #000; padding-bottom: 5px; margin-bottom: 5px; }

        .total-row td { border-top: 1px dashed #000; padding-top: 5px; font-weight: bold; }
        
        .barcode {
            margin: 15px auto;
            text-align: center;
        }
        .barcode img { width: 100%; max-width: 200px; }

        @media print {
            body { background-color: #fff; padding: 0; display: block; margin: 0; }
            .receipt { box-shadow: none; width: 100%; max-width: 300px; margin: 0 auto; }
            .receipt::after { display: none; }
            .no-print { display: none; }
        }
        
        .print-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #0d6efd;
            color: white;
            text-align: center;
            border: none;
            cursor: pointer;
            font-family: sans-serif;
            font-weight: bold;
            margin-top: 20px;
            border-radius: 5px;
        }
        .print-btn:hover { background: #0b5ed7; }
    </style>
</head>
<body>

    <div>
        <div class="receipt">
            <div class="header text-center">
                <h2>CMS STORE</h2>
                <p>Jl. Jend. Sudirman No. 1<br>Jakarta Pusat, 10220</p>
                <p>Telp: (021) 12345678</p>
            </div>
            
            <div class="divider"></div>
            
            <table>
                <tr>
                    <td class="text-left">Code:</td>
                    <td class="text-right"><?= esc($transaction['transaction_code']) ?></td>
                </tr>
                <tr>
                    <td class="text-left">Date:</td>
                    <td class="text-right"><?= date('d M Y H:i', strtotime($transaction['date'])) ?></td>
                </tr>
                <tr>
                    <td class="text-left">Paid At:</td>
                    <td class="text-right"><?= $transaction['paid_at'] ? date('d M Y H:i', strtotime($transaction['paid_at'])) : '-' ?></td>
                </tr>
                <tr>
                    <td class="text-left">Cashier:</td>
                    <td class="text-right">SYS_AUTO</td>
                </tr>
                <tr>
                    <td class="text-left">Buyer:</td>
                    <td class="text-right"><?= esc($transaction['user_name']) ?></td>
                </tr>
            </table>

            <div class="divider"></div>

            <table>
                <tr>
                    <th class="text-left">Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Amt</th>
                </tr>
                <?php foreach ($details as $item): ?>
                <tr>
                    <td class="text-left">
                        <?= esc($item['product_name']) ?><br>
                        <small>@ Rp <?= number_format($item['unit_price'], 0, ',', '.') ?></small>
                    </td>
                    <td class="text-right"><?= $item['qty'] ?></td>
                    <td class="text-right">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
                
                <tr class="total-row">
                    <td colspan="2" class="text-left">GRAND TOTAL</td>
                    <td class="text-right">Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-left">PAYMENT (<?= esc($transaction['payment_method']) ?>)</td>
                    <td class="text-right">Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-left">STATUS</td>
                    <td class="text-right"><?= strtoupper($transaction['status']) ?></td>
                </tr>
            </table>

            <div class="barcode">
                <!-- Using a simple barcode generator API for realistic look -->
                <img src="https://barcodeapi.org/api/128/<?= esc($transaction['transaction_code']) ?>" alt="Barcode">
            </div>

            <div class="text-center" style="margin-top: 15px; font-size: 10px;">
                <p>Thank you for shopping with us!</p>
                <p>Goods sold are not returnable.</p>
            </div>
        </div>
        
        <button class="print-btn no-print" onclick="window.print()">
            🖨️ Print Receipt / Save as PDF
        </button>
    </div>

    <script>
        // Auto trigger print dialog when page loads
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
