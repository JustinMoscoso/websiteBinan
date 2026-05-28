<div class="pagetitle mb-4 pb-2 border-bottom">
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Account Management</h1>
    <nav>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>" class="text-decoration-none"
                    style="color: #2d6a4f;">Dashboard</a></li>
            <li class="breadcrumb-item active">Account Management</li>
        </ol>
    </nav>
</div>

<style>
    /* Admin UI Layout Theme Variable Definitions */
    :root {
        --theme-dark-green: #1b4d3e;
        --theme-mid-green: #2d6a4f;
        --theme-light-green: #d8f3dc;
        --theme-accent: #20c997;
        --theme-surface-bg: #f8f9fa;
    }

    /* Premium Component Containers Card Design */
    .card-premium {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    /* Premium Modal Styling Headers */
    .modal-header-theme {
        background-color: var(--theme-dark-green);
        color: #ffffff;
        border-bottom: none;
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

    #tbluser {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tbluser th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
    }

    #tbluser td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tbluser tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tbluser tbody tr:hover {
        background-color: #eef6f0 !important; /* Soft premium green highlight on hover */
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

    /* Premium Modern Account Type Badge Styles */
    .acctype-badge-dept {
        background-color: #f3e5f5;
        color: #7b1fa2;
        border-color: #e1bee7;
    }
    
    .acctype-badge-brgy {
        background-color: #fff8e1;
        color: #b7791f;
        border-color: #ffe082;
    }
    
    .acctype-badge-system {
        background-color: #f5f5f5;
        color: #616161;
        border-color: #e0e0e0;
    }

    .acctype-dot-dept {
        background-color: #7b1fa2;
        box-shadow: 0 0 6px #7b1fa2;
    }
    
    .acctype-dot-brgy {
        background-color: #b7791f;
        box-shadow: 0 0 6px #b7791f;
    }
    
    .acctype-dot-system {
        background-color: #616161;
    }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 small text-uppercase tracking-wider text-secondary">
                <i class="bi bi-funnel-fill me-2" style="color: var(--theme-mid-green);"></i>Data Directory Filtering
            </h6>
            <form id="userSearchForm">
                <div class="row g-3 align-items-center">

                    <div class="col-xl-5 col-lg-4 col-md-12">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0" id="searchUser"
                                placeholder="Search accounts by username or legal name...">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <select class="form-select" id="searchStatus">
                            <option selected value="">— Status Profile —</option>
                            <option value="ACTIVE">Active Only</option>
                            <option value="INACTIVE">Inactive Only</option>
                            <option value="ARCHIVED">Archived / Suspended</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-6">
                        <select class="form-select" id="searchUserLevel">
                            <option selected value="">— Authority Level —</option>
                            <?php if ($user->user_lvl === 'DEVELOPER'): ?>
                                <option value="DEVELOPER">Developer Engine</option>
                            <?php endif; ?>
                            <option value="SUPERADMIN">Super Administrator</option>
                            <option value="ADMIN">Standard Administrator</option>
                            <option value="ENCODER">Data Encoder Entry</option>
                            <option value="VIEWER">System Read Auditor</option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-2 col-md-12 d-flex gap-2 justify-content-lg-end">
                        <button type="reset"
                            class="btn btn-outline-danger w-50 py-2 text-nowrap d-inline-flex align-items-center justify-content-center gap-1 small fw-semibold">
                            <i class="bi bi-x-circle"></i> Clear
                        </button>
                        <button type="submit"
                            class="btn text-white w-50 py-2 text-nowrap d-inline-flex align-items-center justify-content-center gap-1 small fw-semibold"
                            id="searchBtn" style="background-color: var(--theme-mid-green);">
                            <i class="bi bi-sliders"></i> Search
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="text-end mb-3">
                <button type="button"
                    class="btn text-white shadow-sm px-4 fw-semibold d-inline-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#addModal"
                    style="background-color: var(--theme-mid-green); border-radius: 8px;">
                    <i class="bi bi-person-plus-fill"></i> Add New User Account
                </button>
            </div>

            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4" style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>User Account Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbluser" class="table table-bordered table-hover align-middle mb-0" cellspacing="0" width="100%">
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

            <div class="modal-header modal-header-theme py-3 px-4">
                <h5 class="modal-title fw-bold d-inline-flex align-items-center gap-2" style="font-size: 1.1rem;">
                    <i class="bi bi-person-plus"></i> Create System User Account
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light-surface">

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="txtFirstName" class="form-label small fw-bold text-secondary">First Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtFirstName" name="txtFirstName"
                            placeholder="e.g. John" required>
                    </div>
                    <div class="col-md-3">
                        <label for="txtMiddleName" class="form-label small fw-bold text-secondary">Middle Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtMiddleName" name="txtMiddleName"
                            placeholder="e.g. Smith" required>
                    </div>
                    <div class="col-md-3">
                        <label for="txtLastName" class="form-label small fw-bold text-secondary">Last Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtLastName" name="txtLastName"
                            placeholder="e.g. Doe" required>
                    </div>
                    <div class="col-md-3">
                        <label for="txtSuffix" class="form-label small fw-bold text-secondary">Suffix</label>
                        <input type="text" class="form-control" id="txtSuffix" name="txtSuffix"
                            placeholder="e.g. Jr.">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="txtUsername" class="form-label small fw-bold text-secondary">Account Username <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtUsername" name="txtUsername"
                            placeholder="Unique profile identifier username" required>
                    </div>
                    <div class="col-md-6">
                        <label for="txtEmail" class="form-label small fw-bold text-secondary">Primary E-Mail Address
                            <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="txtEmail" name="txtEmail"
                            placeholder="username@domain.com" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtPassword" class="form-label small fw-bold text-secondary">Access Password <span
                                class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="txtPassword" name="txtPassword"
                            placeholder="Construct a strong protective password passphrase" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="txtAccLevel" class="form-label small fw-bold text-secondary">Access Role
                            Authorization Level <span class="text-danger">*</span></label>
                        <select id="txtAccLevel" name="txtAccLevel" class="form-select" required>
                            <option selected disabled value="">— Select Authorization Level —</option>
                            <option value="SUPERADMIN">Super Administrator</option>
                            <option value="ADMIN">Standard Administrator</option>
                            <option value="ENCODER">Data Encoder Entry</option>
                            <option value="VIEWER">System Read Auditor</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 border-top border-light pt-3 mt-2" id="accountTypeRow">
                    <div class="col-md-6">
                        <label for="txtAccountType" class="form-label small fw-bold text-secondary">Functional Account
                            Scope Boundaries</label>
                        <select id="txtAccountType" name="txtAccountType" class="form-select">
                            <option value="DEPARTMENT">Departmental Agency Account</option>
                            <option value="BARANGAY">Barangay Local Unit Account</option>
                        </select>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Limits organizational data
                            adjustments entirely to their assigned identity parameters.</div>
                    </div>
                    <div class="col-md-6" id="entityRefGroup">
                        <label for="txtEntityRef" class="form-label small fw-bold text-secondary">Linked Enterprise
                            Context Node <span class="text-danger">*</span></label>
                        <select id="txtEntityRef" name="txtEntityRef" class="form-select">
                            <option value="" disabled selected>— Select contextual entity node link —</option>
                        </select>
                        <div class="form-text text-info" id="entityRefHint" style="font-size: 0.75rem;"></div>
                    </div>
                </div>

            </div>

            <div class="modal-footer bg-light py-2 px-4">
                <button type="button" class="btn btn-sm btn-secondary fw-semibold px-3"
                    data-bs-dismiss="modal">Discard</button>
                <button id="btnAdd" type="button" class="btn btn-sm text-white fw-semibold px-4"
                    style="background-color: var(--theme-mid-green);">Save Profile</button>
            </div>

        </div>
    </form>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="editForm" class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">

            <div class="modal-header modal-header-theme py-3 px-4">
                <h5 class="modal-title fw-bold d-inline-flex align-items-center gap-2" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square"></i> Modify Account Properties
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light-surface">
                <input type="hidden" id="editUserId" name="id">

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="editFirstName" class="form-label small fw-bold text-secondary">First Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editFirstName" name="editFirstName"
                            placeholder="Update first name" required>
                    </div>
                    <div class="col-md-3">
                        <label for="editMiddleName" class="form-label small fw-bold text-secondary">Middle Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editMiddleName" name="editMiddleName"
                            placeholder="Update middle name" required>
                    </div>
                    <div class="col-md-3">
                        <label for="editLastName" class="form-label small fw-bold text-secondary">Last Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editLastName" name="editLastName"
                            placeholder="Update last name" required>
                    </div>
                    <div class="col-md-3">
                        <label for="editSuffix" class="form-label small fw-bold text-secondary">Suffix</label>
                        <input type="text" class="form-control" id="editSuffix" name="editSuffix"
                            placeholder="Update suffix">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="editUsername" class="form-label small fw-bold text-secondary">Account Username <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editUsername" name="editUsername"
                            placeholder="Modify account username string" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editEmail" class="form-label small fw-bold text-secondary">Primary E-Mail Address
                            <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="editEmail" name="editEmail"
                            placeholder="Modify account email address location" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label for="editPassword" class="form-label small fw-bold text-secondary">Override Secure
                            Password</label>
                        <input type="password" class="form-control" id="editPassword" name="editPassword"
                            placeholder="Leave empty unless explicitly resetting password authentication variables">
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Leave entirely unpopulated to
                            protect and maintain current active database passwords.</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="editAccLevel" class="form-label small fw-bold text-secondary">Access Role
                            Authorization Level <span class="text-danger">*</span></label>
                        <select id="editAccLevel" name="editAccLevel" class="form-select" required>
                            <option selected disabled value="">— Select Authorization Level —</option>
                            <option value="SUPERADMIN">Super Administrator</option>
                            <option value="ADMIN">Standard Administrator</option>
                            <option value="ENCODER">Data Encoder Entry</option>
                            <option value="VIEWER">System Read Auditor</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 border-top border-light pt-3 mt-2" id="editAccountTypeRow">
                    <div class="col-md-6">
                        <label for="editAccountType" class="form-label small fw-bold text-secondary">Functional Account
                            Scope Boundaries</label>
                        <select id="editAccountType" name="editAccountType" class="form-select">
                            <option value="DEPARTMENT">Departmental Agency Account</option>
                            <option value="BARANGAY">Barangay Local Unit Account</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="editEntityRefGroup">
                        <label for="editEntityRef" class="form-label small fw-bold text-secondary">Linked Enterprise
                            Context Node <span class="text-danger">*</span></label>
                        <select id="editEntityRef" name="editEntityRef" class="form-select">
                            <option value="" disabled selected>— Select contextual entity node link —</option>
                        </select>
                        <div class="form-text text-info" id="editEntityRefHint" style="font-size: 0.75rem;"></div>
                    </div>
                </div>

            </div>

            <div class="modal-footer bg-light py-2 px-4">
                <button type="button" class="btn btn-sm btn-secondary fw-semibold px-3" data-bs-dismiss="modal">Cancel
                    Changes</button>
                <button id="btnEdit" type="button" class="btn btn-sm text-white fw-semibold px-4"
                    style="background-color: var(--theme-mid-green);">Update Parameters</button>
            </div>

        </div>
    </form>
</div>