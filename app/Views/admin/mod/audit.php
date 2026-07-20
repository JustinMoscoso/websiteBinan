<div class="pagetitle mb-4 pb-2 border-bottom">
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">System Audit Logs</h1>
</div>

<?php
$auditCurrentYear = (int) date('Y');
$auditBaseYear = 2011;
$auditDefaultYearStart = $auditBaseYear + (int) (floor(max(0, $auditCurrentYear - $auditBaseYear) / 12) * 12);
$auditDefaultYearEnd = $auditDefaultYearStart + 11;
$auditDefaultYearLabel = $auditDefaultYearStart . '-' . $auditDefaultYearEnd;
?>

<style>
    /* Admin UI Layout Theme Variable Definitions */
    :root {
        --theme-dark-green: #1b4d3e;
        --theme-mid-green: #2d6a4f;
        --theme-light-green: #d8f3dc;
        --theme-accent: #20c997;
        --theme-surface-bg: #f8f9fa;
    }

    /* Premium Component Containers Card Design */
    .card-premium {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    /* SB Admin 2 Data Table Custom Styles */
    .card-sb {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        background-color: #fff;
    }

    .card-sb-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        border-top-left-radius: calc(0.35rem - 1px);
        border-top-right-radius: calc(0.35rem - 1px);
    }

    #tblaudit {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tblaudit th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
        text-align: center !important;
    }

    #tblaudit td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
        text-align: center !important;
    }

    #tblaudit td.audit-details-cell {
        min-width: 300px;
        text-align: left !important;
    }

    .audit-action-cell {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
    }

    .audit-action-cell .badge {
        padding: 0.4rem 0.55rem;
        font-size: 0.72rem;
        letter-spacing: 0.02em;
        white-space: normal;
    }

    .audit-action-module {
        color: #5a5c69;
        font-size: 0.78rem;
        font-weight: 600;
        line-height: 1.25;
    }

    .audit-detail-text {
        display: block;
        color: #5a5c69;
        line-height: 1.5;
        cursor: help;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tblaudit tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tblaudit tbody tr:hover {
        background-color: #eef6f0 !important;
        /* Soft premium green highlight on hover */
    }

    /* Custom Integrated Search Box Filters for DataTables matching SB Admin 2 */
    .dataTables_length label,
    .dataTables_filter label {
        color: #858796;
        font-weight: normal;
        font-size: 0.875rem;
    }

    .dataTables_length select {
        background-color: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        line-height: 1.5;
        color: #6e707e;
        vertical-align: middle;
        font-size: 0.875rem;
        height: 38px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .dataTables_length select:focus {
        border-color: var(--theme-mid-green);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25);
    }

    .dataTables_filter input {
        background-color: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        color: #6e707e;
        font-size: 0.875rem;
        height: 38px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .dataTables_filter input:focus {
        border-color: var(--theme-mid-green);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25);
    }

    .dataTables_info {
        color: #858796;
        font-size: 0.875rem;
    }

    .dataTables_paginate .paginate_button {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background: transparent !important;
    }

    .audit-range-wrap {
        position: relative;
    }

    .audit-range-toggle {
        background: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        min-height: 38px;
        width: 100%;
        text-align: left;
        padding: 0.375rem 0.75rem;
        color: #6e707e;
    }

    .audit-range-toggle:hover,
    .audit-range-toggle:focus {
        border-color: var(--theme-mid-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.15);
        outline: 0;
    }

    .audit-range-popover {
        position: absolute;
        top: calc(100% + 0.5rem);
        left: 50%;
        z-index: 1055;
        display: none;
        width: min(980px, calc(100vw - 2rem));
        max-height: calc(100vh - 12rem);
        background: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.5rem;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.16);
        overflow: auto;
        transform: translateX(-50%);
    }

    .audit-range-popover.is-open {
        display: flex;
    }

    .audit-range-body {
        flex: 1;
        padding: 1rem 1.15rem 1.15rem;
        background: linear-gradient(180deg, #ffffff 0%, #fcfdfd 100%);
    }

    .audit-range-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.9rem;
    }

    .audit-range-group {
        border: 1px solid #e3e6f0;
        border-radius: 0.55rem;
        padding: 0.85rem;
        background: #f8f9fc;
        min-height: 172px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .audit-range-group-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: #1b4d3e;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.5rem;
    }

    .audit-range-picker {
        background: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.9);
    }

    .audit-range-picker-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.45rem 0.55rem;
        border-bottom: 1px solid #e3e6f0;
        background: #fdfefe;
    }

    .audit-range-picker-nav {
        border: 0;
        background: transparent;
        color: #1b1b1b;
        font-size: 1.25rem;
        line-height: 1;
        padding: 0.15rem 0.4rem;
    }

    .audit-range-picker-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #1b4d3e;
        margin: 0;
        text-align: center;
        flex: 1;
    }

    .audit-range-picker-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.35rem;
        padding: 0.75rem;
    }

    .audit-range-picker-cell {
        border: 0;
        background: transparent;
        border-radius: 0.4rem;
        height: 2.25rem;
        font-size: 0.82rem;
        color: #4e4e4e;
        transition: background-color 0.12s ease, color 0.12s ease;
    }

    .audit-range-picker-cell:hover {
        background: #eef6f0;
        color: #1b4d3e;
    }

    .audit-range-picker-cell.active {
        background: #0d6efd;
        color: #fff;
    }

    .audit-range-months {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.35rem;
        padding: 0.75rem;
        border-top: 1px solid #edf1f4;
        background: #fafbfc;
    }

    .audit-range-month {
        border: 0;
        background: transparent;
        border-radius: 0.4rem;
        height: 2.1rem;
        font-size: 0.82rem;
        color: #4e4e4e;
        transition: background-color 0.12s ease, color 0.12s ease;
    }

    .audit-range-month:hover {
        background: #eef6f0;
        color: #1b4d3e;
    }

    .audit-range-month.active {
        background: #0d6efd;
        color: #fff;
    }

    .audit-range-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .audit-range-popover.is-open {
            flex-direction: column;
        }

        .audit-range-popover {
            width: calc(100vw - 1rem);
            max-height: calc(100vh - 1rem);
            left: 0;
            transform: none;
        }

        .audit-range-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 small text-uppercase tracking-wider text-secondary">
                <i class="bi bi-terminal-split me-2" style="color: var(--theme-mid-green);"></i>
                
            </h6>
            <form id="auditLogSearchForm">
                <div class="row g-3 align-items-center">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Logs</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0 ps-0" id="searchAction"
                                placeholder="Search by action, user, or details" name="search_query">
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Date Range</label>
                        <div class="audit-range-wrap">
                            <button type="button" class="audit-range-toggle" id="auditRangeToggle" aria-haspopup="dialog"
                                aria-expanded="false">
                                <span id="auditRangeLabel">Select Range</span>
                            </button>
                            <div class="audit-range-popover" id="auditRangePopover" role="dialog" aria-label="Audit date range picker">
                                <div class="audit-range-body">
                                    <div class="audit-range-grid">
                                        <div class="audit-range-group">
                                            <div class="audit-range-group-title">From</div>
                                           
                                            <div class="audit-range-picker mt-3">
                                                <div class="audit-range-picker-header">
                                                    <button type="button" class="audit-range-picker-nav" data-target="from" data-nav="prev" aria-label="Previous years">‹</button>
                                                    <div class="audit-range-picker-title" id="rangeHeaderFrom"><?= esc($auditDefaultYearLabel) ?></div>
                                                    <button type="button" class="audit-range-picker-nav" data-target="from" data-nav="next" aria-label="Next years">›</button>
                                                </div>
                                                <div class="audit-range-picker-grid" id="yearGridFrom"></div>
                                                <div class="audit-range-months" id="monthGridFrom"></div>
                                            </div>
                                        </div>
                                        <div class="audit-range-group">
                                            <div class="audit-range-group-title">To</div>
                                            
                                            <div class="audit-range-picker mt-3">
                                                <div class="audit-range-picker-header">
                                                    <button type="button" class="audit-range-picker-nav" data-target="to" data-nav="prev" aria-label="Previous years">‹</button>
                                                    <div class="audit-range-picker-title" id="rangeHeaderTo"><?= esc($auditDefaultYearLabel) ?></div>
                                                    <button type="button" class="audit-range-picker-nav" data-target="to" data-nav="next" aria-label="Next years">›</button>
                                                </div>
                                                <div class="audit-range-picker-grid" id="yearGridTo"></div>
                                                <div class="audit-range-months" id="monthGridTo"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="audit-range-actions">
                                        <button type="button" class="btn btn-light" id="auditRangeCancel">Cancel</button>
                                        <button type="button" class="btn btn-success" id="auditRangeApply">Apply</button>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="searchDateFrom" name="searchDateFrom">
                            <input type="hidden" id="searchDateTo" name="searchDateTo">
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6 pt-4">
                        <div class="row g-2 justify-content-end">
                            <div class="col-6">
                                <button type="button"
                                    class="btn btn-primary text-white w-100 py-2 text-nowrap d-inline-flex align-items-center justify-content-center gap-1 small fw-semibold"
                                    id="searchBtn">
                                    <i class="bi bi-filter"></i> Search
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="reset"
                                    class="btn btn-danger w-100 py-2 text-nowrap d-inline-flex align-items-center justify-content-center gap-1 small fw-semibold">
                                    <i class="bi bi-trash"></i> Clear
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblaudit" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
