<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Barangay Details</title>
  <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png" />
  <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon" />
  <?php pre_styles('home'); ?>
  <style>
    .sidebar-link {
      color: #2e7d32;
      padding: 12px 16px;
      font-weight: 500;
      border-left: 4px solid transparent;
      transition: all 0.2s ease-in-out;
      background-color: transparent;
      text-decoration: none;
      display: block;
      margin-bottom: 8px;
      cursor: pointer;
      margin-top: 10px;
    }
    .sidebar-link:hover {
      background-color: #e8f5e9;
      color: #1b5e20;
      border-left: 4px solid #66bb6a;
    }
    .sidebar-link.active {
      background-color: #c8e6c9;
      color: #1b5e20;
      border-left: 4px solid #388e3c;
      font-weight: 600;
    }

    @media (max-width: 767.98px) {
      .row.flex-column.flex-md-row {
        flex-direction: column;
        margin-top: 0 !important;
        padding-top: 0 !important;
      }

      .sidebar {
        background-color: #ffffff;
        padding: 10px 15px 0 15px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        position: relative;
        z-index: 1000;
        margin-top: 5px !important;
        margin-bottom: 15px !important;
      }

      .nav-link.sidebar-link {
        display: block;
        width: 100%;
        background-color: #ffffff;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
        border-radius: 6px;
        margin-bottom: 10px;
        z-index: 3;
        position: relative;
        transition: all 0.3s ease;
      }

      .nav-link.sidebar-link.active {
        background-color: #388e3c !important;
        color: #ffffff !important;
        border: 1px solid #2e7d32;
      }

      .content-tab {
        position: relative;
        background-color: #ffffff;
        z-index: 1;
        padding: 15px;
        border-radius: 6px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.05);
      }
    }

    .content-tab {
      display: none;
      animation: fadeIn 0.4s ease-in-out;
    }

    .content-tab.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .sidebar {
      position: relative;
      z-index: 1000;
    }

    body {
      overflow-x: hidden;
    }
    
    .content-section {
      background-color: #ffffff;
      border-radius: 8px;
      padding: 15px 10px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    #contact.content-tab {
      padding-top: 15px;
      margin-top: 0;
    }
    
    #contact.content-tab h4 {
      margin-top: 0;
      padding-top: 0;
    }

    #contact.content-tab .contact {
      text-align: center;
    }
    
    #about.content-tab {
      padding-top: 15px;
      margin-top: 0;
    }
    
    #about.content-tab h4 {
      margin-top: 0;
      padding-top: 0;
    }

    .text-success {
      color: #388e3c !important;
    }

    .service-card {
      background-color: #ffffff;
      border: 1px solid #e8f5e9;
      border-radius: 8px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.03);
      transition: all 0.3s ease;
      text-align: left;
    }
    .service-card:hover {
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      transform: translateY(-2px);
    }
    .service-card h5 {
      color: #1b5e20;
      font-weight: 700;
    }


    .dept-logo {
      width: 150px;
      height: 150px;
      object-fit: contain;
      border-radius: 16px;
      padding: 8px;
      border: 1px solid #c8e6c9;
      background-color: #ffffff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.04);
      transition: transform 0.3s ease;
      display: inline-block;
    }
    .dept-logo:hover {
      transform: scale(1.05);
    }
    
    .dept-head-badge {
      background-color: #e8f5e9;
      border: 1px solid #c8e6c9;
      color: #1b5e20;
    }
    
    .dept-about-text {
      font-size: 0.95rem;
      line-height: 1.7;
      color: #333333;
    }
  </style>
</head>
<body>
<?php include "navbar.php"; ?>

<?php
require_once 'header.php'; 

// Set up breadcrumbs
$breadcrumbs = [
    ['text' => 'Barangays', 'url' => base_url('/barangays')],
    ['text' => 'Details', 'active' => true]
];

// Use the new header function
include_header(htmlspecialchars($brgy->brgy_name), $breadcrumbs, [
    'layout' => 'side',
    'bg_color' => '#388e3c',
    'text_color' => 'white',
    'logo' => base_url('admin/image/BARANGAY/'.$brgy->img_logo),
    'logo_width' => 90,
    'logo_height' => 90
]);
?>

