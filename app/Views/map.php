<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<body>
<?php include "navbar.php" ?>
    <div class="mapcontainer">
        <div id="map">
            <img src="<?= base_url('assets/img/map.jpeg') ?>" alt="Map">
            <?php foreach ($locations as $location): ?>
                <a href="<?= base_url('barangaycontent/' . $location['id']) ?>" class="location" 
                    style="top: <?= $location['top'] ?>; left: <?= $location['left'] ?>;" 
                    data-bs-toggle="popover" 
                    data-bs-trigger="hover" 
                    title="<?= $location['name'] ?>" 
                    data-bs-content="<?= isset($location['details']) ? htmlspecialchars($location['details'], ENT_QUOTES) : '' ?>">
                    <img src="<?= base_url('assets/img/pin.png') ?>" alt="Marker">
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php include "footer.php"; ?>
	<?php pre_scripts('home'); ?>    
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })
        });
    </script>
</body>
</html>
