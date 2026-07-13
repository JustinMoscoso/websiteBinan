<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Disclosure Policy</title>
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
    <link href="<?= base_url('assets/css/careers.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/fulldisc_directory.css?v=' . time()); ?>" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<?php include 'header.php'; ?>
<?php include_header('Full Disclosure Policy', null, [
    'layout' => 'side',
    'bg_color' => '#388e3c'
]); ?>

<?php
$annualCategories = [
    'Annual Budget Report',
    'Annual Procurement Plan or Procurement List',
    'Supplemental Procurement Plan',
    'Annual Gender and Development Accomplishment Report',
];
$quarterlyCategories = [
    'Quarterly Statement of Cash Flow',
    'Statement of Receipts and Expenditures',
    '20% Component of the Internal Revenue Allotment Utilization',
    'Local Disaster Risk Reduction and Management Fund Utilization',
    'Report of Special Education Fund Utilization',
    'Trust Fund (PDAF) Utilization',
    'Unliquidated Cash Advances',
    'Bid Results on Civil Works and Goods and Services',
    'Manpower Complement',
    'Annual Statement of Indebtedness, Payments and Balances',
];
$allowedCategories = array_merge($annualCategories, $quarterlyCategories);
sort($allowedCategories);

$documents = array_values(array_filter(is_array($fdiscol ?? null) ? $fdiscol : [], static function ($file) use ($allowedCategories) {
    return !empty($file->file_name)
        && isset($file->file_category)
        && in_array($file->file_category, $allowedCategories, true);
}));

usort($documents, static function ($a, $b) {
    $yearCompare = (int) ($b->year ?? 0) <=> (int) ($a->year ?? 0);
    return $yearCompare !== 0
        ? $yearCompare
        : strcasecmp((string) ($a->file_category ?? ''), (string) ($b->file_category ?? ''));
});

$years = [];
foreach ($documents as $document) {
    if (!empty($document->year)) {
        $years[(string) $document->year] = true;
    }
}
$years = array_keys($years);
rsort($years, SORT_NUMERIC);

$quarterLabels = [
    'First' => 'First Quarter',
    'Second' => 'Second Quarter',
    'Third' => 'Third Quarter',
    'Fourth' => 'Fourth Quarter',
    'First Quarter' => 'First Quarter',
    'Second Quarter' => 'Second Quarter',
    'Third Quarter' => 'Third Quarter',
    'Fourth Quarter' => 'Fourth Quarter',
];
?>

