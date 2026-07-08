<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Full Disclosure Policy Management</h1>

    </div>

</div>

<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <form id="docSearchForm">

                <div class="row g-3">

                    <!-- Category -->
                    <div class="col-xl-4 col-lg-6">
                        <label class="form-label small fw-bold text-secondary">
                            Category
                        </label>

                        <select class="form-control form-select bg-light border-secondary-subtle" name="file_category"
                            style="height: 38px; cursor: pointer;">
                            <option value="">Select Categories</option>

                            <optgroup label="Annual Reports">
                                <option value="Annual Budget Report">Annual Budget Report</option>
                                <option value="Annual Procurement Plan or Procurement List">
                                    Annual Procurement Plan or Procurement List
                                </option>
                                <option value="Supplemental Procurement Plan">
                                    Supplemental Procurement Plan
                                </option>
                                <option value="Annual Gender and Development Accomplishment Report">
                                    Annual Gender and Development Accomplishment Report
                                </option>
                            </optgroup>

                            <optgroup label="Quarterly Reports">
                                <option value="Quarterly Statement of Cash Flow">
                                    Quarterly Statement of Cash Flow
                                </option>
                                <option value="Statement of Receipts and Expenditures">
                                    Statement of Receipts and Expenditures
                                </option>
                                <option value="20% Component of the Internal Revenue Allotment Utilization">
                                    20% Component of the Internal Revenue Allotment Utilization
                                </option>
                                <option value="Local Disaster Risk Reduction and Management Fund Utilization">
                                    Local Disaster Risk Reduction and Management Fund Utilization
                                </option>
                                <option value="Report of Special Education Fund Utilization">
                                    Report of Special Education Fund Utilization
                                </option>
                                <option value="Trust Fund (PDAF) Utilization">
                                    Trust Fund (PDAF) Utilization
                                </option>
                                <option value="Unliquidated Cash Advances">
                                    Unliquidated Cash Advances
                                </option>
                                <option value="Bid Results on Civil Works and Goods and Services">
                                    Bid Results on Civil Works and Goods and Services
                                </option>
                                <option value="Manpower Complement">
                                    Manpower Complement
                                </option>
                                <option value="Annual Statement of Indebtedness, Payments and Balances">
                                    Annual Statement of Indebtedness, Payments and Balances
                                </option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Frequency -->
                    <div class="col-xl-2 col-lg-3">
                        <label class="form-label small fw-bold text-secondary">
                            Frequency
                        </label>

                        <select class="form-control form-select bg-light border-secondary-subtle" name="frequency"
                            style="height: 38px; cursor: pointer;">
                            <option value="">Select Frequencies</option>
                            <option value="ANNUAL">Annual</option>
                            <option value="QUARTERLY">Quarterly</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="col-xl-2 col-lg-3">
                        <label class="form-label small fw-bold text-secondary">
                            Status
                        </label>

                        <select class="form-control form-select bg-light border-secondary-subtle" name="status"
                            style="height: 38px; cursor: pointer;">
                            <option value="">Select Status</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>

                    <!-- Buttons Row -->
                    <div class="col-12 mt-2">
                        <hr class="my-2" style="border-color: #adb5bd; opacity: 1;">

                        <div class="row justify-content-end pt-2">
                            <div class="col-xl-4 col-lg-4 col-md-12">
                                <div class="row g-2 admin-filter-actions">
                                    <div class="col-12 col-md-4">
                                        <button type="submit" id="searchBtn"
                                            class="btn btn-primary w-100 fw-semibold shadow-sm" style="height: 38px;">
                                            <i class="bi bi-search me-1"></i>
                                            Search
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <button type="reset" class="btn btn-danger w-100 fw-semibold" style="height: 38px;">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
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
                        <table id="tblfdp" class="table table-bordered table-hover align-middle w-100" cellspacing="0">
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
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">
                    <i class="bi bi-plus-circle me-2"></i><span id="policyModalTitle">Add Policy</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <input type="hidden" id="policyId" name="id">
                <input type="hidden" id="policyMode" name="mode" value="add">
                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label small fw-bold text-secondary">Category</label>
                        <select class="form-control form-select shadow-sm" id="fileCategory" name="fileCategory"
                            required>
                            <option value="" selected disabled>Choose Category</option>
                            <optgroup label="Annual Reports">
                                <option value="Annual Budget Report">Annual Budget Report</option>
                                <option value="Annual Procurement Plan or Procurement List">Annual Procurement Plan or
                                    Procurement List</option>
                                <option value="Supplemental Procurement Plan">Supplemental Procurement Plan</option>
                                <option value="Annual Gender and Development Accomplishment Report">Annual Gender and
                                    Development Accomplishment Report</option>
                            </optgroup>
                            <optgroup label="Quarterly Reports">
                                <option value="Quarterly Statement of Cash Flow">Quarterly Statement of Cash Flow
                                </option>
                                <option value="Statement of Receipts and Expenditures">Statement of Receipts and
                                    Expenditures</option>
                                <option value="20% Component of the Internal Revenue Allotment Utilization">20%
                                    Component of the Internal Revenue Allotment Utilization</option>
                                <option value="Local Disaster Risk Reduction and Management Fund Utilization">Local
                                    Disaster Risk Reduction and Management Fund Utilization</option>
                                <option value="Report of Special Education Fund Utilization">Report of Special Education
                                    Fund Utilization</option>
                                <option value="Trust Fund (PDAF) Utilization">Trust Fund (PDAF) Utilization</option>
                                <option value="Unliquidated Cash Advances">Unliquidated Cash Advances</option>
                                <option value="Bid Results on Civil Works and Goods and Services">Bid Results on Civil
                                    Works and Goods and Services</option>
                                <option value="Manpower Complement">Manpower Complement</option>
                                <option value="Annual Statement of Indebtedness, Payments and Balances">Annual Statement
                                    of Indebtedness, Payments and Balances</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="yr" class="form-label small fw-bold text-secondary">Year</label>
                        <input type="text" class="form-control shadow-sm yearpicker" id="yr" name="yr" placeholder="Select Year" readonly required>
                    </div>

                    <div class="col-md-6">
                        <label for="qtr" class="form-label small fw-bold text-secondary">Quarter</label>
                        <select class="form-control form-select shadow-sm" id="qtr" name="qtr" required>
                            <option value="" selected disabled>Choose Quarter</option>
                            <option value="First">First Quarter</option>
                            <option value="Second">Second Quarter</option>
                            <option value="Third">Third Quarter</option>
                            <option value="Fourth">Fourth Quarter</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="policyFile" class="form-label small fw-bold text-secondary">Upload Document</label>
                        <input type="file" class="form-control shadow-sm" id="policyFile" name="policyFile"
                            accept=".pdf,.xls,.xlsx" required>
                        <div class="form-text text-muted" style="font-size: 0.78rem;">Accepted formats: PDF, XLS, XLSX
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Close</button>
                <button id="btnAdd" type="submit" class="btn btn-success px-4">Save</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3 d-flex justify-content-between align-items-center w-100" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" id="filePreviewModalLabel"><i class="bi bi-eye me-2"></i>Document Preview</h5>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 78vh; background-color: #f4f6f9;">
                <iframe id="filePreviewFrame" src="" style="width:100%; height:100%; border:none;"></iframe>
            </div>
            <div class="modal-footer bg-light px-4 py-2">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close Preview</button>
            </div>
        </div>
    </div>
</div>
