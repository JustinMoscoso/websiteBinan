<?php

include "navbar.php"

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety Seal Process</title>
    <?php include "footer.php"; ?>
	<?php pre_scripts('home'); ?>
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

    .safetyseal .safetyseal-item .btn-safety-seal {
        background-color: #388e3c;
        color: white;
        padding: 8px 30px 10px 30px;
        border-radius: 4px;
    }

    .safetyseal .safetyseal-item .btn-safety-seal:hover {
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
                <h2>Safety Seal Process</h2>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('/home') ?>">Home</a></li>
                    <li class="text-warning">Safety Seal Process</li>
                </ol>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <section>
        <div class="container">

            <!-- Certificatio Pricess -->
            <div class="safetyseals safetyseal-wrap">
                <div class="safetyseal-item border border-3">
                    <h3>CERTIFICATION PROCESS</h3>
                    <ol style="text-align: justify;">
                        <li>Applicant secures checklist and performs self-assessment based on the applicable
                            checklist.
                        </li>
                        <li>Applicant must fill out the Google Form for application of Safety Seal Certificate.</li>
                        <li>Issuing authority contacts applicant for inspection schedule.</em></li>
                        <li>Issuing authority conducts inspection.</li>
                        <li>If <strong>COMPLIANT</strong>, issuing authority provides <strong>SEAL</strong> to
                            applicant.
                            If there are deficiencies, applicant must implement <strong>CORRECTIVE ACTION</strong>
                            and apply for reassessment</li>

                    </ol>
                </div>
                <!-- Requirements -->
                <div class="safetyseal-item border border-3">
                    <h3>ELIGIBILITY REQUIREMENTS FOR SAFETY SEAL CERTIFICATION</h3>
                    <ol style="text-align: justify;">
                        <li>Valid Business Permit/Mayor’s Permit </li>
                        <li>Use of StaySafe.ph (or another CT Tool - BinD360)</li>
                        <li>Availability of temperature or thermal scanner</li>
                        <li>Availability of health declaration sheet</li>
                        <li>QR Codes for StaySafe.ph (or other CT Tool) are conspicuously placed</li>
                        <li>Availability of isolation area</li>
                        <li>BHERTs and other COVID-19 Emergency hotlines are conspicuously displayed</li>
                        <li>Availability of handwashing stations (soap, sanitizers, and hand drying equipment)</li>
                        <li>Installed physical barriers in enclosed areas to maintain social distancing</li>
                        <li>Availability of personnel-in-charge for monitoring and maintaining social distancing,
                            and
                            ensuring compliance to health protocols</li>
                        <li>Availability of windows for adequate air exchange</li>
                        <li>Compliance to the disinfection protocol</li>
                        <li>Conduct of regular (at least twice a week) cleaning and disinfection</li>
                        <li>Personnel, employees, clients and visitors always wear facemasks and face shield
                            especially
                            in enclosed places</li>
                        <li>Presence of designated Safety Officer</li>
                        <li>Availability of storage facility for proper collection, treatment, and disposal of used
                            facemasks and other infectious wastes</li>

                    </ol>
                </div>

                <!-- Reminder -->
                <div style="margin-bottom: 30px;">
                    <h1 style="color: #388E3C;"><b>REMINDER</b></h1>
                    <p><strong>No Safety Seal</strong> shall be awarded to a private establishment without a valid
                        business permit. <br>
                        <strong>LGU shall have the right to order the suspension of operations of establishments with
                            violation of
                            minimum health standards, until corrective actions have been implemented.</strong>
                    </p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row gy-4 align-items-center safetyseal safetyseal-item">
                <div class="safetyseal-item col-lg-4">
                    <a href="" class="btn btn-lg btn-safety-seal align-self-start"
                        onclick="openFile('assets/Safety-Seal-MC.pdf')">
                        GUIDELINES FOR SAFETY SEAL CERTIFICATION PROGRAM
                    </a>
                </div>
                <div class="safetyseal-item col-lg-4">
                    <a href="https://docs.google.com/forms/u/1/d/19rWKK0aY7mDrq6kXhJhabZZuq9AAG1Su_smWxBltptk/viewform?edit_requested=true"
                        class="btn btn-lg btn-safety-seal align-self-start">
                        REQUEST FOR SAFETY SEAL INSPECTION</a>
                </div>
                <div class="safetyseal-item col-lg-4">
                    <a href="" class="btn btn-lg btn-safety-seal align-self-start"
                        onclick="openFile('assets/Establishment-with-Safety-Seal-as-of-April-7-2022.pdf')">
                        LIST OF ESTABLISHMENTS WITH SAFETY SEAL CERTIFICATION</a>
                </div>
            </div>

            <!-- Inquiries -->
            <div style="margin-top: 30px;text-align: center;">

                <p><strong>For inquiries or non-compliance of business establishments, you may contact Business
                        Permit
                        and Licensing Office at:</strong></p>
                <p> INTELCO - (049) 513 5084 | (049) 513 5085 <br>
                    PLDT - (049) 523 5481</p>
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

    <script>
        function openFile(fileUrl) {
            // Redirect the user to the specified file URL
            window.open(fileUrl, '_blank');
        }
    </script>

</body>

</html>