<!-- Removed top margin on mobile -->
<div class="container mt-0 mb-5 pt-0">
  <div class="row flex-column flex-md-row">
    <!-- Sidebar Navigation -->
    <div class="col-md-3 sidebar mb-4 mt-0 pt-0">
      <nav id="sidebar-nav" class="nav flex-column">
        <a href="#" class="nav-link sidebar-link active" data-tab="about">About</a>
        <a href="#" class="nav-link sidebar-link" data-tab="captain">Barangay Officials</a>
        <a href="#" class="nav-link sidebar-link" data-tab="missionvision">Mission & Vision</a>
        <a href="#" class="nav-link sidebar-link" data-tab="services">Services</a>
        <a href="#" class="nav-link sidebar-link " data-tab="contact">Contact Information</a>
      </nav>
    </div>

    <!-- Content Sections -->
    <div class="col-md-9">
        <div id="about" class="content-tab active content-section">
            <h4 class="text-center">About</h4>
            <hr />
            <div class="about-content">
                <div class="dept-header-block d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4 p-3 mb-4">
                        <img src="<?= base_url('admin/image/BARANGAY/' . $brgy->img_logo) ?>" alt="Barangay Logo" class="dept-logo" />
                    <div class="dept-info flex-grow-1 text-center text-md-start">
                        <div class="dept-about-text">
                            <?= $brgy->about ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <div id="captain" class="content-tab content-section">
        <h4 class="text-center">Barangay Officials</h4>
        <hr />
        
        <!-- Barangay Captain Section -->
        <div class="mb-4 text-center">
            <h5 class="fw-bold mb-3" style="color: #1b5e20;">Barangay Captain</h5>
            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill shadow-sm" style="background-color: #e8f5e9; border: 1px solid #c8e6c9;">
                <i class="bi bi-person-badge-fill text-success fs-5"></i>
                <span class="fw-bold fs-6" style="color: #1b5e20;"><?= htmlspecialchars($brgy->brngy_capt) ?></span>
            </div>
        </div>
        
        <!-- Barangay Staff Section -->
        <div class="mt-4">
            <h5 class="text-center fw-bold mb-3" style="color: #1b5e20;">Barangay Staff</h5>
            <div class="p-4 rounded" style="background-color: #f8f9fa; border: 1px dashed #c8e6c9;">
                <?php if (!empty($brgy->barangay_staff)): ?>
                    <div class="dept-about-text">
                        <?= $brgy->barangay_staff ?>
                    </div>
                <?php else: ?>
                    <p class="text-center mb-0 text-muted small">No staff information available.</p>
                <?php endif; ?>
            </div>
        </div>
      </div>

      <div id="missionvision" class="content-tab content-section">
        <div class="row">
          <div class="col-12 mb-4">
            <h5 class="text-dark text-center">Mission</h5>
            <hr />
            <p><?= ($brgy->mission) ?></p>
          </div>
          <div class="col-12 mb-4">
            <h5 class="text-dark text-center">Vision</h5>
            <hr />
            <p><?= ($brgy->vision) ?></p>
          </div>
        </div>
      </div>

      <div id="services" class="content-tab content-section">
        <h4 class="text-center">Services</h4>
        <hr />
        <div class="row">     <?php foreach ($services as $data): ?>
          <div class="col-12">
            <div class="service-card">
              <h5 class="mb-3"><?= htmlspecialchars($data->serv_name) ?></h5>
              <div class="text-muted small">
                <?= $data->content ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div id="contact" class="content-tab content-section">
        <h4 class="text-center">Contact Information</h4>
        <hr />
         <div class="contact text-center">
            <?= $brgy->contact ?>
         </div>
      </div>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>
<?php pre_scripts('home'); ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.sidebar-link');
    const tabs = document.querySelectorAll('.content-tab');

    links.forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const tabId = this.getAttribute('data-tab');

        links.forEach(l => l.classList.remove('active'));
        tabs.forEach(tab => tab.classList.remove('active'));

        this.classList.add('active');
        const activeTab = document.getElementById(tabId);
        if (activeTab) activeTab.classList.add('active');
      });
    });
  });
</script>
</body>
</html>
