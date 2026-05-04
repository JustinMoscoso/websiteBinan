<div class="pagetitle">
  <h1>System Logs</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
      <li class="breadcrumb-item active">System Logs</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<div class="container-fluid py-3">
    <div class="card mb-4">
        <div class="card-body">
            <form id="auditLogSearchForm">
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="searchAction" placeholder="Search Action...">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="searchDate">
                    </div>
                    <div class="col-md-1">
                        <button type="reset" class="btn btn-danger w-100">Clear Filters</button>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary w-100" id="searchBtn">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<section class="section">
    <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <!-- Table -->
                <div class="table-responsive">
                  <table id="tblaudit" class="table table-hover" cellspacing="0" width="100%">
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
</section>

