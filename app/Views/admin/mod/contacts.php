<div class="pagetitle">
  <h1>Contacts Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Contacts Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- Search Filters UI Start -->
<div class="container-fluid py-3">
    <div class="card mb-4">
        <div class="card-body">
            <form id="contactSearchForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchContact" placeholder="Search Contact / Office...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="contactCategory">
                            <option selected value="">- Category -</option>
                            <option value="BRGY">Barangay</option>
                            <option value="DEPT">Department</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="contactStatus">
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
                        <button type="button" class="btn btn-primary w-100" id="searchContactBtn">Search</button>
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
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Contact</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblhotlines" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>



<!-- New Contact Start -->
<div class="modal fade modal-lg" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-dialog-centered" role="document">      
      <div class="modal-content">
        
          <!-- Modal header Start -->
          <div class="modal-header modal-header-bg">
              <h5 class="modal-title">Add Contact</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div> <!-- Modal header End -->
          
          <!-- Modal body Start -->
          <div class="modal-body">
            <div class="form-group">
                <label for="category" class="form-label">Section</label>
                <select class="form-control" id="category" name="category" placeholder="Choose a section" required>
                    <option value="" selected disabled>Choose a section</option>
                    <option value="BRGY">Barangay</option>
                    <option value="DEPT">Department</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <div class="form-group" id="deptGroup" style="display: none;">
                <label for="txtDept" class="form-label">Department</label>
                <select id="txtDept" name="txtDept" placeholder="Choose a department" required>
                    <!-- Get depts -->
                </select>
            </div>

            <div class="form-group" id="brgyGroup" style="display: none;">
                <label for="txtBrgy" class="form-label">Barangay</label>
                <select id="txtBrgy" name="txtBrgy" placeholder="Choose a barangay" required>
                    <!-- Get barangay -->
                </select>
            </div>

            <div class="form-group" id="othersGrp" style="display: none;">
                <label for="txtOthers" class="form-label">Office</label>
                <input type="text" id="txtOthers" name="txtOthers" class="form-control" placeholder="Enter custom office" required>
            </div>

            <!-- Contact Numbers in organized rows -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact" class="form-label">PLDT</label>
                        <input type="text" class="form-control" id="contact" name="contact" placeholder="XXX-XXXX or -" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="smart" class="form-label">SMART</label>
                        <input type="text" class="form-control" id="smart" name="smart" placeholder="XXXX-XXX-XXXX or -" required>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="globe" class="form-label">GLOBE</label>
                        <input type="text" class="form-control" id="globe" name="globe" placeholder="XXXX-XXX-XXXX or -" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telco" class="form-label">INTELCO</label>
                        <input type="text" class="form-control" id="telco" name="telco" placeholder="XXX-XXXX or -" required>
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
</div> <!-- New Contact End -->

<!-- Edit Contact Start -->
<div class="modal fade modal-lg" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="editForm" class="modal-dialog modal-dialog-centered" role="document">  
        <div class="modal-content">
            <!-- Modal header Start -->
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Edit Contact</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- Modal header End -->
            
            <!-- Modal body Start -->
            <div class="modal-body">
                <input type="hidden" id="editId" name="id">

                <div class="form-group">
                    <label for="editcategory" class="form-label">Section</label>
                    <select class="form-control" id="editcategory" name="editcategory" placeholder="Choose a section" required>
                        <option value="" selected disabled>Choose a section</option>
                        <option value="BRGY">Barangay</option>
                        <option value="DEPT">Department</option>
                        <option value="Others">Others</option>
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

                <div class="form-group" id="editothersGrp" style="display: none;">
                    <label for="editOthers" class="form-label">Office</label>
                    <input type="text" id="editOthers" name="editOthers" class="form-control" placeholder="Enter custom office" required>
                </div>

                <!-- Contact Numbers in organized rows -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editContact" class="form-label">PLDT</label>
                            <input type="text" class="form-control" id="editContact" name="editContact" placeholder="XXX-XXXX or -" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editSmart" class="form-label">SMART</label>
                            <input type="text" class="form-control" id="editSmart" name="editSmart" placeholder="XXXX-XXX-XXXX or -" required>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editGlobe" class="form-label">GLOBE</label>
                            <input type="text" class="form-control" id="editGlobe" name="editGlobe" placeholder="XXXX-XXX-XXXX or -" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="editTelco" class="form-label">INTELCO</label>
                    <input type="text" class="form-control" id="editTelco" name="editTelco" placeholder="XXX-XXXX or -" required>
                </div>
            </div> <!-- Modal body End -->

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="btnEdit" type="button" class="btnsave btn-success">Update</button>
            </div>
        </div>
    </form>
</div> <!-- Edit Contact End -->