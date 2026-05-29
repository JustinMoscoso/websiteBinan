<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Department Management</h1>
        <nav>
            <ol class="breadcrumb mb-0 bg-transparent p-2" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Department Management</li>
            </ol>
        </nav>
    </div>

</div>

<style>
    /* Theme Variables matching your sidebar */
    :root {
        --theme-dark-green: #1b4d3e;
        --theme-mid-green: #2d6a4f;
        --theme-light-green: #d8f3dc;
        --theme-accent: #20c997;
    }

    /* Primary Action Button Custom Theme */
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

    /* Premium Content Cards */
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

    #tbldept {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tbldept th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
    }

    #tbldept td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tbldept tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tbldept tbody tr:hover {
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

    /* Fix Quill Snow borders to match theme */
    .ql-toolbar.ql-snow {
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        border: 1px solid #ced4da !important;
        background-color: #f8f9fa;
    }

    .ql-container.ql-snow {
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
        border: 1px solid #ced4da !important;
        font-family: inherit;
    }

    /* Premium Custom Override Matrix for Wide Layout Modals */
    .custom-wide-modal {
        max-width: 1250px !important;
        /* Forces layout wider than standard fluid containers */
        width: 95%;
    }

    .transition-all {
        transition: all 0.2s ease;
    }
</style>

<?php if (!in_array($user->user_lvl, ['VIEWER', 'ENCODER'])): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <form id="barangaySearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-5 col-lg-5 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Query</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0 filter-input" id="searchBrgy"
                                placeholder="Search Barangay / Captain..." style="height: 38px;">
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-select bg-light border-secondary-subtle filter-input" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Statuses</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger w-100 border  fw-semibold text-white"
                                    style="height: 38px;">
                                    Clear
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100  fw-semibold shadow-sm" id="searchBtn"
                                    style="height: 38px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="button"
                                    class="btn btn-success w-100 shadow-sm fw-semibold text-nowrap flex-grow-1"
                                    data-bs-toggle="modal" data-bs-target="#addModal" style="height: 38px;">
                                    Add Record
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
            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Department Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbldept" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-wide-modal modal-dialog-centered modal-dialog-scrollable">
        <form id="addForm" class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Add New Department</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="txtDept" class="form-label small fw-bold text-secondary">Department Name</label>
                        <input type="text" class="form-control shadow-sm" id="txtDept" name="txtDept"
                            placeholder="Enter department name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="txtHead" class="form-label small fw-bold text-secondary">Officer in Charge</label>
                        <input type="text" class="form-control shadow-sm" id="txtHead" name="txtHead"
                            placeholder="Enter full name" required>
                    </div>

                    <div class="col-lg-6 mt-3">
                        <label for="quillAbout" class="form-label small fw-bold text-secondary">About</label>
                        <div id="quillAbout" style="height: 140px;"></div>
                        <input type="hidden" id="txtAbout" name="txtAbout">
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="quillContact" class="form-label small fw-bold text-secondary">Contact
                            Information</label>
                        <div id="quillContact" style="height: 140px;"></div>
                        <input type="hidden" id="txtContact" name="txtContact">
                    </div>

                    <div class="col-lg-6 mt-3">
                        <label for="quillMission" class="form-label small fw-bold text-secondary">Mission</label>
                        <div id="quillMission" style="height: 140px;"></div>
                        <input type="hidden" id="txtMission" name="txtMission" required>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="quillVision" class="form-label small fw-bold text-secondary">Vision</label>
                        <div id="quillVision" style="height: 140px;"></div>
                        <input type="hidden" id="txtVision" name="txtVision" required>
                    </div>

                    <div class="col-12 mt-3">
                        <label for="quillPolicy" class="form-label small fw-bold text-secondary">Quality Policy</label>
                        <div id="quillPolicy" style="height: 130px;"></div>
                        <input type="hidden" id="txtPolicy" name="txtPolicy" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="deptImg" class="form-label small fw-bold text-secondary">Department Logo</label>
                        <input type="file" class="form-control shadow-sm" id="deptImg" name="deptImg" accept="image/*">
                        <div id="addDeptLogoPreview" class="mt-2"></div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="deptOrgChart" class="form-label small fw-bold text-secondary">Organizational
                            Chart</label>
                        <input type="file" class="form-control shadow-sm" id="deptOrgChart" name="deptOrgChart"
                            accept="image/*">
                        <div id="addDeptOrgChartPreview" class="mt-2"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Department</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-wide-modal modal-dialog-centered modal-dialog-scrollable">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editDeptId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Edit Department Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="editDept" class="form-label small fw-bold text-secondary">Department Name</label>
                        <input type="text" class="form-control shadow-sm" id="editDept" name="editDept"
                            placeholder="Enter department name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editHead" class="form-label small fw-bold text-secondary">Officer in Charge</label>
                        <input type="text" class="form-control shadow-sm" id="editHead" name="editHead"
                            placeholder="Enter full name" required>
                    </div>

                    <div class="col-lg-6 mt-3">
                        <label for="editQuillAbout" class="form-label small fw-bold text-secondary">About</label>
                        <div id="editQuillAbout" style="height: 140px;"></div>
                        <input type="hidden" id="editAbout" name="editAbout">
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="editQuillContact" class="form-label small fw-bold text-secondary">Contact
                            Information</label>
                        <div id="editQuillContact" style="height: 140px;"></div>
                        <input type="hidden" id="editContact" name="editContact">
                    </div>

                    <div class="col-lg-6 mt-3">
                        <label for="editQuillMission" class="form-label small fw-bold text-secondary">Mission</label>
                        <div id="editQuillMission" style="height: 140px;"></div>
                        <input type="hidden" id="editMission" name="editMission" required>
                    </div>
                    <div class="col-lg-6 mt-3">
                        <label for="editQuillVision" class="form-label small fw-bold text-secondary">Vision</label>
                        <div id="editQuillVision" style="height: 140px;"></div>
                        <input type="hidden" id="editVision" name="editVision" required>
                    </div>

                    <div class="col-12 mt-3">
                        <label for="editQuillPolicy" class="form-label small fw-bold text-secondary">Quality
                            Policy</label>
                        <div id="editQuillPolicy" style="height: 130px;"></div>
                        <input type="hidden" id="editPolicy" name="editPolicy" required>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label for="editdeptImg" class="form-label small fw-bold text-secondary">Department Logo</label>
                        <input type="file" class="form-control shadow-sm" id="editdeptImg" name="editdeptImg"
                            accept="image/*">
                        <div id="editDeptLogoPreview" class="mt-2"></div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label for="editdeptOrgChart" class="form-label small fw-bold text-secondary">Organizational
                            Chart</label>
                        <input type="file" class="form-control shadow-sm" id="editdeptOrgChart" name="editdeptOrgChart"
                            accept="image/*">
                        <div id="editDeptOrgChartPreview" class="mt-2"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Changes</button>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>