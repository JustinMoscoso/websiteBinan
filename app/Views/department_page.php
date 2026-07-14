<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Departments</title>
  <!-- Favicons -->
  <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
  <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
  <?php pre_styles('home'); ?>
</head>

<body>
<?php include "navbar.php"; ?>
<?php include "header.php"; ?>
    <?php include_header('Departments',null,[
        'layout' => 'side',
        'bg_color' => '#388e3c']); ?>

  <!-- Search Bar Start -->
  <div class="container my-4">
      <div class="row">
          <div class="col-md-6 col-lg-4">
              <form action="<?= base_url('/department') ?>" method="get">
                  <div class="input-group">
                      <input type="text" name="search" class="form-control" placeholder="Search departments..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                      <button class="btn btn-success" type="submit">Search</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <!-- Search Bar End -->

  <!-- ======= departments Section ======= -->
  <section id="departments" class="departments">
    <div class="container py-5">
      <div class="row justify-content-center">

      <!--Echo from database department-->
        <?php if (!empty($depts)): ?>
          <?php foreach ($depts as $data): ?>
            <div class='col-lg-4 col-md-6 d-flex align-items-stretch mt-4 justify-content-center' data-aos='zoom-in' data-aos-delay='200'>
              <a href="<?= base_url('/departmentcontent/' . $data->ID) ?>" class="text-decoration-none" style="width: 100%">
                <div class='icon-box iconbox-blue border border-3 h-100' style='width: 100%; border-color: #388E3C;'>
                  <div class='icon'>
                    <img class='dept-logo img-fluid' src="<?= base_url('admin/image/DEPT/' . $data->img_logo) ?>"></img>
                    <i class='bx bx-arch'></i>
                  </div>
                  <h4 class="text-dark text-center">
                    <?= htmlspecialchars($data->dept_name) ?>
                  </h4>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center mb-0">No records found.</p>
        <?php endif; ?>
      </div>
    </div>
  </section><!-- End departments Section -->
  
  <?php include "footer.php"; ?>
	<?php pre_scripts('home'); ?>    

</body>

</html>
