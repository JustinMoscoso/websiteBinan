<div class="pagetitle">
  <h1>Maps Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Maps Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Maps</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblmap" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                 <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Add Map</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- Modal header End -->
            
            <div class="modal-body">
                <form id="addForm">
                    <div class="mb-3">
                        <label for="brgy_name" class="form-label">Barangay Name</label>
                        <input type="text" class="form-control" id="brgy_name" name="brgy_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="top_loc" class="form-label">Top Location</label>
                        <input type="text" class="form-control" id="top_loc" name="top_loc" required>
                    </div>
                    <div class="mb-3">
                        <label for="left_loc" class="form-label">Left Location</label>
                        <input type="text" class="form-control" id="left_loc" name="left_loc" required>
                    </div>
                    <div class="mb-3">
                        <label for="details" class="form-label">Details</label>
                        <div id="quillDetails" class="quill-editor-full" style="height: 120px;"></div>
                        <input type="hidden" id="details" name="details">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="btnAdd" type="button" class="btnsave btn-success">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
          <h5 class="modal-title">Edit Content</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div> <!-- Modal header End -->
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="editId" name="editId">
                    <div class="mb-3">
                        <label for="edit_brgy_name" class="form-label">Barangay Name</label>
                        <input type="text" class="form-control" id="edit_brgy_name" name="edit_brgy_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_top_loc" class="form-label">Top Location</label>
                        <input type="text" class="form-control" id="edit_top_loc" name="edit_top_loc" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_left_loc" class="form-label">Left Location</label>
                        <input type="text" class="form-control" id="edit_left_loc" name="edit_left_loc" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_details" class="form-label">Details</label>
                        <div id="editQuillDetails" class="quill-editor-full" style="height: 120px;"></div>
                        <input type="hidden" id="edit_details" name="edit_details">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="btnEdit" type="button" class="btnsave btn-success">Update</button>
            </div>
        </div>
    </div>
</div>