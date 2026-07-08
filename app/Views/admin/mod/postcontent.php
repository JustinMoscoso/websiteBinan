<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Post Content Management</h1>

    </div>
</div>

<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4 border-start border-4"
        style="border-start-color: var(--theme-mid-green) !important;">

        <div class="card-body p-4">
            <form id="docSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0" name="search"
                                placeholder="Search Title / Author / Year">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Category</label>
                        <select class="form-select bg-light border-secondary-subtle" name="category"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">Select Categories</option>
                            <option value="NEWS">News and Events</option>
                            <option value="ANNS">Announcements</option>
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
                        <div class="row g-2 admin-filter-actions">



                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm" id="searchBtn"
                                    style="height: 38px;">
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

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblnews" class="table table-bordered table-hover w-100" cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shared Add / Edit Modal -->
<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <form id="addForm" class="modal-content border-0 shadow-lg" enctype="multipart/form-data">

            <!-- Hidden fields: record ID and mode (add | edit) -->
            <input type="hidden" id="recordId" name="id">
            <input type="hidden" id="recordMode" name="mode" value="add">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <span id="recordModalTitle">Add Post</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="content_category" class="form-label small fw-bold text-secondary">Category
                            <span class="text-danger">*</span></label>
                        <select class="form-select" id="content_category" name="content_category" required>
                            <option selected disabled value="">Select Category</option>
                            <option value="NEWS">News and Events</option>
                            <option value="ANNS">Announcements</option>
                        </select>
                    </div>

                    <div class="col-md-4 d-none">
                        <label for="author" class="form-label small fw-bold text-secondary">Author</label>
                        <input type="text" class="form-control bg-light text-muted" id="author" name="author"
                            value="<?= $user->fname . ' ' . $user->lname ?>" readonly>
                    </div>

                    <!-- Date fields — shown only in edit mode -->
                    <div class="col-md-6 edit-only-field d-none">
                        <label class="form-label small fw-bold text-secondary">Created Date</label>
                        <input type="text" class="form-control bg-light text-muted" id="editCreatedDate" readonly>
                    </div>
                    <div class="col-md-6 edit-only-field d-none">
                        <label class="form-label small fw-bold text-secondary">Last Updated</label>
                        <input type="text" class="form-control bg-light text-muted" id="editUpdatedDate" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="newsImg" class="form-label small fw-bold text-secondary">Cover Image<span
                                class="text-danger add-required-star">*</span></label>
                        <input type="file" class="form-control" id="newsImg" name="newsImg" accept="image/*">
                    </div>

                    <div class="col-12">
                        <label for="title" class="form-label small fw-bold text-secondary">Title
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title"
                            required>
                    </div>

                    <div class="col-12">
                        <label for="addDescHidden" class="form-label small fw-bold text-secondary mb-1">Content <span
                                class="text-danger">*</span></label>
                        <div class="editor-wrapper shadow-sm">
                            <div id="quillDesc" style="height: 220px;"></div>
                        </div>
                        <input type="hidden" id="addDescHidden" name="desc">
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-success px-4">
                    <span id="btnAddLabel">Save</span>
                </button>
            </div>

        </form>
    </div>
</div>