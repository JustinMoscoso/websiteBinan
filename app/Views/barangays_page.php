<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Barangays</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<body>
<?php include "navbar.php"; ?>
<?php include "header.php"; ?>
<?php include_header('Barangays', null, [
    'layout' => 'side',
    'bg_color' => '#388e3c']); ?>

<!-- Search Bar Start -->
<div class="container my-4">
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <form action="<?= base_url('/barangays') ?>" method="get">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search barangays..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                    <button class="btn btn-success" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Search Bar End -->

<!-- Page Content Start-->
<section id="departments" class="departments">
<div class="container py-5" style="margin-top: -90px;">
    <div class="row justify-content-center">
        <!--Echo from database-->
        <?php if (!empty($brgys)): ?>
            <?php
            // Sort barangays alphabetically by name
            usort($brgys, function ($a, $b) {
                return strcasecmp($a->brgy_name, $b->brgy_name);
            });
            ?>
            <?php foreach ($brgys as $data): ?>
                <div class='col-lg-4 col-md-6 d-flex align-items-stretch mt-4 justify-content-center' data-aos='zoom-in' data-aos-delay='200'>
                    <!--<a href="<?php /*= base_url('/barangaycontent/' . $data->ID) */?>" class="text-decoration-none" style="width: 100%;">-->
                    <a href="#" class="text-decoration-none" style="width: 100%;">
                        <div class='icon-box iconbox-blue border border-3 h-100 text-center' style='width: 100%; border-color: #388E3C;'>
                            <div class='icon'>
                                <img class='dept-logo img-fluid' src="<?= base_url('admin/image/BARANGAY/' . $data->img_logo) ?>" style="width: 100px; height: 100px;">
                                <i class='bx bx-arch'></i>
                            </div>
                            <h4 class="text-dark mt-3">
                                <?= htmlspecialchars($data->brgy_name) ?>
                            </h4>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Empty!</p>
        <?php endif; ?>
    </div>
</div>
</section>
<!-- Page Content End -->
<?php include "footer.php"; ?>
<?php pre_scripts('home'); ?>
</script>
</body>
</html>