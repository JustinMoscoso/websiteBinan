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

    /* SB Admin 2 Data Table Custom Styles */
    .card-sb {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
        background-color: #fff;
    }

    .card-sb-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        border-top-left-radius: calc(0.35rem - 1px);
        border-top-right-radius: calc(0.35rem - 1px);
    }

    #tbloff {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tbloff th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
    }

    #tbloff td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tbloff tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tbloff tbody tr:hover {
        background-color: #eef6f0 !important; /* Soft premium green highlight on hover */
    }

    /* Custom Integrated Search Box Filters for DataTables matching SB Admin 2 */
    .dataTables_length label,
    .dataTables_filter label {
        color: #858796;
        font-weight: normal;
        font-size: 0.875rem;
    }

    .dataTables_length select {
        background-color: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        line-height: 1.5;
        color: #6e707e;
        vertical-align: middle;
        font-size: 0.875rem;
        height: 38px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .dataTables_length select:focus {
        border-color: var(--theme-mid-green);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25);
    }

    .dataTables_filter input {
        background-color: #fff;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
        padding: 0.375rem 0.75rem;
        line-height: 1.5;
        color: #6e707e;
        font-size: 0.875rem;
        height: 38px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .dataTables_filter input:focus {
        border-color: var(--theme-mid-green);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(45, 106, 79, 0.25);
    }

    .dataTables_info {
        color: #858796;
        font-size: 0.875rem;
    }

    .dataTables_paginate .paginate_button {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background: transparent !important;
    }

    /* Premium Modern Status Badge Styles */
    .status-badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        gap: 6px;
        padding: 6px 12px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 30px;
        border: 1px solid transparent;
    }
    
    .status-badge-active {
        background-color: #e8f5e9;
        color: #2e7d32;
        border-color: #c8e6c9;
    }
    
    .status-badge-inactive {
        background-color: #ffebee;
        color: #c62828;
        border-color: #ffcdd2;
    }
    
    .status-badge-archived {
        background-color: #f5f5f5;
        color: #616161;
        border-color: #e0e0e0;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block !important;
        vertical-align: middle !important;
    }
    
    .status-dot-active {
        background-color: #2e7d32;
        box-shadow: 0 0 6px #2e7d32;
    }
    
    .status-dot-inactive {
        background-color: #c62828;
        box-shadow: 0 0 6px #c62828;
    }
    
    .status-dot-archived {
        background-color: #616161;
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

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
<div class="card card-premium mb-4 border-start border-4"
    style="border-start-color: var(--theme-mid-green) !important;">

    <div class="card-body p-4">
        <form id="cityoffSearchForm">
            <div class="row g-3 align-items-end">

                <div class="col-xl-4 col-lg-4 col-md-12">
                    <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="search"
                            placeholder="Search Name / Position...">
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-secondary">Position Filter</label>
                    <select class="form-select bg-light border-secondary-subtle" name="position"
                        style="height: 38px; cursor: pointer;">
                        <option selected value="">All Positions</option>
                        <option value="CONGRESS">Congress</option>
                        <option value="CITY MAYOR">City Mayor</option>
                        <option value="CITY VICE MAYOR">City Vice Mayor</option>
                        <option value="CITY COUNCILOR">City Councilor</option>
                        <option value="ABC PRESIDENT">ABC President</option>
                        <option value="SK FEDERATION PRESIDENT">SK Federation President</option>
                    </select>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-secondary">Publication Status</label>
                    <select class="form-select bg-light border-secondary-subtle" name="status"
                        style="height: 38px; cursor: pointer;">
                        <option selected value="">All Statuses</option>
                        <option value="ACTIVE">Active</option>
                        <option value="INACTIVE">Inactive</option>
                        <option value="ARCHIVED">Archived</option>
                    </select>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-12">
                    <div class="d-flex gap-2 w-100">
                        <button type="reset" class="btn btn-outline-secondary flex-grow-1 fw-semibold"
                            style="height: 38px;">
                            Clear
                        </button>
                        <button type="submit" class="btn btn-outline-success flex-grow-1 fw-semibold shadow-sm"
                            id="cityoffSearchBtn" style="height: 38px;">
                            Search
                        </button>
                        <button type="button" class="btn btn-success shadow-sm fw-semibold text-nowrap flex-grow-1"
                            data-bs-toggle="modal" data-bs-target="#addModal" style="height: 38px;">
                            <i class="bi bi-plus-circle me-1"></i>Add City Official
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

</div>
<?php endif; ?>

<section class="section">
    <div class="row">
        <div class="col-12">
            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4" style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>City Officials Records
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbloff" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
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