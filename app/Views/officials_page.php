<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>City Officials</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
    <!-- Ensure Bootstrap CSS is included in pre_styles('home') -->
    <style>
        .official-modal .modal-body { display: flex; gap: 20px; }
        .official-modal .modal-body img { max-width: 200px; height: auto; }
        
        /* Perfect circular official images */
        .cookie-card .pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid #4caf50;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            margin: 0 auto;
            flex-shrink: 0;
        }
        
        .cookie-card .pic img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            object-position: center;
            border-radius: 0 !important;
            border: none !important;
            box-shadow: none !important;
            transition: all 0.3s ease;
        }
        
        .cookie-card .pic:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .cookie-card .pic img:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        /* Ensure the pic container is properly sized */
        .cookie-card .pic {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }
        .cookie-card .description h4,
        .cookie-card .description span {
            color: #388E3C !important;
        }
        
        /* Responsive spacing for official cards */
        @media (max-width: 768px) {
            .cookie-card .pic {
                margin-bottom: 0.5rem;
            }
            
            .cookie-card .description {
                text-align: center;
                margin-top: 0.25rem;
            }
            
            .cookie-card .description h4 {
                margin-bottom: 0.25rem;
                font-size: 1.1rem;
            }
            
            .cookie-card .description span {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .cookie-card .pic {
                margin-bottom: 0.75rem;
            }
            
            .cookie-card .description {
                text-align: center;
                margin-top: 0.5rem;
            }
            
            .cookie-card .description h4 {
                margin-bottom: 0.5rem;
                font-size: 1rem;
            }
            
            .cookie-card .description span {
                font-size: 0.85rem;
            }
        }
        
        @media (max-width: 480px) {
            .cookie-card .pic {
                margin-bottom: 1rem;
            }
            
            .cookie-card .description {
                text-align: center;
                margin-top: 0.75rem;
            }
            
            .cookie-card .description h4 {
                margin-bottom: 0.75rem;
                font-size: 0.95rem;
            }
            
            .cookie-card .description span {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <?php include "navbar.php";?>
    <?php include "header.php"; ?>
    <?php include_header('City Officials'); ?>

    <section id="officials" class="officials section-bg">
    <div class="container" data-aos="fade-up">
        <div class="row">
        <?php
        // Debugging: Log the raw $cityoffi data to check if it's populated correctly
        error_log('City Officials Data: ' . print_r($cityoffi, true));

        // Sort the officials by rank
        usort($cityoffi, function($a, $b) {
            return ($a->ranking ?? PHP_INT_MAX) - ($b->ranking ?? PHP_INT_MAX);
        });

        // Initialize arrays for different official types
        $officialsByType = [
            'congress' => null,
            'cityMayor' => null,
            'cityViceMayor' => null,
            'abcPresident' => null,
            'skFederationPresident' => null,
            'cityCouncilors' => [],
        ];

        // Separate officials by type
        foreach ($cityoffi as $official) {
            if ($official->status !== 'ACTIVE') continue; // Only display active officials
            switch ($official->off_position) {
                case 'CONGRESS':
                    $officialsByType['congress'] = $official;
                    break;
                case 'CITY MAYOR':
                    $officialsByType['cityMayor'] = $official;
                    break;
                case 'CITY VICE MAYOR':
                    $officialsByType['cityViceMayor'] = $official;
                    break;
                case 'ABC PRESIDENT':
                    $officialsByType['abcPresident'] = $official;
                    break;
                case 'SK FEDERATION PRESIDENT':
                    $officialsByType['skFederationPresident'] = $official;
                    break;
                case 'CITY COUNCILOR':
                    $officialsByType['cityCouncilors'][] = $official;
                    break;
                default:
                    // Handle other positions if needed
                    break;
            }
        } ?>

            <!-- Display Congress -->
           <?php if ($officialsByType['congress']) : ?>
    <div class="section-title py-2">
        <h2 style="color: #388E3C;">Congressman</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 mt-4">
            <div class="cookie-card view-official-btn" data-bs-toggle="modal" data-bs-target="#officialModal" data-id="<?= $officialsByType['congress']->ID ?>" data-position="<?= $officialsByType['congress']->off_position ?>">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pic">
                            <img src="<?= base_url('admin/image/CITYOFFICIAL/' . $officialsByType['congress']->img_loc) ?>" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8 d-flex align-items-center">
                        <div class="description">
                            <h4><?= $officialsByType['congress']->off_name ?></h4>
                            <span>Representative - Lone District of Biñan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

            <!-- Display City Mayor -->
            <?php if ($officialsByType['cityMayor']) : ?>
    <div class="section-title py-2 mt-5">
        <h2 style="color: #388E3C;">City Mayor</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 mt-4">
            <div class="cookie-card view-official-btn" data-bs-toggle="modal" data-bs-target="#officialModal" data-id="<?= $officialsByType['cityMayor']->ID ?>" data-position="<?= $officialsByType['cityMayor']->off_position ?>">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pic">
                            <img src="<?= base_url('admin/image/CITYOFFICIAL/' . $officialsByType['cityMayor']->img_loc) ?>" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8 d-flex align-items-center">
                        <div class="description">
                            <h4><?= $officialsByType['cityMayor']->off_name ?></h4>
                            <span><?= $officialsByType['cityMayor']->off_position ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

            <!-- Display City Vice Mayor -->
           <?php if ($officialsByType['cityViceMayor']) : ?>
    <div class="section-title py-2 mt-5">
        <h2 style="color: #388E3C;">City Vice Mayor</h2>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 mt-4">
            <div class="cookie-card view-official-btn" data-bs-toggle="modal" data-bs-target="#officialModal" data-id="<?= $officialsByType['cityViceMayor']->ID ?>" data-position="<?= $officialsByType['cityViceMayor']->off_position ?>">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pic">
                            <img src="<?= base_url('admin/image/CITYOFFICIAL/' . $officialsByType['cityViceMayor']->img_loc) ?>" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8 d-flex align-items-center">
                        <div class="description">
                            <h4><?= $officialsByType['cityViceMayor']->off_name ?></h4>
                            <span><?= $officialsByType['cityViceMayor']->off_position ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

            <!-- Display City Councilors -->
            <div class="section-title py-2 mt-5">
    <h2 style="color: #388E3C;">City Councilors</h2>
</div>
<div class="row">
    <?php foreach ($officialsByType['cityCouncilors'] as $official) : ?>
        <div class="col-lg-6 mt-4">
            <div class="cookie-card view-official-btn" data-bs-toggle="modal" data-bs-target="#officialModal" data-id="<?= $official->ID ?>" data-position="<?= $official->off_position ?>">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pic">
                            <img src="<?= base_url('admin/image/CITYOFFICIAL/' . $official->img_loc) ?>" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8 d-flex align-items-center">
                        <div class="description">
                            <h4><?= $official->off_name ?></h4>
                            <span><?= $official->off_position ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

            <!-- Display ABC President and SK Federation President side by side -->
   <div class="row">
    <div class="col-lg-6">
        <div class="section-title py-2 mt-5">
            <h2 style="color: #388E3C;">ABC President</h2>
        </div>
        <?php if ($officialsByType['abcPresident']) : ?>
            <div class="cookie-card view-official-btn" data-bs-toggle="modal" data-bs-target="#officialModal" data-id="<?= $officialsByType['abcPresident']->ID ?>" data-position="<?= $officialsByType['abcPresident']->off_position ?>">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pic">
                            <img src="<?= base_url('admin/image/CITYOFFICIAL/' . $officialsByType['abcPresident']->img_loc) ?>" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8 d-flex align-items-center">
                        <div class="description">
                            <h4><?= $officialsByType['abcPresident']->off_name ?></h4>
                            <span><?= $officialsByType['abcPresident']->off_position ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <p>No ABC President Assigned</p>
        <?php endif; ?>
    </div>

    <div class="col-lg-6">
        <div class="section-title py-2 mt-5">
            <h2 style="color: #388E3C;">SK Federation President</h2>
        </div>
        <?php if ($officialsByType['skFederationPresident']) : ?>
            <div class="cookie-card view-official-btn" data-bs-toggle="modal" data-bs-target="#officialModal" data-id="<?= $officialsByType['skFederationPresident']->ID ?>" data-position="<?= $officialsByType['skFederationPresident']->off_position ?>">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="pic">
                            <img src="<?= base_url('admin/image/CITYOFFICIAL/' . $officialsByType['skFederationPresident']->img_loc) ?>" class="img-fluid" alt="">
                        </div>
                    </div>
                    <div class="col-lg-8 d-flex align-items-center">
                        <div class="description">
                            <h4><?= $officialsByType['skFederationPresident']->off_name ?></h4>
                            <span><?= $officialsByType['skFederationPresident']->off_position ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <p>No SK Federation President Assigned</p>
        <?php endif; ?>
    </div>
</div>
<!-- SK AND ABC END -->
    </section>

  <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Modal</title>
    <link href="<?= base_url('assets/css/fontawesome-all.css'); ?>" rel="stylesheet">
    <style>
/* Modal Dialog Styles */
.modal-dialog {
  max-width: 1200px;
  margin: 2rem auto;
}

.modal-dialog.modal-compact {
  max-width: 600px;
  margin: 2rem auto;
}

/* Modal Compact Styles */
.modal-compact .modal-content {
  border: none;
  border-radius: 12px;
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  background: #ffffff;
}

.modal-compact .modal-header {
  background: linear-gradient(135deg, #2e7d32 0%, #37474f 100%);
  border: none;
  padding: 20px 24px;
  position: relative;
  text-align: center;
}

.modal-compact .modal-title {
  color: white;
  font-size: 1.3rem;
  font-weight: 700;
  margin: 0;
}

.modal-compact .modal-header p {
  font-size: 0.9rem;
  margin: 5px 0 0 0;
  opacity: 0.9;
  color: white;
}

.modal-compact .modal-body {
  padding: 25px;
  text-align: center;
}

.modal-compact .simple-modal-image {
  width: 280px;
  height: 350px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  max-width: 100%;
}

.modal-compact .modal-footer {
  background: linear-gradient(145deg, #eceff1 0%, #ffffff 100%);
  border: none;
  padding: 18px 24px;
  justify-content: center;
}

.modal-compact .btn-danger {
  padding: 10px 24px;
  font-size: 0.95rem;
  min-width: 100px;
  background-color: #dc3545;
  border-color: #dc3545;
  font-weight: 600;
  letter-spacing: 0.5px;
}

/* General Modal Styles */
.modal-content {
  border: none;
  border-radius: 16px;
  box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  background: #ffffff;
}

.modal-header {
  background: linear-gradient(135deg, #2e7d32 0%, #37474f 100%);
  border: none;
  padding: 24px 30px;
  position: relative;
}

.modal-title {
  color: white;
  font-size: 1.6rem;
  font-weight: 700;
  margin: 0;
}

.btn-close {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  border-radius: 8px;
  color: white;
  opacity: 0.8;
  transition: all 0.3s ease;
  padding: 8px 12px;
  font-size: 16px;
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
}

.btn-close:hover {
  background: rgba(255, 255, 255, 0.2);
  opacity: 1;
  color: white;
}

/* Carousel Styles */
.content-carousel-area {
  border: 2px solid #e9ecef;
  height: 400px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f8f9fa;
  border-radius: 8px;
  overflow: hidden;
}

.carousel-nav-btn {
  width: 40px;
  height: 40px;
  border: none;
  background-color: rgba(255, 255, 255, 0.9) !important;
  color: #333 !important;
  border-radius: 50%;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  z-index: 10;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  user-select: none;
}

.carousel-nav-btn:hover {
  background-color: #ffffff !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transform: translateY(-50%) scale(1.1);
}

.carousel-nav-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.carousel-nav-btn.prev {
  left: 10px;
}

.carousel-nav-btn.next {
  right: 10px;
}

.carousel-content {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.carousel-content img {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  max-width: 100%;
  max-height: 100%;
  width: auto;
  height: auto;
  opacity: 0;
  transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  object-fit: contain;
  will-change: opacity;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}

.carousel-content img.active {
  opacity: 1;
}

/* Official Image */
#officialImage {
  max-width: 100%;
  width: 100%;
  height: 400px;
  object-fit: cover;
  object-position: center top;
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Info Card Styles (Matching the Image) */
.info-card {
  background: #f5f5f5; /* Light gray background like first image */
  border: none; /* Remove green borders */
  border-radius: 0; /* Remove rounded corners for cleaner look */
  padding: 25px 30px; /* Adjust padding for better spacing */
  margin-bottom: 20px;
  box-shadow: none; /* Remove shadow for flatter design */
  transition: all 0.3s ease;
  min-height: 150px;
}

.info-card h3 {
  color: #4caf50; /* Keep green color for headings */
  font-size: 1.4rem;
  font-weight: 600;
  margin-bottom: 20px;
  display: block; /* Remove flex to match first image layout */
  text-transform: uppercase; /* Optional: makes it match the style better */
  letter-spacing: 0.5px;
}

.info-card h3 i {
  display: none; /* Hide icons to match first image */
}

.info-content {
  color: #333333; /* Darker text color for better readability */
  line-height: 1.7;
  font-size: 0.95rem;
  font-weight: 400;
}

/* Equal Cards Fix */
.equal-cards-row {
  display: flex;
  gap: 20px;
  margin-bottom: 30px; 
}

.equal-cards-row .col-md-4,
.equal-cards-row .col-md-8 {
  flex: 1;
  max-width: none;
}

.equal-cards-row .info-card {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.equal-cards-row .info-card .info-content {
  flex-grow: 1;
}

/* Modal Scrollbar */
.modal-body {
  max-height: 70vh; /* Limit modal body height to 70% of viewport height */
  overflow-y: auto; /* Add vertical scrollbar when content overflows */
  padding-right: 15px; /* Add some padding to account for scrollbar */
}

/* Custom scrollbar styling (optional) */
.modal-body::-webkit-scrollbar {
  width: 8px;
}

.modal-body::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Modal Footer */
.modal-footer {
  background: linear-gradient(145deg, #eceff1 0%, #ffffff 100%);
  border: none;
  padding: 20px 30px;
}

.btn-danger {
  background-color: #dc3545;
  border-color: #dc3545;
  font-weight: 600;
  letter-spacing: 0.5px;
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
  .equal-cards-row {
    flex-direction: column;
  }
  .equal-cards-row .col-md-4,
  .equal-cards-row .col-md-8 {
    flex: none;
    max-width: 100%;
    width: 100%;
  }

  .modal-dialog {
    margin: 1rem;
    max-width: calc(100% - 2rem);
  }

  .modal-dialog.modal-compact {
    max-width: 350px;
    margin: 1.5rem auto;
  }

  .carousel-nav-btn {
    width: 35px;
    height: 35px;
  }

  .content-carousel-area {
    height: 300px !important;
  }

  .modal-compact .simple-modal-image {
    width: 240px;
    height: 300px;
  }

  .modal-compact .modal-header {
    padding: 15px 20px;
  }

  .modal-compact .modal-body {
    padding: 20px;
  }

  .modal-compact .modal-footer {
    padding: 15px 20px;
  }
}
</style>
</head>
<body>
    <div class="modal fade" id="officialModal" tabindex="-1" role="dialog" aria-labelledby="officialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="w-100 text-center">
                    <h5 class="modal-title mb-1" id="officialModalLabel">
                        <span id="officialName">Official Name</span>
                    </h5>
                    <p class="mb-0 text-white-50" id="officialPosition">Position Title</p>
                </div>
            </div>

            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <img id="officialImage" src="<?= base_url('assets/img/binanlogo.png') ?>" 
                                 alt="Official Photo" class="img-fluid rounded shadow">
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="content-carousel-area position-relative bg-light rounded p-4">
                            <button class="carousel-nav-btn position-absolute start-0 top-50 translate-middle-y btn btn-light rounded-circle" 
                                    id="prevBtn" aria-label="Previous image">
                                <i class="fas fa-chevron-left" aria-hidden="true"></i>
                            </button>
                            <div class="carousel-content h-100 d-flex align-items-center justify-content-center" 
                                 id="carouselContent" role="img" aria-live="polite"></div>
                            <button class="carousel-nav-btn position-absolute end-0 top-50 translate-middle-y btn btn-light rounded-circle" 
                                    id="nextBtn" aria-label="Next image">
                                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="info-card">
                            <h3><i class="fas fa-user"></i> Personal Data</h3>
                            <div id="edit_personal_data" class="info-content"></div>
                        </div>
                    </div>
                </div>

                <div class="row equal-cards-row">
                    <div class="col-md-4">
                        <div class="info-card">
                            <h3><i class="fas fa-project-diagram"></i> Awards and Recognitions</h3>
                            <div id="edit_projects" class="info-content"></div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="info-card">
                            <h3><i class="fas fa-calendar-alt"></i> Years of Service</h3>
                            <div id="edit_years_of_service" class="info-content"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php include "footer.php"; ?>
<?php pre_scripts('home'); ?>

<script>
// Utility Functions
function formatText(text) {
    return text ? text.replace(/\n/g, '<br>') : 'Not provided';
}

function preloadImages(imageArray) {
    const promises = imageArray.map(src => {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.src = src;
            img.onload = () => {
                console.log('Image loaded successfully:', src);
                resolve();
            };
            img.onerror = (error) => {
                console.error('Failed to load image:', src, error);
                reject(error);
            };
        });
    });
    return Promise.all(promises);
}

// Modal Handling
function openOfficialInfoModal(officialId, position) {
    const $ = window.jQuery;
    
    $.ajax({
        url: '<?= base_url("admin/getOfficialDetails/") ?>' + officialId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Modal Data:', response);

            const modal = document.getElementById('officialModal');
            const officialName = document.getElementById('officialName');
            const officialPosition = document.getElementById('officialPosition');
            const officialImage = document.getElementById('officialImage');
            const personalDataElement = document.getElementById('edit_personal_data');
            const yearsOfServiceElement = document.getElementById('edit_years_of_service');
            const projectsElement = document.getElementById('edit_projects');

            // Populate modal content
            officialName.textContent = response.off_name || 'Unnamed Official';
            officialPosition.textContent = response.off_position || 'Position Not Specified';
            
            if (officialImage) {
                officialImage.src = response.img_loc
                    ? '<?= base_url("admin/image/CITYOFFICIAL/") ?>' + response.img_loc
                    : '<?= base_url("assets/img/binanlogo.png") ?>';
            }

            if (personalDataElement) personalDataElement.innerHTML = formatText(response.personal_data);
            if (yearsOfServiceElement) yearsOfServiceElement.innerHTML = formatText(response.years_of_service);
            if (projectsElement) projectsElement.innerHTML = formatText(response.awards);

            // Initialize carousel for all officials
            const carouselImages = response.carouselimages 
                ? response.carouselimages.split(',').map(image => 
                    '<?= base_url("admin/image/CITYOFFICIAL/") ?>' + image
                  )
                : [];

            if (carouselImages.length > 0) {
                preloadImages(carouselImages).then(() => {
                    initializeCarousel('carouselContent', carouselImages, 'prevBtn', 'nextBtn');
                }).catch(err => {
                    console.error('Failed to preload images:', err);
                    initializeCarousel('carouselContent', carouselImages, 'prevBtn', 'nextBtn');
                });
            } else {
                console.log('No carousel images for this official');
                document.getElementById('carouselContent').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100"><p class="text-muted">No additional images available</p></div>';
            }

            // Show the modal
            $(modal).modal('show').on('shown.bs.modal', function () {
                $(this).find('.modal-title').focus();
            });
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr, status, error);
            alert('Failed to fetch official data. Please try again.');
        }
    });
}

// Enhanced Carousel Handling with Smooth Crossfade
function initializeCarousel(contentId, images, prevBtnId, nextBtnId) {
    console.log("Initializing carousel:", contentId);
    console.log("Images array:", images);

    const carouselContent = document.getElementById(contentId);
    const autoSlideInterval = 5000; // Auto-slide every 3 seconds
    let autoSlideTimer = null; // Timer for auto-slide
    
    if (!carouselContent) {
        console.error("Carousel container not found:", contentId);
        return;
    }
    
    if (!images || images.length === 0) {
        console.error("Images array is empty or undefined");
        carouselContent.innerHTML = '<div class="d-flex align-items-center justify-content-center h-100"><p class="text-muted">No images available</p></div>';
        return;
    }

    // Clear previous content and reset
    carouselContent.innerHTML = '';
    carouselContent.style.position = 'relative';
    carouselContent.style.minHeight = '200px'; // Ensure container has height
    
    let currentIndex = 0;
    let isTransitioning = false;

    // Create all images at once for smoother transitions
    const imageElements = images.map((src, index) => {
        const img = document.createElement('img');
        img.src = src;
        img.alt = `Official Image ${index + 1}`;
        img.style.cssText = `
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            opacity: ${index === 0 ? '1' : '0'};
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            object-fit: contain;
        `;
        
        // Add error handling for individual images
        img.onerror = function() {
            console.error('Failed to load image:', src);
            this.src = '<?= base_url("assets/img/binanlogo.png") ?>'; // Fallback image
        };
        
        img.onload = function() {
            console.log('Image loaded:', src);
        };
        
        return img;
    });

    // Append all images to the carousel
    imageElements.forEach(img => carouselContent.appendChild(img));

    console.log("Images added to carousel. Total:", imageElements.length);

    function changeToImage(newIndex, direction = 'next') {
        if (isTransitioning || newIndex === currentIndex || newIndex < 0 || newIndex >= images.length) {
            return;
        }

        isTransitioning = true;
        console.log(`Transitioning from ${currentIndex} to ${newIndex} (${direction})`);

        const currentImg = imageElements[currentIndex];
        const newImg = imageElements[newIndex];

        // Perform transition immediately
        currentImg.style.opacity = '0';
        newImg.style.opacity = '1';

        // Update current index after transition completes
        setTimeout(() => {
            currentIndex = newIndex;
            isTransitioning = false;
            console.log("Transition completed. Current index:", currentIndex);
        }, 600); // Match the CSS transition duration
    }

    function startAutoSlide() {
    if (autoSlideTimer) clearInterval(autoSlideTimer); // Clear any existing timer
    autoSlideTimer = setInterval(() => {
        if (!isTransitioning && images.length > 1) {
            const newIndex = (currentIndex + 1) % images.length;
            changeToImage(newIndex, 'next');
        }
    }, autoSlideInterval);
    console.log("Auto-slide started with interval:", autoSlideInterval, "ms");
}

    // Enhanced button event handlers
    const prevButton = document.getElementById(prevBtnId);
    const nextButton = document.getElementById(nextBtnId);

    if (prevButton) {
        // Remove any existing listeners and add new one
        const newPrevButton = prevButton.cloneNode(true);
        prevButton.parentNode.replaceChild(newPrevButton, prevButton);
        
        newPrevButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (!isTransitioning && images.length > 1) {
                const newIndex = (currentIndex - 1 + images.length) % images.length;
                changeToImage(newIndex, 'prev');
            }
        });
        console.log("Previous button event listener attached");
    } else {
        console.warn("Previous button not found:", prevBtnId);
    }

    if (nextButton) {
        // Remove any existing listeners and add new one
        const newNextButton = nextButton.cloneNode(true);
        nextButton.parentNode.replaceChild(newNextButton, nextButton);
        
        newNextButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (!isTransitioning && images.length > 1) {
                const newIndex = (currentIndex + 1) % images.length;
                changeToImage(newIndex, 'next');
            }
        });
        console.log("Next button event listener attached");
    } else {
        console.warn("Next button not found:", nextBtnId);
    }

    // Optional: Add keyboard navigation
    const handleKeyPress = (e) => {
        if (!isTransitioning && images.length > 1) {
            if (e.key === 'ArrowLeft') {
                const newIndex = (currentIndex - 1 + images.length) % images.length;
                changeToImage(newIndex, 'prev');
            } else if (e.key === 'ArrowRight') {
                const newIndex = (currentIndex + 1) % images.length;
                changeToImage(newIndex, 'next');
            }
        }
    };

    // Add keyboard listener when modal is open
    document.addEventListener('keydown', handleKeyPress);
    
    // Clean up keyboard listener when modal closes
    $('#officialModal').one('hidden.bs.modal', () => {
        document.removeEventListener('keydown', handleKeyPress);
    });

    if (images.length > 1) {
    startAutoSlide();
}

    console.log("Carousel initialization completed");
    
    // Show first image indicators if you have them
    if (images.length > 1) {
        console.log("Carousel ready with", images.length, "images");
    }
}

// Event Listeners
$(document).ready(function() {
    $(document).on('click', '.view-official-btn', function() {
        const officialId = $(this).data('id');
        const position = $(this).data('position');
        openOfficialInfoModal(officialId, position);
    });

    $('#officialModal').on('hidden.bs.modal', function () {
        $('.view-official-btn').focus();
        if (autoSlideTimer) clearInterval(autoSlideTimer);
        document.getElementById('carouselContent').innerHTML = '';
    });
});
</script>