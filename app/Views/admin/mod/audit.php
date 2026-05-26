<div class="pagetitle mb-4 pb-2 border-bottom">
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">System Audit Logs</h1>
    <nav>
        <ol class="breadcrumb mb-0">
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

    /* Standout styling for DataTables filtering tools if embedded within this page structure */
    .dataTables_filter input[type="search"] {
        width: 320px !important;
        border: 2px solid var(--theme-mid-green) !important;
        border-radius: 8px !important;
        padding: 6px 12px !important;
        font-size: 0.95rem !important;
        margin-left: 10px !important;
        outline: none;
    }

    .dataTables_filter input[type="search"]:focus {
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.25) !important;
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

                    <div class="col-xl-7 col-lg-6 col-md-12">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="searchAction"
                                placeholder="Search by action, user, or details..." name="search_query">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="bi bi-calendar3"></i></span>
                            <input type="date" class="form-control border-start-0 ps-0" id="searchDate">
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6 d-flex gap-2 justify-content-md-end">
                        <button type="reset"
                            class="btn btn-outline-danger w-50 py-2 text-nowrap d-inline-flex align-items-center justify-content-center gap-1 small fw-semibold">
                            <i class="bi bi-trash"></i> Clear
                        </button>
                        <button type="button"
                            class="btn text-white w-50 py-2 text-nowrap d-inline-flex align-items-center justify-content-center gap-1 small fw-semibold"
                            id="searchBtn" style="background-color: var(--theme-mid-green);">
                            <i class="bi bi-filter"></i> Search
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card card-premium mb-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tblaudit" class="table table-hover align-middle mb-0" cellspacing="0" width="100%">
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>