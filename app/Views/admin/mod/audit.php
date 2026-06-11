<div class="pagetitle mb-4 pb-2 border-bottom">
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">System Audit Logs</h1>
    <nav>
        <ol class="breadcrumb mb-0 bg-transparent p-2">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>" class="text-decoration-none"
                    style="color: #2d6a4f;">Dashboard</a></li>
            <li class="breadcrumb-item active">System Logs</li>
        </ol>
    </nav>
</div>

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
    }

    #tblaudit td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
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
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 small text-uppercase tracking-wider text-secondary">
                <i class="bi bi-terminal-split me-2" style="color: var(--theme-mid-green);"></i>Audit Log Stream Filtering
            </h6>
            <form id="auditLogSearchForm">
                <div class="row g-3 align-items-center">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0 ps-0" id="searchAction"
                                placeholder="Search by action, user, or details..." name="search_query">
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="input-group">

                            <input type="date" class="form-control border-start-0 ps-0" id="searchDate">
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="row g-2 justify-content-end admin-filter-actions">
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
        <div class="col-lg-12">

            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Audit Log Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblaudit" class="table table-bordered table-hover align-middle mb-0" cellspacing="0"
                            width="100%">
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
