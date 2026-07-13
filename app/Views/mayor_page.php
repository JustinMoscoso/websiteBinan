<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mayor's Corner</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<body>
    <?php include "navbar.php"; ?>
    <?php include "header.php"; ?>
    <?php include_header('Mayor',null,[
        'layout' => 'side',
        'bg_color' => '#388e3c']); ?>

    <!-- ======= Mayor Section ======= -->
    <section id="mayor" class="mayor">
        <div class="container">
            <div class="content-title mayorsection-title">
                <h3>
                    <?php 
                    $mayor_name = 'Mayor.';
                    if (isset($mayor)) {
                        foreach ($mayor as $data) {
                            if ($data->section == 'Personal Data') {
                                $mayor_name = $data->mayor_name;
                                break;
                            }
                        }
                    }
                    echo $mayor_name;
                    ?>
                </h3>
                <p>Biñan City Mayor</p>
            </div>
        </div>
        <div class="container" data-aos="fade-up">
            <div class="mayorsection-title">
                <h2>Mayor's Corner</h2>
            </div>
            <div class="row content">
        <div class="col-lg-6">
            <div class="img">
                <?php 
                $personal_data_content = '';
                $personal_data_img = base_url('assets/img/mayor-silhouette.svg');
                $personal_data_img_alt = 'Mayor silhouette placeholder';

                if (isset($mayor)) {
                    foreach ($mayor as $data) {
                        if ($data->section == 'Personal Data') {
                            $personal_data_content = $data->content;
                            // Decode the JSON array of images
                            $mayor_imgs = json_decode($data->mayor_img, true);
                            // Check if the array is valid and not empty
                            if (!empty($mayor_imgs) && is_array($mayor_imgs)) {
                                $personal_data_img = base_url('admin/image/MAYOR/') . $mayor_imgs[0];
                                $personal_data_img_alt = 'Mayor Image';
                            }
                            break;
                        }
                    }
                }
                ?>
                <img src="<?= esc($personal_data_img) ?>" alt="<?= esc($personal_data_img_alt) ?>" class="img-thumbnail img-responsive<?= $personal_data_img_alt === 'Mayor silhouette placeholder' ? ' mayor-personal-placeholder' : '' ?>">
            </div>
        </div>
        <div class="col-lg-6 pt-4 pt-lg-0">
            <div class="mayorsection-title">
                <p>PERSONAL DATA</p>
            </div>
            <div class="row content">
                <div class="col-lg-12">
                    <ul class="text-justify">
                        <?php if (!empty($personal_data_content)): ?>
                            <li><div class="ql-editor mayor-quill-content"> <?= $personal_data_content ?> </div></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<!-- Mayor's Gallery -->
<div class="row content pt-3">
    <div class="col-lg-12">
        <div class="mayorsection-title">
            <p>MAYOR'S GALLERY</p>
        </div>
        <div id="galleryCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-inner" role="listbox">
        <?php 
        $gallery_images = [];
        foreach ($mayor as $data) {
            if ($data->section == 'Gallery') {
                $gallery_images = json_decode($data->mayor_img);
                break;
            }
        }
        ?>
        <?php if (!empty($gallery_images)): ?>
            <?php foreach ($gallery_images as $index => $image): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= base_url('admin/image/MAYOR/') . $image ?>" class="d-block w-100 img-carousel mayor-carousel-img" alt="...">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="carousel-item active">
                <img src="<?= base_url('assets/img/mayor-silhouette.svg') ?>" class="d-block w-100 img-carousel mayor-carousel-img mayor-carousel-placeholder" alt="Mayor silhouette placeholder">
            </div>
        <?php endif; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<style>
.mayor-carousel-img {
    border: 3px solid #388e3c;
    border-radius: 1rem;
    object-fit: cover;
    aspect-ratio: 4/3;
    max-height: 600px;
    min-height: 320px;
    width: 100%;
    background: #fff;
    box-shadow: 0 2px 8px rgba(56,142,60,0.08);
}
.mayor-carousel-placeholder {
    object-fit: contain;
    padding: 2rem;
    background: transparent;
    border-color: transparent;
    box-shadow: none;
}
.mayor-personal-placeholder {
    background: transparent;
    border-color: transparent;
    box-shadow: none;
}
</style>


    <!-- Years of Service -->
    <div class="mayorsection-title pt-3">
        <p>YEARS OF SERVICE</p>
    </div>
    <div class="row content">
        <div class="col-lg-12">
            <ul class="text-justify">
                <?php 
                $years_of_service_content = '';
                foreach ($mayor as $data) {
                    if ($data->section == 'Years Service') {
                        $years_of_service_content = $data->content;
                        break;
                    }
                }
                ?>
                <?php if (!empty($years_of_service_content)): ?>
                    <li><?= $years_of_service_content ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Awards Received -->
    <div class="mayorsection-title pt-3">
        <p>AWARDS RECEIVED</p>
    </div>
    <div class="row content">
        <div class="col-lg-12">
            <ul class="text-justify">
                <?php 
                $awards_content = '';
                foreach ($mayor as $data) {
                    if ($data->section == 'Awards') {
                        $awards_content = $data->content;
                        break;
                    }
                }
                ?>
                <?php if (!empty($awards_content)): ?>
                    <li><?= $awards_content ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<!-- Combined Contact and Social Media Section -->
<div class="mayorsection-title pt-3 text-center">
        <small style="font-size: 1rem; color: #388e3c; font-weight: bold;">CONTACT INFORMATION</small>
    </div>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="row align-items-center g-4">
                <div class="col-md-6 border-end mb-3 mb-md-0">
                    <div class="mb-2 text-justify"><i class="bi bi-telephone-fill me-2"></i> PLDT: 523-5431(not legit)</div>
                    <div class="mb-2 text-justify"><i class="bi bi-envelope-fill me-2"></i> mayor@binancity.gov.ph(not legit)</div>
                </div>
                <div class="col-md-6">
                    <a href="https://www.facebook.com/mayor.gel.alonte" target="_blank" class="mx-2 text-decoration-none" title="Facebook">
                        <i class="bi bi-facebook" style="font-size: 2rem; color: #1877f3;"></i>
                    </a>
                    <a href="https://www.instagram.com/mayor.gel.alonte" target="_blank" class="mx-2 text-decoration-none" title="Instagram">
                        <i class="bi bi-instagram" style="font-size: 2rem; color: #e4405f;"></i>
                    </a>
                    <a href="https://www.tiktok.com/@mayor.gel.alonte" target="_blank" class="mx-2 text-decoration-none" title="TikTok">
                        <i class="bi bi-tiktok" style="font-size: 2rem; color: #000;"></i>
                    </a>
                    <div class="mt-2 text-secondary small text-justify">Stay connected with our Mayor for updates and announcements!</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Combined Contact and Social Media Section -->
</section><!-- End Mayor Section -->
<?php include "footer.php"; ?>
<?php pre_scripts('home'); ?>

</body>

</html>
