<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center" data-aos="zoom-in">
    <div class="col-md-6 text-center py-5 mt-5">
        <div class="card border-0 shadow-lg rounded-4 p-5">
            <div class="spinner-border text-primary mx-auto mb-4" role="status" style="width: 4rem; height: 4rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h3 class="fw-bold text-dark mb-3">Processing Payment...</h3>
            <p class="text-muted fs-5">Please wait while we connect to the payment gateway securely.</p>
            <p class="text-primary small mt-3"><i class="bi bi-shield-lock-fill me-1"></i> Securing your transaction</p>
        </div>
    </div>
</div>

<script>
    // Simulate payment gateway connection delay (3 seconds)
    setTimeout(() => {
        window.location.href = "/transactions/payment/<?= esc($id) ?>";
    }, 3000);
</script>
<?= $this->endSection() ?>
