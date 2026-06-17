<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Job Management</h1>

    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
    <div class="card card-premium mb-4 border-start border-4"
        style="border-start-color: var(--theme-mid-green) !important;">

        <div class="card-body p-4">
            <form id="jobsSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0" name="search"
                                placeholder="Search Title / Company / Email">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Category</label>
                        <select class="form-control form-select bg-light border-secondary-subtle" name="type"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Job Categories</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-control form-select bg-light border-secondary-subtle" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">All Status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="row g-2 admin-filter-actions">

                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 flex-grow-1 fw-semibold shadow-sm"
                                    id="jobsSearchBtn" style="height: 38px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger w-100 flex-grow-1 fw-semibold"
                                    style="height: 38px;">
                                    Clear
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="button"
                                    class="btn btn-success w-100 shadow-sm fw-semibold text-nowrap flex-grow-1"
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
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-table fa-sm fa-fw text-success me-2"></i>Job Postings Directory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tbljobs" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="addForm" class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="title" class="form-label small fw-bold text-secondary">Job Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="title" name="title"
                            placeholder="e.g. Senior Administrative Assistant" required>
                    </div>

                    <div class="col-md-6">
                        <label for="company" class="form-label small fw-bold text-secondary">Company / Department <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="company" name="company"
                            placeholder="Enter department name" required>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label small fw-bold text-secondary">Job Type <span
                                class="text-danger">*</span></label>
                        <select class="form-control form-select shadow-sm" id="type" name="type" required>
                            <option value="" selected disabled>Select Job Type</option>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="publication_date" class="form-label small fw-bold text-secondary">Publication Date
                            <span class="text-danger">*</span></label>
                        <input type="date" class="form-control shadow-sm" id="publication_date" name="publication_date"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label small fw-bold text-secondary">Contact Email <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control shadow-sm" id="email" name="email"
                            placeholder="hr@department.gov.ph" required>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="description" class="form-label small fw-bold text-secondary">Job Description <span
                                class="text-danger">*</span></label>
                        <div id="quillDescription" class="quill-editor-full border rounded-3 bg-white shadow-sm"
                            style="height: 160px;"></div>
                        <input type="hidden" id="description" name="description" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-success px-4">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editJobId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify Job Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="editTitle" class="form-label small fw-bold text-secondary">Job Title <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="editTitle" name="title" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editCompany" class="form-label small fw-bold text-secondary">Company / Department
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm" id="editCompany" name="company" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editType" class="form-label small fw-bold text-secondary">Job Type <span
                                class="text-danger">*</span></label>
                        <select class="form-control form-select shadow-sm" id="editType" name="type" required>
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="editPublicationDate" class="form-label small fw-bold text-secondary">Publication
                            Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control shadow-sm" id="editPublicationDate"
                            name="publication_date" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editEmail" class="form-label small fw-bold text-secondary">Contact Email <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control shadow-sm" id="editEmail" name="email" required>
                    </div>

                    <div class="col-12">
                        <label for="editDescription" class="form-label small fw-bold text-secondary">Job Description
                            <span class="text-danger">*</span></label>
                        <div id="editQuillDescription" class="quill-editor-full border rounded-3 bg-white shadow-sm"
                            style="height: 160px;"></div>
                        <input type="hidden" id="editDescription" name="description" required>
                    </div>


                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Details</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-eye me-2"></i>Job Information View
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Job Title</label>
                        <div id="viewTitle" class="view-field-box fw-bold text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Company / Department</label>
                        <div id="viewCompany" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Job Type</label>
                        <div id="viewType" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Publication Date</label>
                        <div id="viewPublicationDate" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Contact Email</label>
                        <div id="viewEmail" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <div id="viewStatus" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Created Date</label>
                        <div id="viewCreatedDate" class="view-field-box text-dark"></div>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Job Description</label>
                        <div class="border rounded-3 p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                            <div id="viewDescription" class="text-dark"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-2">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close View</button>
            </div>
        </div>
    </div>
</div>