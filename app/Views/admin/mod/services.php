<?php
$isDeptScopedAdmin = (($user->user_lvl ?? '') === 'ADMIN' && ($user->account_type ?? '') === 'DEPARTMENT');
$isBrgyScopedAdmin = (($user->user_lvl ?? '') === 'ADMIN' && ($user->account_type ?? '') === 'BARANGAY');
$isEntityScopedAdmin = $isDeptScopedAdmin || $isBrgyScopedAdmin;
?>

<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Service Management</h1>
        <nav>
            <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Service Management</li>
            </ol>
        </nav>
    </div>
    <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal"
        data-bs-target="#addModal">
        <i class="bi bi-plus-circle me-2"></i>Add Service
    </button>
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

    /* Consistent Data Tables Structural Cell Rules */
    #tblservice th {
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaedf1;
        padding: 12px 16px;
    }

    #tblservice td {
        padding: 14px 16px;
        vertical-align: middle;
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

                    <div class="col-xl-4 col-lg-3 col-md-12">
                        <label for="service_name" class="form-label small fw-bold text-secondary">Service Query
                            Title</label>
                        <input type="text" class="form-control" name="service_name" id="service_name"
                            placeholder="Search service keyword...">
                    </div>

                    <?php if (!$isEntityScopedAdmin): ?>
                        <div class="col-xl-2 col-lg-3 col-md-6">
                            <label for="searchCategory" class="form-label small fw-bold text-secondary">Scope Category</label>
                            <select class="form-select" name="category" id="searchCategory">
                                <option selected value="">All Categories</option>
                                <option value="BARANGAY">Barangay</option>
                                <option value="DEPARTMENT">Department</option>
                            </select>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-secondary">Assigned Unit</label>

                            <div id="searchBrgyGroup" style="display: none;">
                                <select class="form-select" name="brgy" id="searchBrgy">
                                    <option value="">Select Barangay...</option>
                                </select>
                            </div>

                            <div id="searchDeptGroup" style="display: none;">
                                <select class="form-select" name="dept" id="searchDept">
                                    <option value="">Select Dept...</option>
                                </select>
                            </div>

                            <div id="searchDefaultGroup">
                                <select class="form-select" disabled>
                                    <option value="">Choose category first</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <label for="status" class="form-label small fw-bold text-secondary">Publishing Status</label>
                        <select class="form-select" name="status" id="status">
                            <option selected value="">All Statuses</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-12 col-md-6 d-flex gap-2">
                        <button type="reset" class="btn btn-light border w-50 fw-semibold" style="height: 38px;">
                            Clear
                        </button>
                        <button type="submit" class="btn btn-theme w-50 fw-semibold" id="searchBtn" style="height: 38px;">
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
            <div class="card card-premium">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tblservice" class="table table-striped table-hover align-middle w-100"
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
                        <input type="hidden" id="category" name="category" value="<?= $isBrgyScopedAdmin ? 'BRGY' : 'DEPT' ?>">
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
                        <input type="hidden" id="editcategory" name="editcategory" value="<?= $isBrgyScopedAdmin ? 'BRGY' : 'DEPT' ?>">
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
