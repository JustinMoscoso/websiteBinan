<div class="pagetitle">
  <h1>Full Disclosure Policy Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Full Disclosure Policy Management</li>
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

<!-- Search Filters UI Start -->
<div class="container-fluid py-3">
    <div class="card mb-4">
        <div class="card-body">
            <form id="docSearchForm">
                <div class="row g-2 align-items-end">
                <!-- Reduced from col-lg-6 to col-lg-5 -->
                <div class="col-lg-4 col-md-12">
                        <input type="text" class="form-control" name="search" placeholder="Search File Name / Year...">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select class="form-select" name="frequency">
                            <option selected value="">- Frequency -</option>
                            <option value="ANNUAL">Annual</option>
                            <option value="QUARTERLY">Quarterly</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select class="form-select" name="file_category">
                            <option selected value="">- File Category -</option>
                            <optgroup label="Annual Reports">
                                <option value="Annual Budget Report">Annual Budget Report</option>
                                <option value="Annual Procurement Plan or Procurement List">Annual Procurement Plan or Procurement List</option>
                                <option value="Supplemental Procurement Plan">Supplemental Procurement Plan</option>
                                <option value="Annual Gender and Development Accomplishment Report">Annual Gender and Development Accomplishment Report</option>
                            </optgroup>
                            <optgroup label="Quarterly Reports">
                                <option value="Quarterly Statement of Cash Flow">Quarterly Statement of Cash Flow</option>
                                <option value="Statement of Receipts and Expenditures">Statement of Receipts and Expenditures</option>
                                <option value="20% Component of the Internal Revenue Allotment Utilization">20% Component of the Internal Revenue Allotment Utilization</option>
                                <option value="Local Disaster Risk Reduction and Management Fund Utilization">Local Disaster Risk Reduction and Management Fund Utilization</option>
                                <option value="Report of Special Education Fund Utilization">Report of Special Education Fund Utilization</option>
                                <option value="Trust Fund (PDAF) Utilization">Trust Fund (PDAF) Utilization</option>
                                <option value="Unliquidated Cash Advances">Unliquidated Cash Advances</option>
                                <option value="Bid Results on Civil Works and Goods and Services">Bid Results on Civil Works and Goods and Services</option>
                                <option value="Manpower Complement">Manpower Complement</option>
                                <option value="Annual Statement of Indebtedness, Payments and Balances">Annual Statement of Indebtedness, Payments and Balances</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select class="form-select" name="status">
                            <option selected value="">- Status -</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex gap-2">
                <button type="reset" class="btn btn-danger text-nowrap" style="height:38px; width: 60%; font-size: 14px; padding: 0 5px;">
                    Clear Filters
                </button>
                <button type="submit" class="btn btn-primary text-nowrap" id="searchBtn" style="height:38px; width: 40%; font-size: 14px; padding: 0 5px;">
                    Search
                </button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Search Filters UI End -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Policy</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblfdp" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>


<!-- New Policy Start -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="addForm" class="modal-dialog modal-dialog-centered" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Add Policy</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
          <div class="form-group">
              <label for="fileCategory" class="form-label">File category</label>
              <select class="form-control" id="fileCategory" name="fileCategory" placeholder="Enter file name" required>
                <optgroup label="Annual Reports">
                    <option value="Annual Budget Report">Annual Budget Report</option>
                    <option value="Annual Procurement Plan or Procurement List">Annual Procurement Plan or Procurement List</option>
                    <option value="Supplemental Procurement Plan">Supplemental Procurement Plan</option>
                    <option value="Annual Gender and Development Accomplishment Report">Annual Gender and Development Accomplishment Report</option>
                </optgroup>
                <optgroup label="Quarterly Reports">
                    <option value="Quarterly Statement of Cash Flow">Quarterly Statement of Cash Flow</option>
                    <option value="Statement of Receipts and Expenditures">Statement of Receipts and Expenditures</option>
                    <option value="20% Component of the Internal Revenue Allotment Utilization">20% Component of the Internal Revenue Allotment Utilization</option>
                    <option value="Local Disaster Risk Reduction and Management Fund Utilization">Local Disaster Risk Reduction and Management Fund Utilization</option>
                    <option value="Report of Special Education Fund Utilization">Report of Special Education Fund Utilization</option>
                    <option value="Trust Fund (PDAF) Utilization">Trust Fund (PDAF) Utilization</option>
                    <option value="Unliquidated Cash Advances">Unliquidated Cash Advances</option>
                    <option value="Bid Results on Civil Works and Goods and Services">Bid Results on Civil Works and Goods and Services</option>
                    <option value="Manpower Complement">Manpower Complement</option>
                    <option value="Annual Statement of Indebtedness, Payments and Balances">Annual Statement of Indebtedness, Payments and Balances</option>
                </optgroup>
              </select>
          </div>
          <div class="form-group">
              <label for="yr" class="form-label">Year</label>
              <input type="text" value="" class="yearpicker form-control" id="yr" name="yr" placeholder="Enter Year" required>
          </div>
          <div class="form-group">
              <label for="qtr" class="form-label">Quarter</label>
              <select class="form-select" id="qtr" name="qtr" required>
                <option value="" selected disabled>Choose Quarter</option>  
                <option value="First">First Quarter</option>
                <option value="Second">Second Quarter</option>
                <option value="Third">Third Quarter</option>
                <option value="Fourth">Fourth Quarter</option>
              </select>
          </div>
          <div class="form-group">
              <label for="policyFile" class="form-label">Upload file</label>
              <input type="file" class="form-control" id="policyFile" name="policyFile" accept=".pdf,.xls,.xlsx" required>
          </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="btnAdd" type="button" class="btnsave btn-success">Save</button>
      </div>
    </div>
  </form>
