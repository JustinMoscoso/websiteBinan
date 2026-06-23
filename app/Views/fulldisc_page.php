<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Disclosure Policy</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<body>
<?php include "navbar.php"; ?>
<?php include "header.php"; ?>
<?php include_header('Full Disclosure Policy',null,[
    'layout' => 'side',
    'bg_color' => '#388e3c']); ?>
    <section id="layout-content">
        <div class="container-fluid py-4">
            <div class="transparency-seal">
                <div class="two-column-grid">
                    <!-- Left Column (First 7 Categories) -->
                    <div class="column">
                        <div class="accordion" id="leftAccordion">
                            <?php
                            // Define annual and quarterly categories
                            $annual_categories = [
                                "Annual Budget Report",
                                "Annual Procurement Plan or Procurement List",
                                "Supplemental Procurement Plan",
                                "Annual Gender and Development Accomplishment Report"
                            ];
                            
                            $quarterly_categories = [
                                "Quarterly Statement of Cash Flow",
                                "Statement of Receipts and Expenditures",
                                "20% Component of the Internal Revenue Allotment Utilization",
                                "Local Disaster Risk Reduction and Management Fund Utilization",
                                "Report of Special Education Fund Utilization",
                                "Trust Fund (PDAF) Utilization",
                                "Unliquidated Cash Advances",
                                "Bid Results on Civil Works and Goods and Services",
                                "Manpower Complement",
                                "Annual Statement of Indebtedness, Payments and Balances"
                            ];
                            
                            $categories = array_merge($annual_categories, $quarterly_categories);
                            $quarters = [
                                "First Quarter",
                                "Second Quarter",
                                "Third Quarter",
                                "Fourth Quarter"
                            ];
                            // First 7 categories for the left column
                            for ($i = 0; $i < 7; $i++) :
                                $category = $categories[$i];
                            ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="leftHeading<?= $i ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#leftCollapse<?= $i ?>" aria-expanded="false"
                                                aria-controls="leftCollapse<?= $i ?>">
                                            <?= htmlspecialchars($category) ?>
                                        </button>
                                    </h2>
                                    <div id="leftCollapse<?= $i ?>" class="accordion-collapse collapse"
                                        aria-labelledby="leftHeading<?= $i ?>" data-bs-parent="#leftAccordion">
                                        <div class="accordion-body">
                                            <?php
                                            if (!isset($fdiscol) || !is_array($fdiscol)) :
                                                echo '<p class="error-message">Error: File data is unavailable.</p>';
                                            else :
                                                $files_in_category = array_filter($fdiscol, function ($file) use ($category) {
                                                    return isset($file->file_category) && $file->file_category === $category;
                                                });
                                                if (empty($files_in_category)) :
                                            ?>
                                                    <p>No files available for this category.</p>
                                                <?php else : ?>
                                                    <?php
                                                    $files_by_year = [];
                                                    foreach ($files_in_category as $file) {
                                                        if (isset($file->year)) {
                                                            $files_by_year[$file->year][] = $file;
                                                        }
                                                    }
                                                    krsort($files_by_year);
                                                    if (empty($files_by_year)) :
                                                        echo '<p class="error-message">No valid year data found.</p>';
                                                    else :
                                                        foreach ($files_by_year as $year => $files) :
                                                    ?>
                                                            <div class="accordion-item nested">
                                                                <h2 class="accordion-header" id="leftHeading<?= $i ?><?= $year ?>">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                            data-bs-target="#leftCollapse<?= $i ?><?= $year ?>" aria-expanded="false"
                                                                            aria-controls="leftCollapse<?= $i ?><?= $year ?>">
                                                                        <?= htmlspecialchars($year) ?>
                                                                    </button>
                                                                </h2>
                                                                <div id="leftCollapse<?= $i ?><?= $year ?>" class="accordion-collapse collapse"
                                                                    aria-labelledby="leftHeading<?= $i ?><?= $year ?>" data-bs-parent="#leftCollapse<?= $i ?>">
                                                                    <div class="accordion-body">
                                                                        <?php
                                                                        // Check if this is an annual category
                                                                        $is_annual = in_array($category, $annual_categories);
                                                                        
                                                                        if ($is_annual) :
                                                                        ?>
                                                                            <ul class="mb-0">
                                                                                <?php foreach ($files as $file) : ?>
                                                                                    <li class="d-flex justify-content-between align-items-center mb-2">
                                                                                        <?php
                                                                                        if (isset($file->file_name) && !empty($file->file_name)) {
                                                                                            $fileUrl = base_url('admin/preview_file/FULLDISC/') . urlencode($file->file_name);
                                                                                            echo '<a href="#" class="preview-link fw-semibold text-decoration-none" data-fileurl="' . htmlspecialchars($fileUrl) . '"><i class="fas fa-eye me-1"></i>Preview</a>';
                                                                                            echo '<a href="' . htmlspecialchars($fileUrl) . '" class="p-1 d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold" style="color: var(--binan-green); font-size: 0.875rem; transition: transform 0.2s, color 0.2s;" onmouseover="this.style.transform=\'scale(1.05)\'; this.style.color=\'var(--binan-green-dark)\'" onmouseout="this.style.transform=\'scale(1)\'; this.style.color=\'var(--binan-green)\'" title="Download File" download>Download <i class="fas fa-download"></i></a>';
                                                                                        } else {
                                                                                            echo '<span class="text-muted small">No File Name Available</span>';
                                                                                        }
                                                                                        ?>
                                                                                    </li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        <?php
                                                                        else :
                                                                            // For quarterly reports, group by quarters
                                                                            $files_by_quarter = [];
                                                                            foreach ($files as $file) {
                                                                                if (isset($file->quarter)) {
                                                                                    $files_by_quarter[$file->quarter][] = $file;
                                                                                }
                                                                            }
                                                                            $quarters_order = ['First', 'Second', 'Third', 'Fourth', 'First Quarter', 'Second Quarter', 'Third Quarter', 'Fourth Quarter'];
                                                                            uksort($files_by_quarter, function($a, $b) use ($quarters_order) {
                                                                                $index_a = array_search($a, $quarters_order);
                                                                                $index_b = array_search($b, $quarters_order);
                                                                                $index_a = ($index_a === false) ? 999 : ($index_a % 4);
                                                                                $index_b = ($index_b === false) ? 999 : ($index_b % 4);
                                                                                return $index_a - $index_b;
                                                                            });
                                                                            if (empty($files_by_quarter)) :
                                                                                echo '<p class="error-message">No valid quarter data found.</p>';
                                                                            else :
                                                                            ?>
                                                                                <div class="accordion" id="qAccLeft<?= $i ?>_<?= $year ?>">
                                                                                    <?php
                                                                                    $quarterNames = [
                                                                                        'First' => 'First Quarter',
                                                                                        'Second' => 'Second Quarter',
                                                                                        'Third' => 'Third Quarter',
                                                                                        'Fourth' => 'Fourth Quarter',
                                                                                        'First Quarter' => 'First Quarter',
                                                                                        'Second Quarter' => 'Second Quarter',
                                                                                        'Third Quarter' => 'Third Quarter',
                                                                                        'Fourth Quarter' => 'Fourth Quarter'
                                                                                    ];
                                                                                    foreach ($files_by_quarter as $quarter => $files_in_quarter) :
                                                                                        $displayQuarter = isset($quarterNames[$quarter]) ? $quarterNames[$quarter] : htmlspecialchars($quarter);
                                                                                        $qKey = preg_replace('/[^a-zA-Z0-9]/', '', $quarter);
                                                                                        $qCollapseId = "qCollapseLeft_" . $i . "_" . $year . "_" . $qKey;
                                                                                        $qHeadingId = "qHeadingLeft_" . $i . "_" . $year . "_" . $qKey;
                                                                                    ?>
                                                                                        <div class="accordion-item nested" style="margin-bottom: 6px;">
                                                                                            <h2 class="accordion-header" id="<?= $qHeadingId ?>">
                                                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                                                        data-bs-target="#<?= $qCollapseId ?>" aria-expanded="false"
                                                                                                        aria-controls="<?= $qCollapseId ?>" style="font-size: 0.9rem; min-height: 44px; padding: 8px 12px;">
                                                                                                    <?= $displayQuarter ?>
                                                                                                </button>
                                                                                            </h2>
                                                                                            <div id="<?= $qCollapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $qHeadingId ?>">
                                                                                                <div class="accordion-body" style="padding: 10px 12px;">
                                                                                                    <ul class="mb-0">
                                                                                                        <?php foreach ($files_in_quarter as $file) : ?>
                                                                                                            <li class="d-flex justify-content-between align-items-center mb-2">
                                                                                                                <?php
                                                                                                                if (isset($file->file_name) && !empty($file->file_name)) {
                                                                                                                    $fileUrl = base_url('admin/preview_file/FULLDISC/') . urlencode($file->file_name);
                                                                                                                    echo '<a href="#" class="preview-link fw-semibold text-decoration-none" data-fileurl="' . htmlspecialchars($fileUrl) . '"><i class="fas fa-eye me-1"></i>Preview</a>';
                                                                                                                    echo '<a href="' . htmlspecialchars($fileUrl) . '" class="p-1 d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold" style="color: var(--binan-green); font-size: 0.875rem; transition: transform 0.2s, color 0.2s;" onmouseover="this.style.transform=\'scale(1.05)\'; this.style.color=\'var(--binan-green-dark)\'" onmouseout="this.style.transform=\'scale(1)\'; this.style.color=\'var(--binan-green)\'" title="Download File" download>Download <i class="fas fa-download"></i></a>';
                                                                                                                } else {
                                                                                                                    echo '<span class="text-muted small">No File Name Available</span>';
                                                                                                                }
                                                                                                                ?>
                                                                                                            </li>
                                                                                                        <?php endforeach; ?>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endforeach; ?>
                                                                                </div>
                                                                            <?php
                                                                            endif;
                                                                        endif;
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <!-- Right Column (Last 7 Categories) -->
                    <div class="column">
                        <div class="accordion" id="rightAccordion">
                            <?php
                            // Last 7 categories for the right column
                            for ($i = 7; $i < 14; $i++) :
                                $category = $categories[$i];
                            ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="rightHeading<?= $i ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#rightCollapse<?= $i ?>" aria-expanded="false"
                                                aria-controls="rightCollapse<?= $i ?>">
                                            <?= htmlspecialchars($category) ?>
                                        </button>
                                    </h2>
                                    <div id="rightCollapse<?= $i ?>" class="accordion-collapse collapse"
                                        aria-labelledby="rightHeading<?= $i ?>" data-bs-parent="#rightAccordion">
                                        <div class="accordion-body">
                                            <?php
                                            if (!isset($fdiscol) || !is_array($fdiscol)) :
                                                echo '<p class="error-message">Error: File data is unavailable.</p>';
                                            else :
                                                $files_in_category = array_filter($fdiscol, function ($file) use ($category) {
                                                    return isset($file->file_category) && $file->file_category === $category;
                                                });
                                                if (empty($files_in_category)) :
                                            ?>
                                                    <p>No files available for this category.</p>
                                                <?php else : ?>
                                                    <?php
                                                    $files_by_year = [];
                                                    foreach ($files_in_category as $file) {
                                                        if (isset($file->year)) {
                                                            $files_by_year[$file->year][] = $file;
                                                        }
                                                    }
                                                    krsort($files_by_year);
                                                    if (empty($files_by_year)) :
                                                        echo '<p class="error-message">No valid year data found.</p>';
                                                    else :
                                                        foreach ($files_by_year as $year => $files) :
                                                    ?>
                                                            <div class="accordion-item nested">
                                                                <h2 class="accordion-header" id="rightHeading<?= $i ?><?= $year ?>">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                            data-bs-target="#rightCollapse<?= $i ?><?= $year ?>" aria-expanded="false"
                                                                            aria-controls="rightCollapse<?= $i ?><?= $year ?>">
                                                                        <?= htmlspecialchars($year) ?>
                                                                    </button>
                                                                </h2>
                                                                <div id="rightCollapse<?= $i ?><?= $year ?>" class="accordion-collapse collapse"
                                                                    aria-labelledby="rightHeading<?= $i ?><?= $year ?>" data-bs-parent="#rightCollapse<?= $i ?>">
                                                                    <div class="accordion-body">
                                                                        <?php
                                                                        // Check if this is an annual category
                                                                        $is_annual = in_array($category, $annual_categories);
                                                                        
                                                                        if ($is_annual) :
                                                                        ?>
                                                                            <ul class="mb-0">
                                                                                <?php foreach ($files as $file) : ?>
                                                                                    <li class="d-flex justify-content-between align-items-center mb-2">
                                                                                        <?php
                                                                                        if (isset($file->file_name) && !empty($file->file_name)) {
                                                                                            $fileUrl = base_url('admin/preview_file/FULLDISC/') . urlencode($file->file_name);
                                                                                            echo '<a href="#" class="preview-link fw-semibold text-decoration-none" data-fileurl="' . htmlspecialchars($fileUrl) . '"><i class="fas fa-eye me-1"></i>Preview</a>';
                                                                                            echo '<a href="' . htmlspecialchars($fileUrl) . '" class="p-1 d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold" style="color: var(--binan-green); font-size: 0.875rem; transition: transform 0.2s, color 0.2s;" onmouseover="this.style.transform=\'scale(1.05)\'; this.style.color=\'var(--binan-green-dark)\'" onmouseout="this.style.transform=\'scale(1)\'; this.style.color=\'var(--binan-green)\'" title="Download File" download>Download <i class="fas fa-download"></i></a>';
                                                                                        } else {
                                                                                            echo '<span class="text-muted small">No File Name Available</span>';
                                                                                        }
                                                                                        ?>
                                                                                    </li>
                                                                                <?php endforeach; ?>
                                                                            </ul>
                                                                        <?php
                                                                        else :
                                                                            // For quarterly reports, group by quarters
                                                                            $files_by_quarter = [];
                                                                            foreach ($files as $file) {
                                                                                if (isset($file->quarter)) {
                                                                                    $files_by_quarter[$file->quarter][] = $file;
                                                                                }
                                                                            }
                                                                            $quarters_order = ['First', 'Second', 'Third', 'Fourth', 'First Quarter', 'Second Quarter', 'Third Quarter', 'Fourth Quarter'];
                                                                            uksort($files_by_quarter, function($a, $b) use ($quarters_order) {
                                                                                $index_a = array_search($a, $quarters_order);
                                                                                $index_b = array_search($b, $quarters_order);
                                                                                $index_a = ($index_a === false) ? 999 : ($index_a % 4);
                                                                                $index_b = ($index_b === false) ? 999 : ($index_b % 4);
                                                                                return $index_a - $index_b;
                                                                            });
                                                                            if (empty($files_by_quarter)) :
                                                                                echo '<p class="error-message">No valid quarter data found.</p>';
                                                                            else :
                                                                            ?>
                                                                                <div class="accordion" id="qAccRight<?= $i ?>_<?= $year ?>">
                                                                                    <?php
                                                                                    $quarterNames = [
                                                                                        'First' => 'First Quarter',
                                                                                        'Second' => 'Second Quarter',
                                                                                        'Third' => 'Third Quarter',
                                                                                        'Fourth' => 'Fourth Quarter',
                                                                                        'First Quarter' => 'First Quarter',
                                                                                        'Second Quarter' => 'Second Quarter',
                                                                                        'Third Quarter' => 'Third Quarter',
                                                                                        'Fourth Quarter' => 'Fourth Quarter'
                                                                                    ];
                                                                                    foreach ($files_by_quarter as $quarter => $files_in_quarter) :
                                                                                        $displayQuarter = isset($quarterNames[$quarter]) ? $quarterNames[$quarter] : htmlspecialchars($quarter);
                                                                                        $qKey = preg_replace('/[^a-zA-Z0-9]/', '', $quarter);
                                                                                        $qCollapseId = "qCollapseRight_" . $i . "_" . $year . "_" . $qKey;
                                                                                        $qHeadingId = "qHeadingRight_" . $i . "_" . $year . "_" . $qKey;
                                                                                    ?>
                                                                                        <div class="accordion-item nested" style="margin-bottom: 6px;">
                                                                                            <h2 class="accordion-header" id="<?= $qHeadingId ?>">
                                                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                                                        data-bs-target="#<?= $qCollapseId ?>" aria-expanded="false"
                                                                                                        aria-controls="<?= $qCollapseId ?>" style="font-size: 0.9rem; min-height: 44px; padding: 8px 12px;">
                                                                                                    <?= $displayQuarter ?>
                                                                                                </button>
                                                                                            </h2>
                                                                                            <div id="<?= $qCollapseId ?>" class="accordion-collapse collapse" aria-labelledby="<?= $qHeadingId ?>">
                                                                                                <div class="accordion-body" style="padding: 10px 12px;">
                                                                                                    <ul class="mb-0">
                                                                                                        <?php foreach ($files_in_quarter as $file) : ?>
                                                                                                            <li class="d-flex justify-content-between align-items-center mb-2">
                                                                                                                <?php
                                                                                                                if (isset($file->file_name) && !empty($file->file_name)) {
                                                                                                                    $fileUrl = base_url('admin/preview_file/FULLDISC/') . urlencode($file->file_name);
                                                                                                                    echo '<a href="#" class="preview-link fw-semibold text-decoration-none" data-fileurl="' . htmlspecialchars($fileUrl) . '"><i class="fas fa-eye me-1"></i>Preview</a>';
                                                                                                                    echo '<a href="' . htmlspecialchars($fileUrl) . '" class="p-1 d-inline-flex align-items-center gap-1 text-decoration-none fw-semibold" style="color: var(--binan-green); font-size: 0.875rem; transition: transform 0.2s, color 0.2s;" onmouseover="this.style.transform=\'scale(1.05)\'; this.style.color=\'var(--binan-green-dark)\'" onmouseout="this.style.transform=\'scale(1)\'; this.style.color=\'var(--binan-green)\'" title="Download File" download>Download <i class="fas fa-download"></i></a>';
                                                                                                                } else {
                                                                                                                    echo '<span class="text-muted small">No File Name Available</span>';
                                                                                                                }
                                                                                                                ?>
                                                                                                            </li>
                                                                                                        <?php endforeach; ?>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endforeach; ?>
                                                                                </div>
                                                                            <?php
                                                                            endif;
                                                                        endif;
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include "footer.php"; ?>
    <?php pre_scripts('home'); ?>

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
      <div class="modal-header text-white px-4 py-3 d-flex justify-content-between align-items-center w-100" style="background-color: #1b4d3e !important; border-bottom: none;">
        <h5 class="modal-title fw-bold" id="filePreviewModalLabel">File Preview</h5>
        <div class="d-flex align-items-center gap-2 ms-auto">
          <a id="fileDownloadBtn" href="" class="btn btn-sm btn-light fw-bold px-3 d-flex align-items-center gap-1 shadow-sm" download>
            <i class="fas fa-download"></i> Download
          </a>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <div class="modal-body p-0" style="height: 75vh; position: relative; background-color: #f8f9fa;">
        
        <!-- Native Iframe for Previewable Files (PDF, Images) -->
        <iframe id="filePreviewFrame" src="" style="width:100%; height:100%; border:none; display:none;"></iframe>
        
        <!-- Placeholder for Non-previewable Files (Excel, Word, Zip) -->
        <div id="filePreviewPlaceholder" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center px-4 text-center" style="display:none;">
          <div class="card p-5 border-0 shadow-sm d-flex flex-column align-items-center" style="max-width: 500px; border-radius: 16px; background: #ffffff;">
            <div class="icon-box mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%; background-color: #ffebee; color: #c62828; font-size: 2.2rem;">
              <i class="fas fa-file-excel" id="fileTypeIcon"></i>
            </div>
            <h4 class="fw-bold mb-2 text-dark" style="color: #2d3748;">No Preview Available</h4>
            <p class="text-secondary small mb-4" id="placeholderMessage">This file format (.xlsx) cannot be previewed directly in your browser. You can download the file to view its contents on your device.</p>
            <a id="placeholderDownloadBtn" href="" class="btn btn-primary fw-bold px-4 py-2 d-inline-flex align-items-center gap-2 shadow" style="background-color: #1b4d3e !important; border-color: #1b4d3e !important; border-radius: 6px;" download>
              <i class="fas fa-cloud-download-alt"></i> Download File
            </a>
          </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
</body>
</html>