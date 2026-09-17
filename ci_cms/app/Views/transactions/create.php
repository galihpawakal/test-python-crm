<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <?= view('components/page_header', [
            'title'      => 'Simulate Purchase',
            'icon'       => 'bi-cart-check',
            'icon_color' => 'text-success'
        ]) ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger rounded-3 shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= session('error') ?></div>
        <?php endif; ?>
        <?php if (session('errors')): ?>
            <div class="alert alert-danger rounded-3 shadow-sm">
                <ul class="mb-0">
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body p-5">
                <form action="/transactions/store" method="post" id="transactionForm">
                    
                    <!-- Section 1: Buyer Info -->
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-person-badge text-primary me-2"></i>1. Buyer Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Select Buyer</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-person text-muted"></i></span>
                                    <select name="user_id" class="form-select <?= session('errors.user_id') ? 'is-invalid' : '' ?>" required>
                                        <option value="">-- Choose Buyer --</option>
                                        <?php foreach ($users as $u): ?>
                                            <option value="<?= $u['user_id'] ?>" <?= old('user_id') == $u['user_id'] ? 'selected' : '' ?>><?= esc($u['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Product Cart -->
                    <div class="mb-5">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-box-seam text-primary me-2"></i>2. Product Selection</h5>
                        
                        <div id="cart-container">
                            <!-- Template Row (Hidden) -->
                            <div class="card border mb-3 cart-row template-row d-none bg-light">
                                <div class="card-body p-3">
                                    <div class="row align-items-center g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small fw-semibold text-muted mb-1">Product</label>
                                            <select name="product_id[]" class="form-select product-select" disabled>
                                                <option value="" data-price="0" data-stock="0">-- Select Product --</option>
                                                <?php foreach ($products as $p): ?>
                                                    <option value="<?= $p['product_id'] ?>" data-price="<?= $p['price'] ?>" data-stock="<?= $p['qty_in_stock'] ?>">
                                                        <?= esc($p['product_name']) ?> (Stock: <?= $p['qty_in_stock'] ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-semibold text-muted mb-1">Quantity</label>
                                            <input type="number" name="qty[]" class="form-control qty-input" min="1" value="1" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-semibold text-muted mb-1">Unit Price (Rp)</label>
                                            <input type="text" class="form-control price-input bg-light text-muted" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-semibold text-muted mb-1">Subtotal (Rp)</label>
                                            <input type="text" class="form-control subtotal-input fw-bold text-primary bg-white" readonly>
                                        </div>
                                        <div class="col-md-1 text-end mt-4">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row-btn" title="Remove Item"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-12 text-danger small stock-warning"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Initial Rows will be appended here via JS -->
                        </div>

                        <!-- Add Row Button -->
                        <button type="button" id="addRowBtn" class="btn btn-outline-primary w-100 border-dashed fw-semibold py-2 shadow-sm btn-elevate">
                            <i class="bi bi-plus-circle me-1"></i> Add Another Product
                        </button>
                    </div>

                    <!-- Section 3: Payment & Total -->
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-credit-card text-primary me-2"></i>3. Payment & Confirmation</h5>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Payment Method</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-wallet2 text-muted"></i></span>
                                    <select name="payment_method" class="form-select <?= session('errors.payment_method') ? 'is-invalid' : '' ?>" required>
                                        <option value="">-- Choose Method --</option>
                                        <option value="Credit Card" <?= old('payment_method') == 'Credit Card' ? 'selected' : '' ?>>Credit Card</option>
                                        <option value="PayPal" <?= old('payment_method') == 'PayPal' ? 'selected' : '' ?>>PayPal</option>
                                        <option value="Bank Transfer" <?= old('payment_method') == 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                        <option value="Cash" <?= old('payment_method') == 'Cash' ? 'selected' : '' ?>>Cash</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <h4 class="text-muted mb-0">Grand Total</h4>
                                <h2 class="fw-bold text-success mb-0" id="grandTotalDisplay">Rp 0</h2>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <?= view('components/form_actions', [
                        'cancel_link' => '/transactions',
                        'submit_text' => 'Confirm Purchase',
                        'submit_icon' => 'bi-cart-check'
                    ]) ?>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('cart-container');
    const template = document.querySelector('.template-row');
    const addBtn = document.getElementById('addRowBtn');
    const grandTotalDisplay = document.getElementById('grandTotalDisplay');

    // Add first row immediately
    addRow();

    addBtn.addEventListener('click', addRow);

    function addRow() {
        const newRow = template.cloneNode(true);
        newRow.classList.remove('template-row', 'd-none', 'bg-light');
        newRow.classList.add('active-row', 'bg-white');
        
        // Enable inputs
        newRow.querySelectorAll('select, input').forEach(el => el.disabled = false);
        
        // Event listeners
        const select = newRow.querySelector('.product-select');
        const qtyInput = newRow.querySelector('.qty-input');
        const removeBtn = newRow.querySelector('.remove-row-btn');

        select.addEventListener('change', () => {
            updateRowCalculations(newRow);
            updateGlobalDropdowns();
        });
        
        qtyInput.addEventListener('input', () => {
            updateRowCalculations(newRow);
        });

        removeBtn.addEventListener('click', () => {
            const activeRows = document.querySelectorAll('.active-row');
            if (activeRows.length > 1) {
                newRow.remove();
                calculateGrandTotal();
                updateGlobalDropdowns();
            } else {
                alert('You must have at least one product in the cart.');
            }
        });

        container.appendChild(newRow);
        updateGlobalDropdowns();
    }

    function updateRowCalculations(row) {
        const select = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const subtotalInput = row.querySelector('.subtotal-input');
        const warning = row.querySelector('.stock-warning');
        
        const option = select.options[select.selectedIndex];
        
        if (!option.value) {
            priceInput.value = '';
            subtotalInput.value = '';
            warning.innerText = '';
            calculateGrandTotal();
            return;
        }

        const price = parseFloat(option.dataset.price);
        const stock = parseInt(option.dataset.stock);
        let qty = parseInt(qtyInput.value) || 0;

        // Stock validation inline
        if (qty > stock) {
            warning.innerText = 'Warning: Quantity exceeds available stock (' + stock + ')';
            qtyInput.classList.add('is-invalid');
        } else {
            warning.innerText = '';
            qtyInput.classList.remove('is-invalid');
        }

        priceInput.value = price.toLocaleString('id-ID');
        subtotalInput.value = (price * qty).toLocaleString('id-ID');
        
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.active-row').forEach(row => {
            // Remove dots (thousands separators) before parsing float
            const subtotalStr = row.querySelector('.subtotal-input').value.replace(/\./g, '');
            const subtotal = parseFloat(subtotalStr) || 0;
            total += subtotal;
        });

        // Simple count up animation effect
        let currentDisplayed = parseFloat(grandTotalDisplay.innerText.replace('Rp', '').replace(/\./g, '').trim()) || 0;
        animateValue(grandTotalDisplay, currentDisplayed, total, 300);
    }

    function animateValue(obj, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = 'Rp ' + Math.round(progress * (end - start) + start).toLocaleString('id-ID');
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                // Ensure exact final number to avoid floating precision issues
                obj.innerHTML = 'Rp ' + Math.round(end).toLocaleString('id-ID');
            }
        };
        window.requestAnimationFrame(step);
    }

    function updateGlobalDropdowns() {
        // Collect all selected values
        const selectedValues = [];
        document.querySelectorAll('.active-row .product-select').forEach(select => {
            if (select.value) selectedValues.push(select.value);
        });

        // Apply disabled state to prevent duplicates
        document.querySelectorAll('.active-row .product-select').forEach(select => {
            Array.from(select.options).forEach(option => {
                if (option.value && option.value !== select.value && selectedValues.includes(option.value)) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        });

        // Hide remove button if only 1 row
        const rows = document.querySelectorAll('.active-row');
        rows.forEach(row => {
            row.querySelector('.remove-row-btn').style.display = rows.length === 1 ? 'none' : 'inline-block';
        });
    }
});
</script>
<?= $this->endSection() ?>
