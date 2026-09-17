<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h2>Add New User</h2>
<form action="/users/store" method="post">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <?= view('components/form_actions', [
        'cancel_link' => '/users',
        'submit_text' => 'Save User'
    ]) ?>
</form>
<?= $this->endSection() ?>
