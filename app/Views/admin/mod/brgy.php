<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
  <div>
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Barangay Management</h1>
    <nav>
      <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Barangay Management</li>
      </ol>
    </nav>
  </div>
  <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg me-2"></i>Add Barangay
  </button>
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
        box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
    }

    /* Premium Content Cards */
    .card-premium {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    /* Slick Custom Filter Inputs */
    .filter-input {
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.45rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s ease-in-out;
    }
    .filter-input:focus {
        border-color: var(--theme-mid-green);
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.15);
        outline: 0;
    }

    /* DataTables Search Box Standard Integration */
    .dataTables_filter input[type="search"] {
        width: 320px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.45rem 0.75rem;
        font-size: 0.9rem;
        margin-left: 10px;
        transition: all 0.2s ease-in-out;
    }
    .dataTables_filter input[type="search"]:focus {
        border-color: var(--theme-mid-green);
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.15);
        outline: 0;
    }

    /* Refined Table Styling */
    #tblbrgy th {
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaedf1;
        padding: 12px 16px;
    }
    #tblbrgy td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    /* Fix Quill Snow borders to look continuous and modern */
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

    .transition-all { transition: all 0.2s ease; }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
<div class="card card-premium mb-4">
    <div class="card-body p-4">
        <form id="barangaySearchForm">
            <div class="row g-3 align-items-end">
                <div class="col-lg-7 col-md-12">
                    <label class="form-label small fw-bold text-secondary">Search query</label>
                    <input type="text" class="form-control filter-input" id="searchBrgy" placeholder="Search Barangay / Captain...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label class="form-label small fw-bold text-secondary">Status</label>
                    <select class="form-select filter-input" name="status">
                        <option selected value="">- Status -</option>
                        <option value="ACTIVE">Active</option>
                        <option value="INACTIVE">Inactive</option>
                        <option value="ARCHIVED">Archived</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6 d-flex gap-2">
                    <button type="reset" class="btn btn-light w-100 filter-input fw-semibold text-secondary" style="height:40px;">
                        Clear
                    </button>
                    <button type="submit" class="btn btn-theme w-100 fw-semibold" id="searchBtn" style="height:40px;">
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
                        <table id="tblbrgy" class="table table-striped table-hover align-middle w-100" cellspacing="0">
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade modal-xl" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">     
    <form id="addForm" class="modal-content border-0 shadow-lg">
      <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
          <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Create New Barangay</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body p-4">
          <div class="row g-3">
              <div class="col-md-6">
                  <label for="txtBrgy" class="form-label small fw-bold text-secondary">Barangay Name</label>
                  <input type="text" class="form-control shadow-sm" id="txtBrgy" name="txtBrgy" placeholder="Enter barangay name" required>
              </div>
              <div class="col-md-6">
                  <label for="txtCapt" class="form-label small fw-bold text-secondary">Barangay Captain</label>
                  <input type="text" class="form-control shadow-sm" id="txtCapt" name="txtCapt" placeholder="Enter full name" required>
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
                  <input type="file" class="form-control shadow-sm" id="brgyImg" name="brgyImg" accept="image/*" required>
                  <div id="addBrgyLogoPreview" class="mt-2"></div>
              </div>
          </div>
      </div>
      
      <div class="modal-footer bg-light px-4 py-3">
          <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Close</button>
          <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Barangay</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade modal-xl" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">     
    <form id="editForm" class="modal-content border-0 shadow-lg">
      <input type="hidden" id="editBrgyId" name="id">
      
      <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
          <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Edit Barangay Details</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body p-4">
          <div class="row g-3">
              <div class="col-md-6">
                  <label for="editBrgy" class="form-label small fw-bold text-secondary">Barangay Name</label>
                  <input type="text" class="form-control shadow-sm" id="editBrgy" name="editBrgy" placeholder="Enter barangay name" required>
              </div>
              <div class="col-md-6">
                  <label for="editCapt" class="form-label small fw-bold text-secondary">Barangay Captain</label>
                  <input type="text" class="form-control shadow-sm" id="editCapt" name="editCapt" placeholder="Enter full name" required>
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
                  <input type="file" class="form-control shadow-sm" id="editbrgyImg" name="editbrgyImg" accept="image/*">
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