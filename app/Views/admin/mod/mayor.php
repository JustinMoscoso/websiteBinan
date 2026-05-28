<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Mayor's Corner</h1>
        <nav>
            <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard'); ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Mayor's Corner</li>
            </ol>
        </nav>
    </div>
      <?php if (!(($user->user_lvl ?? '') === 'ADMIN' && ($user->account_type ?? '') === 'DEPARTMENT' && !empty($is_mayor))): ?>
        <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal"
            data-bs-target="#addModal">
            <i class="bi bi-plus-circle me-2"></i>Add Content
        </button>
      <?php endif; ?>
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
    #tblmayor th {
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaedf1;
        padding: 12px 16px;
    }

    #tblmayor td {
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

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
<div class="card card-premium mb-4 border-start border-4"
    style="border-start-color: var(--theme-mid-green) !important;">

    <div class="card-body p-4">
        <form id="mayorSearchForm">
            <div class="row g-3 align-items-end">

                <div class="col-xl-4 col-lg-4 col-md-12">
                    <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="search"
                            placeholder="Search Section / Mayor Name...">
                    </div>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-6">
                    <label class="form-label small fw-bold text-secondary">Category Filter</label>
                    <select class="form-select bg-light border-secondary-subtle" name="category"
                        style="height: 38px; cursor: pointer;">
                        <option selected value="">All Sections</option>
                        <option value="Personal Data">Personal Data</option>
                        <option value="Awards">Awards</option>
                        <option value="Years Service">Years Service</option>
                        <option value="Gallery">Gallery</option>
                        <option value="Home Page">Home Page</option>
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
                            id="mayorSearchBtn" style="height: 38px;">
                            Search
                        </button>
                        <?php if (!(($user->user_lvl ?? '') === 'ADMIN' && ($user->account_type ?? '') === 'DEPARTMENT' && !empty($is_mayor))): ?>
                            <button type="button" class="btn btn-success shadow-sm fw-semibold text-nowrap flex-grow-1"
                                data-bs-toggle="modal" data-bs-target="#addModal" style="height: 38px;">
                                <i class="bi bi-plus-circle me-1"></i>Add Content
                            </button>
                        <?php endif; ?>
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
            <div class="card card-premium">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tblmayor" class="table table-striped table-hover align-middle w-100" cellspacing="0">
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
                    <i class="bi bi-plus-circle me-2"></i>Add Executive Profile Record
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="content_category" class="form-label small fw-bold text-secondary">Information Type /
                            Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="content_category" name="content_category" required>
                            <option selected disabled value="">Choose profile section...</option>
                            <option value="Personal Data">Personal Data</option>
                            <option value="Awards">Awards</option>
                            <option value="Years Service">Years Service</option>
                            <option value="Gallery">Gallery</option>
                            <option value="Home Page">Home Page</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="myrname" class="form-label small fw-bold text-secondary">Full Name of Mayor <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="myrname" name="myrname"
                            placeholder="e.g. Hon. John Doe" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary mb-1">Personal Data / Narrative Biography
                            <span class="text-danger">*</span></label>
                        <div class="editor-wrapper shadow-sm">
                            <div id="addPerdataEditor" style="height: 180px;"></div>
                        </div>
                        <input type="hidden" id="addPerdataHidden" name="perdata" required>
                    </div>

                    <div class="col-12">
                        <label for="mayorimg" class="form-label small fw-bold text-secondary">Media Assets / Press
                            Gallery Files</label>
                        <input type="file" class="form-control" id="mayorimg" name="mayorimg[]" accept="image/*"
                            multiple>
                        <div class="form-text text-muted">Multi-file uploading enabled. Supported document formats: PNG,
                            JPG, JPEG, WEBP.</div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Executive Data</button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" class="modal-content border-0 shadow-lg" enctype="multipart/form-data">
            <input type="hidden" id="editMayorId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify Profile Configuration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="edit_content_category" class="form-label small fw-bold text-secondary">Information
                            Type / Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_content_category" name="edit_content_category" required>
                            <option disabled value="">Choose profile section...</option>
                            <option value="Personal Data">Personal Data</option>
                            <option value="Awards">Awards</option>
                            <option value="Years Service">Years Service</option>
                            <option value="Gallery">Gallery</option>
                            <option value="Home Page">Home Page</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="editmyrname" class="form-label small fw-bold text-secondary">Full Name of Mayor
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editmyrname" name="editmyrname"
                            placeholder="Update identity name profile..." required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary mb-1">Personal Data / Narrative Biography
                            <span class="text-danger">*</span></label>
                        <div class="editor-wrapper shadow-sm">
                            <div id="editperdataEditor" style="height: 180px;"></div>
                        </div>
                        <input type="hidden" id="editperdataHidden" name="editperdata" required>
                    </div>

                    <div class="col-12">
                        <label for="editmayorimg" class="form-label small fw-bold text-secondary">Upload Replacements
                            Media File assets</label>
                        <input type="file" class="form-control" id="editmayorimg" name="editmayorimg[]" accept="image/*"
                            multiple>
                        <div id="edit_img_preview"
                            class="mt-3 d-flex flex-wrap gap-2 p-2 border border-dashed rounded bg-light">
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Records</button>
            </div>

        </form>
    </div>
</div>

<script>
    /* Declared System Parameters Configured on Content Block Editors */
    var quillToolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'align': [] }],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        ['link'],
        ['clean']
    ];
</script>