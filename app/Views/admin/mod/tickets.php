
<div class="pagetitle">
  <h1>Support Tickets</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Support Tickets</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- ── Search Filters ─────────────────────────────────────────── -->
<div class="container-fluid py-3">
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form id="ticketSearchForm">
                <!-- Quick Search Row -->
                <div class="row g-2 mb-2 align-items-end">
                    <div class="col-lg-5 col-md-12">
                        <label class="form-label text-muted small mb-1">Quick Search</label>
                        <input type="text" class="form-control" id="searchConcern"
                               placeholder="Search ticket number, concern, username…">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-muted small mb-1">Status</label>
                        <select class="form-select" id="searchTicketStatus">
                            <option value="">— All Statuses —</option>
                            <option value="OPEN">Open</option>
                            <option value="IN_PROGRESS">In Progress</option>
                            <option value="RESOLVED">Resolved</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-muted small mb-1 d-block">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-danger flex-fill">
                                <i class="bi bi-x-circle me-1"></i>Clear
                            </button>
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bi bi-search me-1"></i>Search
                            </button>
                        </div>
                    </div>

                    <!-- Advanced Search Toggle -->
                    <div class="col-lg-2 col-md-3 text-end">
                        <label class="form-label text-muted small mb-1 d-block">&nbsp;</label>
                        <button type="button" class="btn btn-outline-secondary w-100"
                                data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                            <i class="bi bi-sliders me-1"></i>Advanced
                        </button>
                    </div>
                </div>

                <!-- Advanced Search (collapsible) -->
                <div class="collapse" id="advancedFilters">
                    <hr class="my-2">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label text-muted small mb-1">From Date</label>
                            <input type="date" class="form-control" id="searchDateFrom">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label text-muted small mb-1">To Date</label>
                            <input type="date" class="form-control" id="searchDateTo">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label text-muted small mb-1">Assigned Admin</label>
                            <input type="text" class="form-control" id="searchAdmin"
                                   placeholder="Admin name…">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ── Ticket Table ─────────────────────────────────────────────── -->
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbltickets" class="table table-hover align-middle" cellspacing="0" width="100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── View Ticket Detail Modal ─────────────────────────────────── -->
<div class="modal fade" id="ticketDetailModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title"><i class="bi bi-ticket-perforated me-2"></i>Ticket Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Ticket #</label>
                        <p class="fw-semibold mb-0" id="dtTicketNumber">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Status</label>
                        <p class="mb-0" id="dtStatus">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Submitted By</label>
                        <p class="mb-0" id="dtUsername">—</p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Submitted On</label>
                        <p class="mb-0" id="dtCreatedAt">—</p>
                    </div>
                    <div class="col-md-6" id="dtTakenAtRow" style="display:none;">
                        <label class="text-muted small">Taken On</label>
                        <p class="mb-0" id="dtTakenAt">—</p>
                    </div>
                    <div class="col-md-6" id="dtResolvedAtRow" style="display:none;">
                        <label class="text-muted small">Resolved On</label>
                        <p class="mb-0" id="dtResolvedAt">—</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Concern</label>
                        <p class="mb-0" id="dtConcern">—</p>
                    </div>
                    <div class="col-12" id="dtAdminRow" style="display:none;">
                        <label class="text-muted small">Assigned Admin</label>
                        <p class="mb-0" id="dtAdmin">—</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="dtTakeBtn" style="display:none;"
                        onclick="takeTicketFromDetail()">
                    <i class="bi bi-hand-index me-1"></i>Take It
                </button>
                <button type="button" class="btn btn-success" id="dtResolveBtn" style="display:none;"
                        onclick="resolveTicketFromDetail()">
                    <i class="bi bi-check-circle me-1"></i>Resolve
                </button>
            </div>
        </div>
    </div>
</div>
