<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $title; ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="icon">
  <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">

  <?php pre_styles('admin'); ?>
</head>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="<?= base_url('admin/dashboard') ?>" class="logo d-flex align-items-center">
        <img src="<?= site_url('assets/img/binanlogo.png'); ?>" alt="">
        <div class="d-flex flex-column align-items-start" style="margin-left: 10px;"> <!-- Adjust the margin-left value as needed -->
            <span style="font-size: 12px; font-family: 'Gill Sans'; font-weight: 600;">REPUBLIC OF THE PHILIPPINES</span>
              <hr style="width: 100%; margin: 5px 0; border: none; border-top: 2px solid #000;"> <!-- Adjust the margin and border-top color as needed -->
            <span style="font-size: 14px; font-family: 'Gill Sans'; font-weight: 1000;">CITY GOVERNMENT OF BIÑAN</span>
        </div>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <!--
    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div> -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">



        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-person-circle"></i>    
          <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $user->fname . ' ' . $user->lname; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $user->fname . ' ' . $user->lname; ?></h6>
              <span><?php echo $user->user_lvl; ?></span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
<!--
            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
-->
            <li>
              <a class="dropdown-item d-flex align-items-center" href="<?= site_url('auth/logout'); ?>">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'dashboard' ? '' : 'collapsed' ?>" href="<?= $mode == 'dashboard' ? '#' : site_url('admin/dashboard') ?>">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
      <?php
        // Privileged roles always see the full sidebar regardless of account_type
        $privilegedRoles = ['DEVELOPER', 'SUPERADMIN', 'ADMIN'];
        $isEntityAccount = !in_array($user->user_lvl, $privilegedRoles)
                           && in_array($user->account_type ?? '', ['DEPARTMENT', 'BARANGAY']);
        $showBrgy        = !in_array($user->user_lvl, $privilegedRoles)
                           ? ($user->account_type ?? '') !== 'DEPARTMENT'
                           : true;
        $showDept        = !in_array($user->user_lvl, $privilegedRoles)
                           ? ($user->account_type ?? '') !== 'BARANGAY'
                           : true;
      ?>

      <li class="nav-heading">Content Management</li>

      <?php if (!$isEntityAccount): ?>
      <li class="nav-item">
        <a class="nav-link <?= $mode == 'postcontent' ? '' : 'collapsed' ?>" href="<?= $mode == 'postcontent' ? '#' : site_url('admin/postcontent') ?>">
        <i class="bi bi-newspaper"></i>
          <span>Post Content</span>
        </a>
      </li><!-- End Post Content Nav -->

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'mayor' ? '' : 'collapsed' ?>" href="<?= $mode == 'mayor' ? '#' : site_url('admin/mayor') ?>">
          <i class="bi bi-person-square"></i>
          <span>Mayor's Corner</span>
        </a>
      </li><!-- End Mayor's Corner Nav -->

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'about' ? '' : 'collapsed' ?>" href="<?= $mode == 'about' ? '#' : site_url('admin/about') ?>">
          <i class="bi bi-info-circle"></i>
          <span>About / Homepage</span>
        </a>
      </li><!-- End About Nav -->
      <?php endif; ?>

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'services' ? '' : 'collapsed' ?>" href="<?= $mode == 'services' ? '#' : site_url('admin/services') ?>">
          <i class="bi bi-patch-check"></i>
          <span>Services</span>
        </a>
      </li><!-- End Services Nav -->

      <?php if ($showBrgy): ?>
      <li class="nav-item">
        <a class="nav-link <?= $mode == 'brgy' ? '' : 'collapsed' ?>" href="<?= $mode == 'brgy' ? '#' : site_url('admin/brgy') ?>">
          <i class="bi bi-houses"></i>
          <span>Barangay</span>
        </a>
      </li><!-- End barangay Nav -->
      <?php endif; ?>

      <?php if ($showDept): ?>
      <li class="nav-item">
        <a class="nav-link <?= $mode == 'dept' ? '' : 'collapsed' ?>" href="<?= $mode == 'dept' ? '#' : site_url('admin/dept') ?>">
          <i class="bi bi-bank"></i>
          <span>Departments</span>
        </a>
      </li><!-- End department Nav -->
      <?php endif; ?>

      <?php if (!$isEntityAccount): ?>
      <li class="nav-item">
        <a class="nav-link <?= $mode == 'cityOff' ? '' : 'collapsed' ?>" href="<?= $mode == 'cityOff' ? '#' : site_url('admin/cityOff') ?>">
          <i class="bi bi-people"></i>
          <span>City Officials</span>
        </a>
      </li><!-- End City Officials Nav -->

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'fullDisc' ? '' : 'collapsed' ?>" href="<?= $mode == 'fullDisc' ? '#' : site_url('admin/fullDisc') ?>">
          <i class="bi bi-card-list"></i>
          <span>Full Disclosure Policy</span>
        </a>
      </li><!-- End Full Disclosure Policy Nav -->
        <!-- Hide Map for now -->
      <!-- <li class="nav-item">
        <a class="nav-link <?= $mode == 'map' ? '' : 'collapsed' ?>" href="<?= $mode == 'map' ? '#' : site_url('admin/map') ?>">
          <i class="bi bi-map"></i>
          <span>Map</span>
        </a>
      </li>End Map Nav -->

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'careers' ? '' : 'collapsed' ?>" href="<?= $mode == 'careers' ? '#' : site_url('admin/careers') ?>">
          <i class="bi bi-briefcase"></i>
          <span>Careers</span>
        </a>
      </li><!-- End Careers Nav -->

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'jobs' ? '' : 'collapsed' ?>" href="<?= $mode == 'jobs' ? '#' : site_url('admin/jobs') ?>">
          <i class="bi bi-person-workspace"></i>
          <span>Job Management</span>
        </a>
      </li><!-- End Jobs Nav -->

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'invest' ? '' : 'collapsed' ?>" href="<?= $mode == 'invest' ? '#' : site_url('admin/invest') ?>">
          <i class="bi bi-cash-stack"></i>
          <span>Invest</span>
        </a>
      </li><!-- End Invest Nav -->
      
      <li class="nav-item">
        <a class="nav-link <?= $mode == 'contacts' ? '' : 'collapsed' ?>" href="<?= $mode == 'contacts' ? '#' : site_url('admin/contacts') ?>">
          <i class="bi bi-telephone"></i>
          <span>Contacts</span>
        </a>
      </li><!-- End Contacts Nav -->
      <?php endif; ?>



      <?php if (in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN'])): ?>
      <li class="nav-heading">Admin</li>

      <li class="nav-item">
        <a class="nav-link <?= $mode == 'accounts_mgmt' ? '' : 'collapsed' ?>" href="<?= $mode == 'accounts_mgmt' ? '#' : site_url('admin/accounts_mgmt') ?>">
        <i class="bi bi-person-gear"></i>
          <span>Account Management</span>
        </a>
      </li><!-- End Profile Page Nav -->
      <?php endif; ?>
      <?php if (in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN'])): ?>
      <li class="nav-item">
        <a class="nav-link <?= $mode == 'audit' ? '' : 'collapsed' ?>" href="<?= $mode == 'audit' ? '#' : site_url('admin/audit') ?>">
          <i class="bi bi-shield-shaded"></i>
          <span>System Logs</span>
        </a>
      </li>
      <?php endif; ?>


      
      <!-- End Login Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->

  <body>
    <!-- Main Content Placeholder -->
    <main id="main" class="main">
    <?php
        // Define the path to the view file based on the $mode variable
        $viewFilePath = APPPATH . 'Views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'mod' . DIRECTORY_SEPARATOR . $mode . '.php';

        // Check if the view file exists
        if (file_exists($viewFilePath)) {
            // Load the view and pass any provided data (or an empty array)
            echo view('admin/mod/' . $mode, isset($data) && is_array($data) ? $data : []);
        }
        ?>
    </main><!-- End Main -->


    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
          &copy; Copyright <strong><span>Biñan City Official Website</span></strong>. All Rights Reserved
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<?php
  pre_scripts('admin');
  echo view('admin/common-js.php');

  $jsfile = APPPATH . 'Views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $mode . '.php';

  if (file_exists($jsfile)) {
      // Pass only the known view variables explicitly — get_defined_vars() can leak
      // CI4 internal renderer variables and corrupt the sub-view render cycle.
      $jsViewData = ['user' => $user, 'mode' => $mode, 'title' => $title ?? ''];
      echo view('admin' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $mode, $jsViewData);
  }

?>
  </body>
</html>
