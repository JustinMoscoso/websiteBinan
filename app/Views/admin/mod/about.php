<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">About / Homepage Management</h1>
        <nav>
            <ol class="breadcrumb mb-0 bg-transparent p-2" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">About / Homepage Management</li>
            </ol>
        </nav>
    </div>
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
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
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

    #tblabout {
        border-collapse: collapse !important;
        background-color: #ffffff !important;
    }

    #tblabout th {
        background-color: #f8f9fc !important;
        color: var(--theme-dark-green) !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: 1px solid #e3e6f0 !important;
        padding: 12px 16px;
    }

    #tblabout td {
        padding: 14px 16px;
        vertical-align: middle;
        border: 1px solid #e3e6f0 !important;
        background-color: inherit !important;
    }

    /* Clean, soft table row backgrounds overriding DataTables/Bootstrap defaults */
    #tblabout tbody tr {
        background-color: #ffffff !important;
        transition: background-color 0.15s ease-in-out;
    }

    #tblabout tbody tr:hover {
        background-color: #eef6f0 !important;
        /* Soft premium green highlight on hover */
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

    /* Clean container wrapper for Quill Text Editor components */
    .editor-wrapper {
        border: 1px solid #ced4da;
        border-radius: 6px;
        overflow: hidden;
        background: #ffffff;
    }

    .editor-wrapper .ql-toolbar.ql-snow {
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: 1px solid #ced4da;
        background: #f8f9fa;
    }

    .editor-wrapper .ql-container.ql-snow {
        border: none;
    }

    .transition-all {
        transition: all 0.2s ease;
    }
</style>

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
    <div class="card card-premium mb-4 border-start border-4"
        style="border-start-color: var(--theme-mid-green) !important;">

        <div class="card-body p-4">
            <form id="aboutSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0" name="search"
                                placeholder="Search Title / Description...">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Section Filter</label>
                        <select class="form-select bg-light border-secondary-subtle" name="section"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Sections</option>
                            <option value="Home Page">Home Page</option>
                            <option value="History">History</option>
                            <option value="Content">About - Content</option>
                            <option value="Emergency Hotlines">Emergency Hotlines</option>
                            <option value="Header">Header</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Publication Status</label>
                        <select class="form-select bg-light border-secondary-subtle" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Statuses</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <?php if (in_array($user->user_lvl ?? '', ['DEVELOPER', 'SUPERADMIN'], true)): ?>
                                <option value="ARCHIVED">Archived</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="row g-2">


                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger w-100 fw-semibold" style="height: 38px;">
                                    Clear
                                </button>
                            </div>

                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm"
                                    id="aboutSearchBtn" style="height: 38px;">
                                    Search
                                </button>
                            </div>


                            <div class="col-12 col-md-4">
                                <button type="button" class="btn btn-success w-100 fw-semibold text-white shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Add Record
                                </button>
                            </div>

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
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>About & Homepage Records
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblabout" class="table table-bordered table-hover align-middle w-100"
                            cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="addForm" class="modal-content border-0 shadow-lg" enctype="multipart/form-data">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-plus-circle me-2"></i>Add Corporate Page Content
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="content_category" class="form-label small fw-bold text-secondary">Target Placement
                            Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="content_category" name="content_category" required>
                            <option selected disabled value="">Select placement block...</option>
                            <option value="Home Page">Home Page</option>
                            <option value="History">History</option>
                            <option value="Content">About - Content</option>
                            <option value="Emergency Hotlines">Emergency Hotlines (Home Page)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="TxtTitle" class="form-label small fw-bold text-secondary">Content Block Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="TxtTitle" name="TxtTitle"
                            placeholder="Enter headline/header title..." required>
                    </div>

                    <div class="col-12">
                        <div id="DescGroup" style="display: none;">
                            <label class="form-label small fw-bold text-secondary mb-1">Body Description / Content
                                Copy</label>
                            <div class="editor-wrapper shadow-sm mb-1">
                                <div id="quillDesc" class="quill-editor-full" style="height: 150px;"></div>
                            </div>
                            <input type="hidden" id="addTxtDesc" name="TxtDesc" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div id="AboutImgGrp" style="display: none;">
                            <label for="AboutImg" class="form-label small fw-bold text-secondary">Feature Illustration /
                                Banner Image</label>
                            <input type="file" class="form-control" id="AboutImg" name="AboutImg" accept="image/*">
                            <div class="form-text text-muted">Accepted extensions: PNG, JPG, JPEG, WEBP.</div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Content</button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" class="modal-content border-0 shadow-lg" enctype="multipart/form-data">
            <input type="hidden" id="editAboutId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify System Display Content
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="edit_content_category" class="form-label small fw-bold text-secondary">Target
                            Placement Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_content_category" name="edit_content_category" required>
                            <option disabled value="">Select placement block...</option>
                            <option value="Home Page">Home Page</option>
                            <option value="History">History</option>
                            <option value="Content">About - Content</option>
                            <option value="Emergency Hotlines">Emergency Hotlines</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="EditTxtTitle" class="form-label small fw-bold text-secondary">Content Block Title
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="EditTxtTitle" name="EditTxtTitle"
                            placeholder="Enter headline/header title..." required>
                    </div>

                    <div class="col-12">
                        <div id="EditDescGroup" style="display: none;">
                            <label class="form-label small fw-bold text-secondary mb-1">Body Description / Content
                                Copy</label>
                            <div class="editor-wrapper shadow-sm mb-1">
                                <div id="editQuillDesc" class="quill-editor-full" style="height: 150px;"></div>
                            </div>
                            <input type="hidden" id="EditTxtDesc" name="EditTxtDesc" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <div id="EditAboutImgGrp" style="display: none;">
                            <label for="EditAboutImg" class="form-label small fw-bold text-secondary">Update Graphic
                                Asset File</label>
                            <input type="file" class="form-control" id="EditAboutImg" name="EditAboutImg"
                                accept="image/*">
                            <div id="edit_img_preview"
                                class="mt-3 p-2 border border-dashed rounded bg-light text-center"
                                style="max-width: 250px;">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Changes</button>
            </div>

        </form>
    </div>
</div>
