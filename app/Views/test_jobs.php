<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jobs Test Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3>Jobs System Test</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger">
                                <strong>Error:</strong> <?= esc($error) ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <strong>Success:</strong> <?= esc($test_message) ?>
                            </div>
                            <div class="alert alert-info">
                                <strong>Database:</strong> <?= esc($table_exists) ?>
                            </div>
                        <?php endif; ?>
                        
                        <hr>
                        <h5>Next Steps:</h5>
                        <ol>
                            <li>If you see an error, check the error message above</li>
                            <li>If the jobs table doesn't exist, run the SQL script in phpMyAdmin</li>
                            <li>Once the table exists, try accessing <a href="<?= base_url('/jobs') ?>">/jobs</a></li>
                            <li>For admin access, go to <a href="<?= base_url('/admin/jobs') ?>">/admin/jobs</a></li>
                        </ol>
                        
                        <div class="mt-3">
                            <a href="<?= base_url('/jobs') ?>" class="btn btn-primary">Go to Jobs Page</a>
                            <a href="<?= base_url('/admin/jobs') ?>" class="btn btn-secondary">Go to Admin Jobs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 