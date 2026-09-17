<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?= view('components/page_header', [
    'title'      => 'Our Products',
    'icon'       => 'bi-box-seam',
    'icon_color' => 'text-primary',
    'btn_link'   => '/products/create',
    'btn_text'   => 'Add Product'
]) ?>

<?php if (empty($products)): ?>
    <div class="text-center py-5 text-muted">
        <h4 class="fw-light">No products available.</h4>
        <p>Start by adding some amazing products to your store!</p>
        <a href="/products/create" class="btn btn-outline-primary mt-2">Add First Product</a>
    </div>
<?php else: ?>
    <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
        <?php foreach ($products as $p): ?>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 border-0 card-product rounded-4 overflow-hidden">
                    <img src="<?= esc($p['image']) ?>" class="card-img-top" alt="<?= esc($p['product_name']) ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <span class="badge bg-info text-dark mb-2"><?= esc($p['category_name'] ?? 'Uncategorized') ?></span>
                        <h5 class="card-title fw-bold text-dark mb-1"><?= esc($p['product_name']) ?></h5>
                        <p class="text-muted small text-truncate mb-2"><?= esc($p['description']) ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fs-5 fw-bold text-primary">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
                            <span class="badge <?= $p['qty_in_stock'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                <?= $p['qty_in_stock'] ?> in stock
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 text-end pb-3">
                        <a href="/products/edit/<?= $p['product_id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                        <button onclick="confirmDelete('/products/delete/<?= $p['product_id'] ?>')" class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-1">Delete</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
