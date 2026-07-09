<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>

	<!-- Favicons -->
	<link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
	<link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
	<?php pre_styles('home'); ?>
</head>

<body>
	<?php include "navbar.php"; ?>
	<style>
		.mayor-fixed-row {
			display: flex;
			align-items: center;
			gap: 48px;
			min-height: 400px;
		}

		.mayor-fixed-image {
			flex: 0 0 330px;
			display: flex;
			justify-content: center;
		}

		.mayor-fixed-text {
			flex: 1 1 auto;
			max-width: 760px;
		}

		.mayor-message-content {
			min-height: 150px;
			overflow-wrap: anywhere;
		}

		@media (max-width: 767.98px) {
			.mayor-fixed-row {
				display: block;
				min-height: unset;
			}

			.mayor-fixed-image {
				display: flex;
				justify-content: center;
				margin-bottom: 1.5rem;
			}

			.mayor-fixed-text {
				max-width: none;
			}

			.mayor-message-content {
				min-height: unset;
			}
		}
	</style>
	<!--MAIN-->
	<section data-aos="fade-up" id="hero" class="hero position-relative" style="overflow: hidden; min-height: 100vh;">
		<!-- Responsive Video Background -->
		<div class="video-container position-absolute top-0 start-0 w-100 h-100 z-n1">
			<video autoplay muted loop playsinline class="w-100 h-100" style="object-fit: cover;">
				<source src="assets/video/binanclip.mp4" type="video/mp4">
				Your browser does not support the video tag.
			</video>
			<div class="overlay position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.5);">
			</div>
		</div>

		<!-- Content Over Video -->
		<div class="info d-flex align-items-center min-vh-100 text-white text-center" style="min-height: 100vh;">
			<div class="container py-5">
				<div class="hero-content">
					<img class="img-fluid mx-auto d-block hero-img-responsive"
						src="assets/img/hero4.png" alt="Biñan City Hero Image">
					<!-- <h2 class="hero-subtitle" style="font-size: 3rem;">Mabuhay!</h2>
					<h1 class="hero-title" style="font-size: 4rem; font-family: 'Poppins'">Welcome to the City of Biñan</h1>
					<h2 class="hero-tagline" style="font-size: 3rem;">The City of Life</h2> -->
				</div>
			</div>
		</div>
	</section>

	<!-- Mayor's Message -->
	<section data-aos="fade-up" class="sec mayorsec py-5" id="mayorsec">
		<?php
		$mayor_images = [];
		if (!empty($mayor_content['mayor_img'])) {
			$decoded_mayor_images = json_decode($mayor_content['mayor_img'], true);
			$mayor_images = is_array($decoded_mayor_images) ? array_values(array_filter($decoded_mayor_images)) : [];
		}
		$mayor_image_src = !empty($mayor_images)
			? base_url('admin/image/MAYOR/' . $mayor_images[0])
			: base_url('assets/img/mayor-silhouette.svg');
		$mayor_image_alt = !empty($mayor_images) ? "Mayor's Image" : 'Mayor silhouette placeholder';
		?>
		<div class="container-fluid mayorbox border border-5 p-4 p-md-5" style="min-height: 500px;">
			<div class="mayor-fixed-row">
				<!-- Mayor Image -->
				<div class="mayor-fixed-image">
					<div class="mayor-img-wrapper <?= empty($mayor_images) ? 'mayor-img-placeholder' : '' ?>">
						<img src="<?= $mayor_image_src ?>" class="img-fluid rounded"
							alt="<?= esc($mayor_image_alt) ?>" style="width: 100%; max-width: 300px; height: auto;">
					</div>
				</div>

				<!-- Mayor Text Content -->
				<div class="mayor-fixed-text">
					<h1 class="mayorheader mb-3">
						<b>Mayor's Message</b>
					</h1>
					<?php if (!empty($mayor_content)): ?>
						<div class="mayor-message-content"
							style="font-size: 16px; color:#004600; font-family: 'Gill Sans', sans-serif;">
							<?= $mayor_content['content'] ?>
						</div>
					<?php endif; ?>

					<div class="text-center text-md-start mt-4">
						<a href="<?= base_url('/mayor') ?>" class="mayorbtn btn fw-bold">
							Go to Mayor's Profile
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Mayor's Message End -->
	<!--Know More-->
	<!-- Centering Wrapper -->
	<div class="d-flex justify-content-center align-items-center">
		<section data-aos="fade-up" id="knowmore" class="knowmore" style="background: linear-gradient(to bottom, rgba(34, 70, 34, 0.85), transparent), url('<?= base_url('assets/img/history.svg'); ?>');
		   background-size: cover;
		   background-position: center;
		   background-repeat: no-repeat;
		   padding: 2rem;
		   width: 100%;
		   min-height: 350px;">
			<div class="row justify-content-center m-0">
				<div class="col-lg-6 text-center p-5">
					<h1 class="display-2 mb-0">
						<?= isset($knowmore['title']) ? esc($knowmore['title']) : 'Biñan City' ?>
					</h1>
					<p class="text-white">
						<?= isset($knowmore['description']) ? esc(trim(strip_tags(html_entity_decode($knowmore['description'])))) : 'No content' ?>
					</p>
					<div class="morebutton text-uppercase mx-auto d-block">
						<a href="<?= base_url('/history') ?>">Know more about Biñan</a>
					</div>
				</div>
			</div>
		</section>
	</div>

	<!--Know More End-->

	<!-- News and Events/ Announcements -->
	<section data-aos="fade-up" class="newsevents py-4">
		<div class="container">
			<div
				class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 border-bottom border-3 pb-2">
				<h2 class="fw-bold mb-3 mb-md-0" style="font-size: clamp(24px, 4vw, 30px); color: #388E3C;">NEWS &
					ANNOUNCEMENTS</h2>
			</div>

			<!-- Tabs Nav -->
			<ul class="nav nav-tabs nav-fill mb-3" id="newsTabs" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="news-tab" data-bs-toggle="tab" data-bs-target="#news"
						type="button" role="tab">News and Events</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements"
						type="button" role="tab">Announcements</button>
				</li>
			</ul>

			<!-- News and Events Tab -->
			<div class="tab-content" id="newsTabsContent">
				<!-- News Tab -->
				<div data-aos="fade-up" class="tab-pane fade show active" id="news" role="tabpanel">
					<div class="row g-3">
						<?php if (!empty($news_events)): ?>
							<?php foreach (array_slice($news_events, 0, 3) as $news): ?>
								<div class="col-12 col-lg-4 mb-4">
									<div class="card h-100 shadow-sm d-flex flex-column">
										<div class="card-img-wrapper"
											style="position: relative; overflow: hidden; border-radius: 15px 15px 0 0;">
											<img src="<?= base_url('admin/image/POSTCONTENT/' . $news->file_loc) ?>"
												alt="News Image" class="card-img-top"
												style="width: 100%; height: 200px; object-fit: cover; transition: transform 0.3s ease; border-radius: 15px 15px 0 0;">
										</div>
										<div class="card-body d-flex flex-column p-3">
											<div class="small text-muted mb-2">
												<?= date('M d, Y', strtotime($news->created_date)) ?>
											</div>
											<h5 class="card-title fw-bold mb-2"
												style="color: black; font-size: clamp(16px, 3vw, 18px); line-height: 1.3;">
												<?= htmlspecialchars($news->title) ?>
											</h5>
											<p class="card-text flex-grow-1 mb-3"
												style="font-size: 14px; line-height: 1.4; text-align: justify;">
												<?= htmlspecialchars(substr(strip_tags($news->description), 0, 100)) ?>...
											</p>
											<a href="<?= base_url('/newseventscontent/' . $news->ID) ?>"
												class="btn btn-outline-success btn-sm btn-readmore mt-auto">Read More</a>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="col-12">
								<p class="text-center">No news available.</p>
							</div>
						<?php endif; ?>
					</div>
					<!-- SEE ALL Button under News (Centered) -->
					<div class="d-flex justify-content-center mt-4">
						<a id="seeAllBtnNews" href="<?= base_url('/newsevents/1') ?>"
							class="btn btn-outline-success fw-bold px-4 py-2">SEE ALL NEWS</a>
					</div>
				</div>

				<!-- Announcements Tab -->
				<div class="tab-pane fade" id="announcements" role="tabpanel">
					<div class="row g-3">
						<?php if (!empty($announcements)): ?>
							<?php foreach (array_slice($announcements, 0, 3) as $announcement): ?>
								<div class="col-12 col-lg-4 mb-4">
									<div class="card h-100 shadow-sm d-flex flex-column">
										<div class="card-img-wrapper"
											style="position: relative; overflow: hidden; border-radius: 15px 15px 0 0;">
											<img src="<?= base_url('admin/image/POSTCONTENT/' . $announcement->file_loc) ?>"
												alt="Announcement Image" class="card-img-top"
												style="width: 100%; height: 200px; object-fit: cover; transition: transform 0.3s ease; border-radius: 15px 15px 0 0;">
										</div>
										<div class="card-body d-flex flex-column p-3">
											<div class="small text-muted mb-2">
												<?= date('M d, Y', strtotime($announcement->created_date)) ?>
											</div>
											<h5 class="card-title fw-bold mb-2"
												style="color: black; font-size: clamp(16px, 3vw, 18px); line-height: 1.3;">
												<?= htmlspecialchars($announcement->title) ?>
											</h5>
											<p class="card-text flex-grow-1 mb-3"
												style="font-size: 14px; line-height: 1.4; text-align: justify;">
												<?= htmlspecialchars(substr(strip_tags($announcement->description), 0, 80)) ?>...
											</p>
											<a href="<?= base_url('/announcementcontent/' . $announcement->ID) ?>"
												class="btn btn-outline-success btn-sm btn-readmore mt-auto">Read More</a>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="col-12">
								<p class="text-center">No announcements available.</p>
							</div>
						<?php endif; ?>
					</div>
					<!-- SEE ALL Button under Announcements (Centered) -->
					<div class="d-flex justify-content-center mt-4">
						<a id="seeAllBtnAnnouncements" href="<?= base_url('/announcements/1') ?>"
							class="btn btn-outline-success fw-bold px-4 py-2">SEE ALL ANNOUNCEMENTS</a>
					</div>
				</div>
			</div>
		</div>
	</section>


	<!--Barangays and Dept -->
	<section data-aos="fade-up" id="brgydept" class="my-5">
		<div class="container">
			<div class="row g-3">
				<div class="col-12 col-sm-6 col-lg-3 mb-4">
					<div class="info-card d-flex position-relative overflow-hidden h-100"
						style="background: linear-gradient(135deg, #388E3C 0%, #2E7D32 100%); box-shadow: 0 8px 25px rgba(56, 142, 60, 0.3); border-radius: 15px; transition: transform 0.3s ease, box-shadow 0.3s ease; min-height: 180px;">

						<!-- Background Icon -->
						<div class="position-absolute"
							style="top: -20px; right: -20px; opacity: 0.1; font-size: clamp(4rem, 10vw, 6rem);">
							<i class="fas fa-home"></i>
						</div>

						<div
							class="w-100 p-3 p-md-5 d-flex align-items-center flex-column justify-content-center text-center position-relative z-index-1">
							<!-- Icon -->
							<div class="mb-2 mb-md-4"
								style="font-size: clamp(3rem, 8vw, 2.5rem); color: rgba(255,255,255,0.9);">
								<i class="fas fa-map-marked-alt"></i>
							</div>

							<div class="d-flex align-items-center flex-column text-white info-card-title">
								<h1 class="mb-2 mb-md-4 fw-bold"
									style="font-size: clamp(2rem, 5vw, 2rem); line-height: 1.2;">Barangays</h1>
								<p class="mb-3 mb-md-4 text-center px-2"
									style="font-size: clamp(1.2rem, 3.5vw, 1.1rem); opacity: 0.9; line-height: 1.4;">
									Explore all <strong>24 barangays</strong> of Biñan City
								</p>
							</div>

							<div class="home-btn text-uppercase mx-auto d-block mt-auto">
								<a href="<?= base_url('/barangays') ?>" class="btn btn-orange px-5 py-2 py-md-3 fw-bold"
									style="border-radius: 25px; transition: all 0.3s ease; font-size: clamp(1.2rem, 3vw, 1.1rem);">
									<i class="fas fa-arrow-right me-1"></i>VIEW
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3 mb-4">
					<div class="info-card d-flex position-relative overflow-hidden h-100"
						style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3); border-radius: 15px; transition: transform 0.3s ease, box-shadow 0.3s ease; min-height: 180px;">

						<!-- Background Icon -->
						<div class="position-absolute"
							style="top: -20px; right: -20px; opacity: 0.1; font-size: clamp(4rem, 10vw, 6rem);">
							<i class="fas fa-building"></i>
						</div>

						<div
							class="w-100 p-3 p-md-5 d-flex align-items-center flex-column justify-content-center text-center position-relative z-index-1">
							<!-- Icon -->
							<div class="mb-2 mb-md-4"
								style="font-size: clamp(3rem, 8vw, 2.5rem); color: rgba(255,255,255,0.9);">
								<i class="fas fa-users-cog"></i>
							</div>

							<div class="d-flex align-items-center flex-column text-white info-card-title">
								<h1 class="mb-2 mb-md-4 fw-bold"
									style="font-size: clamp(2rem, 5vw, 2rem); line-height: 1.2;">Departments</h1>
								<p class="mb-3 mb-md-4 text-center px-2"
									style="font-size: clamp(1.2rem, 3.5vw, 1.1rem); opacity: 0.9; line-height: 1.4;">
									Connect with the city government offices & services
								</p>
							</div>

							<div class="home-btn text-uppercase mx-auto d-block mt-auto">
								<a href="<?= base_url('/department') ?>" class="btn btn-light px-5 py-2 py-md-3 fw-bold"
									style="border-radius: 25px; transition: all 0.3s ease; font-size: clamp(1.2rem, 3vw, 1.1rem);">
									<i class="fas fa-arrow-right me-1"></i>VIEW
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3 mb-4">
					<div class="info-card d-flex position-relative overflow-hidden h-100"
						style="background: linear-gradient(135deg, #388E3C 0%, #2E7D32 100%); box-shadow: 0 8px 25px rgba(56, 142, 60, 0.3); border-radius: 15px; transition: transform 0.3s ease, box-shadow 0.3s ease; min-height: 180px;">

						<!-- Background Icon -->
						<div class="position-absolute"
							style="top: -20px; right: -20px; opacity: 0.1; font-size: clamp(4rem, 10vw, 6rem);">
							<i class="fas fa-cogs"></i>
						</div>

						<div
							class="w-100 p-3 p-md-5 d-flex align-items-center flex-column justify-content-center text-center position-relative z-index-1">
							<!-- Icon -->
							<div class="mb-2 mb-md-4"
								style="font-size: clamp(3rem, 8vw, 2.5rem); color: rgba(255,255,255,0.9);">
								<i class="fas fa-hands-helping"></i>
							</div>

							<div class="d-flex align-items-center flex-column text-white info-card-title">
								<h1 class="mb-2 mb-md-4 fw-bold"
									style="font-size: clamp(2rem, 5vw, 2rem); line-height: 1.2;">eServices</h1>
								<p class="mb-3 mb-md-4 text-center px-2"
									style="font-size: clamp(1.2rem, 3.5vw, 1.1rem); opacity: 0.9; line-height: 1.4;">
									Access the city online transactions
								</p>
							</div>

							<div class="home-btn text-uppercase mx-auto d-block mt-auto">
								<a href="<?= base_url('/services') ?>" class="btn btn-orange px-5 py-2 py-md-3 fw-bold"
									style="border-radius: 25px; transition: all 0.3s ease; font-size: clamp(1.2rem, 3vw, 1.1rem);">
									<i class="fas fa-arrow-right me-1"></i>VIEW
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-6 col-lg-3 mb-4">
					<div class="info-card d-flex position-relative overflow-hidden h-100"
						style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); box-shadow: 0 8px 25px rgba(156, 39, 176, 0.3); border-radius: 15px; transition: transform 0.3s ease, box-shadow 0.3s ease; min-height: 180px;">

						<!-- Background Icon -->
						<div class="position-absolute"
							style="top: -20px; right: -20px; opacity: 0.1; font-size: clamp(4rem, 10vw, 6rem);">
							<i class="fas fa-briefcase"></i>
						</div>

						<div
							class="w-100 p-3 p-md-5 d-flex align-items-center flex-column justify-content-center text-center position-relative z-index-1">
							<!-- Icon -->
							<div class="mb-2 mb-md-4"
								style="font-size: clamp(3rem, 8vw, 2.5rem); color: rgba(255,255,255,0.9);">
								<i class="fas fa-user-tie"></i>
							</div>

							<div class="d-flex align-items-center flex-column text-white info-card-title">
								<h1 class="mb-2 mb-md-4 fw-bold"
									style="font-size: clamp(2rem, 5vw, 2rem); line-height: 1.2;">Jobs</h1>
								<p class="mb-3 mb-md-4 text-center px-2"
									style="font-size: clamp(1.2rem, 3.5vw, 1.1rem); opacity: 0.9; line-height: 1.4;">
									Find job opportunities & career openings
								</p>
							</div>

							<div class="home-btn text-uppercase mx-auto d-block mt-auto">
								<a href="<?= base_url('/jobs') ?>" class="btn btn-light px-5 py-2 py-md-3 fw-bold"
									style="border-radius: 25px; transition: all 0.3s ease; font-size: clamp(1.2rem, 3vw, 1.1rem);">
									<i class="fas fa-arrow-right me-1"></i>VIEW
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- =========================================
	EMERGENCY HOTLINES
