<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">About / Homepage Management</h1>
        <nav>
            <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?= base_url('/dashboard') ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">About / Homepage Management</li>
            </ol>
        </nav>
    </div>
    <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal"
        data-bs-target="#addModal">
        <i class="bi bi-plus-circle me-2"></i>Add Content
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
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
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
    #tblabout th {
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaedf1;
        padding: 12px 16px;
    }

    #tblabout td {
        padding: 14px 16px;
        vertical-align: middle;
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

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card card-premium">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tblabout" class="table table-striped table-hover align-middle w-100" cellspacing="0">
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