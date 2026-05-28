<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
  <div>
    <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Maps Management</h1>
    <nav>
      <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Maps Management</li>
      </ol>
    </nav>
  </div>
  <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal" data-bs-target="#addModal">
    <i class="bi bi-plus-circle me-2"></i>Add Map
  </button>
</div>

<style>
    /* Theme Variables matching your sidebar */
    :root {
        --theme-dark-green: #1b4d3e;
        --theme-mid-green: #2d6a4f;
        --theme-light-green: #d8f3dc;
        --theme-accent: #20c997;
    }

    /* Primary Action Button Custom Theme */
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

    /* Polished Card Container */
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

    #tblmap {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tblmap th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
    }

    #tblmap td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tblmap tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tblmap tbody tr:hover {
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
        display: inline-flex;
        align-items-center;
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
        display: inline-block;
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

    .transition-all { transition: all 0.2s ease; }
</style>

<section class="section">
    <div class="row">
        <div class="col-12">
            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4" style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Map Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblmap" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="addForm" class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-plus-circle me-2"></i>Add New Map
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="brgy_name" class="form-label small fw-bold text-secondary">Barangay Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="brgy_name" name="brgy_name" placeholder="Enter barangay name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="top_loc" class="form-label small fw-bold text-secondary">Top Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="top_loc" name="top_loc" placeholder="Coordinate (e.g. 14.123)" required>
                    </div>
                    <div class="col-md-6">
                        <label for="left_loc" class="form-label small fw-bold text-secondary">Left Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="left_loc" name="left_loc" placeholder="Coordinate (e.g. 121.123)" required>
                    </div>
                    <div class="col-12">
                        <label for="details" class="form-label small fw-bold text-secondary">Details <span class="text-danger">*</span></label>
                        <div id="quillDetails" class="quill-editor-full border rounded-3 bg-white shadow-sm" style="height: 140px;"></div>
                        <input type="hidden" id="details" name="details">
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Map</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editId" name="editId">
            
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify Map Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="edit_brgy_name" class="form-label small fw-bold text-secondary">Barangay Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="edit_brgy_name" name="edit_brgy_name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_top_loc" class="form-label small fw-bold text-secondary">Top Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="edit_top_loc" name="edit_top_loc" required>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_left_loc" class="form-label small fw-bold text-secondary">Left Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="edit_left_loc" name="edit_left_loc" required>
                    </div>
                    <div class="col-12">
                        <label for="edit_details" class="form-label small fw-bold text-secondary">Details <span class="text-danger">*</span></label>
                        <div id="editQuillDetails" class="quill-editor-full border rounded-3 bg-white shadow-sm" style="height: 140px;"></div>
                        <input type="hidden" id="edit_details" name="edit_details">
                    </div>
                </div>
            </div>
            
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Map</button>
            </div>
        </form>
    </div>
</div>