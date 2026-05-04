<div class="pagetitle">
  <h1>Career Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Career Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Career</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblcareer" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>


<!-- New Career Start -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="addForm" class="modal-dialog modal-dialog-centered" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Add Career</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
          <div class="form-group row">
              <!-- Publication Date and Level in one row -->
              <div class="row mb-3">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="publication" class="form-label">Publication Date</label>
                          <input type="date" class="form-control" id="publication" name="publication" placeholder="Enter Publication Date" required>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="level" class="form-label">Level</label>
                          <select class="form-control" id="level" name="level" required>
                              <option selected disabled>Select Level</option>
                              <option value="1">Level 1</option>
                              <option value="2">Level 2</option>
                          </select>
                      </div>
                  </div>
              </div>
              <!-- Upload File -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="careerFile" class="form-label">Upload File</label>
                          <input type="file" class="form-control" id="careerFile" name="careerFile" accept=".pdf,.xls,.xlsx" required>
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
</div> <!-- New Career End -->

<!-- Edit Career Start -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="editForm" class="modal-dialog modal-dialog-centered" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Edit Career</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
          <input type="hidden" id="editCareerId" name="id">
          <div class="form-group row">
              <!-- Publication Date and Level in one row -->
              <div class="row mb-3">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="editpublication" class="form-label">Publication Date</label>
                          <input type="date" class="form-control" id="editpublication" name="editpublication" placeholder="Enter Publication Date" required>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="editlevel" class="form-label">Level</label>
                          <select class="form-control" id="editlevel" name="editlevel" required>
                              <option selected disabled>Select Level</option>
                              <option value="1">Level 1</option>
                              <option value="2">Level 2</option>
                          </select>
                      </div>
                  </div>
              </div>
              <!-- Upload File -->
              <div class="row mb-3">
                  <div class="col-12">
                      <div class="form-group">
                          <label for="editCareerFile" class="form-label">Upload File</label>
                          <input type="file" class="form-control" id="editCareerFile" name="editCareerFile" accept=".pdf,.xls,.xlsx">
                      </div>
                  </div>
              </div>
          </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button id="btnEdit" type="button" class="btnsave btn-success">Update</button>
      </div>
    </div>
  </form>
</div> <!-- Edit Career End -->