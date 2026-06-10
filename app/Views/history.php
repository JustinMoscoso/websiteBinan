<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<body>
    <?php include "navbar.php"; ?>
    <?php include "header.php"; ?>
    <?php include_header('History',null,[
        'layout' => 'side',
        'bg_color' => '#388e3c',
        ]); ?>
        
    <div class="container py-5">
        <div class="row text-center text-white mb-3">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-4" style="color: #388E3C;">BIÑAN'S BEGINNINGS</h1>
                <p class="lead mb-0" style="color: #388E3C;">The History of Biñan</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7 mx-auto">
                <!-- Timeline -->
                <ul class="timeline">
                    <?php if (!empty($history_content)): ?>
                        <?php foreach ($history_content as $item): ?>
                            <li class="timeline-item border border-3 rounded ml-3 p-4 shadow">
                                <div class="timeline-arrow"></div>
                                <div class="row">
                                    <a class="col-lg-9 col-md-9" style="text-decoration: none; color: black;">
                                        <h2 class="mb-2 font-weight-bold" style="color: #004600; font-size: calc(1.35rem + 1vw);">
                                            <?= htmlspecialchars($item->title) ?>
                                        </h2>
                                        <div class="text-small mt-2 font-weight-light" style="text-align: justify;">
                                            <?= $item->description ?>
                                        </div>
                                    </a>
                                    <a class="col-lg-3 col-md-3" style="text-decoration: none; color: black;">
                                        <img class="img-fluid center" src="<?= base_url('admin/image/ABOUT/' . $item->about_img) ?>" width="300" height="700" />
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="timeline-item border border-3 rounded ml-3 p-4 shadow">
                            <div class="timeline-arrow"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 text-center">
                                    <p class="text-small mt-2 font-weight-light" style="text-align: center;">No history data available.</p>
                                </div>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul><!-- End -->
            </div>
        </div> <!-- Row End -->
    </div>

    <?php include "footer.php"; ?>
    <?php pre_scripts('home'); ?>
</body>
</html>