<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
  <div>
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">City Officials</h1>
    <nav>
      <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
        <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard'); ?>" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">City Officials</li>
      </ol>
    </nav>
  </div>
  <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-circle me-2"></i>Add City Official
  </button>
</div>

<style>
    /* Admin UI Layout Theme Variable Definitions */
    :root {
        --theme-dark-green: #1b4d3e;
        --theme-mid-green: #2d6a4f;
        --theme-light-green: #d8f3dc;
        --theme-accent: #20c997;
    }

    /* Core Action Element Configurations */
    .btn-theme {
        background-color: var(--theme-dark-green);
        color: #ffffff;
        border: 1px solid var(--theme-dark-green);
        border-radius: 6px;
    }
    .btn-theme:hover {
        background-color: var(--theme-mid-green);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important;
    }

    /* Premium Component Containers Card Design */
    .card-premium {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    /* Custom Integrated Search Box Filters for DataTables */
    .dataTables_filter input[type="search"] {
        width: 320px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.45rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s ease-in-out;
    }
    .dataTables_filter input[type="search"]:focus {
        border-color: var(--theme-mid-green);
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.15);
        outline: 0;
    }

    /* Consistent Data Tables Structural Cell Rules */
    #tbloff th {
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaedf1;
        padding: 12px 16px;
    }
    #tbloff td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    /* Clean container wrapper for Rich Text Textarea components */
    .editor-wrapper {
        border: 1px solid #ced4da;
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }

    .transition-all { transition: all 0.2s ease; }
</style>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card card-premium">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tbloff" class="table table-striped table-hover align-middle w-100" cellspacing="0">
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> 
        <form id="addForm" class="modal-content border-0 shadow-lg">
            
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-person-plus me-2"></i>Add City Official Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    
                    <div class="col-lg-5 col-12 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm p-4 h-100 rounded-3 bg-white">
                            <h6 class="small text-uppercase fw-bold text-muted border-bottom pb-2 mb-3">Identity Specifications</h6>
                            
                            <div class="mb-3">
                                <label for="offname" class="form-label small fw-bold text-secondary">Official Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="offname" name="offname" placeholder="Firstname M.I. Lastname" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="offpos" class="form-label small fw-bold text-secondary">Official Designation Position <span class="text-danger">*</span></label>
                                <select id="offpos" class="form-select" name="offpos" required>
                                    <option selected disabled value="">Choose position role...</option>
                                    <option value="CONGRESS">Congress</option>
                                    <option value="CITY MAYOR">City Mayor</option>
                                    <option value="CITY VICE MAYOR">City Vice Mayor</option>
                                    <option value="CITY COUNCILOR">City Councilor</option>
                                    <option value="ABC PRESIDENT">ABC President</option>
                                    <option value="SK FEDERATION PRESIDENT">SK Federation President</option>
                                </select>
                            </div>

                            <div class="mb-3" id="rankField" style="display: none;">
                                <label for="offrank" class="form-label small fw-bold text-secondary">Council Ranking <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="offrank" name="offrank" placeholder="Rank sequence (1 to 12)" min="1" max="12" step="1">
                            </div>

                            <div class="mb-3">
                                <label for="offimg" class="form-label small fw-bold text-secondary">Primary Profile Image Badge <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="offimg" name="offimg" accept="image/*" required>
                            </div>

                            <div class="mb-0">
                                <label for="offcaroimg" class="form-label small fw-bold text-secondary">Gallery Slider Assets (Max 3)</label>
                                <input type="file" class="form-control" id="offcaroimg" name="offcaroimg[]" accept="image/*" multiple>
                                <div class="form-text text-muted small">You may pick up to 3 showcase images.</div>
                                <div id="addCarouselPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-7 col-12 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm p-4 bg-white">
                            <h6 class="small text-uppercase fw-bold text-muted border-bottom pb-2 mb-3">Biography Information Portals</h6>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Chronological Years of Service</label>
                                <div class="editor-wrapper">
                                    <div id="years_of_service" style="height: 140px;"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Personal Background Data Document</label>
                                <div class="editor-wrapper">
                                    <div id="personal_data" style="height: 140px;"></div>
                                </div>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label small fw-bold text-secondary mb-1">Accredited Awards & Distinctions</label>
                                <div class="editor-wrapper">
                                    <div id="awards" style="height: 140px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Official Record</button>
            </div>
            
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> 
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editCOId" name="id">
            
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify Official Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    
                    <div class="col-lg-5 col-12 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm p-4 h-100 rounded-3 bg-white">
                            <h6 class="small text-uppercase fw-bold text-muted border-bottom pb-2 mb-3">Identity Specifications</h6>
                            
                            <div class="mb-3">
                                <label for="editoffname" class="form-label small fw-bold text-secondary">Official Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editoffname" name="editoffname" placeholder="Firstname M.I. Lastname" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editoffpos" class="form-label small fw-bold text-secondary">Official Designation Position <span class="text-danger">*</span></label>
                                <select id="editoffpos" class="form-select" name="editoffpos" required>
                                    <option disabled value="">Choose position role...</option>
                                    <option value="CONGRESS">Congress</option>
                                    <option value="CITY MAYOR">City Mayor</option>
                                    <option value="CITY VICE MAYOR">City Vice Mayor</option>
                                    <option value="CITY COUNCILOR">City Councilor</option>
                                    <option value="ABC PRESIDENT">ABC President</option>
                                    <option value="SK FEDERATION PRESIDENT">SK Federation President</option>
                                </select>
                            </div>

                            <div class="mb-3" id="editRankField" style="display: none;">
                                <label for="editoffrank" class="form-label small fw-bold text-secondary">Council Ranking <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editoffrank" name="editoffrank" placeholder="Rank sequence (1 to 12)" min="1" max="12" step="1">
                            </div>

                            <div class="mb-3">
                                <label for="editoffimg" class="form-label small fw-bold text-secondary">Update Profile Image Badge</label>
                                <input type="file" class="form-control" id="editoffimg" name="editoffimg" accept="image/*">
                                <div id="editImagePreview" class="mt-2"></div>
                            </div>

                            <div class="mb-0">
                                <label for="editoffcaroimg" class="form-label small fw-bold text-secondary">Replace Gallery Slider Assets (Max 3)</label>
                                <input type="file" class="form-control" id="editoffcaroimg" name="editoffcaroimg[]" accept="image/*" multiple>
                                <div id="carouselPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-7 col-12 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm p-4 bg-white">
                            <h6 class="small text-uppercase fw-bold text-muted border-bottom pb-2 mb-3">Biography Information Portals</h6>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Chronological Years of Service</label>
                                <div class="editor-wrapper">
                                    <div id="edit_years_of_service" style="height: 140px;"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Personal Background Data Document</label>
                                <div class="editor-wrapper">
                                    <div id="edit_personal_data" style="height: 140px;"></div>
                                </div>
                            </div>
                            
                            <div class="mb-0">
                                <label class="form-label small fw-bold text-secondary mb-1">Accredited Awards & Distinctions</label>
                                <div class="editor-wrapper">
                                    <div id="edit_awards" style="height: 140px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Official Record</button>
            </div>
            
        </form>
    </div>
</div>

<script>
    (function() {
        const bindCarouselLimit = (inputId) => {
            const input = document.getElementById(inputId);
            if(input) {
                input.addEventListener('change', function() {
                    if (this.files.length > 3) {
                        alert('Operation restricted: You can only select up to 3 image files for the content slider.');
                        this.value = '';
                    }
                });
            }
        };
        bindCarouselLimit('offcaroimg');
        bindCarouselLimit('editoffcaroimg');
    })();
</script>