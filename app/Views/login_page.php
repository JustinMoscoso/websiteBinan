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
    
  <div class="backgroundlogin">
    <div class="containerlogin col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
      <!-- ======= Logo Start ======= -->
     <!-- ======= Logo End ======= -->
      <div class="loginborder pt-4 pb-2" style="padding: 1rem; border-radius: 8px; max-width: 80%; margin: auto;">
         <div class="d-flex justify-content-center">
          <a href="<?= base_url('#')?>">
            <img src="<?= site_url('assets/img/binanlogo.png'); ?>" alt="" class ="loginlogo img-fluid">
          </a>
      </div> 
        <h1 style="text-align: center; font-size: 2rem;"><b>LOGIN</b></h1>
        <form method="post">

          <label for="txtUser" class="lbl form-label">Username</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-envelope'></i></span>
            <input type="text" class="form-control" name="username" id="txtUser" placeholder="Enter username" required>
            <div class="invalid-feedback">Please enter your username.</div>
          </div>

          <label for="txtPass" class="lbl form-label">Password</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class='bx bxs-lock-alt'></i></span>
            <input type="password" class="form-control" name="password" id="txtPass" placeholder="Enter password">
            <div class="invalid-feedback">Please enter your password!</div>
          </div>

          <div class="mb-3 form-check d-flex align-items-center justify-content-start">
              <input type="checkbox" class="form-check-input" id="check">
              <label class="form-check-label ms-2" for="check"><span>Remember Me</span></label>
          </div>

          <div class="log-btn col-12">
            <button id="btnLoad" class="btn btn-primary w-100" type="button" style="display: none;" disabled>
                <span class="spinner-grow spinner-grow-sm" role="status"></span>
                Please wait...
            </button>
            <button id="btnLogin" class="btn btn-primary w-100" type="submit">Login</button>
          </div>
          <br>

        </form>


      </div>
    </div>
    <!-- ======= Footer ======= -->
    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
      <div class="copyrightlogin text-light">
          &copy; Copyright <strong><span>Biñan City Official Website</span></strong>. All Rights Reserved
      </div>
    </div>

  </div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<?php
pre_scripts('admin');

echo view('login_js');
?>

  </body>
</html>

