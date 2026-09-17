<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?= view('components/page_header', [
    'title'      => 'Transaction History',
    'icon'       => 'bi-wallet-fill',
    'icon_color' => 'text-success',
    'btn_link'   => '/transactions/create',
    'btn_text'   => 'New Simulation'
]) ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Code</th>
                    <th>Date</th>
                    <th>Buyer</th>
                    <th>Payment</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No transactions found.</td></tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td class="ps-4 fw-semibold text-primary"><?= esc($t['transaction_code']) ?></td>
                        <td><?= date('d M Y, H:i', strtotime($t['date'])) ?></td>
                        <td><?= esc($t['user_name']) ?></td>
                        <td><?= esc($t['payment_method']) ?></td>
                        <td class="fw-bold">Rp <?= number_format($t['total_price'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($t['status'] == 'paid' || $t['status'] == 'Success'): ?>
                                <span class="badge bg-success rounded-pill px-3"><?= esc($t['status']) ?></span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3"><?= esc($t['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="/transactions/show/<?= $t['transaction_id'] ?>" class="btn btn-sm btn-outline-info shadow-sm me-1" title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/transactions/receipt/<?= $t['transaction_id'] ?>" target="_blank" class="btn btn-sm btn-outline-primary shadow-sm" title="Print Receipt">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
