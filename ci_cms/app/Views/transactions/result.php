<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-7">
        
        <!-- Step Indicator -->
        <div class="d-flex justify-content-between mb-4 px-4 position-relative" style="z-index: 1;">
            <div class="text-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;"><i class="bi bi-check-lg"></i></div>
                <small class="fw-bold text-success">1. Cart</small>
            </div>
            <div class="text-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;"><i class="bi bi-check-lg"></i></div>
                <small class="fw-bold text-success">2. Payment</small>
            </div>
            <div class="text-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;">3</div>
                <small class="fw-bold text-primary">3. Result</small>
            </div>
            <!-- Line connecting steps -->
            <div class="position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 4px; background: #e9ecef; z-index: -1;"></div>
            <div class="position-absolute top-50 start-0 translate-middle-y bg-success" style="height: 4px; width: 100%; z-index: -1;"></div>
        </div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" data-aos="zoom-in">
            <?php if ($transaction['status'] === 'paid'): ?>
                <!-- SUCCESS -->
                <div class="bg-success text-white text-center py-5">
                    <i class="bi bi-check-circle-fill display-1 mb-3"></i>
                    <h2 class="fw-bold mb-0">Payment Successful!</h2>
                </div>
                <div class="card-body p-5">
                    <div class="row mb-4 text-center">
                        <div class="col-6 border-end">
                            <p class="text-muted small mb-1">Transaction Code</p>
                            <h5 class="fw-bold text-dark"><?= esc($transaction['transaction_code']) ?></h5>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small mb-1">Total Paid</p>
                            <h5 class="fw-bold text-success">Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></h5>
                        </div>
                    </div>
                    
                    <p class="text-muted text-center mb-4">Your transaction has been processed and stock has been deducted. Thank you for your purchase!</p>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="/transactions" class="btn btn-outline-secondary px-4"><i class="bi bi-clock-history me-1"></i> Transaction History</a>
                        <a href="/transactions/receipt/<?= $transaction['transaction_id'] ?>" target="_blank" class="btn btn-primary px-4 fw-bold shadow-sm">
                            <i class="bi bi-printer me-1"></i> Print Receipt (PDF)
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- FAILED / OTHER -->
                <div class="bg-danger text-white text-center py-5">
                    <i class="bi bi-x-octagon-fill display-1 mb-3"></i>
                    <h2 class="fw-bold mb-0">Payment Failed</h2>
                </div>
                <div class="card-body p-5 text-center">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger"><?= session('error') ?></div>
                    <?php endif; ?>
                    
                    <p class="text-muted mb-4">Your payment could not be processed or was cancelled. Don't worry, no stock was deducted.</p>
                    
                    <a href="/transactions/payment/<?= $transaction['transaction_id'] ?>" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                        <i class="bi bi-arrow-repeat me-1"></i> Try Again
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
