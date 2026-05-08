<h1>Job Listings (Admin)</h1>
<a href="<?= site_url('adminjob/create') ?>">Add Job</a>
<table border="1">
    <tr>
        <th>Title</th><th>Company</th><th>Type</th><th>Status</th><th>Actions</th>
    </tr>
    <?php foreach($jobs as $job): ?>
    <tr>
        <td><?= esc($job['title']) ?></td>
        <td><?= esc($job['company']) ?></td>
        <td><?= esc($job['type']) ?></td>
        <td><?= esc($job['status']) ?></td>
        <td>
            <a href="<?= site_url('adminjob/edit/'.$job['id']) ?>">Edit</a>
            <a href="<?= site_url('adminjob/delete/'.$job['id']) ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table> 