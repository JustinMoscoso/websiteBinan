<div class="pagetitle">
  <h1>Service Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Service Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<?php if ($user->user_lvl !== 'VIEWER'): ?>
<!-- Search Filters UI Start -->
<div class="container-fluid py-3">
    <div class="card mb-4">
        <div class="card-body">
            <form id="serviceSearchForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="service_name" id="service_name" placeholder="Service Name">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="category" id="searchCategory">
                            <option selected value="">- Category -</option>
                            <option value="BARANGAY">Barangay</option>
                            <option value="DEPARTMENT">Department</option>
                        </select>
                    </div>
                    <div class="col-md-2" id="searchBrgyGroup" style="display: none;">
                        <select class="form-control" name="brgy" id="searchBrgy">
                            <option value="">- Select Barangay -</option>
                        </select>
                    </div>
                    <div class="col-md-2" id="searchDeptGroup" style="display: none;">
                        <select class="form-control" name="dept" id="searchDept">
                            <option value="">- Select Department -</option>
                        </select>
                    </div>
                    <div class="col-md-2" id="searchDefaultGroup">
                        <select class="form-control" disabled>
                            <option value="">- Select -</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <select class="form-select" name="status" id="status">
                            <option selected value="">- Status -</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="reset" class="btn btn-danger w-100">Clear Filters</button>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Search Filters UI End -->
<?php endif; ?>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Service</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblservice" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>



<!-- New Services Start -->
<div class="modal fade modal-lg" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-dialog-centered" role="document">      
      <div class="modal-content">
        
          <!-- Modal header Start -->
          <div class="modal-header modal-header-bg">
              <h5 class="modal-title">Add Services</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div> <!-- Modal header End -->
          
          <!-- Modal body Start -->
          <div class="modal-body">
            <div class="form-group row">
                <!-- Category and Service Name in one row -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" id="category" name="category" placeholder="Choose a category" required>
                                <option value="" selected disabled>Choose a category</option>
                                <option value="BRGY">Barangay</option>
                                <option value="DEPT">Department</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="serviceName" class="form-label">Service Name</label>
                            <input type="text" class="form-control" id="serviceName" name="serviceName" placeholder="Enter service name" required>
                        </div>
                    </div>
                </div>
                <!-- Department/Barangay Selection -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group" id="deptGroup" style="display: none;">
                            <label for="txtDept" class="form-label">Department</label>
                            <select id="txtDept" name="txtDept" class="form-select" placeholder="Choose a department" required>
                                <option selected disabled>Choose a department</option>
                                <!-- Get depts -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" id="brgyGroup" style="display: none;">
                            <label for="txtBrgy" class="form-label">Barangay</label>
                            <select id="txtBrgy" name="txtBrgy" class="form-select" placeholder="Choose a barangay" required>
                                <option selected disabled>Choose a barangay</option>
                                <!-- Get barangay -->
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Content Field in Add Modal -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="quillContent" class="form-label">Content</label>
                            <div id="quillContent" style="height: 120px;"></div>
                            <input type="hidden" id="content" name="content" required>
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
</div> <!-- New Services End -->


<!-- Edit Services Start -->
<div class="modal fade modal-lg" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="editForm" class="modal-dialog modal-dialog-centered" role="document">  
        <div class="modal-content">
            <!-- Modal header Start -->
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Edit Services</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- Modal header End -->
            
            <!-- Modal body Start -->
            <div class="modal-body">
                <input type="hidden" id="editId" name="id">

                <div class="form-group">
                    <label for="editcategory" class="form-label">Category</label>
                    <select class="form-control" id="editcategory" name="editcategory" placeholder="Choose a category" required>
                        <option value="" selected disabled>Choose a category</option>
                        <option value="BRGY">Barangay</option>
                        <option value="DEPT">Department</option>
                    </select>
                </div>

                <div class="form-group" id="editdeptGroup" style="display: none;">
                    <label for="editDept" class="form-label">Department</label>
                    <select id="editDept" name="editDept" placeholder="Choose a department" required>
                        <!-- Get depts -->
                    </select>
                </div>

                <div class="form-group" id="editbrgyGroup" style="display: none;">
                    <label for="editBrgy" class="form-label">Barangay</label>
                    <select id="editBrgy" name="editBrgy" placeholder="Choose a barangay" required>
                        <!-- Get barangay -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="editServiceName" class="form-label">Service name</label>
                    <input type="text" class="form-control" id="editServiceName" name="editServiceName" placeholder="Enter service name" required>
                </div>
                <!-- Content Field in Edit Modal -->
                <div class="form-group">
                    <label for="editQuillContent" class="form-label">Content</label>
                    <div id="editQuillContent" style="height: 120px;"></div>
                    <input type="hidden" id="editContent" name="editContent" required>
                </div>
            </div> <!-- Modal body End -->

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="btnEdit" type="button" class="btnsave btn-success">Update</button>
            </div>
        </div>
    </form>
</div> <!-- Edit Services End -->

<!-- Add Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Add Quill JS before closing body -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>