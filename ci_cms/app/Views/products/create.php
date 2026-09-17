<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-5">
                <h3 class="fw-bold mb-4">Add New Product</h3>
                
                <form action="/products/store" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Product Name</label>
                            <input type="text" name="product_name" class="form-control <?= session('errors.product_name') ? 'is-invalid' : '' ?>" value="<?= old('product_name') ?>">
                            <div class="invalid-feedback"><?= session('errors.product_name') ?></div>
                        </div>
                        <div class="col-md-6">
                            <?= view('components/category_dropdown', [
                                'categories' => $categories,
                                'selected_id' => old('category_id'),
                                'error_category_id' => session('errors.category_id'),
                                'error_new_category_name' => session('errors.new_category_name')
                            ]) ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?= old('description') ?></textarea>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Stock Quantity</label>
                            <input type="number" name="qty_in_stock" class="form-control <?= session('errors.qty_in_stock') ? 'is-invalid' : '' ?>" value="<?= old('qty_in_stock') ?>">
                            <div class="invalid-feedback"><?= session('errors.qty_in_stock') ?></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Price (Rp)</label>
                            <input type="number" name="price" class="form-control <?= session('errors.price') ? 'is-invalid' : '' ?>" step="1" value="<?= old('price') ?>">
                            <div class="invalid-feedback"><?= session('errors.price') ?></div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-semibold">Product Image (Upload or URL)</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="file" name="image" class="form-control <?= session('errors.image') ? 'is-invalid' : '' ?>" accept="image/*">
                                    <div class="invalid-feedback"><?= session('errors.image') ?></div>
                                    <small class="text-muted">Upload an image from your device</small>
                                </div>
                                <div class="col-md-6">
                                    <input type="url" name="image_url" class="form-control" placeholder="https://..." value="<?= old('image_url') ?>">
                                    <small class="text-muted">OR paste an image URL</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= view('components/form_actions', [
                        'cancel_link' => '/products',
                        'submit_text' => 'Save Product'
                    ]) ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