========================================= -->

	<section class="py-5 hotline-section"
		style="background-image: url('<?= base_url('assets/img/Emergency_Hotline/emergencyBack.png') ?>');">>

		<div class="container hotline-container">

			<div class="text-center hotline-header">
				<span class="badge rounded-pill hotline-top-label">
					24/7 PUBLIC SAFETY SERVICES
				</span>
				<h2 class="fw-bold text-white hotline-main-title">
					Emergency Hotlines
				</h2>
				<p class="text-light hotline-subtitle">
					Quick access to emergency response and public safety services
					within the City of Biñan.
				</p>
			</div>

			<div class="row g-3 hotline-grid-wrapper">

				<?php if (!empty($emergency_hotlines)): ?>

				<?php foreach ($emergency_hotlines as $hotline): ?>
					<?php
						$icon = 'fas fa-phone-alt';
						$image = 'assets/img/default.png';

						if (!empty($hotline->about_img)) {
							$image = 'admin/image/ABOUT/' . $hotline->about_img;
						} elseif (stripos($hotline->title, 'Police') !== false) {
							$icon = 'fas fa-shield-alt';
							$image = 'assets/img/Emergency_Hotline/PNP.png';
						} elseif (stripos($hotline->title, 'Fire') !== false) {
							$icon = 'fas fa-fire-extinguisher';
							$image = 'assets/img/Emergency_Hotline/BFP.png';
						} elseif (stripos($hotline->title, 'Hospital') !== false) {
							$icon = 'fas fa-hospital';
							$image = 'assets/img/Emergency_Hotline/BCH.png';
						} elseif (stripos($hotline->title, 'Disaster') !== false) {
							$icon = 'fas fa-exclamation-triangle';
							$image = 'assets/img/Emergency_Hotline/BCDRRM.png';
						}
						?>

						<div class="col-lg-6 col-12">

							<div class="row mx-0 hotline-row h-100">

								<div class="col-3 d-flex align-items-center justify-content-center hotline-col">
									<div class="hotline-logo-box">
										<img src="<?= base_url($image) ?>" alt="Hotline Image" class="hotline-logo">
									</div>
								</div>

								<div class="col-5 d-flex align-items-center hotline-col">
									<div class="hotline-title-wrapper w-100">
										<h6 class="fw-bold text-dark mb-0 hotline-title">
											<?= htmlspecialchars($hotline->title) ?>
										</h6>
									</div>
								</div>

								<div class="col-4 d-flex align-items-center hotline-desc-col">
									<div class="w-100 hotline-description-wrapper">
										<div class="hotline-description">
											<?= $hotline->description ?>
										</div>
									</div>
								</div>

							</div>

						</div>

					<?php endforeach; ?>
				<?php endif; ?>

			</div>

			<div class="text-center hotline-button-wrapper">
				<a href="<?= base_url('/hotlines') ?>" class="btn btn-outline-light hotline-btn fw-semibold">
					<i class="fas fa-phone-alt me-2"></i>
					View Complete Hotline Directory
				</a>
			</div>

		</div>

	</section>

	<style>
		/* SECTION */
		.hotline-section {
			position: relative;
			padding-top: 60px !important;
			padding-bottom: 60px !important;
			/* Reference your image source here dynamically via PHP or static path */
			background-image: url('<?= base_url("assets/img/Emergency_Hotline/emergencyBack.png") ?>');
			background-size: cover;
			background-position: center;
			background-repeat: no-repeat;
			z-index: 1;
		}

		/* Dark Overlay/Opacity layer to make text readable */
		.hotline-section::before {
			content: "";
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			/* Adjust the 0.85 opacity value below to make the photo lighter or darker */
			background-color: rgba(20, 20, 20, 0.68);
			z-index: -1;
		}

		.hotline-container {
			max-width: 1200px;
			/* Widened slightly to give 2 side-by-side cards breathing room */
		}

		/* HEADER */
		.hotline-header {
			margin-bottom: 40px;
		}

		.hotline-top-label {
			background: rgba(255, 255, 255, 0.10);
			color: white;
			font-size: 11px;
			letter-spacing: 0.5px;
			padding: 8px 18px;
			margin-bottom: 15px;
			display: inline-block;
		}

		.hotline-main-title {
			font-size: clamp(1.75rem, 3vw, 2.25rem);
			margin-bottom: 12px;
		}

		.hotline-subtitle {
			max-width: 650px;
			margin: auto;
			opacity: 0.8;
			font-size: 0.95rem;
			line-height: 1.6;
		}

		/* GRID LIST CONTAINER wrapper */
		.hotline-grid-wrapper {
			margin-bottom: 0;
		}

		/* CARD CONTAINER ROW */
		.hotline-row {
			background: white;
			/* Cream background color */
			border: 1px solid #dcdfe3;
			border-radius: 8px;
			overflow: hidden;
			padding-top: 14px;
			padding-bottom: 14px;
			transition: all 0.25s ease;
		}

		.hotline-row:hover {
			transform: translateY(-2px);
			box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
			border-color: #c9ced6;
		}

		/* COLUMNS */
		.hotline-col {
			position: relative;
		}

		/* VERTICAL SEPARATOR LINE */
		.hotline-col::after {
			content: "";
			position: absolute;
			top: 4px;
			bottom: 4px;
			right: 2px;
			width: 1px;
			background: #000000ff;
		}

		/* LOGO BOX */
		.hotline-logo-box {
			width: 85px;
			height: 85px;
			border-radius: 6px;
			background: transparent;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.hotline-logo {
			width: 85px;
			height: 85px;
			object-fit: contain;
		}

		.hotline-title-wrapper {
			display: flex;
			align-items: center;
			padding-left: 8px;
			padding-right: 4px;
		}

		.hotline-title {
			margin: 0;
			font-size: 0.88rem;
			/* Scaled down slightly to fit 2-column layouts beautifully */
			line-height: 1.4;
			color: #212529;
		}

		/* DESCRIPTION / PHONE NUMBERS */
		.hotline-description-wrapper {
			padding-left: 0;
			padding-right: 0;
			text-align: center;
		}

		.hotline-description {
			font-size: 13px;
			font-weight: 500;
			color: #333333;
			line-height: 1.5;
			text-align: center;
		}

		.hotline-description p {
			text-align: center !important;
			margin-bottom: 0.25rem;
		}

		.hotline-description p:last-child {
			margin-bottom: 0;
		}

		/* FOOTER DIRECTORY BUTTON */
		.hotline-button-wrapper {
			margin-top: 45px;
		}

		.hotline-btn {
			padding: 10px 28px !important;
			border-radius: 999px;
			font-size: 14px;
			letter-spacing: 0.4px;
			transition: all 0.3s ease;
		}

		/* RESPONSIVE LAYOUT FALLBACKS */
		@media (max-width: 991px) {
			.hotline-title {
				font-size: 0.85rem;
			}

			.hotline-description {
				font-size: 12px;
			}
		}

		@media (max-width: 575px) {
			.hotline-col::after {
				display: none;
			}

			.hotline-title-wrapper {
				padding-left: 0;
			}

			.hotline-description-wrapper {
				padding: 8px 0 0 0;
			}

			.hotline-row {
				padding: 12px;
			}

			/* Stack sections vertically only on extra small mobile screens */
			.hotline-row>div {
				width: 100% !important;
				max-width: 100% !important;
				flex: 0 0 100% !important;
				text-align: center;
				justify-content: center !important;
			}

			.hotline-title-wrapper {
				justify-content: center;
				margin: 8px 0;
			}

			.hotline-logo-box {
				margin: auto;
			}
		}
	</style>
	</div>

	<?php include "footer.php"; ?>
	<?php pre_scripts('home'); ?>
</body>

</html>
