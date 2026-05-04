<div class="pagetitle">
  <h1>Job Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Job Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">
              <i class="bi bi-plus-circle me-2"></i>Add Job
            </button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tbljobs" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>

<!-- Add Job Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="addForm" class="modal-dialog modal-dialog-centered modal-lg" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">
            <i class="bi bi-plus-circle me-2"></i>Add New Job
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
                <div class="row">
              <div class="col-md-12 mb-3">
                  <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="title" name="title" placeholder="Enter job title" required>
                    </div>
                    <div class="col-md-6 mb-3">
                  <label for="company" class="form-label">Company <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="company" name="company" placeholder="Enter company name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Job Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">Select Job Type</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                        </select>
                    </div>
              <div class="col-md-6 mb-3">
                  <label for="publication_date" class="form-label">Publication Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="publication_date" name="publication_date" required>
              </div>
              <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">Contact Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter contact email for applications" required>
              </div>
              <div class="col-md-12 mb-3">
                  <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                  <div id="quillDescription" class="quill-editor-full" style="height: 120px;"></div>
                  <input type="hidden" id="description" name="description" required>
                </div>
                </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
          <button id="btnAdd" type="button" class="btn btn-success">
            <i class="bi bi-check-circle me-2"></i>Save Job
                    </button>
        </div>
    </div>
  </form>
</div> <!-- Add Job Modal End -->

<!-- Edit Job Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <form id="editForm" class="modal-dialog modal-dialog-centered modal-lg" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">
            <i class="bi bi-pencil-square me-2"></i>Edit Job
                </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
        <input type="hidden" id="editJobId" name="id">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="editTitle" class="form-label">Job Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editTitle" name="title" placeholder="Enter job title" required>
            </div>
                        <div class="col-md-6 mb-3">
                <label for="editCompany" class="form-label">Company <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editCompany" name="company" placeholder="Enter company name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editType" class="form-label">Job Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="editType" name="type" required>
                                <option value="">Select Job Type</option>
                                <option value="Full Time">Full Time</option>
                                <option value="Part Time">Part Time</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                <label for="editPublicationDate" class="form-label">Publication Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="editPublicationDate" name="publication_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                <label for="editEmail" class="form-label">Contact Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="editEmail" name="email" placeholder="Enter contact email for applications" required>
                        </div>
            <div class="col-md-12 mb-3">
                <label for="editDescription" class="form-label">Job Description <span class="text-danger">*</span></label>
                <div id="editQuillDescription" class="quill-editor-full" style="height: 120px;"></div>
                <input type="hidden" id="editDescription" name="description" required>
                        </div>
                        <?php if ($user->user_lvl !== 'ENCODER' && $user->user_lvl !== 'VIEWER'): ?>
                        <div class="col-md-6 mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select class="form-select" id="editStatus" name="status">
                                <option value="ACTIVE">Active</option>
                                <option value="INACTIVE">Inactive</option>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
      </div> <!-- Modal body End -->
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-2"></i>Cancel
          </button>
          <button id="btnEdit" type="button" class="btn btn-primary">
            <i class="bi bi-check-circle me-2"></i>Update Job
          </button>
      </div>
                    </div>
                </form>
</div> <!-- Edit Job Modal End -->

<!-- View Job Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">     
    <div class="modal-content">
      <!-- Modal header Start -->
      <div class="modal-header modal-header-bg">
          <h5 class="modal-title">
            <i class="bi bi-eye me-2"></i>Job Details
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> <!-- Modal header End -->
      
      <!-- Modal body Start -->
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Job Title</label>
                <p id="viewTitle" class="form-control-plaintext"></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Company</label>
                <p id="viewCompany" class="form-control-plaintext"></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Job Type</label>
                <p id="viewType" class="form-control-plaintext"></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Publication Date</label>
                <p id="viewPublicationDate" class="form-control-plaintext"></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Contact Email</label>
                <p id="viewEmail" class="form-control-plaintext"></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Status</label>
                <p id="viewStatus" class="form-control-plaintext"></p>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Created Date</label>
                <p id="viewCreatedDate" class="form-control-plaintext"></p>
            </div>
            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Job Description</label>
                <div id="viewDescription" class="form-control-plaintext"></div>
            </div>
        </div>
      </div> <!-- Modal body End -->
    </div>
</div>
</div> <!-- View Job Modal End --> 