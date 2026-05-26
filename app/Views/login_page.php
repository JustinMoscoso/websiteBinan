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
    /* Corporate Forest Green Color Identity Variable Matrix */
    :root {
      --theme-dark-green: #113329;
      --theme-mid-green: #1b4d3e;
      --theme-light-green: #2d6a4f;
      --theme-accent: #20c997;
      --glass-panel-light: rgba(255, 255, 255, 0.96);
    }

    body {
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
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
      background: radial-gradient(circle, rgba(17, 51, 41, 0.15) 0%, rgba(10, 26, 21, 0.35) 100%);
      backdrop-filter: blur(2px);
      z-index: -1;
    }

    /* UPDATED: Solid Black Border Architecture */
    .login-container-wrapper {
      max-width: 1010px;
      width: 100%;
      border-radius: 20px;
      /* Changed border color to a solid, distinct black */
      border: 1.5px solid #000000 !important;
      background: transparent;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3) !important;
    }

    /* Left Hand Side Corporate Branding Container Segment */
    .branding-side-panel {
      background: linear-gradient(145deg, var(--theme-dark-green) 0%, var(--theme-mid-green) 100%);
      position: relative;
      overflow: hidden;
      /* Changed internal divider line to black to match the outer frame */
      border-right: 1.5px solid #000000;
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

    .brand-subtext {
      color: #e2ebe7 !important;
      font-weight: 400;
      letter-spacing: 0.025em;
    }

    /* Right Hand Side Refined Soft Panel */
    .login-form-side {
      background: var(--glass-panel-light) !important;
    }

    /* Form Inputs Overrides */
    .custom-input-group {
      border-radius: 8px;
      overflow: hidden;
      transition: all 0.25s ease;
      border: 1px solid #c9d1cc;
      background-color: #ffffff;
    }

    .custom-input-group:focus-within {
      border-color: var(--theme-light-green);
      box-shadow: 0 0 0 4px rgba(45, 106, 79, 0.12);
    }

    .custom-input-group .input-group-text {
      background-color: #f1f5f3;
      border: none;
      color: #52635a;
      padding-left: 16px;
      padding-right: 16px;
    }

    .custom-input-group .form-control {
      border: none;
      padding: 12px 14px;
      font-size: 0.95rem;
      background-color: transparent;
      color: #1e2924;
    }

    .custom-input-group .form-control::placeholder {
      color: #8fa096;
    }

    .custom-input-group .form-control:focus {
      box-shadow: none;
      outline: none;
      background-color: transparent;
    }

    .forgot-link-node {
      color: var(--theme-mid-green);
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .forgot-link-node:hover {
      color: var(--theme-light-green);
      text-decoration: none;
      opacity: 0.85;
    }

    .theme-login-action-btn {
      background-color: var(--theme-mid-green);
      color: #ffffff;
      font-weight: 600;
      padding: 13px 24px;
      border-radius: 8px;
      border: none;
      transition: all 0.2s ease-in-out;
      letter-spacing: 0.01em;
    }

    .theme-login-action-btn:hover {
      background-color: var(--theme-dark-green);
      color: #ffffff;
      transform: translateY(-1px);
      box-shadow: 0 5px 15px rgba(17, 51, 41, 0.25);
    }

    .theme-login-action-btn:active {
      transform: translateY(0);
    }
  </style>
</head>

<body>

  <div class="backgroundlogin d-flex align-items-center justify-content-center min-vh-100 p-3 p-md-4">

    <div class="login-container-wrapper row shadow-2xl overflow-hidden mx-0">

      <div
        class="col-lg-5 branding-side-panel d-none d-lg-flex flex-column justify-content-center align-items-center text-white p-5">
        <img src="<?= site_url('assets/img/binanlogo.png'); ?>" class="brand-logo mb-4 img-fluid"
          style="max-height: 150px; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.3));" alt="Biñan Logo">
        <h1 class="h3 fw-bold mb-2 tracking-wide text-center" style="letter-spacing: 0.05em;">BIÑAN CITY ADMIN</h1>


      </div>

      <div class="col-lg-7 login-form-side p-4 p-sm-5 d-flex align-items-center">
        <div class="w-100 py-2">

          <div class="mb-4 pb-2">
            <h2 class="fw-bold text-dark mb-1" style="color: #0b1411 !important;">Welcome Back</h2>

          </div>

          <form method="post" autocomplete="off">

            <div class="mb-4">
              <label class="form-label small fw-bold text-secondary mb-2"
                style="color: #43534a !important;">Username</label>
              <div class="input-group custom-input-group">
                <span class="input-group-text">
                  <i class='bx bxs-user fs-5'></i>
                </span>
                <input type="text" class="form-control" name="username" id="txtUser" placeholder="Enter username"
                  required>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label small fw-bold text-secondary mb-2"
                style="color: #43534a !important;">Password</label>
              <div class="input-group custom-input-group">
                <span class="input-group-text">
                  <i class='bx bxs-lock-alt fs-5'></i>
                </span>
                <input type="password" class="form-control" name="password" id="txtPass" placeholder="Enter password"
                  required>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 pt-1">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="check"
                  style="cursor: pointer; border-color: #adbdae;">
                <label class="form-check-label small text-secondary user-select-none" for="check"
                  style="cursor: pointer; color: #52635a !important;">
                  Remember me
                </label>


                <div class="d-grid gap-2 mt-4 pt-2">

                  <button id="btnLoad" class="btn theme-login-action-btn d-none" type="button" disabled>
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Verifying Credentials Parameters...
                  </button>

                  <button id="btnLogin" class="btn theme-login-action-btn" type="submit">
                    <i class='bx bx-log-in-circle me-2 align-middle fs-5'></i><span class="align-middle">Login</span>
                  </button>

                </div>
          </form>

        </div>
      </div>

    </div>

  </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <?php
  pre_scripts('admin');
  echo view('login_js');
  ?>

</body>

</html>