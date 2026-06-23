<?php

function pre_styles($mode = 'default')
{
    if ($mode == 'home') {
        $css = array(
            site_url("assets/css/poppins.css"),
            site_url("assets/css/lora.css"),
            site_url("assets/css/oswald.css"),
            site_url("assets/css/yellowtail.css"),
            site_url("assets/css/oswald-500.css"),
            site_url("assets/css/raleway.css"),
            site_url("assets/css/bebas-neue.css"),
            site_url("assets/css/oswald-roboto.css"),
            site_url("assets/css/fontawesome-all.css"),
            site_url("assets/css/fontawesome-min.css"),
            site_url("assets/css/dataTables.dataTables.css"),
            site_url("assets/css/aos.css"),
            site_url("assets/css/bootstrap.min.css"),
            site_url("assets/admin/vendor/bootstrap-icons/bootstrap-icons.css"),
            site_url("assets/admin/vendor/quill/quill.snow.css"),
            site_url("assets/admin/vendor/quill/quill.bubble.css"),
            site_url("assets/css/stylesheet.css?v=4"),
            site_url("assets/css/hotlines.css?v=2"),
            site_url("assets/css/contact_page.css?v=" . time()),
            site_url("assets/css/fulldisc_page.css?v=" . time()),
        );
    } elseif ($mode == 'admin') {
        $css = array(
            site_url("assets/sbadmin2/vendor/fontawesome-free/css/all.min.css"),
            site_url("assets/sbadmin2/css/sb-admin-2.min.css"),
            site_url("assets/css/datatables.min.css"),
            site_url("assets/admin/vendor/quill/quill.snow.css"),
            site_url("assets/admin/vendor/quill/quill.bubble.css"),
            site_url("assets/admin/vendor/simple-datatables/style.css"),
            site_url("assets/admin/yearpicker/dist/yearpicker.css"),
        );
        $css[] = site_url("assets/css/bootstrap-select.min.css");
        $css[] = site_url("assets/css/selectize.bootstrap3.min.css");
    } else {
        $css = array(
            site_url("assets/css/datatables.min.css"),
            site_url("assets/css/google-fonts.css"),
            site_url("assets/css/bootstrap.min.css"),
            site_url("assets/admin/vendor/bootstrap-icons/bootstrap-icons.css"),
            site_url("assets/admin/vendor/boxicons/css/boxicons.min.css"),
            site_url("assets/admin/vendor/quill/quill.snow.css"),
            site_url("assets/admin/vendor/quill/quill.bubble.css"),
            site_url("assets/admin/vendor/remixicon/remixicon.css"),
            site_url("assets/admin/vendor/simple-datatables/style.css"),
            site_url("assets/css/style.css"),
            site_url("assets/admin/yearpicker/dist/yearpicker.css"),
        );
        $css[] = site_url("assets/css/bootstrap-select.min.css");
        $css[] = site_url("assets/css/selectize.bootstrap3.min.css");
    }

    foreach ($css as $elem) {
        echo "<link rel='stylesheet' href='$elem'/>";
    }
}

function pre_scripts($mode = 'default')
{
    if ($mode == 'home') {
        $js = array(
            site_url("assets/js/jquery_v3.6.4.js"),
            site_url("assets/js/vendor/datatables/dataTables.js"),
            site_url("assets/js/vendor/aos/aos.js"),
            site_url("assets/vendor/bootstrap/js/bootstrap.bundle.min.js"),
            site_url("assets/admin/vendor/quill/quill.min.js"),
            site_url("assets/js/main.js"),
            site_url("assets/js/contact_page.js?v=" . time()),
            site_url("assets/js/fulldisc_page.js?v=" . time()),
            /*
            site_url("assets/vendor/aos/aos.js"),
            site_url("assets/vendor/glightbox/js/glightbox.min.js"),
            site_url("assets/vendor/isotope-layout/isotope.pkgd.min.js"),
            site_url("assets/vendor/swiper/swiper-bundle.min.js"),
            site_url("assets/vendor/waypoints/noframework.waypoints.js"),
            site_url("assets/vendor/php-email-form/validate.js"),
            site_url("assets/js/home.js"),*/
        );
    } elseif ($mode == 'admin') {
        $js = array(
            site_url("assets/sbadmin2/vendor/jquery/jquery.min.js"),
            site_url("assets/vendor/bootstrap/js/bootstrap.bundle.min.js"),
            site_url("assets/sbadmin2/vendor/jquery-easing/jquery.easing.min.js"),
            site_url("assets/sbadmin2/js/sb-admin-2.min.js"),
            site_url("assets/js/vendor/pdfmake/pdfmake.min.js"),
            site_url("assets/js/vendor/pdfmake/vfs_fonts.js"),
            site_url("assets/js/vendor/datatables/datatables-bundle.min.js"),
            site_url("assets/js/vendor/moment/moment.min.js"),
            site_url("assets/admin/vendor/apexcharts/apexcharts.min.js"),
            site_url("assets/admin/vendor/chart.js/chart.umd.js"),
            site_url("assets/admin/vendor/echarts/echarts.min.js"),
            site_url("assets/admin/vendor/quill/quill.min.js"),
            site_url("assets/admin/vendor/simple-datatables/simple-datatables.js"),
            site_url("assets/admin/vendor/tinymce/tinymce.min.js"),
            site_url("assets/js/main.js?v=" . time()),
            site_url("assets/admin/yearpicker/dist/yearpicker.js"),
        );
        $js[] = site_url("assets/js/vendor/selectize/selectize.min.js");
        $js[] = site_url("assets/js/vendor/bootstrap-select/bootstrap-select.min.js");
        $js[] = site_url("assets/js/vendor/fontawesome/all.min.js");
        $js[] = site_url("assets/js/vendor/sweetalert2/sweetalert2.min.js");
    } else {
        $js = array(
            site_url("assets/js/jquery_v3.6.4.js"),
            site_url("assets/js/vendor/pdfmake/pdfmake.min.js"),
            site_url("assets/js/vendor/pdfmake/vfs_fonts.js"),
            site_url("assets/js/vendor/datatables/datatables-bundle.min.js"),
            site_url("assets/js/vendor/moment/moment.min.js"),
            site_url("assets/vendor/apexcharts/apexcharts.min.js"),
            site_url("assets/vendor/bootstrap/js/bootstrap.bundle.min.js"),
            site_url("assets/vendor/chart.js/chart.umd.js"),
            site_url("assets/vendor/echarts/echarts.min.js"),
            site_url("assets/vendor/quill/quill.min.js"),
            site_url("assets/vendor/simple-datatables/simple-datatables.js"),
            site_url("assets/vendor/tinymce/tinymce.min.js"),
            site_url("assets/vendor/php-email-form/validate.js"),
            site_url("assets/js/main.js?v=" . time()),
            site_url("assets/admin/yearpicker/dist/yearpicker.js"),
        );
        $js[] = site_url("assets/js/vendor/selectize/selectize.min.js");
        $js[] = site_url("assets/js/vendor/bootstrap-select/bootstrap-select.min.js");
        $js[] = site_url("assets/js/vendor/fontawesome/all.min.js");
        $js[] = site_url("assets/js/vendor/sweetalert2/sweetalert2.min.js");

    }

    foreach ($js as $elem) {
        echo "<script src='$elem'></script>";
    }

    // NEW
    if ($mode == 'home') {
        echo "<script>AOS.init();</script>\n";
    }
}
?>
