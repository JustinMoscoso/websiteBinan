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
    <style>
        .announcement-image-container {
            width: 300px;
            height: 200px;
            overflow: hidden;
            border-radius: 8px;
            flex-shrink: 0;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            position: relative;
        }
        
        .announcement-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
            transition: transform 0.3s ease;
        }
        
        .announcement-image-container:hover img {
            transform: scale(1.05);
        }
        
        .announcement-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }
        
        .announcement-card:hover {
            border-color: #28a745;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .announcement-content {
            padding: 20px;
            flex: 1;
        }
        
        .announcement-meta {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        
        .announcement-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 15px;
            line-height: 1.4;
        }
        
        .announcement-title a {
            color: #343a40;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .announcement-title a:hover {
            color: #28a745;
        }
        
        .announcement-description {
            color: #6c757d;
            font-size: 14px;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .announcement-image-container {
                width: 100%;
                height: 180px;
                margin-bottom: 15px;
            }
            
            .announcement-card {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include "navbar.php"; ?>
    <?php include "header.php"; ?>
    <?php include_header('Announcements',null,[
        'layout' => 'side',
        'bg_color' => '#388e3c']); ?>

    <!-- Search Bar Start -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <form action="<?= base_url('/announcements') ?>" method="get">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search announcements and authors..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <button class="btn btn-success" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Search Bar End -->

    <!-- Announcements List Start -->
    <div class="container my-5">
        <div class="row">
            <?php if (!empty($anns_cont)): ?>
                <?php foreach ($anns_cont as $ann): ?>
                    <div class="col-12 mb-4">
                        <div class="announcement-card d-md-flex">
                            <a href="<?= base_url('/announcementcontent/' . $ann->ID) ?>" class="announcement-image-container">
                                <img src="<?= base_url('admin/image/POSTCONTENT/' . $ann->file_loc) ?>" 
                                     alt="<?= htmlspecialchars($ann->title) ?>"
                                     loading="lazy"
                                     onerror="this.src='<?= base_url('path/to/default.jpg') ?>';" />
                            </a>
                            <div class="announcement-content">
                                <div class="announcement-meta">
                                    <span class="date">
                                        <?= date('M d \'y', strtotime($ann->created_date)) ?> |
                                        <span class="text-primary fw-medium">By <?= htmlspecialchars($ann->author) ?></span>
                                    </span>
                                </div>
                                <h3 class="announcement-title">
                                    <a href="<?= base_url('/announcementcontent/' . $ann->ID) ?>">
                                        <?= htmlspecialchars($ann->title) ?>
                                    </a>
                                </h3>
                                <p class="announcement-description mb-0">
                                    <?= htmlspecialchars(substr(strip_tags($ann->description), 0, 250)) ?>...
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <h4 class="text-muted">No announcements available.</h4>
                        <p class="text-muted">Check back later for updates.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pagination Start -->
        <div class="text-center py-4">
            <div class="custom-pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= base_url('/announcements/' . ($currentPage - 1)) ?>" class="prev">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= base_url('/announcements/' . $i) ?>" class="<?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= base_url('/announcements/' . ($currentPage + 1)) ?>" class="next">Next</a>
                <?php endif; ?>
            </div>
        </div>
        <!-- Pagination End -->
    </div>
    <!-- Announcements List End -->

    <?php include "footer.php"; ?>
    <?php pre_scripts('home'); ?>
</body>
</html>