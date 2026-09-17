<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h2>Edit User</h2>
<form action="/users/update/<?= $user['user_id'] ?>" method="post">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>" required>
    </div>
    <?= view('components/form_actions', [
        'cancel_link' => '/users',
        'submit_text' => 'Update User',
        'submit_icon' => 'bi-pencil-square'
    ]) ?>
</form>
<?= $this->endSection() ?>
