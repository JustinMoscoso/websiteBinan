<?php

include "navbar.php"

    ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Opportunities and Priorities</title>
    <!-- Favicons -->
	<link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="icon">
    <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<style>
    /* InvestmentOpp Section - Home Page
------------------------------*/
    .InvestmentOpp .InvestmentOpps-item {
        position: relative;
        padding-top: 40px;
    }

    .InvestmentOpp .InvestmentOpps-item::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: #388e3c;
    }

    .InvestmentOpp .InvestmentOpps-item::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 30px;
        height: 2px;
        background: #fd7e14;
    }
</style>

<body>

    <!-- Header Start -->
    <header>
        <div class="header breadcrumbs d-flex align-items-center">
            <div class="container position-relative d-flex justify-content-between align-items-center" data-aos="fade">
                <h2>Investment Opportunities and Priorities</h2>
                <ol class="breadcrumb">
                    <li><a href="<?= base_url('/home') ?>">Home</a></li>
                    <li class="text-warning">Investment Opportunities and Priorities</li>
                </ol>
            </div>
        </div>
    </header>
    <!-- Header End -->
    
    <section id="InvestmentOpp" class="InvestmentOpp">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-12">
                    <div class="InvestmentOpps-item d-flex">
                        <div>
                            <h4>Trading Center</h4>
                            <p style="text-align: justify">Biñan City has been popularly recognized as the trading
                                center area immediately south of
                                Metro Manila. The city has the largest public market in the province of Laguna, and in
                                the CALABARZON Region. Retailers from nearby towns often plow the city proper to
                                purchase goods and merchandise intended to be sold elsewhere. Biñan City has also been
                                the center of commerce in the region because of the numerous banking institutions across
                                the city, plus the ever-growing number of commercial establishments and emerging
                                shopping centers.</p>
                        </div>
                    </div>
                </div>
                <!-- End InvestmentOpps Item -->

                <div class="col-lg-12">
                    <div class="InvestmentOpps-item d-flex">
                        <div>
                            <h4>Cottage Industry</h4>
                            <p style="text-align: justify">The creation of products and services are home-based, rather
                                than factory-based. While products and services created by cottage industry are often
                                unique and distinctive given the fact that they are usually not mass-produced, producers
                                in this sector often face numerous disadvantages when trying to compete with much larger
                                factory-based companies.</p>
                        </div>
                    </div>
                </div>
                <!-- End InvestmentOpps Item -->

                <div class="col-lg-12">
                    <div class="InvestmentOpps-item d-flex">
                        <div>
                            <h4>Tourism Development</h4>
                            <p style="text-align: justify">It involves broadening the ownership base such that more
                                people benefit from the tourism industry, skills development, job and wealth creation
                                and ensuring the geographic spread of the industry throughout the city. It is also about
                                construction of resorts and private pools, convention centers, hotels, eco-tourism
                                facilities, historic or cultural heritage projects and other leisure and tourist
                                attractions that will promote the rich cultural and historical heritage of the city.</p>
                        </div>
                    </div>
                </div>
                <!-- End InvestmentOpps Item -->

                <div class="col-lg-12">
                    <div class="InvestmentOpps-item d-flex">
                        <div>
                            <h4>Agricultural</h4>
                            <p style="text-align: justify">Providing assistance to the crop producers with the help of
                                various agricultural resources. Providing protection, assisting in the research sphere,
                                employing latest techniques, controlling pests and facilitating diversity all fall
                                within the purview of agriculture development. Agri-based and agri-related business
                                which include but not limited to high value crop production, rice production,
                                post-harvest facilities, storage facilities, marketing and packaging service facilities.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- End InvestmentOpps Item -->

                <div class="col-lg-12">
                    <div class="InvestmentOpps-item d-flex">
                        <div>
                            <h4>Commercial and Leisure Centers</h4>
                            <p style="text-align: justify">Shopping malls, supermalls, department stores, supermarkets
                                and activity areas.</p>
                        </div>
                    </div>
                </div>
                <!-- End InvestmentOpps Item -->

            </div>

        </div>

    </section>


    <?php include "footer.php"; ?>
	<?php pre_scripts('home'); ?>

</body>

</html>