<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
  <div>
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Invest Management</h1>
    <nav>
      <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>"
            class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Invest Management</li>
      </ol>
    </nav>
  </div>
  <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal"
    data-bs-target="#addModal">
    <i class="bi bi-plus-circle me-2"></i>Add Content
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

  /* Standard Base Containers Elements */
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
  #tblinvest th {
    color: #555;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #eaedf1;
    padding: 12px 16px;
  }

  #tblinvest td {
    padding: 14px 16px;
    vertical-align: middle;
  }

  /* Prevent text compression issues across long category dropdown entries */
  .invest-select-menu option {
    white-space: normal;
    padding: 6px;
  }

  .transition-all {
    transition: all 0.2s ease;
  }
</style>

<section class="section">
  <div class="row">
    <div class="col-12">
      <div class="card card-premium">
        <div class="card-body p-4">
          <div class="table-responsive">
            <table id="tblinvest" class="table table-striped table-hover align-middle w-100" cellspacing="0">
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
        <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
          <i class="bi bi-plus-circle me-2"></i>Add Investment Content
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <div class="row g-3">

          <div class="col-12">
            <label for="fileCategory" class="form-label small fw-bold text-secondary">File Category <span
                class="text-danger">*</span></label>
            <select class="form-select shadow-sm invest-select-menu" id="fileCategory" name="fileCategory" required>
              <option value="" selected disabled>Select classifications...</option>
              <option value="Local Revenue Code">Local Revenue Code</option>
              <option value="Local Investment and Incentive Code">Local Investment and Incentive Code</option>
              <option value="Market Value">Market Value</option>
              <option value="Cost of Doing Business">Cost of Doing Business</option>
              <option value="Investment Opportunities and Priorities">Investment Opportunities and Priorities</option>
              <option value="Business Directory">Business Directory</option>
              <option value="Safety Seal Certification">Safety Seal Certification</option>
            </select>
          </div>

          <div class="col-12">
            <label for="investFile" class="form-label small fw-bold text-secondary">Upload File Attachment <span
                class="text-danger">*</span></label>
            <input type="file" class="form-control shadow-sm" id="investFile" name="investFile" accept=".pdf,.xls,.xlsx"
              required>
            <div class="form-text text-muted small mt-1">Accepted formats: PDF, XLS, XLSX</div>
          </div>

        </div>
      </div>

      <div class="modal-footer bg-light px-4 py-3">
        <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
        <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Content</button>
      </div>

    </form>
  </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="editForm" class="modal-content border-0 shadow-lg">
      <input type="hidden" id="editInvestId" name="id">

      <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
        <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
          <i class="bi bi-pencil-square me-2"></i>Modify Investment Content
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <div class="row g-3">

          <div class="col-12">
            <label for="editFileCategory" class="form-label small fw-bold text-secondary">File Category <span
                class="text-danger">*</span></label>
            <select class="form-select shadow-sm invest-select-menu" id="editFileCategory" name="editFileCategory"
              required>
              <option value="" disabled>Select classifications...</option>
              <option value="Local Revenue Code">Local Revenue Code</option>
              <option value="Local Investment and Incentive Code">Local Investment and Incentive Code</option>
              <option value="Market Value">Market Value</option>
              <option value="Cost of Doing Business">Cost of Doing Business</option>
              <option value="Investment Opportunities and Priorities">Investment Opportunities and Priorities</option>
              <option value="Business Directory">Business Directory</option>
              <option value="Safety Seal Certification">Safety Seal Certification</option>
            </select>
          </div>

          <div class="col-12">
            <label for="editInvestFile" class="form-label small fw-bold text-secondary">Replace File Attachment</label>
            <input type="file" class="form-control shadow-sm" id="editInvestFile" name="editInvestFile"
              accept=".pdf,.xls,.xlsx">
            <div class="form-text text-muted small mt-1">Leave blank to retain current file. Formats: PDF, XLS, XLSX
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer bg-light px-4 py-3">
        <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
        <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Content</button>
      </div>

    </form>
  </div>
</div>