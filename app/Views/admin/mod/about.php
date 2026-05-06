<div class="pagetitle">
  <h1>About / Homepage Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">About / Homepage Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

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
                  <table id="tblabout" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>

<!-- Add Content -->
<div class="modal fade modal-lg" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-dialog-centered" role="document" enctype="multipart/form-data">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Add Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body Start -->
            <div class="modal-body">
                <div class="form-group row">
                    <!-- Category and Title in one row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="content_category" class="form-label">Category</label>
                                <select class="form-control" id="content_category" name="content_category" required>
                                    <option selected disabled>Select Category</option>
                                    <option value="Home Page">Home Page</option>
                                    <option value="History">History</option>
                                    <!-- <option value="Header">About - Header</option> -->
                                    <option value="Content">About - Content</option>
                                    <option value="Emergency Hotlines">Emergency Hotlines (Home Page)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="TxtTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="TxtTitle" name="TxtTitle" placeholder="Enter title" required>
                            </div>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group" id="DescGroup" style="display: none;">
                                <label for="TxtDesc" class="form-label">Description</label>
                                <div id="quillDesc" class="quill-editor-full" style="height: 120px;"></div>
                                <input type="hidden" id="TxtDesc" name="TxtDesc" required>
                            </div>
                        </div>
                    </div>
                    <!-- Upload Image -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group" id="AboutImgGrp" style="display: none;">
                                <label for="AboutImg" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" id="AboutImg" name="AboutImg" accept="image/*">
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
</div> <!-- Content End -->


<!-- Edit Content -->
<div class="modal fade modal-lg" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="editForm" class="modal-dialog modal-dialog-centered" role="document" enctype="multipart/form-data">
        <div class="modal-content">
            <!-- Modal header Start -->
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Edit Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- Modal header End -->

            <!-- Modal body Start -->
            <div class="modal-body">
                <input type="hidden" id="editAboutId" name="id">
                <div class="form-group row">
                    <!-- Category and Title in one row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_content_category" class="form-label">Category</label>
                                <select class="form-control" id="edit_content_category" name="edit_content_category" required>
                                    <option selected disabled>Select Category</option>
                                    <option value="Home Page">Home Page</option>
                                    <option value="History">History</option>
                                    <!-- <option value="Header">About - Header</option> -->
                                    <option value="Content">About - Content</option>
                                    <option value="Emergency Hotlines">Emergency Hotlines</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="EditTxtTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="EditTxtTitle" name="EditTxtTitle" placeholder="Enter title" required>
                            </div>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group" id="EditDescGroup" style="display: none;">
                                <label for="EditTxtDesc" class="form-label">Description</label>
                                <div id="editQuillDesc" class="quill-editor-full" style="height: 120px;"></div>
                                <input type="hidden" id="EditTxtDesc" name="EditTxtDesc" required>
                            </div>
                        </div>
                    </div>
                    <!-- Update Image -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group" id="EditAboutImgGrp" style="display: none;">
                                <label for="EditAboutImg" class="form-label">Update Image</label>
                                <input type="file" class="form-control" id="EditAboutImg" name="EditAboutImg" accept="image/*">
                                <div id="edit_img_preview" class="mt-2"></div>
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
</div> <!-- Edit Content End -->