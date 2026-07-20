<footer id="footer" class="footer pt-0 pb-0">
    <style>
        .copyright-link:hover {
            text-decoration: underline !important;
        }
    </style>
    <div class="container-fluid text-light pt-2 wow fadeIn" style="background:linear-gradient(to bottom, rgba(0, 0, 0, 0.7), transparent), url('<?= base_url('assets/img/footer3.jpg'); ?>') !important; background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed;">
        <div class="container pt-5">
            <div class="row g-5">
                <!-- Logo -->
                <div class="col-lg-2 col-md-3 col-sm-12 text-center text-md-start">
                    <img class="img-fluid footerlogo" src="<?= base_url('assets/img/binanlogo.png'); ?>" alt="Biñan Logo">
                </div>

                <!-- Quick Links -->
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <p class="text-light mb-4 fw-bold" style="font-family: Raleway; font-size: 22px;">Quick Links</p>
                    <div class="row">
                        <?php
                            $quick_links = [
                                'Home' => '/home',
                                'About' => '/about',
                                'History' => '/history',
                                'Maps' => '/map',
                                'Barangays' => '/barangays',
                                'Departments' => '/department',
                                'City Officials' => '/officials',
                                'Careers' => '/careers',
                                'Jobs' => '/jobs',
                                'eServices' => 'services',
                                'Invest' => '/invest',
                                'Full Disclosure Policy' => '/fulldisc',
                            ];
                            $total_links = count($quick_links);
                            $half = ceil($total_links / 2);
                            $left_links = array_slice($quick_links, 0, $half, true);
                            $right_links = array_slice($quick_links, $half, null, true);
                        ?>
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <?php foreach ($left_links as $text => $link): ?>
                                    <li class="mb-2 d-flex align-items-center">
                                        <span class="me-2 text-light">•</span>
                                        <a class="text-light text-decoration-none" href="<?= base_url($link) ?>"><?= $text ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <?php foreach ($right_links as $text => $link): ?>
                                    <li class="mb-2 d-flex align-items-center">
                                        <span class="me-2 text-light">•</span>
                                        <a class="text-light text-decoration-none" href="<?= base_url($link) ?>"><?= $text ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Us -->
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <p class="text-light mb-4 fw-bold" style="font-family: Raleway; font-size: 22px;">Contact Us</p>
                    <div class="mb-3 d-flex align-items-start">
                        <span class="me-2">•</span>
                        <span>838H+3V2, San Pablo St., Biñan, Laguna</span>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <span class="me-2">•</span>
                        <span>Local: +639 12 3456 789</span>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <span class="me-2">•</span>
                        <span>Landline: (049) 123 4567</span>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <span class="me-2">•</span>
                        <a class="text-light text-decoration-none" href="<?= base_url('/hotlines') ?>">Hotlines</a>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <span class="me-2">•</span>
                        <a class="text-light text-decoration-none" href="<?= base_url('/contact') ?>">Contact Us</a>
                    </div>
                </div>

                <!-- Government Links -->
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <p class="text-light mb-4 fw-bold" style="font-family: Raleway; font-size: 22px;">Government Links</p>
                    <div class="row">
                        <?php
                            $gov_links = [
                                'Office of the President' => 'https://op-proper.gov.ph/',
                                'Office of the Vice President' => 'https://www.ovp.gov.ph/',
                                'Senate of the Philippines' => 'https://senate.gov.ph/',
                                'House of Representatives' => 'https://www.congress.gov.ph/',
                                'Supreme Court' => 'https://sc.judiciary.gov.ph/',
                                'Court of Appeals' => 'https://ca.judiciary.gov.ph/',
                                'Sandiganbayan' => 'https://sb.judiciary.gov.ph/',
                            ];
                            $total_gov_links = count($gov_links);
                            $half_gov = ceil($total_gov_links / 2);
                            $left_gov_links = array_slice($gov_links, 0, $half_gov, true);
                            $right_gov_links = array_slice($gov_links, $half_gov, null, true);
                        ?>
                        <div class="col-md-6 col-sm-6">
                            <ul class="list-unstyled">
                                <?php foreach ($left_gov_links as $text => $url): ?>
                                    <li class="mb-2 d-flex align-items-center">
                                        <span class="me-2 text-light">•</span>
                                        <a class="text-light text-decoration-none" href="<?= $url ?>" target="_blank"><?= $text ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <ul class="list-unstyled">
                                <?php foreach ($right_gov_links as $text => $url): ?>
                                    <li class="mb-2 d-flex align-items-center">
                                        <span class="me-2 text-light">•</span>
                                        <a class="text-light text-decoration-none" href="<?= $url ?>" target="_blank"><?= $text ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logos Row (centered above copyright) -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center align-items-center py-3">
                        <img src="<?= base_url('assets/img/transparency.png') ?>" class="img-fluid m-2" style="height: 60px; object-fit: contain;" alt="Transparency Seal">
                        <img src="<?= base_url('assets/img/republic.png') ?>" class="img-fluid m-2" style="height: 60px; object-fit: contain;" alt="Republic Seal">
                        <img src="<?= base_url('assets/img/iso.png') ?>" class="img-fluid m-2" style="height: 60px; object-fit: contain;" alt="ISO Certified">
                    </div>
                </div>
            </div>

            <!-- Copyright Notice -->
            <div class="row">
                <div class="col-12 text-center py-3 border-top border-light border-opacity-25">
                    <p class="mb-0 text-light" style="font-size: 14px;">
                        © 2016-2025 Biñan City Official Website, Developed by <a class="text-light text-decoration-none copyright-link" href="https://www.facebook.com/ictofficebinan" target="_blank">Information and Communications Technology Office - Biñan</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>