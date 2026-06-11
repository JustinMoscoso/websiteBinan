<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin Login</title>
  <meta content="Official Website Administration Content Management System" name="description">
  <meta content="Biñan, Administration, CMS, Login" name="keywords">

  <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="icon">
  <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

  <?php pre_styles('admin'); ?>

  <style>
    :root {
      --theme-dark-green: #113329;
      --theme-mid-green: #1b4d3e;
      --theme-light-green: #2d6a4f;
      --theme-accent: #20c997;
      --glass-panel-light: rgba(255, 255, 255, 0.98);
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
      background-color: #f4f7f5;
    }

    /* Background Setup */
    .backgroundlogin {
      position: relative;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      z-index: 1;
    }

    .backgroundlogin::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle, rgba(17, 51, 41, 0.4) 0%, rgba(10, 26, 21, 0.7) 100%);
      backdrop-filter: blur(4px);
      z-index: -1;
    }

    /* INDUSTRY STANDARD: Responsive Container Architecture */
    .login-container-wrapper {
      max-width: 960px;
      width: 100%;
      border-radius: 16px;
      border: 1px solid rgba(0, 0, 0, 0.12) !important;
      background: transparent;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
    }

    /* Left Hand Side Corporate Branding Container Segment */
    .branding-side-panel {
      background: linear-gradient(145deg, var(--theme-dark-green) 0%, var(--theme-mid-green) 100%);
      position: relative;
      overflow: hidden;
      border-right: 1px solid rgba(0, 0, 0, 0.12);
    }

    .branding-side-panel::before {
      content: "";
      position: absolute;
      width: 320px;
      height: 320px;
      border-radius: 50%;
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
      top: -70px;
      left: -70px;
    }

    /* Right Hand Side Refined Soft Panel */
    .login-form-side {
      background: var(--glass-panel-light) !important;
    }

    /* Form Inputs Overrides */
    .custom-input-group {
      border-radius: 8px;
      overflow: hidden;
      transition: all 0.2s ease;
      border: 1px solid #ced4da;
      background-color: #ffffff;
    }

    .custom-input-group:focus-within {
      border-color: var(--theme-light-green);
      box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.18);
    }

    .custom-input-group .input-group-text {
      background-color: #f8f9fa;
      border: none;
      color: #495057;
      padding-left: 16px;
      padding-right: 16px;
    }

    .custom-input-group .form-control {
      border: none;
      padding: 12px 14px;
      font-size: 1rem;
      /* Industry standard minimum for iOS zoom prevention */
      background-color: transparent;
      color: #212529;
    }

    .custom-input-group .form-control:focus {
      box-shadow: none;
      outline: none;
      background-color: transparent;
    }

    .theme-login-action-btn {
      background-color: var(--theme-mid-green);
      color: #ffffff;
      font-weight: 600;
      padding: 12px 24px;
      border-radius: 8px;
      border: none;
      transition: all 0.2s ease;
    }

    .theme-login-action-btn:hover {
      background-color: var(--theme-dark-green);
      color: #ffffff;
    }

    /* Mobile specific adjustments to ensure smooth UX */
    @media (max-width: 575.98px) {
      .login-container-wrapper {
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
      }

      .login-form-side {
        padding: 2rem 1.5rem !important;
      }

      .custom-input-group .form-control {
        font-size: 16px;
        /* Explicitly forces mobile Safari not to zoom in */
      }
    }
  </style>
</head>

<body>

  <div class="backgroundlogin d-flex align-items-center justify-content-center min-vh-100 p-3 p-sm-4">

    <div class="login-container-wrapper row overflow-hidden mx-0">

      <div
        class="col-lg-5 branding-side-panel d-none d-lg-flex flex-column justify-content-center align-items-center text-white p-5">
        <img src="<?= site_url('assets/img/binanlogo.png'); ?>" class="brand-logo mb-4 img-fluid"
          style="max-height: 140px; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.25));" alt="Biñan Logo">
        <h1 class="h4 fw-bold mb-2 text-center" style="letter-spacing: 0.05em; color: #ffffff;">CITY OF BIÑAN</h1>
      </div>

      <div class="col-lg-7 login-form-side p-4 p-md-5 d-flex align-items-center">
        <div class="w-100">

          <div class="mb-4">
            <div class="d-lg-none text-center mb-4">
              <img src="<?= site_url('assets/img/binanlogo.png'); ?>" class="mb-3" style="max-height: 80px;"
                alt="Biñan Logo">
              <h2 class="h5 fw-bold text-dark mb-1">CITY GOVERNMENT OF BIÑAN</h2>
              <hr class="my-4 opacity-25">
            </div>

            <h2 class="fw-bold text-dark mb-1 d-none d-lg-block">Welcome Back</h2>
          </div>

          <form method="post" autocomplete="off">

            <div class="mb-3">
              <label for="txtUser" class="form-label small fw-bold text-secondary mb-2">Username</label>
              <div class="input-group custom-input-group">
                <span class="input-group-text">
                  <i class='bx bxs-user fs-5'></i>
                </span>
                <input type="text" class="form-control" name="username" id="txtUser" placeholder="Enter username"
                  required>
              </div>
            </div>

            <div class="mb-4">
              <label for="txtPass" class="form-label small fw-bold text-secondary mb-2">Password</label>
              <div class="input-group custom-input-group">
                <span class="input-group-text">
                  <i class='bx bxs-lock-alt fs-5'></i>
                </span>
                <input type="password" class="form-control" name="password" id="txtPass" placeholder="Enter password"
                  required>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check m-0">
                <input class="form-check-input" type="checkbox" id="check" style="cursor: pointer;">
                <label class="form-check-label small text-secondary user-select-none" for="check"
                  style="cursor: pointer;">
                  Remember me
                </label>
              </div>
            </div>

            <div class="d-grid gap-2">
              <button id="btnLoad" class="btn theme-login-action-btn d-none" type="button" disabled>
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Verifying Credentials...
              </button>

              <button id="btnLogin" class="btn theme-login-action-btn w-100" type="submit">
                <i class='bx bx-log-in-circle me-2 align-middle fs-5'></i>
                <span class="align-middle">Login</span>
              </button>
            </div>

          </form>

        </div>
      </div>

    </div>

  </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <?php
  pre_scripts('admin');
  echo view('login_js');
  ?>

</body>

</html>