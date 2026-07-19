<?= view('admin/js/philippine_contact_inputs') ?>

<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Barangay Management</h1>

    </div>
</div>


<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
<style>
    /* Keep the barangay name left-aligned. */
    #tblbrgy tbody td:nth-child(1),
    #tblbrgy tbody td:nth-child(1) * {
        text-align: left !important;
    }

    /* Center Captain and Status, including their rendered child elements. */
    #tblbrgy th.brgy-captain-cell,
    #tblbrgy td.brgy-captain-cell,
    #tblbrgy td.brgy-captain-cell *,
    #tblbrgy th.brgy-status-cell,
    #tblbrgy td.brgy-status-cell,
    #tblbrgy td.brgy-status-cell * {
        text-align: center !important;
        vertical-align: middle !important;
    }

    .brgy-logo-thumb {
        width: 100%;
        height: 96px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 0.25rem;
    }

    .brgy-logo-thumb img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        display: block;
    }

    #tblbrgy td.brgy-logo-cell {
        vertical-align: middle;
    }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <form id="barangaySearchForm" onsubmit="return runBarangayAdvancedSearch(event);">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-4">
                        <label class="form-label small fw-bold text-secondary">Search Query</label>
                        <div class="input-group">

                            <input type="text"  class="form-control" id="searchBrgy"
                                placeholder="Search Barangay / Captain">
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-select bg-light border-secondary-subtle filter-input" name="status"
                            style="height: 38px; cursor: pointer; width: 200px;">
                            <option selected value="">Select Status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="row g-2">

                            <!-- Clear -->


                            <!-- Search -->
                            <div class="col-12 col-md-4">
                                <button type="button" class="btn btn-primary w-100 fw-semibold shadow-sm"
                                    id="barangaySearchBtn" onclick="return runBarangayAdvancedSearch(event);"
                                    style="height: 38px;">
                                    Search
                                </button>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="reset" class="btn btn-danger border w-100 fw-semibold text-white"
                                    style="height: 38px;">
                                    Clear
                                </button>
                            </div>

                            <!-- Add Record -->
                            <div class="col-12 col-md-4">
                                <button type="button"
                                    class="btn btn-success w-100 fw-semibold text-white text-nowrap shadow-sm"
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
                        <table id="tblbrgy" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="addModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true"
    style="overflow-y: auto;">
    <div class="modal-dialog modal-xl custom-wide-modal my-3">
        <form id="addForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="brgyId" name="id">
            <input type="hidden" id="brgyMode" name="mode" value="add">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;" id="brgyModalTitle">Add Record</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="txtBrgy" class="form-label small fw-bold text-secondary">Barangay Name</label>
                        <input type="text" class="form-control shadow-sm" id="txtBrgy" name="txtBrgy"
                            placeholder="Enter barangay name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="txtCapt" class="form-label small fw-bold text-secondary">Barangay Captain</label>
                        <input type="text" class="form-control shadow-sm" id="txtCapt" name="txtCapt"
                            placeholder="Enter full name" required>
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">About</label>
                        <div id="createabout" style="height: 150px;"></div>
                        <input type="hidden" id="createAbout" name="createAbout">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">Mission</label>
                        <div id="txtMission" style="height: 150px;"></div>
                        <input type="hidden" name="txtMission">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label small fw-bold text-secondary">Vision</label>
                        <div id="txtVision" style="height: 150px;"></div>
                        <input type="hidden" name="txtVision">
                    </div>

                    <div class="col-12 mt-2">
                        <fieldset class="border rounded p-3 bg-light">
                            <legend class="float-none w-auto px-2 mb-2 fs-6 fw-bold text-secondary">Contact Information</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="txtPhoneNumber" class="form-label small fw-bold text-secondary">Phone Number</label>
                                    <input type="tel" class="form-control shadow-sm" id="txtPhoneNumber" name="txtPhoneNumber"
                                        inputmode="tel" maxlength="16" placeholder="+63 9XX XXX XXXX">
                                </div>
                                <div class="col-md-6">
                                    <label for="txtLandline" class="form-label small fw-bold text-secondary">Landline</label>
                                    <input type="tel" class="form-control shadow-sm" id="txtLandline" name="txtLandline"
                                        inputmode="tel" maxlength="15" placeholder="(049) 123-4567 or (02) 1234-5678">
                                </div>
                                <div class="col-md-6">
                                    <label for="txtEmailAddress" class="form-label small fw-bold text-secondary">Email Address</label>
                                    <input type="email" class="form-control shadow-sm" id="txtEmailAddress" name="txtEmailAddress"
                                        maxlength="255" placeholder="e.g. barangay@binan.gov.ph">
                                </div>
                                <div class="col-md-6">
                                    <label for="txtOfficeAddress" class="form-label small fw-bold text-secondary">Office Address</label>
                                    <textarea class="form-control shadow-sm" id="txtOfficeAddress" name="txtOfficeAddress"
                                        rows="2" maxlength="500" placeholder="Enter the complete barangay office address"></textarea>
                                </div>
                            </div>
                            <small class="form-text text-muted mt-2">Provide at least one contact method.</small>
                        </fieldset>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label for="brgyImg" class="form-label small fw-bold text-secondary">Barangay Logo</label>
                        <input type="file" class="form-control shadow-sm" id="brgyImg" name="brgyImg" accept="image/*"
                            required>
                        <div id="addBrgyLogoPreview" class="mt-2"></div>
                    </div>

					<div class="col-md-6 mt-2">
						<label for="brgyOrgChart" class="form-label small fw-bold text-secondary">Organizational Chart</label>
						<input type="file" class="form-control shadow-sm" id="brgyOrgChart" name="brgyOrgChart"
							accept="image/png,image/jpeg,image/gif,image/webp">
						<div class="form-text text-muted">Accepted formats: PNG, JPG, JPEG, GIF, or WEBP. Maximum 4 MB.</div>
						<div id="addBrgyOrgChartPreview" class="mt-2"></div>
					</div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                <button id="btnBrgySave" type="submit" class="btn btn-success px-4">Save</button>
            </div>
        </form>
    </div>
</div>
