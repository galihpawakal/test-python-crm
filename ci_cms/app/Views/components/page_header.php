<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <h2 class="fw-bold text-dark">
        <?php if (!empty($icon)): ?>
            <i class="<?= esc($icon) ?> me-2 <?= esc($icon_color ?? 'text-primary') ?>"></i>
        <?php endif; ?>
        <?= esc($title) ?>
    </h2>
    <?php if (!empty($btn_link) && !empty($btn_text)): ?>
        <a href="<?= esc($btn_link) ?>" class="btn btn-primary rounded-pill px-4 shadow-sm btn-elevate fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> <?= esc($btn_text) ?>
        </a>
    <?php endif; ?>
</div>
