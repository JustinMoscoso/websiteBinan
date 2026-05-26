<div class="pagetitle d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h3 fw-bold mb-1" style="color: #1b4d3e;">Full Disclosure Policy Management</h1>
        <nav>
            <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="<?php echo site_url('admin/dashboard'); ?>"
                        class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item active fw-semibold" style="color: #2d6a4f;">Full Disclosure Policy</li>
            </ol>
        </nav>
    </div>
    <button type="button" class="btn btn-theme shadow-sm px-4 fw-semibold transition-all" data-bs-toggle="modal"
        data-bs-target="#addModal">
        <i class="bi bi-file-earmark-plus me-2"></i>Add Policy
    </button>
</div>

<style>
    /* Premium Theme Context Variables */
    :root {
        --theme-dark-green: #1b4d3e;
        --theme-mid-green: #2d6a4f;
        --theme-light-green: #d8f3dc;
        --theme-accent: #20c997;
    }

    /* Primary Action Elements Custom Theme Styling */
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

    /* Premium Design Interface Container Cards */
    .card-premium {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        background: #ffffff;
    }

    /* Refined Context Filtering Elements */
    .filter-input {
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.45rem 0.75rem;
        font-size: 0.9rem;
        transition: all 0.2s ease-in-out;
    }

    .filter-input:focus {
        border-color: var(--theme-mid-green);
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.15);
        outline: 0;
    }

    /* Integrated Global DataTables Search Box Styling */
    .dataTables_filter input[type="search"] {
        width: 320px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 0.45rem 0.75rem;
        font-size: 0.9rem;
        margin-left: 10px;
        transition: all 0.2s ease-in-out;
    }

    .dataTables_filter input[type="search"]:focus {
        border-color: var(--theme-mid-green);
        box-shadow: 0 0 0 0.25rem rgba(45, 106, 79, 0.15);
        outline: 0;
    }

    /* Uniform Layout Structural Data Tables styling */
    #tblfdp th {
        color: #555;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eaedf1;
        padding: 12px 16px;
    }

    #tblfdp td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    .transition-all {
        transition: all 0.2s ease;
    }
</style>

