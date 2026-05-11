<div class="pagetitle">
  <h1>Barangay Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Barangay Management</li>
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
<?php if ($user->user_lvl !== 'VIEWER'): ?>
 <!-- Search Filters UI Start -->
<div class="container-fluid py-3">
    <div class="card mb-4">
        <div class="card-body">
            <form id="barangaySearchForm">
                <div class="row g-2 align-items-end">
                <!-- Reduced from col-lg-6 to col-lg-5 -->
                    <div class="col-lg-8 col-md-12">
                        <input type="text" class="form-control" id="searchBrgy" placeholder="Search Barangay / Captain...">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <select class="form-select" name="status">
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
<?php endif; ?>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Barangay</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblbrgy" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>



<!-- Add Barangay Start -->
<div class="modal fade modal-xl" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-dialog-centered" role="document">      
        <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Create New Barangay</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body" style="padding: 2rem;">
                
                <!-- Row 1: Barangay and Captain -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="form-group">
                            <label for="txtBrgy" class="form-label">Barangay Name</label>
                            <input type="text" class="form-control py-2" id="txtBrgy" name="txtBrgy" placeholder="Enter barangay name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txtCapt" class="form-label">Barangay Captain</label>
                            <input type="text" class="form-control py-2" id="txtCapt" name="txtCapt" placeholder="Enter full name" required>
                        </div>
                    </div>
                </div>
                
                <!-- About Section -->
                <div class="form-group mb-4">
                    <label class="form-label mb-2">About</label>
                    <div id="createabout" style="height: 250px; margin-bottom: 1.5rem;"></div>
                    <input type="hidden" id="createAbout" name="createAbout">
                </div>
                
                <!-- Row 2: Mission and Vision -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="form-group">
                            <label for="txtMission" class="form-label">Mission</label>
                            <div id="txtMission" style="height: 200px; margin-bottom: 1.5rem;"></div>
                            <input type="hidden" name="txtMission">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txtVision" class="form-label">Vision</label>
                            <div id="txtVision" style="height: 200px; margin-bottom: 1.5rem;"></div>
                            <input type="hidden" name="txtVision">
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="txtContact" class="form-label">Contact Information</label>
                            <div id="txtContact" style="height: 150px; margin-bottom: 1.5rem;"></div>
                            <input type="hidden" name="txtContact">
                        </div>
                    </div>
                </div>
                
                <!-- Barangay Staff Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="txtStaff" class="form-label">Barangay Staff</label>
                            <div id="txtStaff" style="height: 200px; margin-bottom: 1.5rem;"></div>
                            <input type="hidden" name="txtStaff">
                        </div>
                    </div>
                </div>
                
                <!-- Row 3: Image Uploads -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="brgyImg" class="form-label">Barangay Logo</label>
                            <input type="file" class="form-control py-2" id="brgyImg" name="brgyImg" accept="image/*" required>
                            <div id="addBrgyLogoPreview" class="mt-2"></div>
                        </div>
                    </div>
                    <!-- Captain Image - Commented out as not needed
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="brgyImgCapt" class="form-label">Captain Image</label>
                            <input type="file" class="form-control py-2" id="brgyImgCapt" name="brgyImgCapt" accept="image/*" required>
                            <div id="addBrgyCaptPreview" class="mt-2"></div>
                        </div>
                    </div>
                    -->
                </div>
                
            </div> <!-- End Modal Body -->
            
            <!-- Modal Footer -->
            <div class="modal-footer" style="padding: 1rem 2rem;">
                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Close</button>
                <button id="btnAdd" type="button" class="btnsave btn-success px-4 py-2">Save</button>
            </div>
            
        </div>
    </form>
</div> <!-- Add Barangay End -->


<!-- Edit Barangay Start -->
<div class="modal fade modal-xl" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="editForm" class="modal-dialog modal-dialog-centered" role="document">      
      <div class="modal-content">
        
          <!-- Modal header Start -->
          <div class="modal-header modal-header-bg">
              <h5 class="modal-title">Edit Barangay</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div> <!-- Modal header End -->
          
          <!-- Modal body Start -->
          <div class="modal-body" style="padding: 2rem;">
            <input type="hidden" id="editBrgyId" name="id">
            
            <!-- First Row - Barangay and Captain -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="form-group">
                        <label for="editBrgy" class="form-label mb-2">Barangay</label>
                        <input type="text" class="form-control py-2" id="editBrgy" name="editBrgy" placeholder="Enter barangay name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editCapt" class="form-label mb-2">Barangay Captain</label>
                        <input type="text" class="form-control py-2" id="editCapt" name="editCapt" placeholder="Enter full name" required>
                    </div>
                </div>
            </div>

            <!-- About Section (Keep Existing) -->
            <div class="form-group mb-4">
                <label for="editabout" class="form-label mb-2">About</label>
                <div id="editabout" style="height: 250px; margin-bottom: 1.5rem;"></div>
                <input type="hidden" id="editAbout" name="editAbout">
            </div>

            <!-- Mission & Vision (Now Quill Editors, Same IDs) -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="form-group">
                        <label for="editMission" class="form-label mb-2">Mission</label>
                        <div id="editMission" style="height: 200px; margin-bottom: 1.5rem;"></div>
                        <input type="hidden" name="editMission"> <!-- Hidden input for form submission -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="editVision" class="form-label mb-2">Vision</label>
                        <div id="editVision" style="height: 200px; margin-bottom: 1.5rem;"></div>
                        <input type="hidden" name="editVision"> <!-- Hidden input for form submission -->
                    </div>
                </div>
            </div>

            <!-- Contact Information (Now Quill Editor, Same ID) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-group">
                        <label for="editContact" class="form-label mb-2">Contact Information</label>
                        <div id="editContact" style="height: 150px; margin-bottom: 1.5rem;"></div>
                        <input type="hidden" name="editContact"> <!-- Hidden input for form submission -->
                    </div>
                </div>
            </div>
            
            <!-- Barangay Staff Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-group">
                        <label for="editStaff" class="form-label mb-2">Barangay Staff</label>
                        <div id="editStaff" style="height: 200px; margin-bottom: 1.5rem;"></div>
                        <input type="hidden" name="editStaff"> <!-- Hidden input for form submission -->
                    </div>
                </div>
            </div>
            
            <!-- Third Row - Images -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="mb-3">
                        <label for="editbrgyImg" class="form-label mb-2">Barangay Logo</label>
                        <input type="file" class="form-control py-2" id="editbrgyImg" name="editbrgyImg" accept="image/*">
                        <div id="editBrgyLogoPreview" class="mt-2"></div>
                    </div>
                </div>
                <!-- Captain Image - Commented out as not needed
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="editbrgyImgCapt" class="form-label mb-2">Captain Image</label>
                        <input type="file" class="form-control py-2" id="editbrgyImgCapt" name="editbrgyImgCapt" accept="image/*">
                        <div id="editBrgyCaptPreview" class="mt-2"></div>
                    </div>
                </div>
                -->
            </div>
          </div> <!-- Modal body End -->
          
          <!-- Modal Footer -->
          <div class="modal-footer" style="padding: 1rem 2rem;">
              <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Close</button>
              <button id="btnEdit" type="button" class="btnsave btn-success px-4 py-2">Update</button>
          </div>
      </div>
    </form>
</div> <!-- Edit Barangay End -->
