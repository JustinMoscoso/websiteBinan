<div class="pagetitle">
  <h1>Department Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Department Management</li>
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
            <form id="departmentSearchForm">
                <div class="row g-2 align-items-end">
                <!-- Reduced from col-lg-6 to col-lg-5 -->
                    <div class="col-lg-8 col-md-12">
                        <input type="text" class="form-control" id="searchDept" placeholder="Search Department / Officer...">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select class="form-select" name="deptStatus">
                            <option selected value="">- Status -</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
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
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Department</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tbldept" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>


<!-- New Department Start -->
<div class="modal fade modal-lg" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="addForm" class="modal-dialog modal-dialog-centered" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Add Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
          <div class="form-group row">
              <!-- Department and Officer in Charge in one row -->
              <div class="row mb-3">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="txtDept" class="form-label">Department</label>
                          <input type="text" class="form-control" id="txtDept" name="txtDept" placeholder="Enter department name" required>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="txtHead" class="form-label">Officer in Charge</label>
                          <input type="text" class="form-control" id="txtHead" name="txtHead" placeholder="Enter full name" required>
                      </div>
                  </div>
              </div>
              <!-- About Field in Add Modal -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="quillAbout" class="form-label">About</label>
                          <div id="quillAbout" style="height: 120px;"></div>
                          <input type="hidden" id="txtAbout" name="txtAbout">
                      </div>
                  </div>
              </div>
              <!-- Contact Information Field in Add Modal (Quill) -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="quillContact" class="form-label">Contact Information</label>
                          <div id="quillContact" style="height: 120px;"></div>
                          <input type="hidden" id="txtContact" name="txtContact">
                      </div>
                  </div>
              </div>
              <!-- Mission Field in Add Modal -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="quillMission" class="form-label">Mission</label>
                          <div id="quillMission" style="height: 120px;"></div>
                          <input type="hidden" id="txtMission" name="txtMission" required>
                      </div>
                  </div>
              </div>
              <!-- Vision Field in Add Modal -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="quillVision" class="form-label">Vision</label>
                          <div id="quillVision" style="height: 120px;"></div>
                          <input type="hidden" id="txtVision" name="txtVision" required>
                      </div>
                  </div>
              </div>
              <!-- Quality Policy Field in Add Modal -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="quillPolicy" class="form-label">Quality Policy</label>
                          <div id="quillPolicy" style="height: 120px;"></div>
                          <input type="hidden" id="txtPolicy" name="txtPolicy" required>
                      </div>
                  </div>
              </div>
              <!-- Department Logo -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="deptImg" class="form-label">Department Logo</label>
                          <input type="file" class="form-control" id="deptImg" name="deptImg" accept="image/*">
                          <div id="addDeptLogoPreview" class="mt-2"></div>
                      </div>
                  </div>
              </div>
              
              <!-- Organizational Chart -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="deptOrgChart" class="form-label">Organizational Chart</label>
                          <input type="file" class="form-control" id="deptOrgChart" name="deptOrgChart" accept="image/*">
                          <div id="addDeptOrgChartPreview" class="mt-2"></div>
                      </div>
                  </div>
              </div>
          </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="btnAdd" type="button" class="btnsave btn-success">Save</button>
      </div>
    </div>
  </form>
</div> <!-- New Department End -->

<!-- Edit Department Start -->
<div class="modal fade modal-lg" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="editForm" class="modal-dialog modal-dialog-centered" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Edit Department</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
        <input type="hidden" id="editDeptId" name="id">
          <div class="form-group">
              <label for="editDept" class="form-label">Department</label>
              <input type="text" class="form-control" id="editDept" name="editDept" placeholder="Enter department name" required>
          </div>

          <div class="form-group">
              <label for="editHead" class="form-label">Officer in Charge</label>
              <input type="text" class="form-control" id="editHead" name="editHead" placeholder="Enter full name" required>
          </div>
<!--
          <div class="form-group">
              <label for="editTitle" class="form-label">Post Title</label>
              <input type="text" class="form-control" id="editTitle" name="editTitle" placeholder="Enter title" required>
          </div>
-->
          <!-- About Field in Edit Modal -->
          <div class="form-group">
              <label for="editQuillAbout" class="form-label">About</label>
              <div id="editQuillAbout" style="height: 120px;"></div>
              <input type="hidden" id="editAbout" name="editAbout">
          </div>
          <!-- Contact Information Field in Edit Modal (Quill) -->
          <div class="form-group">
              <label for="editQuillContact" class="form-label">Contact Information</label>
              <div id="editQuillContact" style="height: 120px;"></div>
              <input type="hidden" id="editContact" name="editContact">
          </div>
          <!-- Mission Field in Edit Modal -->
          <div class="form-group">
              <label for="editQuillMission" class="form-label">Mission</label>
              <div id="editQuillMission" style="height: 120px;"></div>
              <input type="hidden" id="editMission" name="editMission" required>
          </div>

          <!-- Vision Field in Edit Modal -->
          <div class="form-group">
              <label for="editQuillVision" class="form-label">Vision</label>
              <div id="editQuillVision" style="height: 120px;"></div>
              <input type="hidden" id="editVision" name="editVision" required>
          </div>
          
          <!-- Quality Policy Field in Edit Modal -->
          <div class="form-group">
              <label for="editQuillPolicy" class="form-label">Quality Policy</label>
              <div id="editQuillPolicy" style="height: 120px;"></div>
              <input type="hidden" id="editPolicy" name="editPolicy" required>
          </div>
          <div class="mb-3">
              <label for="editdeptImg" class="form-label">Department Logo</label>
              <input type="file" class="form-control" id="editdeptImg" name="editdeptImg" accept="image/*">
              <div id="editDeptLogoPreview" class="mt-2"></div>
          </div>
          
          <div class="mb-3">
              <label for="editdeptOrgChart" class="form-label">Organizational Chart</label>
              <input type="file" class="form-control" id="editdeptOrgChart" name="editdeptOrgChart" accept="image/*">
              <div id="editDeptOrgChartPreview" class="mt-2"></div>
          </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="btnEdit" type="button" class="btnsave btn-success">Update</button>
      </div>
    </div>
  </form>
</div> <!-- Edit Department End -->

<!-- Add Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Add Quill JS before closing body -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
