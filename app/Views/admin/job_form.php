<h1><?= isset($job) ? 'Edit' : 'Add' ?> Job</h1>
<form method="post" action="<?= isset($job) ? site_url('adminjob/update/'.$job['id']) : site_url('adminjob/store') ?>">
    <input type="text" name="title" placeholder="Title" value="<?= isset($job) ? esc($job['title']) : '' ?>" required><br>
    <textarea name="description" placeholder="Description" required><?= isset($job) ? esc($job['description']) : '' ?></textarea><br>
    <input type="text" name="location" placeholder="Location" value="<?= isset($job) ? esc($job['location']) : '' ?>"><br>
    <input type="text" name="type" placeholder="Type" value="<?= isset($job) ? esc($job['type']) : '' ?>"><br>
    <label for="company" class="form-label">Company <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="company" name="company" value="<?= isset($job) ? esc($job['company']) : '' ?>" required>
    <select name="status">
        <option value="ACTIVE" <?= isset($job) && $job['status']=='ACTIVE' ? 'selected' : '' ?>>Active</option>
        <option value="INACTIVE" <?= isset($job) && $job['status']=='INACTIVE' ? 'selected' : '' ?>>Inactive</option>
    </select><br>
    <button type="submit">Save</button>
</form> 