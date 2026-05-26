<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
  <div>
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Contacts Management</h1>
    <nav>
      <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Contacts Management</li>
      </ol>
    </nav>
  </div>
  <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-circle me-2"></i>Add Contact
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
        box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
    }

    /* Premium Containers Standard Theme Configuration */
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
    #tblhotlines th {
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaedf1;
        padding: 12px 16px;
    }
    #tblhotlines td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    .transition-all { transition: all 0.2s ease; }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
<div class="card card-premium mb-4">
    <div class="card-body p-4">
        <form id="contactSearchForm">
            <div class="row g-3 align-items-end">
                
                <div class="col-xl-5 col-lg-4 col-md-12">
                    <label class="form-label small fw-bold text-secondary">Search Contact / Office</label>
                    <input type="text" class="form-control" id="searchContact" placeholder="Search Contact / Office Name...">
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
                    <button type="reset" class="btn btn-light fw-semibold text-secondary w-50" style="height: 40px; padding: 0;">
                        Clear
                    </button>
                    <button type="submit" class="btn btn-theme fw-semibold w-50" id="searchBtn" style="height: 40px; padding: 0;">
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
                        <table id="tblhotlines" class="table table-striped table-hover align-middle w-100" cellspacing="0">
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-3">
                    
                    <div class="col-12">
                        <label for="category" class="form-label small fw-bold text-secondary">Directory Section <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="category" name="category" required>
                            <option value="" selected disabled>Choose a section...</option>
                            <option value="BRGY">Barangay</option>
                            <option value="DEPT">Department</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="col-12" id="deptGroup" style="display: none;">
                        <label for="txtDept" class="form-label small fw-bold text-secondary">Department Assignment <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="txtDept" name="txtDept" required>
                            </select>
                    </div>

                    <div class="col-12" id="brgyGroup" style="display: none;">
                        <label for="txtBrgy" class="form-label small fw-bold text-secondary">Barangay Location <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="txtBrgy" name="txtBrgy" required>
                            </select>
                    </div>

                    <div class="col-12" id="othersGrp" style="display: none;">
                        <label for="txtOthers" class="form-label small fw-bold text-secondary">Office Name <span class="text-danger">*</span></label>
                        <input type="text" id="txtOthers" name="txtOthers" class="form-control shadow-sm" placeholder="Enter custom office / establishment designation" required>
                    </div>

                    <div class="col-12 mt-4 mb-1">
                        <h6 class="small text-uppercase fw-bold text-muted tracking-wider border-bottom pb-2">Telecommunications Channels</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="contact" class="form-label small fw-bold text-secondary">PLDT Landline</label>
                        <input type="text" class="form-control shadow-sm" id="contact" name="contact" placeholder="XXX-XXXX or -" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="smart" class="form-label small fw-bold text-secondary">SMART Network Mobile</label>
                        <input type="text" class="form-control shadow-sm" id="smart" name="smart" placeholder="09XX-XXX-XXXX or -" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="globe" class="form-label small fw-bold text-secondary">GLOBE Network Mobile</label>
                        <input type="text" class="form-control shadow-sm" id="globe" name="globe" placeholder="09XX-XXX-XXXX or -" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="telco" class="form-label small fw-bold text-secondary">INTELCO Line</label>
                        <input type="text" class="form-control shadow-sm" id="telco" name="telco" placeholder="XXX-XXXX or -" required>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <input type="hidden" id="editId" name="id">
                
                <div class="row g-3">
                    
                    <div class="col-12">
                        <label for="editcategory" class="form-label small fw-bold text-secondary">Directory Section <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="editcategory" name="editcategory" required>
                            <option value="" disabled>Choose a section...</option>
                            <option value="BRGY">Barangay</option>
                            <option value="DEPT">Department</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <div class="col-12" id="editdeptGroup" style="display: none;">
                        <label for="editDept" class="form-label small fw-bold text-secondary">Department Assignment <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="editDept" name="editDept" required>
                            </select>
                    </div>

                    <div class="col-12" id="editbrgyGroup" style="display: none;">
                        <label for="editBrgy" class="form-label small fw-bold text-secondary">Barangay Location <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm" id="editBrgy" name="editBrgy" required>
                            </select>
                    </div>

                    <div class="col-12" id="editothersGrp" style="display: none;">
                        <label for="editOthers" class="form-label small fw-bold text-secondary">Office Name <span class="text-danger">*</span></label>
                        <input type="text" id="editOthers" name="editOthers" class="form-control shadow-sm" required>
                    </div>

                    <div class="col-12 mt-4 mb-1">
                        <h6 class="small text-uppercase fw-bold text-muted tracking-wider border-bottom pb-2">Telecommunications Channels</h6>
                    </div>

                    <div class="col-md-6">
                        <label for="editContact" class="form-label small fw-bold text-secondary">PLDT Landline</label>
                        <input type="text" class="form-control shadow-sm" id="editContact" name="editContact" placeholder="XXX-XXXX or -" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="editSmart" class="form-label small fw-bold text-secondary">SMART Network Mobile</label>
                        <input type="text" class="form-control shadow-sm" id="editSmart" name="editSmart" placeholder="09XX-XXX-XXXX or -" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="editGlobe" class="form-label small fw-bold text-secondary">GLOBE Network Mobile</label>
                        <input type="text" class="form-control shadow-sm" id="editGlobe" name="editGlobe" placeholder="09XX-XXX-XXXX or -" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="editTelco" class="form-label small fw-bold text-secondary">INTELCO Line</label>
                        <input type="text" class="form-control shadow-sm" id="editTelco" name="editTelco" placeholder="XXX-XXXX or -" required>
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