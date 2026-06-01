<?php
$isDeptScopedAdmin = (in_array($user->user_lvl ?? '', ['ADMIN', 'ENCODER']) && ($user->account_type ?? '') === 'DEPARTMENT');
$isBrgyScopedAdmin = (in_array($user->user_lvl ?? '', ['ADMIN', 'ENCODER']) && ($user->account_type ?? '') === 'BARANGAY');
$isEntityScopedAdmin = $isDeptScopedAdmin || $isBrgyScopedAdmin;
?>

<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Service Management</h1>
        <nav>
            <ol class="breadcrumb mb-0 bg-transparent p-2 " style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Service Management</li>
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

    /* Premium Component Containers Card Design */
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

    #tblservice {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tblservice th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
    }

    #tblservice td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tblservice tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tblservice tbody tr:hover {
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
        margin-top: 1px;
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

    /* Clean container wrapper for Quill Text Editor components */
    .editor-wrapper {
        border: 1px solid #ced4da;
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
    }

    .editor-wrapper .ql-toolbar.ql-snow {
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: 1px solid #ced4da;
        background: #f8f9fa;
    }

    .editor-wrapper .ql-container.ql-snow {
        border: none;
    }

    .transition-all {
        transition: all 0.2s ease;
    }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <form id="serviceSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="<?= !$isEntityScopedAdmin ? 'col-xl-3' : 'col-xl-6' ?> col-lg-4 col-md-12">
                        <label for="service_name" class="form-label small fw-bold text-secondary">Service Query
                            Title</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0" name="service_name" id="service_name"
                                placeholder="Search service keyword..." style="height: 38px;">
                        </div>
                    </div>

                    <?php if (!$isEntityScopedAdmin): ?>
                        <div class="col-xl-2 col-lg-2 col-md-6">
                            <label for="searchCategory" class="form-label small fw-bold text-secondary">Scope Category</label>
                            <select class="form-control form-select bg-light border-secondary-subtle" name="category"
                                id="searchCategory" style="height: 38px; cursor: pointer;">
                                <option selected value="">All Categories</option>
                                <option value="BARANGAY">Barangay</option>
                                <option value="DEPARTMENT">Department</option>
                            </select>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-md-6">
                            <label class="form-label small fw-bold text-secondary">Assigned Unit</label>

                            <div id="searchBrgyGroup" style="display: none;">
                                <select class="form-select bg-light border-secondary-subtle" name="brgy" id="searchBrgy"
                                    style="height: 38px; cursor: pointer;">
                                    <option value="">Select Barangay...</option>
                                </select>
                            </div>

                            <div id="searchDeptGroup" style="display: none;">
                                <select class="form-select bg-light border-secondary-subtle" name="dept" id="searchDept"
                                    style="height: 38px; cursor: pointer;">
                                    <option value="">Select Dept...</option>
                                </select>
                            </div>

                            <div id="searchDefaultGroup">
                                <select class="form-control form-select bg-light border-secondary-subtle" disabled
                                    style="height: 38px;">
                                    <option value="">Choose category first</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label for="status" class="form-label small fw-bold text-secondary">Publishing Status</label>
                        <select class="form-control form-select bg-light border-secondary-subtle" name="status" id="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Statuses</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <?php if (in_array($user->user_lvl ?? '', ['DEVELOPER', 'SUPERADMIN'], true)): ?>
                                <option value="ARCHIVED">Archived</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="<?= !$isEntityScopedAdmin ? 'col-xl-3' : 'col-xl-4' ?> col-lg-4 col-md-12">
                        <div class="row g-2">

                            <!-- Clear -->
                            <div class="col-4">
                                <button type="reset" class="btn btn-danger w-100 fw-semibold" style="height: 38px;">
                                    Clear
                                </button>
                            </div>

                            <!-- Search -->
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm" id="searchBtn"
                                    style="height: 38px;">
                                    Search
                                </button>
                            </div>

                            <!-- Add Service -->
                            <div class="col-4">
                                <button type="button" class="btn btn-success w-100 fw-semibold text-white shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#addModal" style="height: 38px;">
                                    <i class="bi bi-plus-circle me-1"></i>
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
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Service Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblservice" class="table table-bordered table-hover align-middle w-100"
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
                    <i class="bi bi-plus-circle me-2"></i>Add Service Provision Listing
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <?php if ($isEntityScopedAdmin): ?>
                        <input type="hidden" id="category" name="category"
                            value="<?= $isBrgyScopedAdmin ? 'BRGY' : 'DEPT' ?>">
                        <div class="col-12">
                            <label for="serviceName" class="form-label small fw-bold text-secondary">Service Provision Name
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="serviceName" name="serviceName"
                                placeholder="Enter registration, licensing title name..." required>
                        </div>
                    <?php else: ?>
                        <div class="col-md-6">
                            <label for="category" class="form-label small fw-bold text-secondary">Category Group Scope <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" selected disabled>Choose classification...</option>
                                <option value="BRGY">Barangay</option>
                                <option value="DEPT">Department</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="serviceName" class="form-label small fw-bold text-secondary">Service Provision Name
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="serviceName" name="serviceName"
                                placeholder="Enter service description..." required>
                        </div>

                        <div class="col-12">
                            <div id="deptGroup" style="display:none;">
                                <label for="txtDept" class="form-label small fw-bold text-secondary">Responsible Department
                                    Entity <span class="text-danger">*</span></label>
                                <select id="txtDept" name="txtDept" class="form-select">
                                    <option selected disabled value="">Choose regional department...</option>
                                </select>
                            </div>

                            <div id="brgyGroup" style="display:none;">
                                <label for="txtBrgy" class="form-label small fw-bold text-secondary">Responsible Barangay
                                    Ward <span class="text-danger">*</span></label>
                                <select id="txtBrgy" name="txtBrgy" class="form-select">
                                    <option selected disabled value="">Choose community ward...</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary mb-1">Detailed Service Instructions &
                            Content Requirements <span class="text-danger">*</span></label>
                        <div class="editor-wrapper shadow-sm">
                            <div id="quillContent" style="height: 180px;"></div>
                        </div>
                        <input type="hidden" id="content" name="content" required>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Provision</button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify Service Configuration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <?php if ($isEntityScopedAdmin): ?>
                        <input type="hidden" id="editcategory" name="editcategory"
                            value="<?= $isBrgyScopedAdmin ? 'BRGY' : 'DEPT' ?>">
                    <?php else: ?>
                        <div class="col-md-6">
                            <label for="editcategory" class="form-label small fw-bold text-secondary">Category Group Scope
                                <span class="text-danger">*</span></label>
                            <select class="form-select" id="editcategory" name="editcategory" required>
                                <option value="" disabled>Choose classification...</option>
                                <option value="BRGY">Barangay</option>
                                <option value="DEPT">Department</option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="<?= $isEntityScopedAdmin ? 'col-12' : 'col-md-6' ?>">
                        <label for="editServiceName" class="form-label small fw-bold text-secondary">Service Provision
                            Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editServiceName" name="editServiceName"
                            placeholder="Enter service description..." required>
                    </div>

                    <?php if (!$isEntityScopedAdmin): ?>
                        <div class="col-12">
                            <div id="editDeptFieldGroup" style="display:none;" class="mb-2">
                                <label for="editDept" class="form-label small fw-bold text-secondary">Responsible Department
                                    Entity <span class="text-danger">*</span></label>
                                <select id="editDept" name="editDept" class="form-select">
                                </select>
                            </div>

                            <div id="editBrgyFieldGroup" style="display:none;">
                                <label for="editBrgy" class="form-label small fw-bold text-secondary">Responsible Barangay
                                    Ward <span class="text-danger">*</span></label>
                                <select id="editBrgy" name="editBrgy" class="form-select">
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary mb-1">Detailed Service Instructions &
                            Content Requirements <span class="text-danger">*</span></label>
                        <div class="editor-wrapper shadow-sm">
                            <div id="editQuillContent" style="height: 180px;"></div>
                        </div>
                        <input type="hidden" id="editContent" name="editContent" required>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Changes</button>
            </div>

        </form>
    </div>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