</div> <!-- New Policy End -->

<!-- Edit Policy Start -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="editForm" class="modal-dialog modal-dialog-centered" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Edit Policy</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
        <input type="hidden" id="editPolicyId" name="id">
          <div class="form-group">
              <label for="editFileCategory" class="form-label">File category</label>
              <select class="form-control" id="editFileCategory" name="editFileCategory" placeholder="Enter file name" required>
                <optgroup label="Annual Reports">
                    <option value="Annual Budget Report">Annual Budget Report</option>
                    <option value="Annual Procurement Plan or Procurement List">Annual Procurement Plan or Procurement List</option>
                    <option value="Supplemental Procurement Plan">Supplemental Procurement Plan</option>
                    <option value="Annual Gender and Development Accomplishment Report">Annual Gender and Development Accomplishment Report</option>
                </optgroup>
                <optgroup label="Quarterly Reports">
                    <option value="Quarterly Statement of Cash Flow">Quarterly Statement of Cash Flow</option>
                    <option value="Statement of Receipts and Expenditures">Statement of Receipts and Expenditures</option>
                    <option value="20% Component of the Internal Revenue Allotment Utilization">20% Component of the Internal Revenue Allotment Utilization</option>
                    <option value="Local Disaster Risk Reduction and Management Fund Utilization">Local Disaster Risk Reduction and Management Fund Utilization</option>
                    <option value="Report of Special Education Fund Utilization">Report of Special Education Fund Utilization</option>
                    <option value="Trust Fund (PDAF) Utilization">Trust Fund (PDAF) Utilization</option>
                    <option value="Unliquidated Cash Advances">Unliquidated Cash Advances</option>
                    <option value="Bid Results on Civil Works and Goods and Services">Bid Results on Civil Works and Goods and Services</option>
                    <option value="Manpower Complement">Manpower Complement</option>
                    <option value="Annual Statement of Indebtedness, Payments and Balances">Annual Statement of Indebtedness, Payments and Balances</option>
                </optgroup>
              </select>
          </div>
          <div class="form-group">
              <label for="edityr" class="form-label">Year</label>
              <input type="text" value="" class="yearpicker form-control" id="edityr" name="edityr" placeholder="Enter Year" required>
          </div>
          <div class="form-group">
              <label for="qtr" class="form-label">Quarter</label>
              <select class="form-select" id="editqtr" name="editqtr" required>
                <option value="" selected disabled>Choose Quarter</option>  
                <option value="First">First Quarter</option>
                <option value="Second">Second Quarter</option>
                <option value="Third">Third Quarter</option>
                <option value="Fourth">Fourth Quarter</option>
              </select>
          </div>
          <div class="form-group">
              <label for="editpolicyFile" class="form-label">Upload file</label>
              <input type="file" class="form-control" id="editpolicyFile" name="editpolicyFile" accept=".pdf,.xls,.xlsx" required>
          </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="btnEdit" type="button" class="btnsave btn-success">Update</button>
      </div>
    </div>
  </form>
</div> <!-- Edit Policy End -->

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="height:80vh;">
        <iframe id="filePreviewFrame" src="" style="width:100%;height:100%;border:none;"></iframe>
      </div>
    </div>
  </div>
</div>