<div class="pagetitle">
  <h1>Account Management</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">Account Management</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<!-- Search Filters UI Start -->
<div class="container-fluid py-3">
    <div class="card mb-4">
        <div class="card-body">
            <form id="userSearchForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchUser" placeholder="Search username/name...">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="searchStatus">
                            <option selected value="">- Status -</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="searchUserLevel">
                            <option selected value="">- User Level -</option>
                            <option value="DEVELOPER">Developer</option>
                            <option value="SUPERADMIN">Super Admin</option>
                            <option value="ADMIN">Admin</option>
                            <option value="ENCODER">Encoder</option>
                            <option value="VIEWER">Viewer</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="reset" class="btn btn-danger w-100">Clear Filters</button>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary w-100" id="searchBtn">Search</button>
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
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add User</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tbluser" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>



<!-- Add User Start -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-dialog-centered modal-lg" role="document">      
      <div class="modal-content">
        
          <!-- Modal header Start -->
          <div class="modal-header modal-header-bg">
              <h5 class="modal-title">Create User Account</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div> <!-- Modal header End -->
          
          <!-- Modal body Start -->
          <div class="modal-body">
              <div class="form-group row">
                  <!-- Name fields in one row -->
                  <div class="row mb-3">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="txtFirstName" class="form-label">First Name</label>
                              <input type="text" class="form-control" id="txtFirstName" name="txtFirstName" placeholder="Enter first name" required>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="txtLastName" class="form-label">Last Name</label>
                              <input type="text" class="form-control" id="txtLastName" name="txtLastName" placeholder="Enter last name" required>
                          </div>
                      </div>
                  </div>
                  <!-- Username and Email in one row -->
                  <div class="row mb-3">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="txtUsername" class="form-label">Username</label>
                              <input type="text" class="form-control" id="txtUsername" name="txtUsername" placeholder="Enter username" required>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="txtEmail" class="form-label">Email</label>
                              <input type="email" class="form-control" id="txtEmail" name="txtEmail" placeholder="Enter email" required>
                          </div>
                      </div>
                  </div>
                  <!-- Password full width -->
                  <div class="row mb-3">
                      <div class="col-12">
                          <div class="form-group">
                              <label for="txtPassword" class="form-label">Password</label>
                              <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Enter password" required>
                          </div>
                      </div>
                  </div>
                  <!-- Account Level and Department in one row -->
                  <div class="row mb-3">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="txtAccLevel" class="form-label">Account Level</label>
                              <select id="txtAccLevel" name="txtAccLevel" class="form-select" required>
                                  <option selected disabled>Select Level</option>
                                  <option value="DEVELOPER">Developer</option>
                                  <option value="SUPERADMIN">Super admin</option>
                                  <option value="ADMIN">Admin</option>
                                  <option value="ENCODER">Encoder</option>
                                  <option value="VIEWER">Viewer</option>
                              </select>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="txtDept" class="form-label">Department</label>
                              <select id="txtDept" name="txtDept" class="form-select" required>
                                  <option selected disabled>Select Department</option>
                                  <!-- Get depts -->
                              </select>
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
</div> <!-- Add User End -->


<!-- Edit User Start -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="editForm" class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <!-- Modal header Start -->
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Edit User Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- Modal header End -->

            <!-- Modal body Start -->
            <div class="modal-body">
                <input type="hidden" id="editUserId" name="id">
                <div class="form-group row">
                    <!-- Name fields in one row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="editFirstName" name="editFirstName" placeholder="Enter first name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="editLastName" name="editLastName" placeholder="Enter last name" required>
                            </div>
                        </div>
                    </div>
                    <!-- Username and Email in one row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editUsername" class="form-label">Username</label>
                                <input type="text" class="form-control" id="editUsername" name="editUsername" placeholder="Enter username" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="editEmail" placeholder="Enter email" required>
                            </div>
                        </div>
                    </div>
                    <!-- Password full width -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="editPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="editPassword" name="editPassword" placeholder="Enter password">
                            </div>
                        </div>
                    </div>
                    <!-- Account Level and Department in one row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editAccLevel" class="form-label">Account Level</label>
                                <select id="editAccLevel" name="editAccLevel" class="form-select" required>
                                    <option selected disabled>Select Level</option>
                                    <option value="DEVELOPER">Developer</option>
                                    <option value="SUPERADMIN">Super admin</option>
                                    <option value="ADMIN">Admin</option>
                                    <option value="ENCODER">Encoder</option>
                                    <option value="VIEWER">Viewer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editDept" class="form-label">Department</label>
                                <select id="editDept" name="editDept" class="form-select" required>
                                    <option selected disabled>Select Department</option>
                                    <!-- Options will be populated via AJAX -->
                                </select>
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
</div> <!-- Edit User End -->


