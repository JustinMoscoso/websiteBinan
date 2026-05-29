<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Contacts Management</h1>
        <nav>
            <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Contacts Management</li>
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

    /* Premium Containers Standard Theme Configuration */
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

    #tblhotlines {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tblhotlines th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
    }

    #tblhotlines td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tblhotlines tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tblhotlines tbody tr:hover {
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

    .transition-all {
        transition: all 0.2s ease;
    }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <form id="contactSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-5 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Contact / Office</label>
                        <input type="text" class="form-control" id="searchContact"
                            placeholder="Search Contact / Office Name...">
                    </div>

                    <div class="col-xl-2.5 col-lg-3 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Section Category</label>
                        <select class="form-select" name="contactCategory">
                            <option selected value="">- Category -</option>
                            <option value="BRGY">Barangay</option>
                            <option value="DEPT">Department</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="col-xl-2.5 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-select" name="contactStatus">
                            <option selected value="">- Status -</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-12 d-flex gap-2">
                        <button type="reset" class="btn btn-light fw-semibold text-secondary w-50"
                            style="height: 40px; padding: 0;">
                            Clear
                        </button>
                        <button type="submit" class="btn btn-theme fw-semibold w-50" id="searchBtn"
                            style="height: 40px; padding: 0;">
                            Search
                        </button>
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
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Contact Directory Records
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblhotlines" class="table table-bordered table-hover align-middle w-100"
                            cellspacing="0">
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
                    <i class="bi bi-plus-circle me-2"></i>Add New Contact Directory
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <div class="col-12">
                        <label for="category" class="form-label small fw-bold text-secondary">Directory Section <span
                                class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="category" name="category" required>
                            <option value="" selected disabled>Choose a section...</option>
                            <option value="BRGY">Barangay</option>
                            <option value="DEPT">Department</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="col-12" id="deptGroup" style="display: none;">
                        <label for="txtDept" class="form-label small fw-bold text-secondary">Department Assignment <span
                                class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="txtDept" name="txtDept" required>
                        </select>
                    </div>

                    <div class="col-12" id="brgyGroup" style="display: none;">
                        <label for="txtBrgy" class="form-label small fw-bold text-secondary">Barangay Location <span
                                class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="txtBrgy" name="txtBrgy" required>
                        </select>
                    </div>

                    <div class="col-12" id="othersGrp" style="display: none;">
                        <label for="txtOthers" class="form-label small fw-bold text-secondary">Office Name <span
                                class="text-danger">*</span></label>
                        <input type="text" id="txtOthers" name="txtOthers" class="form-control shadow-sm"
                            placeholder="Enter custom office / establishment designation" required>
                    </div>

                    <div class="col-12 mt-4 mb-1">
                        <h6 class="small text-uppercase fw-bold text-muted tracking-wider border-bottom pb-2">
                            Telecommunications Channels</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="contact" class="form-label small fw-bold text-secondary">PLDT Landline</label>
                        <input type="text" class="form-control shadow-sm" id="contact" name="contact"
                            placeholder="XXX-XXXX or -" required>
                    </div>

                    <div class="col-md-6">
                        <label for="smart" class="form-label small fw-bold text-secondary">SMART Network Mobile</label>
                        <input type="text" class="form-control shadow-sm" id="smart" name="smart"
                            placeholder="09XX-XXX-XXXX or -" required>
                    </div>

                    <div class="col-md-6">
                        <label for="globe" class="form-label small fw-bold text-secondary">GLOBE Network Mobile</label>
                        <input type="text" class="form-control shadow-sm" id="globe" name="globe"
                            placeholder="09XX-XXX-XXXX or -" required>
                    </div>

                    <div class="col-md-6">
                        <label for="telco" class="form-label small fw-bold text-secondary">INTELCO Line</label>
                        <input type="text" class="form-control shadow-sm" id="telco" name="telco"
                            placeholder="XXX-XXXX or -" required>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Contact Entry</button>
            </div>

        </form>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" class="modal-content border-0 shadow-lg">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify Contact Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="editId" name="id">

                <div class="row g-3">

                    <div class="col-12">
                        <label for="editcategory" class="form-label small fw-bold text-secondary">Directory Section
                            <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="editcategory" name="editcategory" required>
                            <option value="" disabled>Choose a section...</option>
                            <option value="BRGY">Barangay</option>
                            <option value="DEPT">Department</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="col-12" id="editdeptGroup" style="display: none;">
                        <label for="editDept" class="form-label small fw-bold text-secondary">Department Assignment
                            <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="editDept" name="editDept" required>
                        </select>
                    </div>

                    <div class="col-12" id="editbrgyGroup" style="display: none;">
                        <label for="editBrgy" class="form-label small fw-bold text-secondary">Barangay Location <span
                                class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="editBrgy" name="editBrgy" required>
                        </select>
                    </div>

                    <div class="col-12" id="editothersGrp" style="display: none;">
                        <label for="editOthers" class="form-label small fw-bold text-secondary">Office Name <span
                                class="text-danger">*</span></label>
                        <input type="text" id="editOthers" name="editOthers" class="form-control shadow-sm" required>
                    </div>

                    <div class="col-12 mt-4 mb-1">
                        <h6 class="small text-uppercase fw-bold text-muted tracking-wider border-bottom pb-2">
                            Telecommunications Channels</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="editContact" class="form-label small fw-bold text-secondary">PLDT Landline</label>
                        <input type="text" class="form-control shadow-sm" id="editContact" name="editContact"
                            placeholder="XXX-XXXX or -" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editSmart" class="form-label small fw-bold text-secondary">SMART Network
                            Mobile</label>
                        <input type="text" class="form-control shadow-sm" id="editSmart" name="editSmart"
                            placeholder="09XX-XXX-XXXX or -" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editGlobe" class="form-label small fw-bold text-secondary">GLOBE Network
                            Mobile</label>
                        <input type="text" class="form-control shadow-sm" id="editGlobe" name="editGlobe"
                            placeholder="09XX-XXX-XXXX or -" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editTelco" class="form-label small fw-bold text-secondary">INTELCO Line</label>
                        <input type="text" class="form-control shadow-sm" id="editTelco" name="editTelco"
                            placeholder="XXX-XXXX or -" required>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Contact Entry</button>
            </div>

        </form>
    </div>
</div>