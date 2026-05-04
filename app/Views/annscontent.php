<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Announcements</title>
     <!-- Favicons -->
     <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<body>
    <?php include "navbar.php"; ?>
    <?php include "header.php"; ?>
    <?php include_header('Biñan Announcement',null,[
        'layout' => 'side',
        'bg_color' => '#388e3c']); ?>
   <!--CONTENT-TITLE -->
<div class="container mt-5">
    <div class="content-title post-title">
        <?php if (isset($anns) && !empty($anns)): ?>
            <p><?= htmlspecialchars($anns->title) ?></p>
            <h5>By <?= htmlspecialchars($anns->author) ?></h5>
        <?php else: ?>
            <p>No announcement content found.</p>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <div class="post-title">
        <h2><?= date('F d, Y', strtotime($anns->created_date)) ?></h2>
    </div>
</div>

<div class="page-content-wrap d-flex justify-content-center">
    <img src="<?= base_url('admin/image/POSTCONTENT/' . $anns->file_loc) ?>" alt="" class="img-fluid" style="max-width: 70%;">
</div>

<!--CONTENT -->
<div class="page-content-wrap py-5" style="position: relative;">
    <div class="container" style="width: 98%; display: flex; justify-content: center; align-items: center;">
        <div class="text-left" style="line-height: 1.8 !important"><?= $anns->description ?></div>
    </div>
</div>

<!-- Latest Announcements -->
<section id="announcements">
    <hr style="width:90%; margin: auto">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="relatedtitle mb-3">
                    <h5>Latest Announcements</h5>
                </div>
            </div>
        </div>
        <div data-aos="fade-up" class="tab-pane fade show active" role="tabpanel">
            <div class="row">
                <?php if (!empty($announcements)): ?>
                    <?php foreach (array_slice($announcements, 0, 3) as $announcement): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border border-3" style="border-color: #388e3c !important;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($announcement->title) ?></h5>
                                    <p class="card-text flex-grow-1"><?= htmlspecialchars(substr(strip_tags($announcement->description), 0, 80)) ?>...</p>
                                    <a href="<?= base_url('/announcementcontent/' . $announcement->ID) ?>" class="btn btn-outline-success btn-sm mt-auto">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center text-muted">No announcements available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- SEE ALL Button under Announcements (Centered) -->
        <div class="d-flex justify-content-center mt-3">
            <a id="seeAllBtnAnnouncements" href="<?= base_url('/announcements/1') ?>" class="btn btn-outline-success fw-bold">SEE OTHER ANNOUCEMENTS</a>
        </div>
    </div>
</section>


    <?php include "footer.php"; ?>
    <?php pre_scripts('home'); ?>
</body>
</html>