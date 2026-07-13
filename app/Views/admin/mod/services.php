<?php
$isDeptScopedAdmin = (in_array($user->user_lvl ?? '', ['ADMIN', 'ENCODER']) && ($user->account_type ?? '') === 'DEPARTMENT');
$isBrgyScopedAdmin = (in_array($user->user_lvl ?? '', ['ADMIN', 'ENCODER']) && ($user->account_type ?? '') === 'BARANGAY');
$isEntityScopedAdmin = $isDeptScopedAdmin || $isBrgyScopedAdmin;
?>

<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Service Management</h1>
    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<style>
    /* Service details are easier to scan when aligned from a common left edge. */
    #tblservice tbody td:nth-child(-n+2),
    #tblservice tbody td:nth-child(-n+2) * {
        text-align: left !important;
    }

    #tblservice tbody td:nth-child(-n+2) .d-flex {
        justify-content: flex-start !important;
    }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <form id="serviceSearchForm">
                <div class="row g-3">

                    <div class="<?= !$isEntityScopedAdmin ? 'col-xl-4' : 'col-xl-9' ?> col-lg-6 col-md-12">
                        <label for="service_name" class="form-label small fw-bold text-secondary">Title</label>
                        <div class="input-group">
                            <input type="text" class="form-control border-start-0" name="service_name" id="service_name"
                                placeholder="Search Title" style="height: 38px;">
                        </div>
                    </div>

                    <?php if (!$isEntityScopedAdmin): ?>
                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <label for="searchCategory" class="form-label small fw-bold text-secondary">Category</label>
                            <select class="form-control form-select bg-light border-secondary-subtle" name="category"
                                id="searchCategory" style="height: 38px; cursor: pointer;">
                                <option selected value="">Select Categories</option>
                                <option value="BARANGAY">Barangay</option>
                                <option value="DEPARTMENT">Department</option>
                            </select>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-secondary">Select Department / Barangay</label>

                            <div id="searchBrgyGroup" style="display: none;">
                                <select class="form-control form-select bg-light border-secondary-subtle" name="brgy"
                                    id="searchBrgy" style="height: 38px; cursor: pointer;">
                                    <option value="">Select Barangay...</option>
                                </select>
                            </div>

                            <div id="searchDeptGroup" style="display: none;">
                                <select class="form-control form-select bg-light border-secondary-subtle" name="dept"
                                    id="searchDept" style="height: 38px; cursor: pointer;">
                                    <option value="">Select Dept...</option>
                                </select>
                            </div>

                            <div id="searchDefaultGroup">
                                <select class="form-control form-select bg-light border-secondary-subtle" disabled
                                    style="height: 38px;">
                                    <option value="">Select Category First</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="<?= !$isEntityScopedAdmin ? 'col-xl-2' : 'col-xl-3' ?> col-lg-3 col-md-6">
                        <label for="status" class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-control form-select bg-light border-secondary-subtle" name="status" id="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">Select Status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>

                    <!-- Buttons Row -->
                    <div class="col-12 mt-2">
                        <hr class="my-2" style="border-color: #adb5bd; opacity: 1;">
                        <div class="row justify-content-end pt-2">
                            <div class="col-xl-4 col-lg-4 col-md-12">
                                <div class="row g-2 admin-filter-actions">
                                    <div class="col-12 col-md-4">
                                        <button type="submit" id="searchBtn"
                                            class="btn btn-primary w-100 fw-semibold shadow-sm" style="height: 38px;">
                                            <i class="bi bi-search me-1"></i>
                                            Search
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <button type="reset" class="btn btn-danger w-100 fw-semibold" style="height: 38px;">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Clear
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <button type="button" class="btn w-100 btn-success fw-semibold text-white text-nowrap shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#addModal">
                                            <i class="bi bi-plus-circle me-1"></i>
                                            Add Record
                                        </button>
                                    </div>
                                </div>
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
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">
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

<!-- Shared Add / Edit Modal -->
<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="addForm" class="modal-content border-0 shadow-lg">

            <!-- Hidden fields: record ID and mode (add | edit) -->
            <input type="hidden" id="recordId" name="id">
            <input type="hidden" id="recordMode" name="mode" value="add">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <span id="recordModalTitle">Add Service</span>
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
                            <label for="category" class="form-label small fw-bold text-secondary">Category<span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="" selected disabled>Choose Category</option>
                                <option value="BRGY">Barangay</option>
                                <option value="DEPT">Department</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div id="deptGroup" style="display:none;">
                                <label for="txtDept" class="form-label small fw-bold text-secondary">Department
                                    <span class="text-danger">*</span></label>
                                <select id="txtDept" name="txtDept" class="form-control form-select">
                                    <option selected disabled value="">Select Department</option>
                                </select>
                            </div>

                            <div id="brgyGroup" style="display:none;">
                                <label for="txtBrgy" class="form-label small fw-bold text-secondary">Barangay
                                    Ward <span class="text-danger">*</span></label>
                                <select id="txtBrgy" name="txtBrgy" class="form-control form-select">
                                    <option selected disabled value="">Select Barangay</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="serviceName" class="form-label small fw-bold text-secondary">Service
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="serviceName" name="serviceName"
                                placeholder="Enter Service Title" required>
                        </div>
                    <?php endif; ?>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary mb-1">Service Details<span
                                class="text-danger">*</span></label>
                        <div class="editor-wrapper shadow-sm">
                            <div id="quillContent" style="height: 180px;"></div>
                        </div>
                        <input type="hidden" id="content" name="content" required>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">
                    <span id="btnAddLabel">Save</span>
                </button>
            </div>

        </form>
    </div>
</div>
