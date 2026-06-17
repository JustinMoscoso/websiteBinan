<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Career Management</h1>

    </div>

</div>


<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
    <div class="card card-premium mb-4 border-start border-4"
        style="border-start-color: var(--theme-mid-green) !important;">

        <div class="card-body p-4">
            <form id="careerSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0" name="search"
                                placeholder="Search Date / File Name">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Category</label>
                        <select class="form-select bg-light border-secondary-subtle" name="level"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Categories</option>
                            <option value="1">Level 1</option>
                            <option value="2">Level 2</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-select bg-light border-secondary-subtle" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="row g-2 justify-content-end admin-filter-actions">



                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100  fw-semibold shadow-sm"
                                    id="careerSearchBtn" style="height: 38px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger w-100 fw-semibold" style="height: 38px;">
                                    Clear
                                </button>
                            </div>

                            <div class="col-12 col-md-4">
                                <button type="button" class="btn btn-success w-100 shadow-sm fw-semibold text-nowrap "
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

<section class="section">
    <div class="row">
        <div class="col-12">
            <!-- SB Admin 2 Styled Card -->
            <div class="card shadow mb-4 border-top border-4"
                style="border-top-color: var(--theme-mid-green) !important;">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblcareer" class="table table-bordered table-hover align-middle w-100"
                            cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="addForm" class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="publication" class="form-label small fw-bold text-secondary">Publication
                            Date</label>
                        <input type="date" class="form-control shadow-sm" id="publication" name="publication" required>
                    </div>
                    <div class="col-md-6">
                        <label for="level" class="form-label small fw-bold text-secondary">Level</label>
                        <select class="form-select shadow-sm" id="level" name="level" required>
                            <option selected disabled value="">Select Level</option>
                            <option value="1">Level 1</option>
                            <option value="2">Level 2</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="careerFile" class="form-label small fw-bold text-secondary">Upload Document
                        </label>
                        <input type="file" class="form-control shadow-sm" id="careerFile" name="careerFile"
                            accept=".pdf,.xls,.xlsx" required>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Supported types: .pdf, .xls, .xlsx
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editCareerId" name="id">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Modify Career Entry</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="editpublication" class="form-label small fw-bold text-secondary">
                            Date</label>
                        <input type="date" class="form-control shadow-sm" id="editpublication" name="editpublication"
                            required>
                    </div>
                    <div class="col-md-6">
                        <label for="editlevel" class="form-label small fw-bold text-secondary">Category</label>
                        <select class="form-select shadow-sm" id="editlevel" name="editlevel" required>
                            <option selected disabled value="">All Category</option>
                            <option value="1">Level 1</option>
                            <option value="2">Level 2</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="editCareerFile" class="form-label small fw-bold text-secondary">Replace Document
                            Attachment</label>
                        <input type="file" class="form-control shadow-sm" id="editCareerFile" name="editCareerFile"
                            accept=".pdf,.xls,.xlsx">
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Leave empty to keep current file
                            attachment</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Changes</button>
            </div>
        </form>
    </div>
</div>