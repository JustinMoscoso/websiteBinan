<div class="pagetitle">
  <h1>Invest Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Invest Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<style>
    /* Standout styling for the DataTables search box */
    .dataTables_filter input[type="search"] {
        width: 350px !important;
        border: 2px solid #388e3c !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 1rem !important;
        margin-left: 10px !important;
    }
</style>
<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Content</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblinvest" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>


<!-- New Invest Start -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="addForm" class="modal-dialog modal-dialog-centered" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Add Content</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
        <div class="form-group">
          <label for="fileCategory" class="form-label">File category</label>
          <select class="form-control" id="fileCategory" name="fileCategory" placeholder="Enter file name" required>
            <option value="Local Revenue Code">Local Revenue Code</option>
            <option value="Local Investment and Incentive Code">Local Investment and Incentive Code</option>
            <option value="Market Value">Market Value</option>
            <option value="Cost of Doing Business">Cost of Doing Business</option>
            <option value="Investment Opportunities and Priorities">Investment Opportunities and Priorities</option>
            <option value="Business Directory">Business Directory</option>
            <option value="Safety Seal Certification">Safety Seal Certification</option>
          </select>
        </div>
        <div class="form-group">
            <label for="investFile" class="form-label">Upload file</label>
            <input type="file" class="form-control" id="investFile" name="investFile" accept=".pdf,.xls,.xlsx" required>
        </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="btnAdd" type="button" class="btnsave btn-success">Save</button>
      </div>
    </div>
  </form>
</div> <!-- New Invest End -->

<!-- Edit Invest Start -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="editForm" class="modal-dialog modal-dialog-centered" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Edit Content</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
      <input type="hidden" id="editInvestId" name="id">
        <div class="form-group">
            <label for="editFileCategory" class="form-label">File category</label>
            <select class="form-control" id="editFileCategory" name="editFileCategory" placeholder="Enter file name" required>
              <option value="Local Revenue Code">Local Revenue Code</option>
              <option value="Local Investment and Incentive Code">Local Investment and Incentive Code</option>
              <option value="Market Value">Market Value</option>
              <option value="Cost of Doing Business">Cost of Doing Business</option>
              <option value="Investment Opportunities and Priorities">Investment Opportunities and Priorities</option>
              <option value="Business Directory">Business Directory</option>
              <option value="Safety Seal Certification">Safety Seal Certification</option>
            </select>
        </div>
        <div class="form-group">
            <label for="editInvestFile" class="form-label">Upload file</label>
            <input type="file" class="form-control" id="editInvestFile" name="editInvestFile" accept=".pdf,.xls,.xlsx">
        </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="btnEdit" type="button" class="btnsave btn-success">Update</button>
      </div>
    </div>
  </form>
</div> <!-- Edit Invest End -->