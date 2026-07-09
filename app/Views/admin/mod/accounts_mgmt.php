<div class="pagetitle mb-4 pb-2 border-bottom">
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Account Management</h1>

</div>

<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 small text-uppercase tracking-wider text-secondary">
                <i class="bi bi-funnel-fill me-2" style="color: var(--theme-mid-green);"></i>
            </h6>
            <form id="userSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Account</label>
                        <input type="text" class="form-control" id="searchUser"
                            placeholder="Search accounts by Name">
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-control form-select" id="searchStatus">
                            <option selected value="">Select Status</option>
                            <option value="ACTIVE">Active Only</option>
                            <option value="INACTIVE">Inactive Only</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Account Level</label>
                        <select class="form-control form-select" id="searchUserLevel">
                            <option selected value="">Select Account Level</option>
                            <?php if ($user->user_lvl === 'DEVELOPER'): ?>
                                <option value="DEVELOPER">Developer</option>
                            <?php endif; ?>
                            <option value="SUPERADMIN">Super Admin</option>
                            <option value="ADMIN">Admin</option>
                            <option value="ENCODER">Encoder</option>
                            <option value="VIEWER">Viewer</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="row g-2 admin-filter-actions">

                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 flex-grow-1 fw-semibold shadow-sm"
                                    id="searchBtn" style="height: 38px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger w-100 flex-grow-1 fw-semibold"
                                    style="height: 38px;">
                                    Clear
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="button"
                                    class="btn btn-success w-100 shadow-sm fw-semibold text-nowrap flex-grow-1"
                                    onclick="openUserModal('add')" style="height: 38px;">
                                    <i class="bi bi-person-plus-fill me-1"></i>Add Record
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<section class=" section">
    <div class="row">
        <div class="col-lg-12">

            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbluser" class="table table-bordered table-hover align-middle mb-0" cellspacing="0"
                            width="100%">
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Shared Add / Edit Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

            <!-- Hidden control fields -->
            <input type="hidden" id="userRecordId" name="id">
            <input type="hidden" id="userMode" name="mode" value="add">

            <div class="modal-header modal-header-theme py-3 px-4" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold d-inline-flex align-items-center gap-2" style="font-size: 1.1rem;">
                    <i id="userModalIcon" class="bi bi-person-plus"></i>
                    <span id="userModalTitle">Add Account</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light-surface">

                <!-- Section: Personal Information -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fw-bold small text-uppercase tracking-wider" style="color: var(--theme-dark-green); letter-spacing: 0.5px;">Personal Information</span>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label for="txtFirstName" class="form-label small fw-bold text-secondary">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="txtFirstName" name="txtFirstName" placeholder="e.g. John" required>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="txtMiddleName" class="form-label small fw-bold text-secondary">Middle Name</label>
                            <input type="text" class="form-control" id="txtMiddleName" name="txtMiddleName" placeholder="e.g. Smith">
                        </div>
                        <div class="w-100"></div>
                        <div class="col-md-6 col-sm-10">
                            <label for="txtLastName" class="form-label small fw-bold text-secondary">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="txtLastName" name="txtLastName" placeholder="e.g. Doe" required>
                        </div>
                        <div class="col-md-3 col-sm-2">
                            <label for="txtSuffix" class="form-label small fw-bold text-secondary">Suffix</label>
                            <select class="form-select" id="txtSuffix" name="txtSuffix">
                                <option value="">None</option>
                                <option value="Jr.">Jr.</option>
                                <option value="Sr.">Sr.</option>
                                <option value="II">II</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                                <option value="VI">VI</option>
                                <option value="VII">VII</option>
                                <option value="VIII">VIII</option>
                                <option value="IX">IX</option>
                                <option value="X">X</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="my-4" style="opacity: 0.15;">

                <!-- Section: Account Credentials -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fw-bold small text-uppercase tracking-wider" style="color: var(--theme-dark-green); letter-spacing: 0.5px;">Account Credentials</span>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label for="txtUsername" class="form-label small fw-bold text-secondary">Account Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="txtUsername" name="txtUsername" placeholder="Username" required>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="txtEmail" class="form-label small fw-bold text-secondary">E-Mail Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="username@domain.com" required>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="txtPassword" class="form-label small fw-bold text-secondary">
                                Password <span class="text-danger" id="passwordRequiredStar">*</span>
                                <small id="passwordOptionalHint" class="text-muted fw-normal" style="display:none;">(leave blank to keep current)</small>
                            </label>
                            <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="*********">
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="txtAccLevel" class="form-label small fw-bold text-secondary">Account Level <span class="text-danger">*</span></label>
                            <select id="txtAccLevel" name="txtAccLevel" class="form-select" required>
                                <option selected disabled value="">— Select Authorization Level —</option>
                                <option value="SUPERADMIN">Super Administrator</option>
                                <option value="ADMIN">Standard Administrator</option>
                                <option value="ENCODER">Data Encoder Entry</option>
                                <option value="VIEWER">System Read Auditor</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="accountTypeRow" style="display: none;">
                    <hr class="my-4" style="opacity: 0.15;">
                    <!-- Section: Account Scope & Boundaries -->
                    <div class="mb-2">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="fw-bold small text-uppercase tracking-wider" style="color: var(--theme-dark-green); letter-spacing: 0.5px;">Account Scope &amp; Boundaries</span>
                        </div>
                        <hr>
                        <div class="row g-3">
                            <div class="col-md-6 col-sm-12">
                                <label for="txtAccountType" class="form-label small fw-bold text-secondary">Account Type</label>
                                <select id="txtAccountType" name="txtAccountType" class="form-select">
                                    <option disabled value="">— Select Account Scope Boundaries —</option>
                                    <option value="DEPARTMENT" selected>Department</option>
                                    <option value="BARANGAY">Barangay</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12" id="entityRefGroup">
                                <label for="txtEntityRef" class="form-label small fw-bold text-secondary">Select Department or Barangay <span class="text-danger">*</span></label>
                                <select id="txtEntityRef" name="txtEntityRef" class="form-control form-select">
                                    <option value="" disabled selected>Select department / barangay</option>
                                </select>
                                <div class="form-text text-info" id="entityRefHint" style="font-size: 0.75rem;"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer bg-light py-2 px-4">
                <button type="button" class="btn btn-sm btn-secondary fw-semibold px-3"
                    data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="button" class="btn btn-sm text-white fw-semibold px-4"
                    style="background-color: var(--theme-mid-green);">Save</button>
            </div>

        </div>
    </form>
</div>
