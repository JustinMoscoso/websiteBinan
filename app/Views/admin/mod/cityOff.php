<div class="pagetitle">
    <h1>City Officials</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard'); ?>">Dashboard</a></li>
            <li class="breadcrumb-item">City Officials</li>
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
<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <!-- Button trigger modal -->
          <div class="text-end mb-3">
            <button type="button" class="btn button-32" data-bs-toggle="modal" data-bs-target="#addModal">Add City Official</button>
          </div>
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tbloff" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>

<!-- Add City Official -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
    <form id="addForm" class="modal-dialog modal-xl modal-dialog-centered" role="document">     
        <div class="modal-content">
            <!-- Modal header Start -->
            <div class="modal-header modal-header-bg">
                <h5 class="modal-title">Add City Official</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> <!-- Modal header End -->
            
            <!-- Modal body Start -->
            <div class="modal-body">
                <div class="row">
                    <!-- Name and Position in two columns -->
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="offname" class="form-label">Official Name</label>
                            <input type="text" class="form-control" id="offname" name="offname" placeholder="Enter Name" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="offpos" class="form-label">Official Position</label>
                            <select id="offpos" class="form-select" name="offpos" required>
                                <option selected disabled>Choose a Position</option>
                                <option value="CONGRESS">Congress</option>
                                <option value="CITY MAYOR">City Mayor</option>
                                <option value="CITY VICE MAYOR">City Vice Mayor</option>
                                <option value="CITY COUNCILOR">City Councilor</option>
                                <option value="ABC PRESIDENT">ABC President</option>
                                <option value="SK FEDERATION PRESIDENT">SK Federation President</option>
                            </select>
                        </div>
                    </div>

                    <!-- Image Logo - Full width -->
                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label for="offimg" class="form-label">Image Logo</label>
                            <input type="file" class="form-control" id="offimg" name="offimg" accept="image/*" required>
                        </div>
                    </div>

                    <!-- Rank field - Full width -->
                    <div class="col-12 mb-3" id="rankField" style="display: none;">
                        <div class="form-group">
                            <label for="offrank" class="form-label">Rank</label>
                            <input type="number" class="form-control" id="offrank" name="offrank" placeholder="Enter Rank No. from 1 to 12" min="1" max="12" step="1">
                        </div>
                    </div>

                    <!-- Rich text editors - Full width -->
                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label for="years_of_service" class="form-label">Years of Service</label>
                            <div id="years_of_service" style="height: 200px;"></div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label for="personal_data" class="form-label">Personal Data</label>
                            <div id="personal_data" style="height: 200px;"></div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label for="awards" class="form-label">Awards</label>
                            <div id="awards" style="height: 200px;"></div>
                        </div>
                    </div>

                    <!-- Carousel Images - Full width -->
                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label for="offcaroimg" class="form-label">Carousel Images (up to 3)</label>
                            <input type="file" class="form-control" id="offcaroimg" name="offcaroimg[]" accept="image/*" multiple>
                            <small class="form-text text-muted">Select multiple images. Max 3 images.</small>
                            <div id="addCarouselPreview" class="mt-2"></div>
                        </div>
                    </div>

                    <script>
                    document.getElementById('offcaroimg').addEventListener('change', function(e) {
                        if (this.files.length > 3) {
                            alert('You can only upload up to 3 images.');
                            this.value = ''; // Clear the input
                        }
                    });
                    </script>
                </div>
            </div> <!-- Modal body End -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="btnAdd" type="button" class="btnsave btn-success">Save</button>
            </div>
        </div>
    </form>
</div> <!-- Add City Official End -->

 <!-- Edit City Official (Updated) -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static">
        <form id="editForm" class="modal-dialog modal-xl modal-dialog-centered" role="document">     
            <div class="modal-content">
                <!-- Modal header Start -->
                <div class="modal-header modal-header-bg">
                    <h5 class="modal-title">Edit City Official</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> <!-- Modal header End -->
                
                <!-- Modal body Start -->
                <div class="modal-body">
                    <input type="hidden" id="editCOId" name="id">
                    <div class="row">
                        <!-- Name and Position in two columns -->
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="editoffname" class="form-label">Official Name</label>
                                <input type="text" class="form-control" id="editoffname" name="editoffname" placeholder="Enter Name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="editoffpos" class="form-label">Official Position</label>
                                <select id="editoffpos" class="form-select" name="editoffpos" required>
                                    <option selected disabled>Choose a Position</option>
                                    <option value="CONGRESS">Congress</option>
                                    <option value="CITY MAYOR">City Mayor</option>
                                    <option value="CITY VICE MAYOR">City Vice Mayor</option>
                                    <option value="CITY COUNCILOR">City Councilor</option>
                                    <option value="ABC PRESIDENT">ABC President</option>
                                    <option value="SK FEDERATION PRESIDENT">SK Federation President</option>
                                </select>
                            </div>
                        </div>

                        <!-- Image Logo - Full width -->
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="editoffimg" class="form-label">Image Logo</label>
                                <input type="file" class="form-control" id="editoffimg" name="editoffimg" accept="image/*">
                                <div id="editImagePreview" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Rank field - Full width -->
                        <div class="col-12 mb-3" id="editRankField" style="display: none;">
                            <div class="form-group">
                                <label for="editoffrank" class="form-label">Rank</label>
                                <input type="number" class="form-control" id="editoffrank" name="editoffrank" placeholder="Enter Rank No. from 1 to 12" min="1" max="12" step="1">
                            </div>
                        </div>

                        <!-- Rich text editors - Full width -->
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="edit_years_of_service" class="form-label">Years of Service</label>
                                <div id="edit_years_of_service" style="height: 200px;"></div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="edit_personal_data" class="form-label">Personal Data</label>
                                <div id="edit_personal_data" style="height: 200px;"></div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="edit_awards" class="form-label">Awards</label>
                                <div id="edit_awards" style="height: 200px;"></div>
                            </div>
                        </div>

                        <!-- Carousel Images - Full width -->
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="editoffcaroimg" class="form-label">Carousel Images (up to 3)</label>
                                <input type="file" class="form-control" id="editoffcaroimg" name="editoffcaroimg[]" accept="image/*" multiple>
                                <small class="form-text text-muted">Select multiple images. Max 3 images.</small>
                                <div id="carouselPreview" class="mt-2"></div>
                            </div>
                        </div>

                        <script>
                        document.getElementById('editoffcaroimg').addEventListener('change', function(e) {
                            if (this.files.length > 3) {
                                alert('You can only upload up to 3 images.');
                                this.value = ''; // Clear the input
                            }
                        });
                        </script>
                    </div>
                </div> <!-- Modal body End -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnEdit" type="button" class="btnsave btn-success">Update</button>
                </div>
            </div>
        </form>
    </div> <!-- Edit City Official End -->
