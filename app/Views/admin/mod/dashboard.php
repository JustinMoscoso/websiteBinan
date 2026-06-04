<div class="d-sm-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Dashboard</h1>

    </div>
</div>

<style>
    /* Premium Component Containers Card Design */
    .card-premium {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.07);
    }

    /* Summary Metric Indicators Border Accents */
    .metric-card {
        border-left: 4px solid #333;
    }

    .metric-primary {
        border-left-color: #0d6efd !important;
    }

    .metric-success {
        border-left-color: var(--theme-mid-green) !important;
    }

    .metric-info {
        border-left-color: #0dcaf0 !important;
    }

    .metric-warning {
        border-left-color: #ffc107 !important;
    }

    /* Custom Theme Chart Control Elements */
    .btn-chart-page {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    /* Custom Context Scrollbar Styling for Activity Feeds */
    .custom-activity-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .custom-activity-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .custom-activity-scroll::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .custom-activity-scroll::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-premium metric-card metric-primary h-100 py-2 position-relative" id="website-visits-card">
            <div class="card-body py-3">
                <div class="row g-0 align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-primary text-uppercase tracking-wider mb-1">
                            Website Traffic <span id="visits-filter-text" class="text-muted fw-normal lowercase">|
                                Today</span>
                        </div>
                        <div class="h4 mb-0 fw-bold text-dark" id="visit-count">
                            <?php echo isset($visit_count) ? $visit_count : 0; ?>
                        </div>
                    </div>
                    <div class="col-auto text-muted opacity-50">
                        <i class="bi bi-globe2 fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="dropdown no-arrow position-absolute" style="top: 12px; right: 15px;">
                <a class="text-decoration-none text-muted p-1" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots-vertical small"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 filter">
                    <div class="dropdown-header small text-uppercase fw-bold text-secondary">Scope Filter:</div>
                    <a class="dropdown-item active-theme-item" href="#" data-filter="Today">Today</a>
                    <a class="dropdown-item" href="#" data-filter="This Month">This Month</a>
                    <a class="dropdown-item" href="#" data-filter="This Year">This Year</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-premium metric-card metric-success h-100 py-2">
            <div class="card-body py-3">
                <div class="row g-0 align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-uppercase tracking-wider mb-1"
                            style="color: var(--theme-mid-green);">
                            Registered Members
                        </div>
                        <div class="h4 mb-0 fw-bold text-dark" id="users-count">
                            <?= (new \App\Models\UserAccount())->countAllResults(); ?>
                        </div>
                    </div>
                    <div class="col-auto opacity-50" style="color: var(--theme-mid-green);">
                        <i class="bi bi-people-fill fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-premium metric-card metric-info h-100 py-2 position-relative">
            <div class="card-body py-3">
                <div class="row g-0 align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-info text-uppercase tracking-wider mb-1">
                            Announcements <span id="filter-text" class="text-muted fw-normal lowercase">| Today</span>
                        </div>
                        <div class="h4 mb-0 fw-bold text-dark" id="content-count">0</div>
                    </div>
                    <div class="col-auto text-info opacity-50">
                        <i class="bi bi-megaphone-fill fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="dropdown no-arrow position-absolute" style="top: 12px; right: 15px;">
                <a class="text-decoration-none text-muted p-1" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots-vertical small"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 filter">
                    <div class="dropdown-header small text-uppercase fw-bold text-secondary">Scope Filter:</div>
                    <a class="dropdown-item" href="#" data-filter="Today">Today</a>
                    <a class="dropdown-item" href="#" data-filter="This Month">This Month</a>
                    <a class="dropdown-item" href="#" data-filter="This Year">This Year</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-premium metric-card metric-warning h-100 py-2 position-relative" id="news-card">
            <div class="card-body py-3">
                <div class="row g-0 align-items-center">
                    <div class="col me-2">
                        <div class="text-xs fw-bold text-warning text-uppercase tracking-wider mb-1">
                            Recent Press Articles <span id="news-filter" class="text-muted fw-normal lowercase">|
                                Today</span>
                        </div>
                        <div class="h4 mb-0 fw-bold text-dark" id="news-count">
                            <span class="spinner-border spinner-border-sm text-secondary" role="status"></span>
                        </div>
                    </div>
                    <div class="col-auto text-warning opacity-50">
                        <i class="bi bi-newspaper fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="dropdown no-arrow position-absolute" style="top: 12px; right: 15px;">
                <a class="text-decoration-none text-muted p-1" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-three-dots-vertical small"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 filter">
                    <div class="dropdown-header small text-uppercase fw-bold text-secondary">Scope Filter:</div>
                    <a class="dropdown-item" href="#" data-filter="Today">Today</a>
                    <a class="dropdown-item" href="#" data-filter="This Month">This Month</a>
                    <a class="dropdown-item" href="#" data-filter="This Year">This Year</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card card-premium h-100">
            <div
                class="card-header bg-white border-bottom py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 fw-bold" style="color: var(--theme-dark-green); font-size: 0.95rem;">
                    <i class="bi bi-graph-up me-2"></i>Traffic Performance Overview
                </h6>
                <div class="dropdown no-arrow filter">
                    <button class="btn btn-link btn-sm text-muted p-0 border-0" type="button" id="dropdownMenuLink"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header small text-uppercase fw-bold text-secondary">Timeframe Filter:</div>
                        <a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=today') ?>"><i
                                class="bi bi-calendar-event me-2"></i>Today</a>
                        <a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=month') ?>"><i
                                class="bi bi-calendar-month me-2"></i>This Month</a>
                        <a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=year') ?>"><i
                                class="bi bi-calendar-check me-2"></i>This Year</a>
                    </div>
                </div>
            </div>
            <div class="card-body d-flex flex-column justify-content-between p-4">
                <div class="chart-area position-relative w-100 flex-grow-1" style="min-height: 280px;">
                    <canvas id="myAreaChart"></canvas>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-2 mt-3 pt-2 border-top border-light">
                    <span class="text-muted small me-2" style="font-size: 0.8rem;">Switch Dataset View:</span>
                    <button id="page1Btn" class="btn btn-chart-page btn-primary border-0 shadow-sm">1</button>
                    <button id="page2Btn" class="btn btn-chart-page btn-outline-secondary border-1">2</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5 mb-4 d-flex flex-column gap-4">

        <div class="card card-premium flex-grow-1">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 fw-bold" style="color: var(--theme-dark-green); font-size: 0.95rem;">
                    <i class="bi bi-pie-chart me-2"></i>Channel Content Balance
                </h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-center p-4">
                <div class="chart-pie position-relative pb-2" style="height: 180px;">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-3 text-center small text-secondary" id="pie-chart-legend">
                </div>
            </div>
        </div>

        <div class="card card-premium border-top border-3" style="border-top-color: var(--theme-mid-green) !important;">
            <div
                class="card-header bg-white border-bottom py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 fw-bold" style="color: var(--theme-dark-green); font-size: 0.95rem;">
                    <i class="bi bi-rss me-2"></i>Latest News Feed
                </h6>
                <a href="<?= base_url('admin/postcontent') ?>"
                    class="btn btn-sm btn-link text-decoration-none p-0 fw-semibold"
                    style="color: var(--theme-mid-green); font-size: 0.85rem;">
                    View Board <i class="bi bi-arrow-right small ms-1"></i>
                </a>
            </div>
            <div class="card-body p-3 custom-activity-scroll" id="news-activity"
                style="max-height: 215px; overflow-y: auto;">
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-4 d-block mb-1 opacity-50"></i>
                    <p class="small mb-0">No active news items found in database system archive.</p>
                </div>
            </div>
        </div>

    </div>
</div>