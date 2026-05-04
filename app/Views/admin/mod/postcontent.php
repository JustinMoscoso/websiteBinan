<div class="pagetitle">
  <h1>Post Content Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item">Post Content Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- Search Filters UI Start -->
<div class="container-fluid py-3">
    <div class="card mb-4">
        <div class="card-body">
            <form id="docSearchForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="search" placeholder="Search Title / Author / Year...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="category">
                            <option selected value="">- Category -</option>
                            <option value="NEWS">News</option>
                            <option value="ANNS">Announcements</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="status">
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

<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add Post Content</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblnews" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>

<!--Add Post Content-->
<div class="modal fade" id="addModal" tabindex="-1"  role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-dialog-centered modal-xl" role="document">     
        <div class="modal-content">
            <!-- Modal header Start -->
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Add Post Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- Modal header End -->
            
            <!-- Modal body Start -->
            <div class="modal-body">
                <div class="form-group row">
                    <!-- Category, Author, Upload Image in one row -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="content_category" class="form-label">Category</label>
                                <select class="form-control" id="content_category" name="content_category" required>
                                    <option selected disabled>Select Category</option>
                                    <option value="NEWS">News and Events</option>
                                    <option value="ANNS">Announcements</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author" 
                                       value="<?= $user->fname . ' ' . $user->lname ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="newsImg" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" id="newsImg" name="newsImg" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                    <!-- Title full width -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" required>
                            </div>
                        </div>
                    </div>
                    <!-- Description full width -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="desc" class="form-label">Description</label>
                                <div id="quillDesc" class="quill-editor-full" style="height: 120px;"></div>
                                <input type="hidden" id="desc" name="desc" required>
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
</div> <!-- Add Post Content End -->

<!--Edit Post Content-->
<div class="modal fade" id="editModal" tabindex="-1"  role="dialog" data-bs-backdrop="static">
    <form id="editForm" class="modal-dialog modal-dialog-centered modal-xl" role="document">     
        <div class="modal-content">
            <!-- Modal header Start -->
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Edit Post Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- Modal header End -->
            
            <!-- Modal body Start -->
            <div class="modal-body">
                <div class="form-group row">
                    <input type="hidden" id="editNewsId" name="id">
                    <!-- Category, Author, Upload Image in one row -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="edit_content_category" class="form-label">Category</label>
                                <select class="form-control" id="edit_content_category" name="edit_content_category" required>
                                    <option selected disabled>Select Category</option>
                                    <option value="NEWS">News and Events</option>
                                    <option value="ANNS">Announcements</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editAuthor" class="form-label">Author</label>
                                <input type="text" class="form-control" id="editAuthor" name="editAuthor" 
                                       value="<?= $user->fname . ' ' . $user->lname ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="editNewsImg" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" id="editNewsImg" name="editNewsImg" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                    <!-- Title full width -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="editTitle" class="form-label">Title</label>
                                <input type="text" class="form-control" id="editTitle" name="editTitle" placeholder="Enter title" required>
                            </div>
                        </div>
                    </div>
                    <!-- Description full width -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="desc" class="form-label">Description</label>
                                <div id="editQuillDesc" class="quill-editor-full" style="height: 120px;"></div>
                                <input type="hidden" id="editDesc" name="editDesc" required>
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
</div> <!-- Edit Post Content End -->        