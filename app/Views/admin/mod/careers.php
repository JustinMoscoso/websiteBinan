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
                        <label class="form-label small fw-bold text-secondary">Search Publication Date</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <select class="form-select bg-light border-secondary-subtle" id="careerSearchMonth"
                                    aria-label="Publication month">
                                    <option value="">Select Month</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <select class="form-select bg-light border-secondary-subtle" id="careerSearchYear"
                                    aria-label="Publication year">
                                    <option value="">Select Year</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" id="careerPublicationDateSearch" name="publication_date">
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Level</label>
                        <select class="form-select bg-light border-secondary-subtle" name="level"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">Select Level</option>
                            <option value="1">Level 1</option>
                            <option value="2">Level 2</option>
                            <option value="3">Level 1 &amp; 2</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-select bg-light border-secondary-subtle" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">Select Status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
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
                                    data-bs-toggle="modal" data-bs-target="#careerModal" style="height: 38px;">
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

<div class="modal fade" id="careerModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="careerForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="careerId" name="id">
            <input type="hidden" id="careerMode" name="mode" value="add">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;" id="careerModalTitle">Add Record</h5>
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
                            <option value="3">Level 1 &amp; 2</option>
                        </select>
                    </div>
                    <div class="col-12 mt-3">
                        <label for="careerFile" class="form-label small fw-bold text-secondary">Upload Document
                        </label>
                        <input type="file" class="form-control shadow-sm" id="careerFile" name="careerFile"
                            accept=".pdf,application/pdf" required>
                        <div class="form-text text-muted" style="font-size: 0.75rem;">Supported type: .pdf only
                        </div>
                        <div class="form-text text-muted d-none" id="currentCareerFileWrap" style="font-size: 0.75rem;">
                            Current file: <span id="currentCareerFileName"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnCareerSave" type="submit" class="btn btn-theme px-4">Save</button>
            </div>
        </form>
    </div>
</div>