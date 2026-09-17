<div class="d-flex justify-content-end gap-2 mt-4 pt-2">
    <a href="<?= esc($cancel_link ?? 'javascript:history.back()') ?>" class="btn btn-light rounded-pill px-4 shadow-sm btn-elevate fw-semibold">Cancel</a>
    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm btn-elevate fw-semibold" onclick="this.innerHTML='<i class=\'bi bi-hourglass-split me-1\'></i> Processing...';">
        <i class="<?= esc($submit_icon ?? 'bi bi-check-lg') ?> me-1"></i> <?= esc($submit_text ?? 'Save Changes') ?>
    </button>
</div>
