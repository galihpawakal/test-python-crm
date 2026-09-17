<?php
// Variables expected: $categories, $selected_id, $error_category_id, $error_new_category_name
$selected_id = $selected_id ?? old('category_id');
?>
<label class="form-label fw-semibold">Category</label>
<select name="category_id" id="categorySelect" class="form-select <?= !empty($error_category_id) ? 'is-invalid' : '' ?>" onchange="toggleNewCategoryInput()">
    <option value="">-- Choose Category --</option>
    <?php foreach ($categories as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $selected_id == $c['id'] ? 'selected' : '' ?>><?= esc($c['name']) ?></option>
    <?php endforeach; ?>
    <option value="new" class="text-primary fw-bold" <?= $selected_id === 'new' ? 'selected' : '' ?>>+ Add New Category</option>
</select>
<div class="invalid-feedback"><?= $error_category_id ?? '' ?></div>

<div id="newCategoryWrapper" class="mt-2" style="<?= $selected_id === 'new' ? '' : 'display: none;' ?>">
    <input type="text" name="new_category_name" id="newCategoryInput" class="form-control <?= !empty($error_new_category_name) ? 'is-invalid' : '' ?>" placeholder="Type new category name..." value="<?= old('new_category_name') ?>">
    <div class="invalid-feedback"><?= $error_new_category_name ?? '' ?></div>
</div>

<script>
    function toggleNewCategoryInput() {
        const select = document.getElementById('categorySelect');
        const wrapper = document.getElementById('newCategoryWrapper');
        const input = document.getElementById('newCategoryInput');
        
        if (select.value === 'new') {
            wrapper.style.display = 'block';
            input.focus();
        } else {
            wrapper.style.display = 'none';
        }
    }
</script>
