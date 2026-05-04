<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Services</title>
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
    <style>
    .service-item {
        background-color: #fff;
        border-radius: 15px;
        padding: 40px 30px;
        text-align: center;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        transition: all 0.3s ease-in-out;
        height: 100%;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .service-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }

    .service-item .icon {
        margin: 0 auto 25px auto;
        width: 64px;
        height: 64px;
        background: #388E3C;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease-in-out;
    }

    .service-item:hover .icon {
        background: #FF9800;
    }

    .service-item .icon i {
        color: #fff;
        font-size: 28px;
        line-height: 0;
    }

    .service-item h5 {
        font-weight: 700;
        margin-bottom: 0;
        font-size: 20px;
    }

    a, a:hover {
        text-decoration: none;
        color: #333;
    }
    </style>
</head>
<body>
<?php include "navbar.php"; ?>
<?php include "header.php"; ?>
<?php include_header('eServices',null,[
    'layout' => 'side',
    'bg_color' => '#388e3c',
]); ?>

<div class="container">
    <div class="row py-5 gy-4">
        <div class="col-lg-6 col-md-6">
            <a href="https://binancityrealproperty.online/index.php/login?redirect=home" target="_blank">
                <div class="service-item">
                    <div class="icon">
                        <i class="fas fa-landmark"></i>
                    </div>
                    <h5>Online Real Property Tax Assessment & Payment</h5>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-md-6">
            <a href="https://www.binancitybusinesspermit.online/" target="_blank">
                <div class="service-item">
                    <div class="icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h5>Online Business Registration and Payment</h5>
                </div>
            </a>
        </div>

        <!-- Add more external links as needed -->
    </div>
</div>

<?php include "footer.php"; ?>
<?php pre_scripts('home'); ?>
</body>
</html>
