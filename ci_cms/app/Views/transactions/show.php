<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <?= view('components/page_header', [
            'title'      => 'Transaction Details',
            'icon'       => 'bi-info-circle',
            'icon_color' => 'text-info'
        ]) ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" data-aos="fade-up">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"># <?= esc($transaction['transaction_code']) ?></h5>
                <?php if ($transaction['status'] == 'paid' || $transaction['status'] == 'Success'): ?>
                    <span class="badge bg-success rounded-pill px-3 py-2"><?= esc($transaction['status']) ?></span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><?= esc($transaction['status']) ?></span>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <p class="text-muted small fw-semibold mb-1">Buyer Information</p>
                        <h6 class="fw-bold mb-0"><i class="bi bi-person text-primary me-2"></i><?= esc($transaction['user_name']) ?></h6>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <p class="text-muted small fw-semibold mb-1">Transaction Date</p>
                        <h6 class="fw-bold mb-0"><i class="bi bi-calendar3 text-primary me-2"></i><?= date('d M Y, H:i', strtotime($transaction['date'])) ?></h6>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <p class="text-muted small fw-semibold mb-1">Payment Method</p>
                        <h6 class="fw-bold mb-0"><i class="bi bi-credit-card text-primary me-2"></i><?= esc($transaction['payment_method']) ?></h6>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <p class="text-muted small fw-semibold mb-1">Payment Date</p>
                        <h6 class="fw-bold mb-0">
                            <?php if ($transaction['paid_at']): ?>
                                <i class="bi bi-clock-history text-success me-2"></i><?= date('d M Y, H:i', strtotime($transaction['paid_at'])) ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </h6>
                    </div>
                </div>

                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">Order Items</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product Name</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details as $item): ?>
                            <tr>
                                <td class="fw-semibold text-dark"><?= esc($item['product_name']) ?></td>
                                <td class="text-end text-muted">Rp <?= number_format($item['unit_price'], 0, ',', '.') ?></td>
                                <td class="text-center"><span class="badge bg-secondary rounded-pill"><?= $item['qty'] ?></span></td>
                                <td class="text-end fw-semibold">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="border-top-0">
                            <tr>
                                <td colspan="3" class="text-end fw-bold text-dark pt-4">Grand Total</td>
                                <td class="text-end fw-bold text-success fs-5 pt-4">Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="card-footer bg-light border-top-0 p-4 text-end">
                <a href="/transactions" class="btn btn-outline-secondary px-4 me-2"><i class="bi bi-arrow-left me-1"></i> Back to History</a>
                <a href="/transactions/receipt/<?= $transaction['transaction_id'] ?>" target="_blank" class="btn btn-primary px-4 fw-bold">
                    <i class="bi bi-printer me-1"></i> Print Receipt
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
