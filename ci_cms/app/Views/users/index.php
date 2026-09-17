<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?= view('components/page_header', [
    'title'      => 'Registered Users (Buyers)',
    'icon'       => 'bi-people-fill',
    'icon_color' => 'text-info',
    'btn_link'   => '/users/create',
    'btn_text'   => 'Add User'
]) ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Name</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td class="ps-4 fw-semibold text-muted">#<?= $u['user_id'] ?></td>
                    <td class="fw-bold"><?= esc($u['name']) ?></td>
                    <td class="pe-4 text-end">
                        <a href="/users/edit/<?= $u['user_id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</a>
                        <button onclick="confirmDelete('/users/delete/<?= $u['user_id'] ?>')" class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-1">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
