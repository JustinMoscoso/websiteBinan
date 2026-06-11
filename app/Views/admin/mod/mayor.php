<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Mayor's Corner</h1>
        <nav>
            <ol class="breadcrumb mb-0 bg-transparent p-2" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard'); ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Mayor's Corner</li>
            </ol>
        </nav>
    </div>

</div>


<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
    <div class="card card-premium mb-4 border-start border-4"
        style="border-start-color: var(--theme-mid-green) !important;">

        <div class="card-body p-4">
            <form id="mayorSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                        <div class="input-group">

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
                        <div class="row g-2">




                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm"
                                    id="mayorSearchBtn" style="height: 38px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger w-100 fw-semibold" style="height: 38px;">
                                    Clear
                                </button>
                            </div>


                            <div class="col-12 col-md-4">
                                <button type="button" class="btn btn-success w-100 fw-semibold text-white shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#addModal" style="height: 38px;">
                                    <i class="bi bi-plus-circle me-1"></i>Add Record
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
<?php endif; ?>

<section class=" section">
    <div class="row">
        <div class="col-12">
            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Executive
                        Profile Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblmayor" class="table table-bordered table-hover align-middle w-100"
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
                    <i class="bi bi-plus-circle me-2"></i>Add Executive Profile Record
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="content_category" class="form-label small fw-bold text-secondary">
                            Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="content_category" name="content_category" required>
                            <option selected disabled value="">Choose profile section...
                            </option>
                            <option value="Personal Data">Personal Data</option>
                            <option value="Awards">Awards</option>
                            <option value="Years Service">Years Service</option>
                            <option value="Gallery">Gallery</option>
                            <option value="Home Page">Home Page</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="myrname" class="form-label small fw-bold text-secondary">Full Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="myrname" name="myrname"
                            placeholder="e.g. Hon. John Doe" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary mb-1">Personal
                            Data
                            <span class="text-danger">*</span></label>
                        <div class="editor-wrapper shadow-sm">
                            <div id="addPerdataEditor" style="height: 180px;"></div>
                        </div>
                        <input type="hidden" id="addPerdataHidden" name="perdata" required>
                    </div>

                    <div class="col-12">
                        <label for="mayorimg" class="form-label small fw-bold text-secondary">Media Assets
                            / Press
                            Gallery Files</label>
                        <input type="file" class="form-control" id="mayorimg" name="mayorimg[]" accept="image/*"
                            multiple>
                        <div class="form-text text-muted">Multi-file uploading enabled.
                            Supported document formats: PNG,
                            JPG, JPEG, WEBP.</div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save
                </button>
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
                        <label for="editmyrname" class="form-label small fw-bold text-secondary">Full Name of
                            Mayor
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editmyrname" name="editmyrname"
                            placeholder="Update identity name profile..." required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary mb-1">Personal
                            Data / Narrative Biography
                            <span class="text-danger">*</span></label>
                        <div class="editor-wrapper shadow-sm">
                            <div id="editperdataEditor" style="height: 180px;"></div>
                        </div>
                        <input type="hidden" id="editperdataHidden" name="editperdata" required>
                    </div>

                    <div class="col-12">
                        <label for="editmayorimg" class="form-label small fw-bold text-secondary">Upload
                            Replacements
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
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update
                    Records</button>
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