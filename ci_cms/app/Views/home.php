<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    /* Hero Gradient Background with subtle Blob */
    .hero-section {
        background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 100%);
        position: relative;
        overflow: hidden;
    }
    
    /* Subtle Blob Shape SVG Absolute */
    .hero-blob {
        position: absolute;
        top: -50px;
        right: -100px;
        width: 500px;
        opacity: 0.4;
        z-index: 0;
        pointer-events: none;
    }

    /* Content should be above absolute blob */
    .hero-content {
        position: relative;
        z-index: 1;
    }

    /* Button hover elevation */
    .btn-elevate {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-elevate:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
    }
    .btn-outline-elevate:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 20px rgba(25, 135, 84, 0.2);
    }

    /* Card Icon Circle */
    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto;
    }
    .icon-circle.primary { background-color: #e7f0ff; color: #0d6efd; }
    .icon-circle.success { background-color: #e8f9f0; color: #198754; }
    .icon-circle.info { background-color: #e0f7fa; color: #0dcaf0; }
</style>

<!-- Hero Section -->
<div class="hero-section p-5 mb-5 rounded-5 shadow-sm text-center border-0" data-aos="fade-up">
    <!-- Subtle Background SVG Blob -->
    <svg class="hero-blob" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
        <path fill="#0d6efd" d="M47.1,-60.8C61.4,-51.2,73.6,-38.3,80.1,-22.4C86.7,-6.6,87.7,12.3,81.3,29.3C75,46.3,61.3,61.3,44.9,71C28.4,80.6,9.1,84.9,-8.8,82.8C-26.7,80.7,-43.3,72.2,-55.8,59.3C-68.2,46.3,-76.6,28.9,-79.8,10.6C-83.1,-7.7,-81.2,-26.8,-71.4,-41.8C-61.6,-56.9,-44.1,-67.7,-27.9,-71.1C-11.7,-74.4,3.1,-70.4,17.1,-67.3Z" transform="translate(100 100)" />
    </svg>

    <div class="container-fluid py-5 hero-content">
        <!-- Pill Badge -->
        <div class="mb-4" data-aos="zoom-in" data-aos-delay="100">
            <span class="badge bg-white text-primary border border-primary px-3 py-2 rounded-pill shadow-sm fs-6">
                🎓 Simulasi Sistem Pembelian
            </span>
        </div>

        <!-- Big Vector Icon -->
        <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
            <i class="bi bi-cart-check text-primary" style="font-size: 5rem; drop-shadow: 0 4px 6px rgba(13,110,253,0.3);"></i>
        </div>

        <!-- Title -->
        <h1 class="display-4 fw-bolder text-dark mb-3" data-aos="fade-up" data-aos-delay="300">
            Welcome to <span class="text-primary">CMS Store</span>
        </h1>

        <p class="col-md-8 mx-auto fs-5 text-secondary mb-5" data-aos="fade-up" data-aos-delay="400">
            This is a modern Content Management System built with CodeIgniter 4 to simulate a product purchasing process. Manage your products, users, and transactions effortlessly.
        </p>
        
        <!-- Action Buttons -->
        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap" data-aos="fade-up" data-aos-delay="500">
            <a href="/products" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm btn-elevate fw-semibold">
                <i class="bi bi-box-seam me-2"></i> Manage Products
            </a>
            <a href="/transactions/create" class="btn btn-success text-white btn-lg rounded-pill px-5 shadow-sm btn-elevate fw-semibold">
                <i class="bi bi-credit-card me-2"></i> Simulate Purchase
            </a>
        </div>
    </div>
</div>

<!-- Feature Cards -->
<div class="row g-4 mt-2">
    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
        <a href="/products" class="text-decoration-none">
            <div class="card border-0 card-lift h-100 text-center p-5 bg-white">
                <div class="icon-circle primary mb-4 shadow-sm">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <h4 class="fw-bold text-dark">Products</h4>
                <p class="text-secondary mb-4">Easily add, edit, or remove products. Track your inventory and pricing in real-time.</p>
                <div class="mt-auto">
                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6 shadow-sm">
                        <?= number_format($product_count ?? 0) ?> Active Products
                    </span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <a href="/transactions" class="text-decoration-none">
            <div class="card border-0 card-lift h-100 text-center p-5 bg-white">
                <div class="icon-circle success mb-4 shadow-sm">
                    <i class="bi bi-wallet-fill"></i>
                </div>
                <h4 class="fw-bold text-dark">Transactions</h4>
                <p class="text-secondary mb-4">Simulate purchases with auto-calculating totals and automatic stock deductions.</p>
                <div class="mt-auto">
                    <span class="badge bg-success rounded-pill px-3 py-2 fs-6 shadow-sm">
                        <?= number_format($transaction_count ?? 0) ?> Total Logs
                    </span>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
        <a href="/users" class="text-decoration-none">
            <div class="card border-0 card-lift h-100 text-center p-5 bg-white">
                <div class="icon-circle info mb-4 shadow-sm">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h4 class="fw-bold text-dark">Buyers</h4>
                <p class="text-secondary mb-4">Manage the list of buyers registered in the system to assign transactions correctly.</p>
                <div class="mt-auto">
                    <span class="badge bg-info text-dark rounded-pill px-3 py-2 fs-6 shadow-sm">
                        <?= number_format($user_count ?? 0) ?> Registered Users
                    </span>
                </div>
            </div>
        </a>
    </div>
</div>
<?= $this->endSection() ?>
