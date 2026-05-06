<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission & Vision</title>
    <!-- Favicons -->
    <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="icon">
    <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>

<body>
<?php include "navbar.php"; ?>
<?php include "header.php"; ?>
<?php include_header('Mission & Vision', null, [
    'layout' => 'side',
    'bg_color' => '#388e3c']); ?>


<!-- Page Content Start-->
<section data-aos="fade-up" id="about" class="my-3">
    <div class="container-fluid px-4">
        <div class="row rounded">
            <div class="row d-flex">
                <section id="about-counter" class="about-counter section-bg">
                    <div class="container" style="padding-bottom: 20px">
                        <div class="row gy-4 justify-content-center">
                            <?php if (!empty($content_sections)): ?>
                                <?php foreach ($content_sections as $section): ?>
                                    <div class="col-lg-12 col-md-6">
                                        <div class="aboutbox border border-3" style="border-color: #004600 !important;">
                                            <h2 class="text-left" style="color: #388E3C;">
                                                <strong><?= htmlspecialchars($section->title) ?></strong>
                                            </h2>
                                            <div class="text-left">
                                                <?= $section->description ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-lg-12 col-md-6">
                                    <div class="aboutbox border border-3" style="border-color: #004600 !important;">
                                        <h2 class="text-left">
                                            <strong>No Content Available</strong>
                                        </h2>
                                        <p class="text-left">
                                            No additional content has been provided for this section.
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

<!-- Page Content End -->
<!-- Footer -->
<?php include "footer.php"; ?>
<?php pre_scripts('home'); ?>
</body>
</html>