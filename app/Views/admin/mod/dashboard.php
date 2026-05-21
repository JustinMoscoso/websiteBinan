<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<div class="row">

    <!-- Website Visits Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 website-visits-card" id="website-visits-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Website Visits <span id="visits-filter-text">| Today</span>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="visit-count">
                            <?php echo isset($visit_count) ? $visit_count : 0; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-globe fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <!-- Filter Dropdown overlay (optional styling approach) -->
            <div class="dropdown no-arrow" style="position: absolute; top: 10px; right: 15px;">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in filter">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item" href="#" data-filter="Today">Today</a>
                    <a class="dropdown-item" href="#" data-filter="This Month">This Month</a>
                    <a class="dropdown-item" href="#" data-filter="This Year">This Year</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Registered Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="users-count">
                            <?= (new \App\Models\UserAccount())->countAllResults(); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 revenue-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Announcements <span id="filter-text">| Today</span>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="content-count">0</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <!-- Filter Dropdown overlay -->
            <div class="dropdown no-arrow" style="position: absolute; top: 10px; right: 15px;">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in filter">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item" href="#" data-filter="Today">Today</a>
                    <a class="dropdown-item" href="#" data-filter="This Month">This Month</a>
                    <a class="dropdown-item" href="#" data-filter="This Year">This Year</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests / Recent News Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 news-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Recent News <span id="news-filter">| Today</span></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="news-count">Loading...</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <!-- Filter Dropdown overlay -->
            <div class="dropdown no-arrow" style="position: absolute; top: 10px; right: 15px;">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in filter">
                    <div class="dropdown-header">Filter:</div>
                    <a class="dropdown-item" href="#" data-filter="Today">Today</a>
                    <a class="dropdown-item" href="#" data-filter="This Month">This Month</a>
                    <a class="dropdown-item" href="#" data-filter="This Year">This Year</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Page Views Overview</h6>
                <div class="dropdown no-arrow filter">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Filter:</div>
                        <a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=today') ?>">Today</a>
                        <a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=month') ?>">This Month</a>
                        <a class="dropdown-item" href="<?= base_url('admin/dashboard?filter=year') ?>">This Year</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
                <div class="text-center mt-3 small" style="display: flex; justify-content: flex-end; gap: 5px;">
                  <button id="page1Btn" class="btn btn-sm btn-primary" style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">1</button>
                  <button id="page2Btn" class="btn btn-sm btn-outline-primary" style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">2</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart and Lists -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Top 3 Pages Revenue</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small" id="pie-chart-legend">
                    <!-- Populated by JS -->
                </div>
            </div>
        </div>
        
        <!-- Recent News List Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Latest News Feed</h6>
                <a href="<?= base_url('admin/postcontent') ?>" class="small text-primary">View All</a>
            </div>
            <div class="card-body" id="news-activity" style="max-height: 250px; overflow-y: auto;">
                <p class="text-muted">No recent news added.</p>
            </div>
        </div>
    </div>
</div>