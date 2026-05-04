<?php

include "navbar.php"

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Contents</title>
    <?php pre_styles('home'); ?>
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<style>
    /*--------------------------------------------------------------
# General
--------------------------------------------------------------*/
    body {
        font-family: "Poppins", sans-serif;
        color: #444444;
    }

    a {
        color: #ed502e;
        text-decoration: none;
    }

    a:hover {
        color: #f1775d;
        text-decoration: none;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: "Roboto", sans-serif;
    }


    /*--------------------------------------------------------------
# barangays
--------------------------------------------------------------*/
    .barangays .barangay-wrap {
        padding-left: 30px;
    }


    .barangays .barangay-item {
        box-sizing: content-box;
        padding: 30px 30px 30px 30px;
        margin: 30px 15px;
        min-height: 200px;
        box-shadow: 0px 0px 20px 0px rgba(11, 35, 65, 0.1);
        position: relative;
        background: #fff;
        border-radius: 10px;
        border-color: #004600 !important;
    }

    .barangays .barangay-item .barangay-img {
        width: 90px;
        border-radius: 10px;
        border: 6px solid #fff;
        position: absolute;
        left: -45px;
    }

    .barangays .barangay-item h3 {
        margin: 0;
        margin: 0;
        font-size: 35px;
        font-weight: 700;
        color: #388E3C;
        padding-bottom: 10px;
    }

    .barangays .barangay-item h4 {
        font-size: 14px;
        color: #999;
        margin: 0;
    }


    .barangays .barangay-item p {
        font-style: italic;
        margin: 15px auto 15px auto;
        font-size: 20px;
        font-family: "Lora", sans-serif;
    }

    .barangays .swiper-pagination {
        margin-top: 20px;
        position: relative;
    }

    .barangays .swiper-pagination .swiper-pagination-bullet {
        width: 12px;
        height: 12px;
        background-color: #fff;
        opacity: 1;
        border: 1px solid #ed502e;
    }

    .barangays .swiper-pagination .swiper-pagination-bullet-active {
        background-color: #ed502e;
    }
</style>

<body>
    <!-- Header Start -->
    <header>
        <div class="header breadcrumbs d-flex align-items-center">
            <div class="container position-relative d-flex justify-content-between align-items-center" data-aos="fade">
                <h2>Services</h2>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('/home') ?>">Services</a></li>
                    <li class="text-warning">Service</li>
                </ol>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <section id="barangays" class="barangays section-bg">
        <div class="container" data-aos="fade-up">
            <div class="text-center" >
                <h1 style="color: #388E3C;"><b>Service Name</b></h1>
                <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint
                    consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia
                    fugiat sit in iste officiis commodi quidem hic quas.</p>
            </div>

            <div data-aos="fade-up" data-aos-delay="100">
                <div class="barangay-wrap">
                    <div class="barangay-item border border-3">
                        <h3>Requirements</h3>
                        <ol style="text-align: justify;">
                            <li>Duly accomplished application forms, signed by the owner/ applicant, signed and sealed
                                by respective engineers/ architect, notarized. <strong>Forms are available at the Office
                                    of the Building Official</strong></li>
                            <li>Eight (8) sets of Construction plans/Blueprints : scale 1:100</li>
                            <li>Photocopy of updated PTR / PRC ID / Accredited Professional Organization ID <em>(Affix 3
                                    original specimen signature in BLUE INK and stamp Professional’s Seal)</em></li>
                            <li>Photocopy of owner’s valid ID <em>(Preferably Government Issued ID)</em></li>
                            <li>Authorization Letter to transact in behalf of the owner or SPA from owner/applicant</li>
                            <li>Five (5) sets Notarized Bill of Materials (BOM) / Cost Estimate,</li>
                            <li>Five (5) sets Notarized Specifications</li>
                            <li>Five (5) sets Electrical Design Analysis, Voltage Drop Calculation, Short Circuit
                                Calculation <em>(Duly Signed and Sealed by a Professional Electrical Engineer)</em></li>
                            <li>Locational Clearance from City Zoning Office</li>
                            <li>Fire Safety Evaluation Clearance (FSEC) from Alabang Fire Department</li>
                            <li>Construction Safety and Health Program (CSHP) approval from DOLE, (BOM&lt; or = P3M
                                apply in Las Piñas, BOM &gt; P3M apply in Manila)</li>
                            <li>Construction Business Permit from BPLO or City Tax <em>(For Non-Contractor)</em> With
                                Contractor-Notice of Construction and photocopy of PCAB License</li>
                            <li>Certified True Copy of Transfer Certificate of Title (TCT) from Registry of
                                Deeds&nbsp;Or&nbsp; photocopy of Deed of Absolute Sale</li>
                            <li>(1) Logbook</li>
                            <li>(2) Long Folders <em>(White)</em></li>
                            <li>(1) Long Expanding Plastic Envelope <em>(will be returned upon release of permit
                                    together with applicant’s copy of submitted documents)</em></li>
                            <li><strong>Standard Evaluation Checklist</strong></li>
                            <li>Duly accomplished application forms, signed by the owner/ applicant, signed and sealed
                                by respective engineers/ architect, notarized. <strong>Forms are available at the Office
                                    of the Building Official</strong></li>
                            <li>Eight (8) sets of Construction plans/Blueprints : scale 1:100</li>
                            <li>Photocopy of updated PTR / PRC ID / Accredited Professional Organization ID <em>(Affix 3
                                    original specimen signature in BLUE INK and stamp Professional’s Seal)</em></li>
                            <li>Photocopy of owner’s valid ID <em>(Preferably Government Issued ID)</em></li>
                            <li>Authorization Letter to transact in behalf of the owner or SPA from owner/applicant</li>
                            <li>Five (5) sets Notarized Bill of Materials (BOM) / Cost Estimate,</li>
                            <li>Five (5) sets Notarized Specifications</li>
                            <li>Five (5) sets Electrical Design Analysis, Voltage Drop Calculation, Short Circuit
                                Calculation <em>(Duly Signed and Sealed by a Professional Electrical Engineer)</em></li>
                            <li>Locational Clearance from City Zoning Office</li>
                            <li>Fire Safety Evaluation Clearance (FSEC) from Alabang Fire Department</li>
                            <li>Construction Safety and Health Program (CSHP) approval from DOLE, (BOM&lt; or = P3M
                                apply in Las Piñas, BOM &gt; P3M apply in Manila)</li>
                            <li>Construction Business Permit from BPLO or City Tax <em>(For Non-Contractor)</em> With
                                Contractor-Notice of Construction and photocopy of PCAB License</li>
                            <li>Certified True Copy of Transfer Certificate of Title (TCT) from Registry of
                                Deeds&nbsp;Or&nbsp; photocopy of Deed of Absolute Sale</li>
                            <li>(1) Logbook</li>
                            <li>(2) Long Folders <em>(White)</em></li>
                            <li>(1) Long Expanding Plastic Envelope <em>(will be returned upon release of permit
                                    together with applicant’s copy of submitted documents)</em></li>
                            <li><strong>Standard Evaluation Checklist</strong></li>
                        </ol>
                    </div>
                </div>
            </div>



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