<?php if ($user->user_lvl !== 'VIEWER'): ?>
    <div class="card card-premium mb-4">
        <div class="card-body p-4">
            <form id="docSearchForm">
                <div class="row g-3 align-items-end">

                    <div class="col-xl-3 col-lg-3 col-md-12">
                        <label class="form-label small fw-bold text-secondary">Search Query</label>
                        <input type="text" class="form-control filter-input" name="search"
                            placeholder="Search File Name / Year...">
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-4">
                        <label class="form-label small fw-bold text-secondary">Frequency</label>
                        <select class="form-select filter-input" name="frequency">
                            <option selected value="">- Frequency -</option>
                            <option value="ANNUAL">Annual</option>
                            <option value="QUARTERLY">Quarterly</option>
                        </select>
                    </div>

                    <div class="col-xl-4 col-lg-3 col-md-5">
                        <label class="form-label small fw-bold text-secondary">File Category</label>
                        <select class="form-select filter-input" name="file_category" style="font-size: 0.85rem;">
                            <option selected value="">- File Category -</option>
                            <optgroup label="Annual Reports">
                                <option value="Annual Budget Report">Annual Budget Report</option>
                                <option value="Annual Procurement Plan or Procurement List">Annual Procurement Plan or
                                    Procurement List</option>
                                <option value="Supplemental Procurement Plan">Supplemental Procurement Plan</option>
                                <option value="Annual Gender and Development Accomplishment Report">Annual Gender and
                                    Development Accomplishment Report</option>
                            </optgroup>
                            <optgroup label="Quarterly Reports">
                                <option value="Quarterly Statement of Cash Flow">Quarterly Statement of Cash Flow</option>
                                <option value="Statement of Receipts and Expenditures">Statement of Receipts and
                                    Expenditures</option>
                                <option value="20% Component of the Internal Revenue Allotment Utilization">20% Component of
                                    the Internal Revenue Allotment Utilization</option>
                                <option value="Local Disaster Risk Reduction and Management Fund Utilization">Local Disaster
                                    Risk Reduction and Management Fund Utilization</option>
                                <option value="Report of Special Education Fund Utilization">Report of Special Education
                                    Fund Utilization</option>
                                <option value="Trust Fund (PDAF) Utilization">Trust Fund (PDAF) Utilization</option>
                                <option value="Unliquidated Cash Advances">Unliquidated Cash Advances</option>
                                <option value="Bid Results on Civil Works and Goods and Services">Bid Results on Civil Works
                                    and Goods and Services</option>
                                <option value="Manpower Complement">Manpower Complement</option>
                                <option value="Annual Statement of Indebtedness, Payments and Balances">Annual Statement of
                                    Indebtedness, Payments and Balances</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="col-xl-1 col-lg-2 col-md-3">
                        <label class="form-label small fw-bold text-secondary">Status</label>
                        <select class="form-select filter-input" name="status">
                            <option selected value="">- Status -</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-12 d-flex gap-2">
                        <button type="reset" class="btn btn-light filter-input fw-semibold text-secondary w-50"
                            style="height: 40px; padding: 0;">
                            Clear
                        </button>
                        <button type="submit" class="btn btn-theme fw-semibold w-50" id="searchBtn"
                            style="height: 40px; padding: 0;">
                            Search
                        </button>
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
                        <table id="tblfdp" class="table table-striped table-hover align-middle w-100" cellspacing="0">
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
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Add Policy</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-xl-4-5 col-lg-4 col-md-5">
                        <label class="form-label small fw-bold text-secondary">File Category</label>
                        <select class="form-select filter-input" name="file_category">
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
                        <input type="text" class="yearpicker form-control shadow-sm" id="yr" name="yr"
                            placeholder="Select Year" required>
                    </div>

                    <div class="col-md-6">
                        <label for="qtr" class="form-label small fw-bold text-secondary">Quarter</label>
                        <select class="form-select shadow-sm" id="qtr" name="qtr" required>
                            <option value="" selected disabled>Choose Quarter</option>
                            <option value="First">First Quarter</option>
                            <option value="Second">Second Quarter</option>
                            <option value="Third">Third Quarter</option>
                            <option value="Fourth">Fourth Quarter</option>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="policyFile" class="form-label small fw-bold text-secondary">Upload Document</label>
                        <input type="file" class="form-control shadow-sm" id="policyFile" name="policyFile"
                            accept=".pdf,.xls,.xlsx" required>
                        <div class="form-text text-muted" style="font-size: 0.78rem;">Accepted formats: PDF, XLS, XLSX
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Close</button>
                <button id="btnAdd" type="submit" class="btn btn-theme px-4">Save Policy</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editForm" class="modal-content border-0 shadow-lg">
            <input type="hidden" id="editPolicyId" name="id">

            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" style="font-size: 1.1rem;">Edit Policy Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="editFileCategory" class="form-label small fw-bold text-secondary">File
                            Category</label>
                        <select class="form-select shadow-sm" id="editFileCategory" name="editFileCategory" required>
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
                        <label for="edityr" class="form-label small fw-bold text-secondary">Year</label>
                        <input type="text" class="yearpicker form-control shadow-sm" id="edityr" name="edityr" required>
                    </div>

                    <div class="col-md-6">
                        <label for="editqtr" class="form-label small fw-bold text-secondary">Quarter</label>
                        <select class="form-select shadow-sm" id="editqtr" name="editqtr" required>
                            <option value="First">First Quarter</option>
                            <option value="Second">Second Quarter</option>
                            <option value="Third">Third Quarter</option>
                            <option value="Fourth">Fourth Quarter</option>
                        </select>
                    </div>

                    <div class="col-12 mt-2">
                        <label for="editpolicyFile" class="form-label small fw-bold text-secondary">Replace
                            Document</label>
                        <input type="file" class="form-control shadow-sm" id="editpolicyFile" name="editpolicyFile"
                            accept=".pdf,.xls,.xlsx">
                        <div class="form-text text-muted" style="font-size: 0.78rem;">Leave blank if you wish to retain
                            the current file.</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Close</button>
                <button id="btnEdit" type="submit" class="btn btn-theme px-4">Update Policy</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white px-4 py-3" style="background-color: var(--theme-dark-green);">
                <h5 class="modal-title fw-bold" id="filePreviewModalLabel"><i class="bi bi-eye me-2"></i>Document
                    Preview</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
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