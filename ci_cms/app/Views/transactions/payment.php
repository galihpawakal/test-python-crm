<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <!-- Step Indicator -->
        <div class="d-flex justify-content-between mb-4 px-4 position-relative" style="z-index: 1;">
            <div class="text-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;"><i class="bi bi-check-lg"></i></div>
                <small class="fw-bold text-success">1. Cart</small>
            </div>
            <div class="text-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;">2</div>
                <small class="fw-bold text-primary">2. Payment</small>
            </div>
            <div class="text-center opacity-50">
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 40px; height: 40px;">3</div>
                <small class="fw-bold">3. Result</small>
            </div>
            <!-- Line connecting steps -->
            <div class="position-absolute top-50 start-0 w-100 translate-middle-y" style="height: 4px; background: #e9ecef; z-index: -1;"></div>
            <div class="position-absolute top-50 start-0 translate-middle-y bg-primary" style="height: 4px; width: 50%; z-index: -1; transition: width 0.5s ease;"></div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-5 text-center">
                <h3 class="fw-bold mb-1">Complete Your Payment</h3>
                <p class="text-muted">Transaction ID: <span class="fw-semibold"><?= esc($transaction['transaction_code']) ?></span></p>
                <h1 class="text-success fw-bold display-5 mb-0">Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></h1>
            </div>
            
            <div class="card-body p-5">
                <form action="/transactions/confirmPayment/<?= $transaction['transaction_id'] ?>" method="post">
                    
                    <?php if ($transaction['payment_method'] === 'Bank Transfer'): ?>
                        <!-- BANK TRANSFER SIMULATION -->
                        <div class="text-center mb-5 bg-light p-4 rounded-4 border">
                            <i class="bi bi-bank text-primary display-4 mb-3 d-block"></i>
                            <h5 class="text-muted mb-2">Virtual Account Number</h5>
                            <h2 class="fw-bold text-dark letter-spacing-2 font-monospace mb-4">8808-<?= mt_rand(1000, 9999) ?>-<?= mt_rand(1000, 9999) ?></h2>
                            <p class="small text-muted mb-0">Please transfer exactly <strong class="text-dark">Rp <?= number_format($transaction['total_price'], 0, ',', '.') ?></strong> to the above Virtual Account.</p>
                        </div>

                    <?php elseif ($transaction['payment_method'] === 'Credit Card'): ?>
                        <!-- CREDIT CARD SIMULATION -->
                        <div class="bg-light p-4 rounded-4 border mb-5">
                            <h5 class="fw-bold mb-3"><i class="bi bi-credit-card-2-front text-primary me-2"></i>Enter Card Details</h5>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-muted">Card Number</label>
                                <input type="text" class="form-control form-control-lg" placeholder="XXXX XXXX XXXX XXXX" required>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label small fw-semibold text-muted">Expiry Date</label>
                                    <input type="text" class="form-control" placeholder="MM/YY" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-semibold text-muted">CVV</label>
                                    <input type="password" class="form-control" placeholder="•••" required>
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" height="20" class="me-2 opacity-75">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20" class="opacity-75">
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- QRIS / E-WALLET / CASH SIMULATION -->
                        <div class="text-center mb-5 bg-light p-4 rounded-4 border">
                            <i class="bi bi-qr-code-scan text-primary display-4 mb-3 d-block"></i>
                            <h5 class="text-muted mb-3">Scan to Pay (<?= esc($transaction['payment_method']) ?>)</h5>
                            <!-- Dummy QR Code -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= esc($transaction['transaction_code']) ?>" alt="QR Code" class="img-fluid rounded mb-3 shadow-sm border p-2 bg-white">
                            <p class="small text-muted mb-0">Scan this QR code with your payment app.</p>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-3 justify-content-center">
                        <input type="hidden" name="is_success" id="paymentStatus" value="1">
                        
                        <button type="submit" class="btn btn-danger px-4 py-2" onclick="document.getElementById('paymentStatus').value='0'">
                            <i class="bi bi-x-circle me-1"></i> Simulate Failure
                        </button>
                        
                        <button type="submit" class="btn btn-success px-5 py-2 fw-bold" onclick="document.getElementById('paymentStatus').value='1'">
                            <i class="bi bi-check-circle me-1"></i> I Have Paid
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.letter-spacing-2 { letter-spacing: 2px; }
</style>
<?= $this->endSection() ?>
