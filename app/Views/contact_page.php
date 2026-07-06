<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact</title>
    <?php pre_styles('home'); ?>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">

</head>



<body>
    <?php include "navbar.php"; ?>
    <?php include "header.php"; ?>
    <?php include_header('Contact Us'); ?>


    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">
            <div class="section-title">
                <h2><b>Contact Us</b></h2>
            </div>
            <div class="row contact-container">
                <!-- Left Column: Contact Info -->
                <div class="col-lg-5 contact-info-col">
                    <div class="contact-info-item">
                        <div class="icon"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <h4>Location:</h4>
                            <p>838H+3V2, San Pablo St., Biñan, Laguna</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="icon"><i class="bi bi-envelope"></i></div>
                        <div>
                            <h4>Email:</h4>
                            <p><a href="mailto:cityofbinan@binancity.gov.ph">cityofbinan@binancity.gov.ph</a></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="icon"><i class="bi bi-phone"></i></div>
                        <div>
                            <h4>Call:</h4>
                            <p>(049) 523-5400</p>
                        </div>
                    </div>

                    <div class="mapouter mt-4">
                        <div class="gmap_canvas">
                            <iframe width="100%" height="100%" id="gmap_canvas"
                                src="https://maps.google.com/maps?q=binan city hall&t=&z=10&ie=UTF8&iwloc=&output=embed"
                                frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Contact Form -->
                <div class="col-lg-7 contact-form-col">
                    <h3>Send us a message</h3>
                    <form id="contactForm" action="<?= base_url('contact/send') ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6 form-floating mb-3">
                                <input type="text" name="name" class="form-control" id="name" required
                                    placeholder="Your Name">
                                <label for="name" class="ms-2">Your Name</label>
                            </div>
                            <div class="col-md-6 form-floating mb-3">
                                <input type="email" class="form-control" name="email" id="email" required
                                    placeholder="Your Email">
                                <label for="email" class="ms-2">Your Email</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="subject" id="subject" required
                                placeholder="Subject">
                            <label for="subject">Subject</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="message" id="message" required
                                placeholder="Enter your message" style="height: 150px;"></textarea>
                            <label for="message">Message</label>
                        </div>
                        <div class="my-3">
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Your message has been sent. Thank you!</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" id="submitBtn" class="btn-submit">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section><!-- End Contact Section -->

    <?php include "footer.php"; ?>
    <?php pre_scripts('home'); ?>
    <script async src='https://www.googletagmanager.com/gtag/js?id=G-P7JSYB1CSP'></script>
    <script>
        if (window.self == window.top) {
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', 'G-P7JSYB1CSP');
        }
    </script>

    <script src="<?= base_url('assets/js/sweetalert.js') ?>"></script>

</body>

</html>