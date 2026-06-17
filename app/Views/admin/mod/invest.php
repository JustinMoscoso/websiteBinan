<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
  <div>
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Invest Management</h1>

  </div>
</div>


<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
  <div class="card card-premium mb-4 border-start border-4"
    style="border-start-color: var(--theme-mid-green) !important;">

    <div class="card-body p-4">
      <form id="investSearchForm">
        <div class="row g-3 align-items-end">

          <div class="col-xl-4 col-lg-4 col-md-12">
            <label class="form-label small fw-bold text-secondary">Search Keyword</label>
            <div class="input-group">

              <input type="text" class="form-control border-start-0" name="search"
                placeholder="Search Category / File Name">
            </div>
          </div>

          <div class="col-xl-2 col-lg-2 col-md-6">
            <label class="form-label small fw-bold text-secondary">Category</label>
            <select class="form-control form-select bg-light border-secondary-subtle" name="category"
              style="height: 38px; cursor: pointer;">
              <option selected value="">All Categories</option>
              <option value="Local Revenue Code">Local Revenue Code</option>
              <option value="Local Investment and Incentive Code">Local Investment and Incentive Code</option>
              <option value="Market Value">Market Value</option>
              <option value="Cost of Doing Business">Cost of Doing Business</option>
              <option value="Investment Opportunities and Priorities">Investment Opportunities and Priorities</option>
              <option value="Business Directory">Business Directory</option>
              <option value="Safety Seal Certification">Safety Seal Certification</option>
            </select>
          </div>

          <div class="col-xl-2 col-lg-2 col-md-6">
            <label class="form-label small fw-bold text-secondary">Status</label>
            <select class="form-control form-select bg-light border-secondary-subtle" name="status"
              style="height: 38px; cursor: pointer;">
              <option selected value="">All Status</option>
              <option value="ACTIVE">Active</option>
              <option value="INACTIVE">Inactive</option>
              <option value="ARCHIVED">Archived</option>
            </select>
          </div>

          <div class="col-xl-4 col-lg-4 col-md-12">
            <div class="row g-2 admin-filter-actions">

              <div class="col-12 col-md-4">
                <button type="submit" class="btn btn-primary w-100 flex-grow-1 fw-semibold shadow-sm" id="investSearchBtn"
                  style="height: 38px;">
                  Search
                </button>
              </div>
              <div class="col-12 col-md-4">
                <button type="reset" class="btn btn-danger w-100 flex-grow-1 fw-semibold" style="height: 38px;">
                  Clear
                </button>
              </div>
              <div class="col-12 col-md-4">
                <button type="button" class="btn btn-success w-100 shadow-sm fw-semibold text-nowrap flex-grow-1"
                  data-bs-toggle="modal" data-bs-target="#addModal" style="height: 38px;">
                  <i class="bi bi-plus-circle me-1"></i>Add Record
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
      <div class="card shadow mb-4 border-top border-4" style="border-top-color: var(--theme-mid-green) !important;">
      
        <div class="card-body">
          <div class="table-responsive">
            <table id="tblinvest" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
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

        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <div class="row g-3">

          <div class="col-12">
            <label for="fileCategory" class="form-label small fw-bold text-secondary">File Category <span
                class="text-danger">*</span></label>
            <select class="form-control form-select shadow-sm invest-select-menu" id="fileCategory" name="fileCategory"
              required>
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
        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
        <button id="btnAdd" type="submit" class="btn btn-success px-4">Save</button>
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
            <select class="form-control form-select shadow-sm invest-select-menu" id="editFileCategory"
              name="editFileCategory" required>
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