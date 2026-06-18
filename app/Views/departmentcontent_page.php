<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Department Details</title>
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
      border-left: 5px solid #388e3c;
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
      width: 130px;
      height: 130px;
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

    .org-chart-block img:hover {
      transform: scale(1.01);
    }

    .mv-card {
      background-color: #ffffff;
      border: 1px solid #c8e6c9;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .mv-card:hover {
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      transform: translateY(-3px);
    }
    
    .mv-icon-wrapper {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background-color: #e8f5e9;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .mv-text {
      font-size: 0.95rem;
      line-height: 1.6;
    }
  </style>
</head>
<body>
<?php include "navbar.php"; ?>
<?php
require_once 'header.php'; 
$breadcrumbs = [
    ['text' => 'Departments', 'url' => base_url('/department')],
    ['text' => 'Details', 'active' => true]
];
include_header(htmlspecialchars($dept->dept_name), $breadcrumbs, [
    'layout' => 'side',
    'bg_color' => '#388e3c',
    'text_color' => 'white',
    'logo' => base_url('admin/image/DEPT/'.$dept->img_logo),
    'logo_width' => 90,
    'logo_height' => 90
]);
?>


<div class="container mt-0 mb-5 pt-0">
  <div class="row flex-column flex-md-row">
    <!-- Sidebar Navigation -->
    <div class="col-md-3 sidebar mb-4 mt-0 pt-0">
      <nav id="sidebar-nav" class="nav flex-column">
        <a href="#" class="nav-link sidebar-link active" data-tab="about">About</a>
        <a href="#" class="nav-link sidebar-link" data-tab="missionvision">Mission & Vision</a>
        <a href="#" class="nav-link sidebar-link" data-tab="services">Services</a>
        <a href="#" class="nav-link sidebar-link" data-tab="contact">Contact Information</a>
      </nav>
    </div>

    <!-- Content Sections -->
    <div class="col-md-9">
      <div id="about" class="content-tab active content-section">
        <h4 class="text-center">About</h4>
        <hr />
        <div class="about-content">
          <div class="dept-header-block d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4 p-3 mb-4">
            <div class="dept-logo-wrapper flex-shrink-0 d-flex flex-column align-items-center gap-3">
              <div class="dept-head-badge d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill">
                <i class="bi bi-person-badge-fill text-success"></i>
                <span class="small fw-semibold text-secondary">Head:</span>
                <span class="small fw-bold" style="color: #1b5e20;"><?= htmlspecialchars($dept->head) ?></span>
              </div>
              <img src="<?= base_url('admin/image/DEPT/' . $dept->img_logo) ?>" alt="Department Logo" class="dept-logo" />
            </div>
            <div class="dept-info flex-grow-1 text-center text-md-start">
              <div class="dept-about-text">
                <?= $dept->about ?>
              </div>
            </div>
          </div>
          
          <!-- Organizational Chart Section -->
          <?php if (!empty($dept->org_chart_img)): ?>
          <div class="org-chart-block border-top pt-4 mt-4">
            <h5 class="text-center fw-bold mb-4" style="color: #1b5e20; letter-spacing: 0.5px;">
              <i class="bi bi-diagram-3 me-2"></i> ORGANIZATIONAL CHART
            </h5>
            <div class="text-center p-2 rounded" style="background-color: #f8f9fa; border: 1px dashed #c8e6c9;">
              <img src="<?= base_url('admin/image/DEPT/' . $dept->org_chart_img) ?>" 
                   alt="Organizational Chart" 
                   class="img-fluid rounded shadow-sm" 
                   style="max-height: 550px; object-fit: contain; cursor: pointer; transition: transform 0.2s;"
                   onclick="window.open(this.src)"
                   title="Click to view full size" />
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <div id="missionvision" class="content-tab content-section" style="border: none; box-shadow: none; padding: 0; background: transparent;">
        <div class="row g-4">
          <!-- Mission Column -->
          <div class="col-md-6">
            <div class="mv-card h-100 p-4 text-center">
              <div class="mv-icon-wrapper mb-3 mx-auto">
                <i class="bi bi-bullseye fs-2" style="color: #388e3c;"></i>
              </div>
              <h4 class="fw-bold mb-3" style="color: #1b5e20;">Mission</h4>
              <div class="mv-text text-muted">
                <?= $dept->mission ?>
              </div>
            </div>
          </div>
          
          <!-- Vision Column -->
          <div class="col-md-6">
            <div class="mv-card h-100 p-4 text-center">
              <div class="mv-icon-wrapper mb-3 mx-auto">
                <i class="bi bi-lightbulb fs-2" style="color: #388e3c;"></i>
              </div>
              <h4 class="fw-bold mb-3" style="color: #1b5e20;">Vision</h4>
              <div class="mv-text text-muted">
                <?= $dept->vision ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="services" class="content-tab content-section">
        <h4 class="text-center">Services</h4>
        <hr />
        <div class="row">
          <?php foreach ($services as $service): ?>
          <div class="col-12">
            <div class="service-card">
              <h5 class="mb-3"><?= htmlspecialchars($service->serv_name) ?></h5>
              <div class="text-muted small">
                <?= $service->content ?>
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
            <?= $dept->contact ?>
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
