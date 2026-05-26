<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
  <div>
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Career Management</h1>
    <nav>
      <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Career Management</li>
      </ol>
    </nav>
  </div>
  <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-lg me-2"></i>Add Career
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

    /* Polished Card Container */
    .card-premium {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    /* Sleek DataTables Controls Alignment */
    .dataTables_length select {
        border-radius: 6px;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        border: 1px solid #ced4da;
    }
    .dataTables_filter input[type="search"] {
        width: 300px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.45rem 0.75rem 0.45rem 2.2rem; /* Left padded for a search icon if needed */
        font-size: 0.9rem;
        margin-left: 10px;
        transition: all 0.2s ease-in-out;
    }
    .dataTables_filter input[type="search"]:focus {
        border-color: var(--theme-mid-green);
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.15);
        outline: 0;
    }

    /* Refined Table Styling to complement your image rows */
    #tblcareer {
        border-collapse: separate;
        border-spacing: 0 8px; /* Creates modern floating row effect if desired */
    }
    #tblcareer th {
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaedf1;
        padding: 12px 16px;
    }
    #tblcareer td {
        background: #ffffff;
        padding: 14px 16px;
        border-top: 1px solid #f1f3f5;
        border-bottom: 1px solid #f1f3f5;
    }
    #tblcareer tr:hover td {
        background-color: #fdfefe;
    }

    /* Styling Table Content Elements */
    .link-preview {
        color: var(--theme-mid-green);
        text-decoration: none;
        font-weight: 500;
    }
    .link-preview:hover {
        color: var(--theme-dark-green);
        text-decoration: underline;
    }
    
    /* Styled Dropdown Actions */
    .btn-action-dropdown {
        border: 1px solid #ced4da;
        background: #fff;
        color: #495057;
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
    }
    .btn-action-dropdown:hover {
        background: #f8f9fa;
        border-color: #b5bbc1;
    }

    /* Micro utility for transitions */
    .transition-all { transition: all 0.2s ease; }
</style>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card card-premium">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tblcareer" class="table align-middle w-100" cellspacing="0">
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">     
    <form id="addForm" class="modal-content border-0 shadow-lg">
      <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
          <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Add New Career Entry</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
          <div class="row g-3">
              <div class="col-md-6">
                  <label for="publication" class="form-label small fw-bold text-secondary">Publication Date</label>
                  <input type="date" class="form-control shadow-sm" id="publication" name="publication" required>
              </div>
              <div class="col-md-6">
                  <label for="level" class="form-label small fw-bold text-secondary">Level</label>
                  <select class="form-select shadow-sm" id="level" name="level" required>
                      <option selected disabled value="">Select Level</option>
                      <option value="1">Level 1</option>
                      <option value="2">Level 2</option>
                  </select>
              </div>
              <div class="col-12 mt-3">
                  <label for="careerFile" class="form-label small fw-bold text-secondary">Upload Document Attachment</label>
                  <input type="file" class="form-control shadow-sm" id="careerFile" name="careerFile" accept=".pdf,.xls,.xlsx" required>
                  <div class="form-text text-muted" style="font-size: 0.75rem;">Supported types: .pdf, .xls, .xlsx</div>
              </div>
          </div>
      </div>
      <div class="modal-footer bg-light px-4 py-3">
          <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
          <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Record</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">     
    <form id="editForm" class="modal-content border-0 shadow-lg">
      <input type="hidden" id="editCareerId" name="id">
      <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
          <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Modify Career Entry</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
          <div class="row g-3">
              <div class="col-md-6">
                  <label for="editpublication" class="form-label small fw-bold text-secondary">Publication Date</label>
                  <input type="date" class="form-control shadow-sm" id="editpublication" name="editpublication" required>
              </div>
              <div class="col-md-6">
                  <label for="editlevel" class="form-label small fw-bold text-secondary">Level</label>
                  <select class="form-select shadow-sm" id="editlevel" name="editlevel" required>
                      <option selected disabled value="">Select Level</option>
                      <option value="1">Level 1</option>
                      <option value="2">Level 2</option>
                  </select>
              </div>
              <div class="col-12 mt-3">
                  <label for="editCareerFile" class="form-label small fw-bold text-secondary">Replace Document Attachment</label>
                  <input type="file" class="form-control shadow-sm" id="editCareerFile" name="editCareerFile" accept=".pdf,.xls,.xlsx">
                  <div class="form-text text-muted" style="font-size: 0.75rem;">Leave empty to keep current file attachment</div>
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