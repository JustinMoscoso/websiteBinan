<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News and Events</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<body>
<?php include "navbar.php"; ?>
<?php include "header.php"; ?>
<?php include_header('Biñan News',null,[
    'layout' => 'side',
    'bg_color' => '#388e3c']); ?>

<!-- CONTENT-TITLE -->
<div class="container mt-4" style="max-width: 800px;">
    <div class="content-title post-title">
        <?php if (isset($news_event) && !empty($news_event)): ?>
            <p style="font-size: 1.5rem; font-weight: 600;"><?= htmlspecialchars($news_event->title) ?></p>
            <h6>By <?= htmlspecialchars($news_event->author) ?></h6>
        <?php else: ?>
            <p>No news event content found.</p>
        <?php endif; ?>
    </div>
</div>

<div class="container" style="max-width: 800px;">
    <div class="post-title">
        <h6 class="text-muted"><?= date('F d, Y', strtotime($news_event->created_date)) ?></h6>
    </div>
</div>

<div class="page-content-wrap d-flex justify-content-center mb-4">
    <img src="<?= base_url('admin/image/POSTCONTENT/' . $news_event->file_loc) ?>" alt="News Image" class="img-fluid" style="max-width: 100%; max-height: 400px; object-fit: cover; border-radius: 10px;">
</div>

<!-- CONTENT -->
<div class="page-content-wrap py-4" style="position: relative;">
    <div class="container" style="max-width: 800px;">
        <div class="text-left" style="line-height: 1.6; font-size: 1rem;"><?= $news_event->description ?></div>
    </div>
</div>

<!-- Latest News -->
<section id="blog">
    <hr style="width:90%; margin: auto">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="relatedtitle mb-3">
                    <h5>Latest News & Events</h5>
                </div>
            </div>
        </div>
        <div data-aos="fade-up" class="tab-pane fade show active" role="tabpanel">
            <div class="row">
                <?php if (isset($news_events) && !empty($news_events)): ?>
                    <?php 
                    $filtered_news = array_filter($news_events, function($news) use ($news_event) {
                        return isset($news->ID) && isset($news_event->ID) && $news->ID != $news_event->ID;
                    });
                    $recent_news = array_slice($filtered_news, 0, 4);
                    ?>
                    <?php if (!empty($recent_news)): ?>
                        <?php foreach ($recent_news as $news): ?>
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 shadow-sm d-flex flex-column">
                                    <img src="<?= base_url('admin/image/POSTCONTENT/' . $news->file_loc) ?>" alt="News Image" style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px 10px 0 0;">
                                    <div class="card-body d-flex flex-column">
                                        <div class="small text-muted mb-2"><?= htmlspecialchars($news->category) ?> | <?= date('M d, Y', strtotime($news->created_date)) ?></div>
                                        <h6 class="card-title fw-bold"><?= htmlspecialchars($news->title) ?></h6>
                                        <p class="card-text flex-grow-1" style="font-size: 0.9rem;"><?= htmlspecialchars(substr(strip_tags($news->description), 0, 100)) ?>...</p>
                                        <a href="<?= base_url('/newseventscontent/' . $news->ID) ?>" class="btn btn-outline-primary btn-sm mt-auto">Read More</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-center text-muted">No other news available at the moment.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center text-muted">No news available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- SEE ALL Button under News (Centered) -->
        <div class="d-flex justify-content-center mt-3">
            <a id="seeAllBtnNews" href="<?= base_url('/newsevents/1') ?>" class="btn btn-outline-success fw-bold">SEE OTHER NEWS</a>
        </div>
    </div>
</section>

<?php include "footer.php"; ?>
<?php pre_scripts('home'); ?>
</body>
</html>
