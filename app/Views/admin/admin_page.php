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

  <style>
    /* SIDEBAR */
    #wrapper {
      display: flex;
      min-width: 0;
    }

    .sidebar {
      position: sticky;
      top: 0;
      width: 310px !important;
      flex: 0 0 310px;
      height: 100vh;
      overflow-y: auto;
      overflow-x: hidden;
      overscroll-behavior: contain;

      /* Firefox */
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.35) transparent;
    }

    /* Chrome / Edge / Safari */
    .sidebar::-webkit-scrollbar {
      width: 8px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.35);
      border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.55);
    }

    /* Mobile drawer style for sidebar */
    @media (max-width: 767.98px) {
      body.sidebar-toggled {
        overflow: hidden;
      }

      #content-wrapper {
        width: 100%;
        min-width: 0;
      }

      .sidebar {
        position: fixed !important;
        top: 0;
        left: 0;
        bottom: auto;
        width: min(280px, calc(100vw - 56px)) !important;
        max-width: calc(100vw - 56px);
        flex-basis: auto;
        height: 100vh !important;
        height: 100dvh !important;
        z-index: 1051;
        transform: translate3d(-100%, 0, 0);
        visibility: hidden;
        pointer-events: none;
        transition: transform 0.25s ease, visibility 0.25s ease;
        display: flex !important;
        flex-direction: column;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        -webkit-overflow-scrolling: touch;
        padding-bottom: max(1rem, env(safe-area-inset-bottom));
        box-shadow: 0 0 24px rgba(0, 0, 0, 0.28);
      }

      .sidebar.toggled {
        width: min(280px, calc(100vw - 56px)) !important;
        transform: translate3d(0, 0, 0);
        visibility: visible;
        pointer-events: auto;
      }

      .sidebar .navbar-brand {
        min-height: 76px;
        padding-right: 3.25rem !important;
      }

      .sidebar .nav-item .nav-link {
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        width: 100% !important;
        padding: 0.7rem 1rem !important;
        text-align: left !important;
        white-space: normal;
      }

      .sidebar .nav-item .nav-link i {
        width: 1.5rem;
        margin: 0 !important;
        font-size: 1rem !important;
        text-align: center;
        flex: 0 0 1.5rem;
      }

      .sidebar .nav-item .nav-link span {
        display: inline !important;
        min-width: 0;
        font-size: 0.84rem !important;
        line-height: 1.3;
      }

      .sidebar .sidebar-heading {
        padding: 0 1rem;
        text-align: left;
      }

      .sidebar-close {
        position: absolute;
        top: 0.9rem;
        right: 0.75rem;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        padding: 0;
        color: #fff;
        background: rgba(255, 255, 255, 0.12);
        border: 0;
        border-radius: 50%;
      }

      .sidebar-close:focus-visible {
        outline: 2px solid #fff;
        outline-offset: 2px;
      }

      .sidebar-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1040;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.25s ease, visibility 0.25s ease;
      }

      body.sidebar-toggled .sidebar-backdrop {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .sidebar,
      .sidebar-backdrop {
        transition: none !important;
      }
    }

    /* Centering all data table column headers for consistency */
    .table th,
    .table thead th,
    table.dataTable thead th,
    div.dataTables_wrapper table.dataTable thead th {
      text-align: center !important;
    }

    /* Centering all data table rows/cells for consistency */
    .table td,
    table.dataTable tbody td,
    div.dataTables_wrapper table.dataTable tbody td {
      text-align: center !important;
    }
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #1B4332;">

      <button type="button" class="sidebar-close d-md-none" id="sidebarClose" aria-label="Close navigation menu">
        <i class="fas fa-times" aria-hidden="true"></i>
      </button>

      <!-- Sidebar - Brand -->
      <a class="navbar-brand d-flex align-items-center px-3 py-3" href="<?= base_url('admin/dashboard') ?>"
        style="gap: 10px;">
        <img src="<?= base_url('assets/img/binanlogo.png') ?>" alt="Logo" width="45" height="45"
          class="img-fluid flex-shrink-0">
        <div class="d-flex flex-column align-items-start" style="width: 100%; max-width: 180px;">
           <span style="font-size: 12px; font-family: 'Gill Sans'; font-weight: 900; color: #ffffffff;">REPUBLIC OF THE
          PHILIPPINES</span>
        <hr style="width: 100%; margin: 1px 0; border: none; border-top: 2px solid #ffffffff;">
        <!-- Adjust the margin and border-top color as needed -->
        <span style="font-size: 14px; font-family: 'Gill Sans'; font-weight: 900; color: #ffffffff;">CITY GOVERNMENT OF
          BIÑAN</span>
        </div>
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
      $isDeptEncoder = ($is_dept_encoder ?? false);
      $isBrgyEncoder = ($is_brgy_encoder ?? false);

      // ADMIN or ENCODER scoped to a department/barangay behaves like an entity account for sidebar
      $isEntityAccount = !in_array($user->user_lvl, $privilegedRoles)
        && ($isDeptAdmin || $isBrgyAdmin || $isDeptEncoder || $isBrgyEncoder
          || in_array($user->account_type ?? '', ['DEPARTMENT', 'BARANGAY']));
      $showBrgy = !in_array($user->user_lvl, $privilegedRoles)
        ? ($user->account_type ?? '') !== 'DEPARTMENT' && !$isDeptAdmin && !$isBrgyAdmin && !$isDeptEncoder && !$isBrgyEncoder
        : true;
      $showDept = !in_array($user->user_lvl, $privilegedRoles)
        ? ($user->account_type ?? '') !== 'BARANGAY' && !$isBrgyAdmin && !$isDeptAdmin && !$isBrgyEncoder && !$isDeptEncoder
        : true;
      ?>

      <!-- Heading -->
      <div class="sidebar-heading text-muted">
        Content Management
      </div>

      <?php if ((!$isDeptAdmin && !$isEntityAccount) || ($is_mayor ?? false) || ($is_cio ?? false)): ?>
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

      <?php if ((!$isDeptAdmin && !$isEntityAccount) || ($is_cio ?? false)): ?>
        <li class="nav-item <?= $mode == 'about' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= site_url('admin/about') ?>">
            <i class="fas fa-fw fa-info-circle"></i>
            <span>About MVQ / Homepage</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if (($user->account_type ?? '') !== 'DEPARTMENT' || $isDeptAdmin): ?>
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

      <?php if ((!$isDeptAdmin && !$isEntityAccount) || ($is_hrdo ?? false)): ?>
        <li class="nav-item <?= $mode == 'careers' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= site_url('admin/careers') ?>">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Careers</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if (!$isDeptAdmin && !$isEntityAccount): ?>
        <li class="nav-item <?= $mode == 'cityOff' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= site_url('admin/cityOff') ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>City Officials</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if (!$isEntityAccount || ($is_cio ?? false)): ?>
        <li class="nav-item <?= $mode == 'fullDisc' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= site_url('admin/fullDisc') ?>">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Full Disclosure Policy</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if ((!$isDeptAdmin && !$isEntityAccount) || ($is_peso ?? false)): ?>
        <li class="nav-item <?= $mode == 'jobs' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= site_url('admin/jobs') ?>">
            <i class="fas fa-fw fa-user-md"></i>
            <span>Job Management</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if ((!$isDeptAdmin && !$isEntityAccount) || ($is_bplo ?? false)): ?>
        <li class="nav-item <?= $mode == 'invest' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= site_url('admin/invest') ?>">
            <i class="fas fa-fw fa-coins"></i>
            <span>Invest</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if ((!$isDeptAdmin && !$isEntityAccount) || ($is_cio ?? false)): ?>
        <li class="nav-item <?= $mode == 'contacts' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= site_url('admin/contacts') ?>">
            <i class="fas fa-fw fa-phone"></i>
            <span>Contacts</span>
          </a>
        </li>
      <?php endif; ?>

      <?php if (in_array($user->user_lvl, ['DEVELOPER', 'SUPERADMIN', 'ADMIN']) && !$isDeptAdmin && !$isBrgyAdmin): ?>
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


    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" type="button"
            aria-controls="accordionSidebar" aria-expanded="false" aria-label="Open navigation menu">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span
                  class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user->fname . ' ' . $user->lname; ?>
                  (<?php echo $user->user_lvl; ?>)</span>
                <span id="topbarProfileAvatar">
                  <?php if (!empty($user->profile_image)): ?>
                    <img class="rounded-circle" src="<?= site_url('admin/image/PROFILE/' . $user->profile_image); ?>"
                      alt="Profile picture" style="width: 32px; height: 32px; object-fit: cover;">
                  <?php else: ?>
                    <i class="fas fa-user-circle fa-2x text-gray-300"></i>
                  <?php endif; ?>
                </span>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?= site_url('admin/profile'); ?>">
                  <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-gray-400"></i>
                  Edit Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= site_url('auth/logout'); ?>">
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

  <!-- Sidebar Backdrop -->
  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <script>
    $(document).ready(function () {
      // Prevent native form submission globally for AJAX-handled add/edit forms
      $(document).on('submit', '#addForm, #editForm', function (e) {
        e.preventDefault();
      });

      var mobileSidebarQuery = window.matchMedia('(max-width: 767.98px)');
      var mobileSidebarOpen = false;

      function closeMobileSidebar() {
        $('body').removeClass('sidebar-toggled');
        $('.sidebar').removeClass('toggled');
        mobileSidebarOpen = false;
        $('#sidebarToggleTop').attr('aria-expanded', 'false').attr('aria-label', 'Open navigation menu');
      }

      function syncMobileSidebarState() {
        if (!mobileSidebarQuery.matches) {
          $('body').removeClass('sidebar-toggled');
          $('#sidebarToggleTop').attr('aria-expanded', 'false').attr('aria-label', 'Open navigation menu');
          return;
        }

        var isOpen = $('.sidebar').hasClass('toggled');
        mobileSidebarOpen = isOpen;
        $('body').toggleClass('sidebar-toggled', isOpen);
        $('#sidebarToggleTop')
          .attr('aria-expanded', isOpen ? 'true' : 'false')
          .attr('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
      }

      // SB Admin toggles the classes first; synchronize accessibility and backdrop state.
      $(document).on('click', '#sidebarToggle, #sidebarToggleTop', function () {
        syncMobileSidebarState();
      });

      $('#sidebarBackdrop, #sidebarClose').on('click', closeMobileSidebar);

      $(document).on('keydown', function (event) {
        if (event.key === 'Escape' && mobileSidebarQuery.matches && $('.sidebar').hasClass('toggled')) {
          closeMobileSidebar();
          $('#sidebarToggleTop').trigger('focus');
        }
      });

      $('.sidebar').on('click', 'a.nav-link', function () {
        if (mobileSidebarQuery.matches) {
          closeMobileSidebar();
        }
      });

      $(window).on('resize', function () {
        if (!mobileSidebarQuery.matches) {
          closeMobileSidebar();
        } else if (!mobileSidebarOpen) {
          // Override SB Admin's built-in behavior that opens the menu below 480px.
          closeMobileSidebar();
        } else {
          syncMobileSidebarState();
        }
      });

      if (mobileSidebarQuery.matches) {
        closeMobileSidebar();
      }
    });
  </script>
</body>

</html>
