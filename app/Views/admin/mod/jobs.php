<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Job Management</h1>
        <nav>
            <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Job Management</li>
            </ol>
        </nav>
    </div>
</div>

<style>
    /* Admin UI Layout Theme Variable Definitions */
    :root {
        --theme-dark-green: #1b4d3e;
        --theme-mid-green: #2d6a4f;
        --theme-light-green: #d8f3dc;
        --theme-accent: #20c997;
    }

    /* Core Action Element Configurations */
    .btn-theme {
        background-color: var(--theme-dark-green);
        color: #ffffff;
        border: 1px solid var(--theme-dark-green);
        border-radius: 6px;
    }

    .btn-theme:hover {
        background-color: var(--theme-mid-green);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    }

    /* Standard Base Containers Elements */
    .card-premium {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    /* Custom Integrated Search Box Filters for DataTables */
    .dataTables_filter input[type="search"] {
        width: 320px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.45rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s ease-in-out;
    }

    .dataTables_filter input[type="search"]:focus {
        border-color: var(--theme-mid-green);
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.15);
        outline: 0;
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

    #tbljobs {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tbljobs th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
    }

    #tbljobs td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tbljobs tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tbljobs tbody tr:hover {
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

    /* Premium Modern Status Badge Styles */
    .status-badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        gap: 6px;
        padding: 6px 12px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 30px;
        border: 1px solid transparent;
    }

    .status-badge-active {
        background-color: #e8f5e9;
        color: #2e7d32;
        border-color: #c8e6c9;
    }

    .status-badge-inactive {
        background-color: #ffebee;
        color: #c62828;
        border-color: #ffcdd2;
    }

    .status-badge-archived {
        background-color: #f5f5f5;
        color: #616161;
        border-color: #e0e0e0;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block !important;
        vertical-align: middle !important;
    }

    .status-dot-active {
        background-color: #2e7d32;
        box-shadow: 0 0 6px #2e7d32;
    }

    .status-dot-inactive {
        background-color: #c62828;
        box-shadow: 0 0 6px #c62828;
    }

    .status-dot-archived {
        background-color: #616161;
    }

    /* Premium Modern Job Type Badge Styles */
    .jobtype-badge-fulltime {
        background-color: #e3f2fd;
        color: #0d47a1;
        border-color: #bbdefb;
    }

    .jobtype-badge-parttime {
        background-color: #e0f7fa;
        color: #006064;
        border-color: #b2ebf2;
    }

    .jobtype-dot-fulltime {
        background-color: #0d47a1;
        box-shadow: 0 0 6px #0d47a1;
    }

    .jobtype-dot-parttime {
        background-color: #006064;
        box-shadow: 0 0 6px #006064;
    }

    /* Clean Info Text Field Styling for View Modals */
    .view-field-box {
        background-color: #f8f9fa;
        border-radius: 6px;
        padding: 0.6rem 0.9rem;
        border-left: 3px solid var(--theme-mid-green);
        font-size: 0.95rem;
    }

    .transition-all {
        transition: all 0.2s ease;
    }
</style>

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
    <div class="card card-premium mb-4 border-start border-4"
        style="border-start-color: var(--theme-mid-green) !important;">

        <div class="card-body p-4">
            <form id="jobsSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" name="search"
                                placeholder="Search Title / Company / Email...">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Job Type Filter</label>
                        <select class="form-select bg-light border-secondary-subtle" name="type"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Types</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Publication Status</label>
                        <select class="form-select bg-light border-secondary-subtle" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Statuses</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <?php if (in_array($user->user_lvl ?? '', ['DEVELOPER', 'SUPERADMIN'], true)): ?>
                                <option value="ARCHIVED">Archived</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="d-grid gap-2">
                            <button type="reset" class="btn btn-outline-secondary flex-grow-1 fw-semibold"
                                style="height: 38px;">
                                Clear
                            </button>
                            <button type="submit" class="btn btn-outline-success flex-grow-1 fw-semibold shadow-sm"
                                id="jobsSearchBtn" style="height: 38px;">
                                Search
                            </button>
                            <button type="button" class="btn btn-success shadow-sm fw-semibold text-nowrap flex-grow-1"
                                data-bs-toggle="modal" data-bs-target="#addModal" style="height: 38px;">
                                <i class="bi bi-plus-circle me-1"></i>Add Record
                            </button>
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
            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Job Postings Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbljobs" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="addForm" class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-plus-circle me-2"></i>Add New Job Opening
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="title" class="form-label small fw-bold text-secondary">Job Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="title" name="title"
                            placeholder="e.g. Senior Administrative Assistant" required>
                    </div>

                    <div class="col-md-6">
                        <label for="company" class="form-label small fw-bold text-secondary">Company / Department <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="company" name="company"
                            placeholder="Enter department name" required>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label small fw-bold text-secondary">Job Type <span
                                class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="type" name="type" required>
                            <option value="" selected disabled>Select Job Type</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="publication_date" class="form-label small fw-bold text-secondary">Publication Date
                            <span class="text-danger">*</span></label>
                        <input type="date" class="form-control shadow-sm" id="publication_date" name="publication_date"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label small fw-bold text-secondary">Contact Email <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control shadow-sm" id="email" name="email"
                            placeholder="hr@department.gov.ph" required>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="description" class="form-label small fw-bold text-secondary">Job Description <span
                                class="text-danger">*</span></label>
                        <div id="quillDescription" class="quill-editor-full border rounded-3 bg-white shadow-sm"
                            style="height: 160px;"></div>
                        <input type="hidden" id="description" name="description" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Job Opening</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editJobId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify Job Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="editTitle" class="form-label small fw-bold text-secondary">Job Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="editTitle" name="title" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editCompany" class="form-label small fw-bold text-secondary">Company / Department
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="editCompany" name="company" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editType" class="form-label small fw-bold text-secondary">Job Type <span
                                class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="editType" name="type" required>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="editPublicationDate" class="form-label small fw-bold text-secondary">Publication
                            Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control shadow-sm" id="editPublicationDate"
                            name="publication_date" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editEmail" class="form-label small fw-bold text-secondary">Contact Email <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control shadow-sm" id="editEmail" name="email" required>
                    </div>

                    <div class="col-12">
                        <label for="editDescription" class="form-label small fw-bold text-secondary">Job Description
                            <span class="text-danger">*</span></label>
                        <div id="editQuillDescription" class="quill-editor-full border rounded-3 bg-white shadow-sm"
                            style="height: 160px;"></div>
                        <input type="hidden" id="editDescription" name="description" required>
                    </div>

                    <?php if (!in_array($user->user_lvl, ['ENCODER', 'VIEWER'])): ?>
                        <div class="col-md-6">
                            <label for="editStatus" class="form-label small fw-bold text-secondary">Listing Status</label>
                            <select class="form-select shadow-sm" id="editStatus" name="status">
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Details</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-eye me-2"></i>Job Information View
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Job Title</label>
                        <div id="viewTitle" class="view-field-box fw-bold text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Company / Department</label>
                        <div id="viewCompany" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Job Type</label>
                        <div id="viewType" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Publication Date</label>
                        <div id="viewPublicationDate" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Contact Email</label>
                        <div id="viewEmail" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <div id="viewStatus" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Created Date</label>
                        <div id="viewCreatedDate" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Job Description</label>
                        <div class="border rounded-3 p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                            <div id="viewDescription" class="text-dark"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-2">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close View</button>
            </div>
        </div>
    </div>
</div>
