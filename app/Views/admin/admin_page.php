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
    /* =========================================
   BINAN ADMIN SIDEBAR THEME
========================================= */

    /* SIDEBAR */
    #wrapper {
      display: flex;
    }

    .sidebar {
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
      overflow-x: hidden;
      overscroll-behavior: contain;
    }

    /* Mobile drawer style for sidebar */
    @media (max-width: 767.98px) {
      .sidebar {
        position: fixed !important;
        top: 0;
        bottom: 0;
        left: -224px;
        width: 224px !important;
        height: 100vh !important;
        z-index: 1050;
        transition: left 0.25s ease-in-out;
        display: flex !important;
      }
      .sidebar.toggled {
        left: 0 !important;
        width: 224px !important;
        overflow-y: auto !important;
      }
      .sidebar-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1040;
        display: none;
      }
      body.sidebar-toggled .sidebar-backdrop {
        display: block;
      }
    }

    /* BRAND */

    .sidebar-brand {
      background: rgba(0, 0, 0, 0.08);
      height: 80px !important;
    }

    /* BRAND TEXT */

    .sidebar-brand-text {
      font-weight: 700;
      letter-spacing: 0.4px;
    }

    /* NAV LINKS */
    .sidebar .nav-item {
      width: 100%;
    }

    .sidebar .nav-item .nav-link {
      width: calc(100% - 20px);
      margin: 4px 10px;
      padding: 12px 16px;
      border-radius: 12px;
      box-sizing: border-box;
    }

    /* ICONS */

    .sidebar .nav-item .nav-link i {
      margin-right: 8px;
      color: rgba(255, 255, 255, 0.75) !important;
    }

    /* HOVER */

    .sidebar .nav-item .nav-link:hover {
      background: rgba(255, 255, 255, 0.10);
      color: #fff !important;
      transform: translateX(1px);
    }

    /* ACTIVE */

    .sidebar .nav-item.active .nav-link {
      background: rgba(255, 255, 255, 0.16);
      color: #fff !important;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    }

    /* ACTIVE ICON */

    .sidebar .nav-item.active .nav-link i {
      color: #fff !important;
    }

    /* HEADINGS */

    .sidebar-heading {
      color: rgba(255, 255, 255, 0.65) !important;
      font-size: 11px;
      letter-spacing: 1px;
    }

    /* DIVIDER */

    .sidebar-divider {
      border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    /* TOGGLE BUTTON */

    #sidebarToggle {
      background: rgba(255, 255, 255, 0.12);
    }

    #sidebarToggle::after {
      color: white;
    }

    /* TOPBAR */

    .topbar {
      border-bottom: 1px solid #eaeaea;
    }

    /* BODY */

    body {
      background: #f5f7fb;
    }

    /* =========================================
       GLOBAL RESPONSIVE OVERRIDES
       ========================================= */
    @media (max-width: 767.98px) {
      .topbar {
        padding: 0.5rem 1rem !important;
      }
      .container-fluid {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
      }
      .card-body {
        padding: 1rem !important;
      }
      /* Ensure tables scroll horizontally on mobile */
      .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
      }
      /* Prevent Quill editor toolbars from overflowing */
      .ql-toolbar.ql-snow {
        display: flex !important;
        flex-wrap: wrap !important;
        padding: 4px 6px !important;
      }
      .ql-toolbar.ql-snow .ql-formats {
        margin-right: 4px !important;
        margin-bottom: 4px !important;
        display: inline-flex !important;
        flex-wrap: wrap !important;
      }
      /* Make DataTable search and page size filters responsive */
      .dataTables_filter {
        width: 100% !important;
        text-align: left !important;
        margin-top: 0.5rem;
      }
      .dataTables_filter label {
        display: flex !important;
        flex-direction: column !important;
        width: 100% !important;
        align-items: flex-start !important;
      }
      .dataTables_filter input[type="search"] {
        width: 100% !important;
        margin-left: 0 !important;
        margin-top: 0.25rem !important;
      }
      .dataTables_length {
        width: 100% !important;
        text-align: left !important;
      }
      .dataTables_length label {
        display: flex !important;
        align-items: center !important;
        width: 100% !important;
        justify-content: space-between !important;
      }
      /* Align modal buttons nicely on mobile */
      .modal-footer {
        display: flex !important;
        flex-direction: column-reverse !important;
        gap: 0.85rem;
        padding: 1rem !important;
      }
      .modal-footer .btn {
        width: 100% !important;
        margin: 0 !important;
      }
      .modal-body {
        padding: 1rem !important;
      }
      .modal-header {
        padding: 1rem !important;
      }
    }
  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #1B4332;">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center"
        href="<?= base_url('admin/dashboard') ?>">
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
      <div class="sidebar-heading">
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
            <span>About / Homepage</span>
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
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
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
    $(document).ready(function() {
      var sidebarOpen = false;

      // Track explicit user clicks on toggle buttons
      $(document).on('click', '#sidebarToggle, #sidebarToggleTop', function() {
        sidebarOpen = !sidebarOpen;
      });

      // Track clicks on backdrop to dismiss sidebar
      $('#sidebarBackdrop').on('click', function() {
        $('body').removeClass('sidebar-toggled');
        $('.sidebar').removeClass('toggled');
        sidebarOpen = false;
      });

      // Prevent SB Admin 2 from forcing the sidebar open on small screens / window resizes
      $(window).on('resize', function() {
        if ($(window).width() < 768 && !sidebarOpen) {
          $('body').removeClass('sidebar-toggled');
          $('.sidebar').removeClass('toggled');
        }
      });

      // Initialize closed state on initial mobile load
      if ($(window).width() < 768) {
        $('body').removeClass('sidebar-toggled');
        $('.sidebar').removeClass('toggled');
        sidebarOpen = false;
      }
    });
  </script>
</body>
</html>