<main class="careers-container disclosure-directory">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <section class="search-section disclosure-filters" aria-label="Filter disclosure documents">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="disclosureCategory">Category</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-folder-open"></i></span>
                                <select id="disclosureCategory" class="form-select">
                                    <option value="">Select categories</option>
                                    <?php foreach ($allowedCategories as $category): ?>
                                        <option value="<?= esc(strtolower($category)) ?>"><?= esc($category) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="disclosureYear">Year</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                                <select id="disclosureYear" class="form-select">
                                    <option value="">Select years</option>
                                    <?php foreach ($years as $year): ?>
                                        <option value="<?= esc($year) ?>"><?= esc($year) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="filter-footer">
                        <span class="filter-help"><i class="fas fa-shield-alt me-2"></i>Official transparency documents of the City Government of Biñan</span>
                        <button id="clearDisclosureFilters" type="button" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-undo-alt me-1"></i>Clear filters
                        </button>
                    </div>
                </section>

                <div class="table-stats" aria-live="polite">
                    <i class="fas fa-info-circle me-2"></i>
                    <span id="disclosureStats">Showing <?= count($documents) ?> documents</span>
                </div>

                <div class="table-responsive disclosure-table-card">
                    <table id="disclosureTable" class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col"><i class="fas fa-folder me-2"></i>Category</th>
                                <th scope="col"><i class="fas fa-calendar-check me-2"></i>Reporting Period</th>
                                <th scope="col"><i class="fas fa-eye me-2"></i>Preview</th>
                                <th scope="col"><i class="fas fa-download me-2"></i>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($documents as $document): ?>
                            <?php
                            $category = (string) ($document->file_category ?? 'Uncategorized');
                            $year = (string) ($document->year ?? 'Not specified');
                            $quarter = trim((string) ($document->quarter ?? ''));
                            $isAnnual = in_array($category, $annualCategories, true);
                            $period = $year;
                            if (!$isAnnual && $quarter !== '') {
                                $period .= ' · ' . ($quarterLabels[$quarter] ?? $quarter);
                            }
                            $fileUrl = base_url('admin/preview_file/FULLDISC/') . rawurlencode($document->file_name);
                            $searchText = strtolower($category . ' ' . $period);
                            ?>
                            <tr class="disclosure-row"
                                data-category="<?= esc(strtolower($category)) ?>"
                                data-year="<?= esc($year) ?>"
                                data-search="<?= esc($searchText) ?>">
                                <td data-label="Category">
                                    <span class="document-category"><?= esc($category) ?></span>
                                </td>
                                <td data-label="Reporting Period">
                                    <span class="period-main"><?= esc($year) ?></span>
                                    <?php if (!$isAnnual && $quarter !== ''): ?>
                                        <span class="period-sub"><?= esc($quarterLabels[$quarter] ?? $quarter) ?></span>
                                    <?php else: ?>
                                        <span class="period-sub">Annual report</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Preview">
                                    <button type="button" class="action-btn btn-preview preview-link" data-fileurl="<?= esc($fileUrl) ?>">
                                        <i class="fas fa-eye"></i>Preview
                                    </button>
                                </td>
                                <td data-label="Download">
                                    <a href="<?= esc($fileUrl) ?>" class="action-btn btn-download" download>
                                        <i class="fas fa-download"></i>Download
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                            <tr id="noDisclosureResults" class="d-none">
                                <td colspan="4">
                                    <div class="empty-disclosure">
                                        <i class="fas fa-file-search"></i>
                                        <h3>No documents found</h3>
                                        <p>Try changing or clearing the filters.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php if (empty($documents)): ?>
                            <tr>
                                <td colspan="4">
                                    <div class="empty-disclosure">
                                        <i class="fas fa-folder-open"></i>
                                        <h3>No disclosure documents available</h3>
                                        <p>Please check back later for published records.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg preview-modal-content">
            <div class="modal-header text-white modal-header-binan">
                <h5 class="modal-title fw-bold" id="filePreviewModalLabel">File Preview</h5>
                <div class="d-flex align-items-center gap-2">
                    <a id="fileDownloadBtn" href="" class="btn btn-sm btn-light fw-bold px-3" download>
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0 preview-modal-body">
                <iframe id="filePreviewFrame" src="" title="Disclosure document preview"></iframe>
                <div id="filePreviewPlaceholder" class="preview-placeholder">
                    <div class="preview-placeholder-card">
                        <div class="preview-file-icon"><i class="fas fa-file" id="fileTypeIcon"></i></div>
                        <h4>No Preview Available</h4>
                        <p id="placeholderMessage">This file format cannot be previewed directly in your browser.</p>
                        <a id="placeholderDownloadBtn" href="" class="btn btn-success" download>
                            <i class="fas fa-download me-2"></i>Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php pre_scripts('home'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const category = document.getElementById('disclosureCategory');
    const year = document.getElementById('disclosureYear');
    const clear = document.getElementById('clearDisclosureFilters');
    const rows = Array.from(document.querySelectorAll('.disclosure-row'));
    const stats = document.getElementById('disclosureStats');
    const empty = document.getElementById('noDisclosureResults');

    function filterDocuments() {
        const selectedCategory = category.value;
        const selectedYear = year.value;
        let visible = 0;

        rows.forEach(function (row) {
            const matchesCategory = !selectedCategory || row.dataset.category === selectedCategory;
            const matchesYear = !selectedYear || row.dataset.year === selectedYear;
            const show = matchesCategory && matchesYear;
            row.classList.toggle('d-none', !show);
            if (show) visible++;
        });

        stats.textContent = 'Showing ' + visible + (visible === 1 ? ' document' : ' documents');
        empty.classList.toggle('d-none', visible !== 0 || rows.length === 0);
    }

    category.addEventListener('change', filterDocuments);
    year.addEventListener('change', filterDocuments);
    clear.addEventListener('click', function () {
        category.value = '';
        year.value = '';
        filterDocuments();
        category.focus();
    });
});
</script>
</body>
</html>
