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

<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if (!in_array($user->user_lvl, ['VIEWER', 'ENCODER'])): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <form id="barangaySearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Query</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0 filter-input" id="searchBrgy"
                                placeholder="Search Barangay / Captain..." style="height: 38px;">
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-control form-select bg-light border-secondary-subtle filter-input" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Statuses</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="row g-2 admin-filter-actions">

                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100  fw-semibold shadow-sm" id="searchBtn"
                                    style="height: 38px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger w-100 border  fw-semibold text-white"
                                    style="height: 38px;">
                                    Clear
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