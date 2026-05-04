<?php
$currentpage = ''; // Initialize the variable with an empty string

// Get the current page URL
$currentURL = $_SERVER['REQUEST_URI'];

// Check if the current URL matches a specific page and set the value for $currentpage accordingly
if (strpos($currentURL, '/home') == true) {
    $currentpage = 'home';
} elseif (strpos($currentURL, '/about') == true || strpos($currentURL, '/barangays') == true || strpos($currentURL, '/department') == true) {
    $currentpage = 'about';
} elseif (strpos($currentURL, '/officials') == true || strpos($currentURL, '/fulldisc') == true) {
    $currentpage = 'officials';
} elseif (strpos($currentURL, '/careers') == true) {
    $currentpage = 'careers';
} elseif (strpos($currentURL, '/invest') == true) {
    $currentpage = 'invest';
} elseif (strpos($currentURL, '/contact') == true) {
    $currentpage = 'contact';
}

?>


<!-- NavBar Start -->
<nav class="stroke navbar navbar-expand-lg navbar-light fixed-top" style="background-color: rgba(255, 255, 255, 1); box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.1);">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

        <!-- Logo + Text -->
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/home') ?>">
            <img src="<?= base_url('assets/img/binanlogo.png') ?>" alt="Logo" width="50" height="50" class="d-inline-block">
            <div class="d-none d-sm-flex flex-column align-items-start ms-2">
                <span style="font-size: 12px; font-family: 'Gill Sans'; font-weight: 900; color: #000;">REPUBLIC OF THE PHILIPPINES</span>
                <hr style="width: 100%; margin: 1px 0; border: none; border-top: 2px solid #000;">
                <!-- Adjust the margin and border-top color as needed -->
                <span style="font-size: 14px; font-family: 'Gill Sans'; font-weight: 900; color: #000;">CITY GOVERNMENT OF BIÑAN</span>
            </div>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="navi nav-link fw-bold <?= ($currentpage == 'home') ? 'active' : ''; ?>" href="<?= base_url('/home') ?>">HOME</a>
                </li>

                <!-- ABOUT Dropdown -->
                <li class="nav-item dropdown">
                    <a class="navi nav-link fw-bold dropdown-toggle <?= ($currentpage == 'about' || $currentpage == 'barangays' || $currentpage == 'department') ? 'active' : ''; ?>"
                       href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        ABOUT
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                        <li><a class="dropdown-item" href="<?= base_url('/history') ?>">History</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/about') ?>">Mission & Vision</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/barangays') ?>">Barangays</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/department') ?>">Departments</a></li>
                        <li><a class="dropdown-item" href="https://experiencebinan.com/" target="_blank">Experience Biñan</a></li>
                    </ul>
                </li>

                <!-- TRANSPARENCY Dropdown -->
                <li class="nav-item dropdown">
                    <a class="navi nav-link fw-bold dropdown-toggle <?= ($currentpage == 'officials' || $currentpage == 'fulldisc') ? 'active' : ''; ?>"
                       href="#" id="transDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        TRANSPARENCY
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="transDropdown">
                        <li><a id="orgchartpdf" class="dropdown-item" href="#">Organizational Chart</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/officials') ?>">City Officials</a></li>
                        <li><a class="dropdown-item" href="https://apps.binan.gov.ph/sp/or-rs/">City Ordinances</a></li>
                        <li><a id="citcharterpdf" class="dropdown-item" href="#">Citizen's Charter</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('/fulldisc') ?>">Full Disclosure Policy</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="navi nav-link fw-bold <?= ($currentpage == 'services') ? 'active' : ''; ?>" href="<?= base_url('/services') ?>">eSERVICES</a></li>
                <li class="nav-item"><a class="navi nav-link fw-bold <?= ($currentpage == 'careers') ? 'active' : ''; ?>" href="<?= base_url('/careers') ?>">CAREERS</a></li>
                <li class="nav-item"><a class="navi nav-link fw-bold" href="<?= base_url('/jobs') ?>">JOBS</a></li>
                <li class="nav-item"><a class="navi nav-link fw-bold <?= ($currentpage == 'invest') ? 'active' : ''; ?>" href="<?= base_url('/invest') ?>">INVEST</a></li>
                <!--<li class="nav-item"><a class="navi nav-link fw-bold <?php /*= ($currentpage == 'contact') ? 'active' : ''; */?>" href="<?php /*= base_url('/contact') */?>">CONTACT</a></li>-->
                <li class="nav-item"><a class="navi nav-link fw-bold <?= ($currentpage == 'contact') ? 'active' : ''; ?>" href="#">CONTACT</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- PDF open script -->
<script>
    document.getElementById('citcharterpdf').addEventListener('click', function (e) {
        e.preventDefault();
        const pdfUrl = '<?= base_url('assets/LGU BIÑAN_ 2026 CITIZENS CHARTER_1st Edition.pdf') ?>';
        window.open(pdfUrl, '_blank');
    });

    document.getElementById('orgchartpdf').addEventListener('click', function (e) {
        e.preventDefault();
        const pdfUrl = '<?= base_url('assets/BCH-Organizational-Chart-as-of-February-15-2023.pdf') ?>';
        window.open(pdfUrl, '_blank');
    });
</script>
<!-- NavBar End -->
