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
                                                                        <ul>
                                                                            <?php
                                                                            // Check if this is an annual category
                                                                            $is_annual = in_array($category, $annual_categories);
                                                                            
                                                                            if ($is_annual) :
                                                                                // For annual reports, show files directly without quarter grouping
                                                                                foreach ($files as $file) :
                                                                            ?>
                                                                                    <li>
                                                                                        <?php
                                                                                        if (isset($file->file_name) && !empty($file->file_name)) {
                                                                                            $fileUrl = base_url('admin/preview_file/FULLDISC/') . urlencode($file->file_name);
                                                                                            echo '<a href="#" class="preview-link" data-fileurl="' . htmlspecialchars($fileUrl) . '">Preview</a>';
                                                                                        } else {
                                                                                            echo 'No File Name Available';
                                                                                        }
                                                                                        ?>
                                                                                    </li>
                                                                            <?php 
                                                                                endforeach;
                                                                            else :
                                                                                // For quarterly reports, group by quarters
                                                                                $files_by_quarter = [];
                                                                                foreach ($files as $file) {
                                                                                    if (isset($file->quarter)) {
                                                                                        $files_by_quarter[$file->quarter][] = $file;
                                                                                    }
                                                                                }
                                                                                uksort($files_by_quarter, function($a, $b) use ($quarters) {
                                                                                    return array_search($a, $quarters) - array_search($b, $quarters);
                                                                                });
                                                                                if (empty($files_by_quarter)) :
                                                                                    echo '<p class="error-message">No valid quarter data found.</p>';
                                                                                else :
                                                                                    foreach ($files_by_quarter as $quarter => $files_in_quarter) :
                                                                            ?>
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
                                                                                        $displayQuarter = isset($quarterNames[$quarter]) ? $quarterNames[$quarter] : htmlspecialchars($quarter);
                                                                                        ?>
                                                                                        <li class="quarter-label"><?= $displayQuarter ?></li>
                                                                                        <ul>
                                                                                            <?php foreach ($files_in_quarter as $file) : ?>
                                                                                                <li>
                                                                                                    <?php
                                                                                                    if (isset($file->file_name) && !empty($file->file_name)) {
                                                                                                        $fileUrl = base_url('admin/preview_file/FULLDISC/') . urlencode($file->file_name);
                                                                                                        echo '<a href="#" class="preview-link" data-fileurl="' . htmlspecialchars($fileUrl) . '">Preview</a>';
                                                                                                    } else {
                                                                                                        echo 'No File Name Available';
                                                                                                    }
                                                                                                    ?>
                                                                                                </li>
                                                                                            <?php endforeach; ?>
                                                                                        </ul>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </ul>
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
                                                                        <ul>
                                                                            <?php
                                                                            // Check if this is an annual category
                                                                            $is_annual = in_array($category, $annual_categories);
                                                                            
                                                                            if ($is_annual) :
                                                                                // For annual reports, show files directly without quarter grouping
                                                                                foreach ($files as $file) :
                                                                            ?>
                                                                                    <li>
                                                                                        <?php
                                                                                        if (isset($file->file_name) && !empty($file->file_name)) {
                                                                                            $fileUrl = base_url('admin/preview_file/FULLDISC/') . urlencode($file->file_name);
                                                                                            echo '<a href="#" class="preview-link" data-fileurl="' . htmlspecialchars($fileUrl) . '">Preview</a>';
                                                                                        } else {
                                                                                            echo 'No File Name Available';
                                                                                        }
                                                                                        ?>
                                                                                    </li>
                                                                            <?php 
                                                                                endforeach;
                                                                            else :
                                                                                // For quarterly reports, group by quarters
                                                                                $files_by_quarter = [];
                                                                                foreach ($files as $file) {
                                                                                    if (isset($file->quarter)) {
                                                                                        $files_by_quarter[$file->quarter][] = $file;
                                                                                    }
                                                                                }
                                                                                uksort($files_by_quarter, function($a, $b) use ($quarters) {
                                                                                    return array_search($a, $quarters) - array_search($b, $quarters);
                                                                                });
                                                                                if (empty($files_by_quarter)) :
                                                                                    echo '<p class="error-message">No valid quarter data found.</p>';
                                                                                else :
                                                                                    foreach ($files_by_quarter as $quarter => $files_in_quarter) :
                                                                            ?>
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
                                                                                        $displayQuarter = isset($quarterNames[$quarter]) ? $quarterNames[$quarter] : htmlspecialchars($quarter);
                                                                                        ?>
                                                                                        <li class="quarter-label"><?= $displayQuarter ?></li>
                                                                                        <ul>
                                                                                            <?php foreach ($files_in_quarter as $file) : ?>
                                                                                                <li>
                                                                                                    <?php
                                                                                                    if (isset($file->file_name) && !empty($file->file_name)) {
                                                                                                        $fileUrl = base_url('admin/preview_file/FULLDISC/') . urlencode($file->file_name);
                                                                                                        echo '<a href="#" class="preview-link" data-fileurl="' . htmlspecialchars($fileUrl) . '">Preview</a>';
                                                                                                    } else {
                                                                                                        echo 'No File Name Available';
                                                                                                    }
                                                                                                    ?>
                                                                                                </li>
                                                                                            <?php endforeach; ?>
                                                                                        </ul>
                                                                                    <?php endforeach; ?>
                                                                                <?php endif; ?>
                                                                            <?php endif; ?>
                                                                        </ul>
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
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="height:80vh;">
        <iframe id="filePreviewFrame" src="" style="width:100%;height:100%;border:none;"></iframe>
      </div>
    </div>
  </div>
</div>
<script src="<?= base_url('assets/js/fulldisc_page.js') ?>"></script>
</body>
</html>