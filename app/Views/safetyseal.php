<?php

include "navbar.php"

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety Seal</title>
    <?php pre_styles('home'); ?>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<style>
    /* safetyseal Section - Home Page
------------------------------*/
    .safetyseal .safetyseal-item {
        color: black;
    }


    .safetyseal .safetyseal-item h3 {
        color: black;
        font-weight: 700;
        font-size: 26px;
    }

    .safetyseal .safetyseal-item .btn-get-started {
        background-color: #388e3c;
        color: white;
        padding: 8px 30px 10px 30px;
        border-radius: 4px;
        /* height: 65px; */
    }

    .safetyseal .safetyseal-item .btn-get-started:hover {
        background-color: #198754;
    }

    .safetyseals .safetyseal-wrap {
        padding-left: 30px;
    }


    .safetyseals .safetyseal-item {
        box-sizing: content-box;
        padding: 15px 15px 15px 15px;
        margin-bottom: 15px;
        min-height: 200px;
        box-shadow: 0px 0px 20px 0px rgba(11, 35, 65, 0.1);
        position: relative;
        background: #fff;
        border-radius: 10px;
        border-color: #004600 !important;
    }
</style>

<body>

    <!-- Header Start -->
    <header>
        <div class="header breadcrumbs d-flex align-items-center">
            <div class="container position-relative d-flex justify-content-between align-items-center" data-aos="fade">
                <h2>Safety Seal</h2>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('/home') ?>">Home</a></li>
                    <li class="text-warning">Safety Seal</li>
                </ol>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <section id="safetyseal" class="safetyseal">

        <div class="container">

            <div class="row gy-4 align-items-center safetyseal-item">
                <div class="col-lg-4">
                    <img src="assets/img/safetyseal.png" class="img-fluid" alt="">
                </div>
                <div class="col-lg-8">
                    <h3>What is Safety Seal Certifcation Program?</h3>
                    <p>Safety Seal Certification Program is an IATF response to assure the public of establishments’
                        compliance with minimum public health standards and encourage the adaption of digital contact
                        tracing application such as the StaySafe.ph and BinD360. (IATF Resolution No. 87 S. 2020)</p>
                    <ul>
                        <li><i class="bi bi-check"></i> <span>Must be displayed conspicuously at all entrance
                                points.</span></li>
                        <li><i class="bi bi-check"></i><span> Free, at no cost to the establishment.</span></li>
                        <li><i class="bi bi-check"></i> <span>Valid for six (6) months, except in the case of tourism
                                enterprises where the seal is valid for one (1) year, renewable.</span></li>
                    </ul>

                    <h3>Can establishments still be allowed to operate even without a Safety Seal?</h3>
                    <p>Safety Seal is only voluntary.
                        However, establishments still need to comply with MPHS and allowable operational capacity for
                        continued operation.</p>
                    <a href="<?= base_url('/safetysealprocess') ?>" class="btn btn-get-started align-self-start">Know More</a>
                </div>

            </div><!-- safetyseal Item -->

            

        </div>
    </section>

    <?php
    include "footer.php"
        ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>

</body>

</html>