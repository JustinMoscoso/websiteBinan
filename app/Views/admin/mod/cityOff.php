<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">City Officials</h1>

    </div>
</div>


<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if (in_array($user->user_lvl, ['ADMIN', 'SUPERADMIN', 'DEVELOPER'])): ?>
    <div class="card card-premium mb-4 border-start border-4"
        style="border-start-color: var(--theme-mid-green) !important;">

        <div class="card-body p-4">
            <form id="cityoffSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Keyword</label>
                        <div class="input-group">

                            <input type="text" class="form-control border-start-0" name="search"
                                placeholder="Search Name / Position">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Category</label>
                        <select class="form-select bg-light border-secondary-subtle" name="position"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">Select Categories</option>
                            <option value="CONGRESS">Congress</option>
                            <option value="CITY MAYOR">City Mayor</option>
                            <option value="CITY VICE MAYOR">City Vice Mayor</option>
                            <option value="CITY COUNCILOR">City Councilor</option>
                            <option value="ABC PRESIDENT">ABC President</option>
                            <option value="SK FEDERATION PRESIDENT">SK Federation President</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-select bg-light border-secondary-subtle" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option selected value="">Select Status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                            <option value="ARCHIVED">Archived</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <div class="row g-2 admin-filter-actions">


                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100  fw-semibold shadow-sm"
                                    id="cityoffSearchBtn" style="height: 38px;">
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
                        <table id="tbloff" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
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
                    <i class="bi bi-person-plus me-2"></i>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row g-4">

                    <div class="col-12 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm p-4 rounded-3 bg-white">
                            <h6 class="small text-uppercase fw-bold text-muted border-bottom pb-2 mb-3">Identity
                                Specifications</h6>

                            <div class="mb-3">
                                <label for="offname" class="form-label small fw-bold text-secondary">Official Full Name
                                    <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="offname" name="offname"
                                    placeholder="Firstname M.I. Lastname" required>
                            </div>

                            <div class="mb-3">
                                <label for="offpos" class="form-label small fw-bold text-secondary">Official Designation
                                    Position <span class="text-danger">*</span></label>
                                <select id="offpos" class="form-select" name="offpos" required>
                                    <option selected disabled value="">Choose position role</option>
                                    <option value="CONGRESS">Congress</option>
                                    <option value="CITY MAYOR">City Mayor</option>
                                    <option value="CITY VICE MAYOR">City Vice Mayor</option>
                                    <option value="CITY COUNCILOR">City Councilor</option>
                                    <option value="ABC PRESIDENT">ABC President</option>
                                    <option value="SK FEDERATION PRESIDENT">SK Federation President</option>
                                </select>
                            </div>

                            <div class="mb-3" id="rankField" style="display: none;">
                                <label for="offrank" class="form-label small fw-bold text-secondary">Council Ranking
                                    <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="offrank" name="offrank"
                                    placeholder="Rank sequence (1 to 12)" min="1" max="12" step="1">
                            </div>

                            <div class="mb-3">
                                <label for="offimg" class="form-label small fw-bold text-secondary">Primary Profile
                                    Image Badge <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="offimg" name="offimg" accept="image/*"
                                    required>
                            </div>

                            <div class="mb-0">
                                <label for="offcaroimg" class="form-label small fw-bold text-secondary">Gallery Slider
                                    Assets (Max 3)</label>
                                <input type="file" class="form-control" id="offcaroimg" name="offcaroimg[]"
                                    accept="image/*" multiple>
                                <div class="form-text text-muted small">You may pick up to 3 showcase images.</div>
                                <div id="addCarouselPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm p-4 bg-white">
                            <h6 class="small text-uppercase fw-bold text-muted border-bottom pb-2 mb-3">Biography
                                </h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Years of
                                    Service</label>
                                <div class="editor-wrapper">
                                    <div id="years_of_service" style="height: 220px;"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Personal Data
                                    </label>
                                <div class="editor-wrapper">
                                    <div id="personal_data" style="height: 220px;"></div>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label small fw-bold text-secondary mb-1">Awards &
                                    Distinctions</label>
                                <div class="editor-wrapper">
                                    <div id="awards" style="height: 220px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnAdd" type="submit" class="btn btn-success px-4">Save</button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editCOId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-pencil-square me-2"></i>Modify Official Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row g-4">

                    <div class="col-12 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm p-4 rounded-3 bg-white">
                            <h6 class="small text-uppercase fw-bold text-muted border-bottom pb-2 mb-3">Identity
                                </h6>

                            <div class="mb-3">
                                <label for="editoffname" class="form-label small fw-bold text-secondary">Official Full
                                    Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editoffname" name="editoffname"
                                    placeholder="Firstname M.I. Lastname" required>
                            </div>

                            <div class="mb-3">
                                <label for="editoffpos" class="form-label small fw-bold text-secondary">Official
                                    Designation Position <span class="text-danger">*</span></label>
                                <select id="editoffpos" class="form-select" name="editoffpos" required>
                                    <option disabled value="">Choose position role</option>
                                    <option value="CONGRESS">Congress</option>
                                    <option value="CITY MAYOR">City Mayor</option>
                                    <option value="CITY VICE MAYOR">City Vice Mayor</option>
                                    <option value="CITY COUNCILOR">City Councilor</option>
                                    <option value="ABC PRESIDENT">ABC President</option>
                                    <option value="SK FEDERATION PRESIDENT">SK Federation President</option>
                                </select>
                            </div>

                            <div class="mb-3" id="editRankField" style="display: none;">
                                <label for="editoffrank" class="form-label small fw-bold text-secondary">Council Ranking
                                    <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editoffrank" name="editoffrank"
                                    placeholder="Rank sequence (1 to 12)" min="1" max="12" step="1">
                            </div>

                            <div class="mb-3">
                                <label for="editoffimg" class="form-label small fw-bold text-secondary">Update Profile
                                    Image Badge</label>
                                <input type="file" class="form-control" id="editoffimg" name="editoffimg"
                                    accept="image/*">
                                <div id="editImagePreview" class="mt-2"></div>
                            </div>

                            <div class="mb-0">
                                <label for="editoffcaroimg" class="form-label small fw-bold text-secondary">Replace
                                    Gallery Slider Assets (Max 3)</label>
                                <input type="file" class="form-control" id="editoffcaroimg" name="editoffcaroimg[]"
                                    accept="image/*" multiple>
                                <div id="carouselPreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-flex flex-column gap-3">
                        <div class="card border-0 shadow-sm p-4 bg-white">
                            <h6 class="small text-uppercase fw-bold text-muted border-bottom pb-2 mb-3">Biography
                                Information Portals</h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Chronological Years of
                                    Service</label>
                                <div class="editor-wrapper">
                                    <div id="edit_years_of_service" style="height: 220px;"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary mb-1">Personal Background Data
                                    Document</label>
                                <div class="editor-wrapper">
                                    <div id="edit_personal_data" style="height: 220px;"></div>
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label small fw-bold text-secondary mb-1">Accredited Awards &
                                    Distinctions</label>
                                <div class="editor-wrapper">
                                    <div id="edit_awards" style="height: 220px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3 border-top">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Save</button>
            </div>

        </form>
    </div>
</div>

<script>
    (function () {
        const bindCarouselLimit = (inputId) => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('change', function () {
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