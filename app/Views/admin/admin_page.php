<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title><?php echo $title; ?></title>

  <!-- Favicons -->
  <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="icon">
  <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">

  <?php pre_styles('admin'); ?>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin/dashboard') ?>">
          <div class="sidebar-brand-icon">
              <img src="<?= site_url('assets/img/binanlogo.png'); ?>" alt="" style="width: 40px; height: 40px;">
          </div>
          <div class="sidebar-brand-text mx-3">Biñan Admin</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?= $mode == 'dashboard' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= site_url('admin/dashboard') ?>">
              <i class="fas fa-fw fa-tachometer-alt"></i>
              <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <?php
        // Privileged roles see the full sidebar UNLESS they are a dept/brgy-scoped ADMIN
        $privilegedRoles = ['DEVELOPER', 'SUPERADMIN'];
        $isDeptAdmin = ($user->user_lvl === 'ADMIN' && ($user->account_type ?? '') === 'DEPARTMENT');
        $isBrgyAdmin = ($user->user_lvl === 'ADMIN' && ($user->account_type ?? '') === 'BARANGAY');

        // ADMIN scoped to a department/barangay behaves like an entity account for sidebar
        $isEntityAccount = !in_array($user->user_lvl, $privilegedRoles)
                           && ($isDeptAdmin || $isBrgyAdmin
                               || in_array($user->account_type ?? '', ['DEPARTMENT', 'BARANGAY']));
        $showBrgy        = !in_array($user->user_lvl, $privilegedRoles)
                           ? ($user->account_type ?? '') !== 'DEPARTMENT' && !$isDeptAdmin
                           : true;
        $showDept        = !in_array($user->user_lvl, $privilegedRoles)
                           ? ($user->account_type ?? '') !== 'BARANGAY' && !$isBrgyAdmin
                           : true;
      ?>

      <!-- Heading -->
      <div class="sidebar-heading">
          Content Management
      </div>

      <?php if (!$isEntityAccount || ($is_mayor ?? false) || ($is_cio ?? false)): ?>
      <li class="nav-item <?= $mode == 'postcontent' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/postcontent') ?>">
        <i class="fas fa-fw fa-newspaper"></i>
          <span>Post Content</span>
        </a>
      </li>
      <li class="nav-item <?= $mode == 'mayor' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/mayor') ?>">
          <i class="fas fa-fw fa-user-tie"></i>
          <span>Mayor's Corner</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (!$isEntityAccount || ($is_cio ?? false)): ?>
      <li class="nav-item <?= $mode == 'about' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/about') ?>">
          <i class="fas fa-fw fa-info-circle"></i>
          <span>About / Homepage</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (($user->account_type ?? '') !== 'DEPARTMENT'): ?>
      <li class="nav-item <?= $mode == 'services' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/services') ?>">
          <i class="fas fa-fw fa-certificate"></i>
          <span>Services</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if ($showBrgy): ?>
      <li class="nav-item <?= $mode == 'brgy' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/brgy') ?>">
          <i class="fas fa-fw fa-home"></i>
          <span>Barangay</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if ($showDept): ?>
      <li class="nav-item <?= $mode == 'dept' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/dept') ?>">
          <i class="fas fa-fw fa-building"></i>
          <span>Departments</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (!$isEntityAccount || ($is_hrdo ?? false)): ?>
      <li class="nav-item <?= $mode == 'careers' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/careers') ?>">
          <i class="fas fa-fw fa-briefcase"></i>
          <span>Careers</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (!$isEntityAccount): ?>
      <li class="nav-item <?= $mode == 'cityOff' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/cityOff') ?>">
          <i class="fas fa-fw fa-users"></i>
          <span>City Officials</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (!$isEntityAccount || ($is_cio ?? false) || ($is_mayor ?? false)): ?>
      <li class="nav-item <?= $mode == 'fullDisc' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/fullDisc') ?>">
          <i class="fas fa-fw fa-file-alt"></i>
          <span>Full Disclosure Policy</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (!$isEntityAccount || ($is_peso ?? false)): ?>
      <li class="nav-item <?= $mode == 'jobs' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/jobs') ?>">
          <i class="fas fa-fw fa-user-md"></i>
          <span>Job Management</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (!$isEntityAccount || ($is_bplo ?? false)): ?>
      <li class="nav-item <?= $mode == 'invest' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/invest') ?>">
          <i class="fas fa-fw fa-coins"></i>
          <span>Invest</span>
        </a>
      </li>
      <?php endif; ?>
      
      <?php if (!$isEntityAccount || ($is_cio ?? false)): ?>
      <li class="nav-item <?= $mode == 'contacts' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/contacts') ?>">
          <i class="fas fa-fw fa-phone"></i>
          <span>Contacts</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN'])): ?>
      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
          Admin
      </div>

      <li class="nav-item <?= $mode == 'accounts_mgmt' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/accounts_mgmt') ?>">
        <i class="fas fa-fw fa-user-cog"></i>
          <span>Account Management</span>
        </a>
      </li>
      <?php endif; ?>

      <?php if (in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN'])): ?>
      <li class="nav-item <?= $mode == 'audit' ? 'active' : '' ?>">
        <a class="nav-link" href="<?= site_url('admin/audit') ?>">
          <i class="fas fa-fw fa-shield-alt"></i>
          <span>System Logs</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
          <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user->fname . ' ' . $user->lname; ?> (<?php echo $user->user_lvl; ?>)</span>
                    <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <?php
          // Define the path to the view file based on the $mode variable
          $viewFilePath = APPPATH . 'Views' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'mod' . DIRECTORY_SEPARATOR . $mode . '.php';

          // Check if the view file exists
          if (file_exists($viewFilePath)) {
              // Load the view and pass any provided data (or an empty array)
              echo view('admin/mod/' . $mode, isset($data) && is_array($data) ? $data : []);
          }
          ?>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
          <div class="container my-auto">
              <div class="copyright text-center my-auto">
                  <span>&copy; Copyright <strong>Biñan City Official Website</strong>. All Rights Reserved</span>
              </div>
          </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
              <div class="modal-footer">
                  <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                  <a class="btn btn-primary" href="<?= site_url('auth/logout'); ?>">Logout</a>
              </div>
          </div>
      </div>
  </div>

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
