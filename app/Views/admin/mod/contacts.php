<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Contacts Management</h1>
        <nav>
            <ol class="breadcrumb mb-0 bg-transparent p-2" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Contacts Management</li>
            </ol>
        </nav>
    </div>
</div>


<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

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
                        <select class="form-control form-select" name="contactCategory">
                            <option selected value="">- Category -</option>
                            <option value="BRGY">Barangay</option>
                            <option value="DEPT">Department</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="col-xl-2.5 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-control form-select" name="contactStatus">
                            <option selected value="">- Status -</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="row g-2">

                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger w-100 fw-semibold" style="height: 38px;">
                                    Clear
                                </button>
                            </div>

                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm" id="searchBtn"
                                    style="height: 38px;">
                                    Search
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