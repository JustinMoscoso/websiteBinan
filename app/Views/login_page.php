<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin Login</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="icon">
  <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">

  <?php pre_styles('admin'); ?>

</head>

<body>

  <div class="backgroundlogin d-flex align-items-center justify-content-center min-vh-100">

    <div class="login-wrapper row shadow-lg overflow-hidden">

      <!-- Left Branding Side -->
      <div
        class="col-lg-5 branding-side d-none d-lg-flex flex-column justify-content-center align-items-center text-white">

        <img src="<?= site_url('assets/img/binanlogo.png'); ?>" class="brand-logo mb-4" alt="Biñan Logo">

        <h1 class="fw-bold mb-2">BIÑAN ADMIN</h1>

        <p class="text-center px-4 opacity-75">
          Official Content Management System
          for Biñan City Website Administration.
        </p>

      </div>

      <!-- Right Login Side -->
      <div class="col-lg-7 bg-white login-side">

        <div class="login-content">

          <div class="mb-4">
            <h2 class="fw-bold text-dark">Welcome Back</h2>
            <p class="text-muted">
              Sign in to continue to dashboard
            </p>
          </div>

          <form method="post">

            <!-- Username -->
            <div class="mb-4">
              <label class="form-label fw-semibold">
                Username
              </label>

              <div class="input-group custom-input-group">
                <span class="input-group-text">
                  <i class='bx bxs-user'></i>
                </span>

                <input type="text" class="form-control" name="username" id="txtUser" placeholder="Enter username"
                  required>
              </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label class="form-label fw-semibold">
                Password
              </label>

              <div class="input-group custom-input-group">
                <span class="input-group-text">
                  <i class='bx bxs-lock-alt'></i>
                </span>

                <input type="password" class="form-control" name="password" id="txtPass" placeholder="Enter password"
                  required>
              </div>
            </div>

            <!-- Remember -->
            <div class="d-flex justify-content-between align-items-center mb-4">

              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="check">

                <label class="form-check-label" for="check">
                  Remember me
                </label>
              </div>

              <a href="#" class="forgot-link">
                Forgot Password?
              </a>

            </div>
            <!-- Buttons -->
            <div class="d-grid mt-4">

              <!-- Loading Button -->
              <button id="btnLoad" class="btn login-btn d-none" type="button" disabled>

                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>

                Signing in...

              </button>

              <!-- Login Button -->
              <button id="btnLogin" class="btn login-btn" type="submit">

                <i class='bx bx-log-in-circle me-2'></i>
                Login

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