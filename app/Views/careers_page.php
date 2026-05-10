<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Careers</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
    <!-- Careers Page Styles -->
    <link href="<?= base_url('assets/css/careers.css'); ?>" rel="stylesheet">
</head>

<body>
    <?php include "navbar.php"; ?>
    <?php include "header.php"; ?>
    <?php include_header('Careers',null,[
        'layout' => 'side',
        'bg_color' => '#388e3c',
        ]); ?>
    
    <div id="app">
        <div class="careers-container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Search Section -->
                        <div class="search-section mb-4">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-6 col-12 mb-2 mb-md-0">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                                        <input type="month" id="searchInput" class="form-control" placeholder="Select month and year to filter...">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fas fa-layer-group"></i></span>
                                        <select id="levelFilter" class="form-select">
                                            <option value="all">Show All Levels</option>
                                            <option value="1">Level 1 Only</option>
                                            <option value="2">Level 2 Only</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Table Stats -->
                        <div class="table-stats" id="tableStats">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="statsText">Loading entries...</span>
                        </div>
                        
                        <!-- Table Section -->
                        <div class="table-responsive" style="position: relative;">
                            <div class="loading-overlay" id="loadingOverlay">
                                <div class="loading-spinner"></div>
                            </div>
                            
                            <table id="careersTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 40%; cursor:pointer;" id="dateSortHeader">
                                            <i class="fas fa-calendar-alt me-2"></i>Vacancy Dates
                                            <span id="dateSortIcon" style="font-size:0.9em;"><i class="fas fa-sort"></i></span>
                                        </th>
                                        <th scope="col" style="width: 20%" class="not-sortable"><i class="fas fa-layer-group me-2"></i>Level</th>
                                        <th scope="col" style="width: 20%"><i class="fas fa-eye me-2"></i>Preview</th>
                                        <th scope="col" style="width: 20%" class="not-sortable"><i class="fas fa-download me-2"></i>Download</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($careers)): ?>
                                        <?php foreach ($careers as $career): ?>
                                            <?php if (!empty($career->publication_date)): ?>
                                                <tr data-pubmonth="<?= date('Y-m', strtotime($career->publication_date)) ?>">
                                                    <td class="date-cell" data-order="<?= strtotime($career->publication_date) ?>" data-label="Vacancy Date">
                                                        <span class="date-main"><?= date('F j, Y', strtotime($career->publication_date)) ?></span>
                                                        <span class="date-sub"><?= date('l', strtotime($career->publication_date)) ?></span>
                                                    </td>
                                                    <td data-label="Level">
                                                        <?php if (isset($career->level) && $career->level): ?>
                                                            Level <?= htmlspecialchars($career->level) ?>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                    <td data-label="Preview">
                                                        <div class="action-buttons">
                                                            <button class="action-btn btn-preview" onclick="openPreviewModal('<?= base_url('admin/preview_file/CAREERS/' . $career->file_name) ?>', '<?= pathinfo($career->file_name, PATHINFO_EXTENSION) ?>')">
                                                                <i class="fas fa-eye"></i>
                                                                Preview
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td data-label="Download">
                                                        <div class="action-buttons">
                                                            <a href="<?= base_url('admin/preview_file/CAREERS/' . $career->file_name) ?>" 
                                                               class="action-btn btn-download" 
                                                               download>
                                                                <i class="fas fa-download"></i>
                                                                Download
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4">
                                                <div class="no-careers">
                                                    <i class="fas fa-briefcase"></i>
                                                    <h4>No Active Careers Available</h4>
                                                    <p>There are currently no career opportunities posted. Please check back later for new openings.</p>
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
        </div>
    </div>
        <!-- File Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">File Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewBody" style="min-height: 500px; text-align: center;">
                <iframe id="previewIframe" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                <p id="unsupportedMsg" class="text-muted" style="display:none;">This file type cannot be previewed. Please download to view.</p>
            </div>
            </div>
        </div>
    </div>
    
    <?php include "footer.php"; ?>
    <?php pre_scripts('home'); ?>
    <!-- Careers Page JavaScript -->
    <script src="<?= base_url('assets/js/careers.js'); ?>"></script>
    <script>
// Level filter logic
const levelFilter = document.getElementById('levelFilter');
if (levelFilter) {
    levelFilter.addEventListener('change', function() {
        const selected = this.value;
        const rows = document.querySelectorAll('#careersTable tbody tr');
        rows.forEach(row => {
            const levelCell = row.querySelector('td[data-label="Level"]');
            if (!levelCell) return;
            const text = levelCell.textContent.trim();
            if (selected === 'all') {
                row.style.display = '';
            } else if (text === `Level ${selected}`) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}

// Date sorting logic
let dateSortAsc = true;
document.getElementById('dateSortHeader').addEventListener('click', function() {
    const tbody = document.querySelector('#careersTable tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.sort((a, b) => {
        const aOrder = parseInt(a.querySelector('.date-cell').getAttribute('data-order'));
        const bOrder = parseInt(b.querySelector('.date-cell').getAttribute('data-order'));
        return dateSortAsc ? aOrder - bOrder : bOrder - aOrder;
    });
    rows.forEach(row => tbody.appendChild(row));
    dateSortAsc = !dateSortAsc;
    document.getElementById('dateSortIcon').innerHTML = dateSortAsc ? '<i class="fas fa-sort-amount-down"></i>' : '<i class="fas fa-sort-amount-up"></i>';
});
</script>
</body>
</html>