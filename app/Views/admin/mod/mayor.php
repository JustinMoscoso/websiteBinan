<div class="pagetitle">
    <h1>Mayor's Corner</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Mayor's Corner</li>
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
                        <table id="tblmayor" class="table table-hover" cellspacing="0" width="100%">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Mayor's Content -->
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
                    <!-- Category and Name in one row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="content_category" class="form-label">Category</label>
                                <select class="form-control" id="content_category" name="content_category" required>
                                    <option selected disabled>Select Category</option>
                                    <option value="Personal Data">Personal Data</option>
                                    <option value="Awards">Awards</option>
                                    <option value="Years Service">Years Service</option>
                                    <option value="Gallery">Gallery</option>
                                    <option value="Home Page">Home Page</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="myrname" class="form-label">Name of Mayor</label>
                                <input type="text" class="form-control" id="myrname" name="myrname" placeholder="Enter Mayor's Name" required>
                            </div>
                        </div>
                    </div>
                    <!-- Personal Data -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="perdata" class="form-label">Personal Data</label>
                                <div id="perdata" name="perdata" style="height: 200px;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Image Logo -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="mayorimg" class="form-label">Image Logo</label>
                                <input type="file" class="form-control" id="mayorimg" name="mayorimg[]" accept="image/*" multiple>
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
</div> <!-- Add Mayor's Content End -->


<!-- Edit Mayor's Content -->
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
                <input type="hidden" id="editMayorId" name="id">
                <div class="form-group row">
                    <!-- Category and Name in one row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_content_category" class="form-label">Category</label>
                                <select class="form-control" id="edit_content_category" name="edit_content_category" required>
                                    <option selected disabled>Select Category</option>
                                    <option value="Personal Data">Personal Data</option>
                                    <option value="Awards">Awards</option>
                                    <option value="Years Service">Years Service</option>
                                    <option value="Gallery">Gallery</option>
                                    <option value="Home Page">Home Page</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editmyrname" class="form-label">Name of Mayor</label>
                                <input type="text" class="form-control" id="editmyrname" name="editmyrname" placeholder="Enter Mayor's Name" required>
                            </div>
                        </div>
                    </div>
                    <!-- Personal Data -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="editperdata" class="form-label">Personal Data</label>
                                <div id="editperdata" name="editperdata" style="height: 200px;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Image Logo -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="editmayorimg" class="form-label">Image Logo</label>
                                <input type="file" class="form-control" id="editmayorimg" name="editmayorimg[]" accept="image/*" multiple>
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
</div> <!-- Edit Mayor's Content End -->

<script>
// Quill toolbar options without image and video
var quillToolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  [{ 'align': [] }],                               // text align
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],    // lists
  ['link'],                                        // only link, no image or video
  ['clean']                                        // remove formatting
];
</script>
