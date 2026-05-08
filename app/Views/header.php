<?php
function include_header($title, $breadcrumbs = null) {
    if ($breadcrumbs === null) {
        $breadcrumbs = [
            ['text' => 'Home', 'url' => base_url('/home')],
            ['text' => $title, 'active' => true]
        ];
    }
?>
<style>
.header.breadcrumbs {
    background: #388e3c;
    color: #fff;
    padding: 2rem 0 1.2rem 0;
}
.header.breadcrumbs h2 {
    font-weight: 700;
    margin: 0;
    color: #fff;
}
.header.breadcrumbs .breadcrumb {
    background: rgba(255,255,255,0.08);
    border-radius: 20px;
    margin-bottom: 0;
    padding: 0.5rem 1.5rem;
}
.header.breadcrumbs .breadcrumb a {
    color: #fff;
    text-decoration: none;
}
.header.breadcrumbs .breadcrumb .text-warning {
    color: #ffeb3b !important;
    font-weight: 500;
}
</style>
<header>
    <div class="header breadcrumbs d-flex align-items-center">
        <div class="container position-relative d-flex justify-content-between align-items-center" data-aos="fade">
            <h2><?= htmlspecialchars($title) ?></h2>
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $index => $crumb): ?>
                    <?php if ($index > 0): ?>
                    <?php endif; ?>
                    <?php if (isset($crumb['active']) && $crumb['active']): ?>
                        <li class="text-warning"><?= htmlspecialchars($crumb['text']) ?></li>
                    <?php else: ?>
                        <li><a href="<?= $crumb['url'] ?>"><?= htmlspecialchars($crumb['text']) ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</header>
<?php } ?>