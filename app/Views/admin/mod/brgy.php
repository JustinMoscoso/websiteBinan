<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Barangay Management</h1>
        <nav>
            <ol class="breadcrumb mb-0 bg-transparent p-2" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Barangay Management</li>
            </ol>
        </nav>
    </div>
</div>


<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if ($user->user_lvl !== 'VIEWER'): ?>
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
                        <select class="form-select bg-light border-secondary-subtle filter-input" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Statuses</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="row g-2 admin-filter-actions">

                            <!-- Clear -->


                            <!-- Search -->
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm" id="searchBtn"
                                    style="height: 38px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger border w-100 fw-semibold text-white"
                                    style="height: 38px;">
                                    Clear
                                </button>
                            </div>

                            <!-- Add Record -->
                            <div class="col-12 col-md-4">
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
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Barangay Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblbrgy" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
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
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Create New Barangay</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cancel"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="txtBrgy" class="form-label small fw-bold text-secondary">Barangay Name</label>
                        <input type="text" class="form-control shadow-sm" id="txtBrgy" name="txtBrgy"
                            placeholder="Enter barangay name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="txtCapt" class="form-label small fw-bold text-secondary">Barangay Captain</label>
                        <input type="text" class="form-control shadow-sm" id="txtCapt" name="txtCapt"
                            placeholder="Enter full name" required>
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">About</label>
                        <div id="createabout" style="height: 220px;"></div>
                        <input type="hidden" id="createAbout" name="createAbout">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label small fw-bold text-secondary">Mission</label>
                        <div id="txtMission" style="height: 180px;"></div>
                        <input type="hidden" name="txtMission">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label small fw-bold text-secondary">Vision</label>
                        <div id="txtVision" style="height: 180px;"></div>
                        <input type="hidden" name="txtVision">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">Contact Information</label>
                        <div id="txtContact" style="height: 140px;"></div>
                        <input type="hidden" name="txtContact">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">Barangay Staff</label>
                        <div id="txtStaff" style="height: 180px;"></div>
                        <input type="hidden" name="txtStaff">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label for="brgyImg" class="form-label small fw-bold text-secondary">Barangay Logo</label>
                        <input type="file" class="form-control shadow-sm" id="brgyImg" name="brgyImg" accept="image/*"
                            required>
                        <div id="addBrgyLogoPreview" class="mt-2"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Close</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-xl custom-wide-modal modal-dialog-centered modal-dialog-scrollable">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editBrgyId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Edit Barangay Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="editBrgy" class="form-label small fw-bold text-secondary">Barangay Name</label>
                        <input type="text" class="form-control shadow-sm" id="editBrgy" name="editBrgy"
                            placeholder="Enter barangay name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editCapt" class="form-label small fw-bold text-secondary">Barangay Captain</label>
                        <input type="text" class="form-control shadow-sm" id="editCapt" name="editCapt"
                            placeholder="Enter full name" required>
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">About</label>
                        <div id="editabout" style="height: 220px;"></div>
                        <input type="hidden" id="editAbout" name="editAbout">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label small fw-bold text-secondary">Mission</label>
                        <div id="editMission" style="height: 180px;"></div>
                        <input type="hidden" name="editMission">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label small fw-bold text-secondary">Vision</label>
                        <div id="editVision" style="height: 180px;"></div>
                        <input type="hidden" name="editVision">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">Contact Information</label>
                        <div id="editContact" style="height: 140px;"></div>
                        <input type="hidden" name="editContact">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">Barangay Staff</label>
                        <div id="editStaff" style="height: 180px;"></div>
                        <input type="hidden" name="editStaff">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label for="editbrgyImg" class="form-label small fw-bold text-secondary">Barangay Logo</label>
                        <input type="file" class="form-control shadow-sm" id="editbrgyImg" name="editbrgyImg"
                            accept="image/*">
                        <div id="editBrgyLogoPreview" class="mt-2"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Close</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Changes</button>
            </div>
        </form>
    </div>
</div>
