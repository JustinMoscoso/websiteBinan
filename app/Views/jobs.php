<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Job Openings</title>
  <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
  <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
  <?php pre_styles('home'); ?>
</head>
<body>
<?php include "navbar.php"; ?>
<?php include "header.php"; ?>
<?php include_header('Job Openings',null,[
    'layout' => 'side',
    'bg_color' => '#388e3c']); ?>

<section data-aos="fade-up" class="py-5" id="jobs-section">
  <div class="container">
    <!-- Search and Filter Bar Start -->
    <div class="row mb-4">
      <div class="col-md-6 col-lg-4 mb-3">
        <form action="<?= base_url('/jobs') ?>" method="get" id="searchForm">
          <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search job titles or companies..." 
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button class="btn btn-success" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>
        <small class="text-muted mt-1 d-block">
          <i class="fas fa-info-circle me-1"></i>
          Search by job title or company name only
        </small>
      </div>
      <div class="col-md-6 col-lg-4 mb-3">
        <form action="<?= base_url('/jobs') ?>" method="get" id="filterForm">
          <input type="hidden" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
          <select class="form-select" name="company" onchange="this.form.submit()">
            <option value="">All Companies</option>
            <?php 
            // Get unique companies from jobs
            $companies = [];
            if (!empty($jobs)) {
              foreach ($jobs as $job) {
                if (!empty($job['company']) && !in_array($job['company'], $companies)) {
                  $companies[] = $job['company'];
                }
              }
            }
            foreach ($companies as $comp): ?>
              <option value="<?= htmlspecialchars($comp) ?>" 
                      <?= (isset($_GET['company']) && $_GET['company'] === $comp) ? 'selected' : '' ?>>
                <?= htmlspecialchars($comp) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </form>
      </div>
      <div class="col-md-6 col-lg-4 mb-3">
        <div class="d-flex gap-2">
          <a href="<?= base_url('/jobs') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-times me-1"></i>Clear Filters
          </a>
          <span class="badge badge-green-custom align-self-center" id="resultCount">
            <?= count($jobs) ?> job<?= count($jobs) != 1 ? 's' : '' ?> found
          </span>
        </div>
      </div>
    </div>
    <!-- Search and Filter Bar End -->

    <div class="row" id="jobsContainer">
      <?php if (isset($error)): ?>
        <div class="col-12">
          <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?= esc($error) ?>
          </div>
        </div>
      <?php elseif (empty($jobs)): ?>
        <div class="col-12">
          <div class="text-center py-5">
            <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No Job Openings Available</h4>
            <p class="text-muted">Check back later for new opportunities.</p>
          </div>
        </div>
      <?php else: ?>
        <?php foreach($jobs as $job): ?>
          <div class="col-md-6 col-lg-4 mb-4 job-item" 
               data-title="<?= strtolower(esc($job['title'])) ?>"
               data-company="<?= strtolower(esc($job['company'] ?? '')) ?>"
               data-description="<?= strtolower(esc($job['description'])) ?>">
            <div class="card shadow-sm job-card" style="cursor: pointer;" 
                 data-job='<?= json_encode([
                   "title" => $job["title"],
                   "description" => $job["description"],
                   "company" => $job["company"] ?? "",
                   "publication_date" => $job["publication_date"] ?? "",
                   "email" => $job["email"] ?? "",
                   "type" => $job["type"] ?? ""
                 ], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>'>
              <div class="card-body d-flex flex-column p-4">
                <h5 class="job-card-title"><?= esc($job['title']) ?></h5>
                <p class="job-card-company">
                  <i class="fas fa-building me-1 text-secondary"></i>
                  <strong>Company:</strong> <?= !empty($job['company']) ? esc($job['company']) : 'N/A' ?>
                </p>
                <p class="job-card-description">
                  <?= htmlspecialchars(substr(strip_tags($job['description']), 0, 300)) ?>...
                </p>
                <div class="job-card-footer-section">
                  <div class="job-card-badges">
                    <?php if (!empty($job['type'])): ?>
                      <span class="badge job-card-badge <?= $job['type'] === 'Full Time' ? 'badge-dark-green-custom' : 'badge-green-custom' ?>">Type: <?= esc($job['type']) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($job['publication_date'])): ?>
                      <span class="badge job-card-badge badge-light-green-custom">
                        <i class="fas fa-calendar me-1"></i>
                        <?= date('M d, Y', strtotime($job['publication_date'])) ?>
                      </span>
                    <?php endif; ?>
                  </div>
                  <div class="job-card-footer">
                    <small class="text-muted d-flex align-items-center">
                      <i class="fas fa-info-circle me-2 text-success"></i>
                      Click to view full details
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Job Details Modal -->
<div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="jobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header modal-header-green-gradient-custom text-white">
        <h5 class="modal-title" id="jobModalLabel">
          <i class="fas fa-briefcase me-2"></i>Job Details
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <h4 id="modalJobTitle" class="fw-bold text-success mb-3"></h4>
            
            <div class="row mb-3">
              <div class="col-md-6">
                <p class="mb-1"><strong>Company:</strong> <span id="modalCompany"></span></p>
                <p class="mb-1"><strong>Publication Date:</strong> <span id="modalPublicationDate"></span></p>
              </div>
              <div class="col-md-6">
                <p class="mb-1"><strong>Job Type:</strong> <span id="modalType"></span></p>
              </div>
            </div>
            
            <div class="mb-4">
              <h6 class="fw-bold text-dark mb-2">Job Description:</h6>
              <div id="modalDescription" class="p-3 bg-light rounded" style="line-height: 1.6;"></div>
            </div>
            
            <div id="modalEmailSection" class="border-top pt-3">
              <h6 class="fw-bold text-dark mb-2">How to Apply:</h6>
              <p class="mb-2">Send your resume and application to:</p>
              <a id="modalEmailLink" href="#" class="modal-email-link">
                <i class="fas fa-envelope me-2"></i>
                <span id="modalEmail"></span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "footer.php"; ?>
<?php pre_scripts('home'); ?>

<script>
$(document).ready(function() {
    // Make job cards clickable
    $(document).on('click', '.job-card', function() {
        var jobData = $(this).data('job');
        
        // Populate modal with job data
        $('#modalJobTitle').text(jobData.title);
        $('#modalCompany').text(jobData.company || 'N/A');
        $('#modalPublicationDate').text(jobData.publication_date ? new Date(jobData.publication_date).toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        }) : 'N/A');
        $('#modalType').text(jobData.type || 'N/A');
        $('#modalDescription').html(jobData.description);
        
        // Handle email section
        if (jobData.email) {
            $('#modalEmail').text(jobData.email);
            $('#modalEmailLink').attr('href', 'mailto:' + jobData.email);
            $('#modalEmailSection').show();
        } else {
            $('#modalEmailSection').hide();
        }
        
        // Show modal
        $('#jobModal').modal('show');
    });
    

});

$('#jobModal').on('shown.bs.modal', function () {
    $(this).attr('aria-hidden', 'false');
});
$('#jobModal').on('hidden.bs.modal', function () {
    $(this).attr('aria-hidden', 'true');
});
</script>

<style>
.job-card {
    height: 380px; /* Fixed height to make all cards identical in size */
    transition: all 0.3s ease;
    border: 1px solid #e0e0e0 !important;
    border-radius: 12px !important;
    overflow: hidden;
}

.job-card:hover {
    border-color: #388e3c !important;
    box-shadow: 0 8px 24px rgba(56, 142, 60, 0.15) !important;
    transform: translateY(-4px);
}

.job-card-title {
    color: var(--color1) !important; /* Theme color */
    font-size: 1.15rem;
    font-weight: 700;
    line-height: 1.4;
    height: 3.2rem; /* Exact height for 2 lines */
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 0.5rem;
}

.job-card-company {
    font-size: 0.875rem;
    color: #6c757d;
    height: 1.25rem;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    margin-bottom: 0.75rem;
}

.job-card-description {
    font-size: 0.9rem;
    color: #495057;
    line-height: 1.6;
    height: 4.8rem; /* Exact height for 3 lines */
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    text-align: justify;
    margin-bottom: 1.25rem;
}

.job-card-footer-section {
    margin-top: auto;
}

.job-card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
}

.job-card-badge {
    padding: 6px 12px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
}

.job-card-footer {
    border-top: 1px solid #f1f3f5;
    padding-top: 12px;
}

.modal-email-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 8px 18px;
    border: 2px solid var(--color1);
    border-radius: 8px;
    color: var(--color1) !important;
    font-weight: 600;
    font-size: 0.9rem;
    background-color: transparent;
    text-decoration: none;
    transition: background-color 0.2s ease, color 0.2s ease;
}

.modal-email-link:hover {
    background-color: #f0faf0;
    color: var(--color1) !important;
    text-decoration: none;
}

#modalDescription {
    max-height: 300px;
    overflow-y: auto;
}

.modal-header {
    background: linear-gradient(135deg, #28a745, #20c997);
}

/* Responsive adjustments */
@media (max-width: 740px) {
    .input-group {
        margin-bottom: 10px;
    }
    
    #clearFilters {
        width: 100%;
        margin-bottom: 10px;
    }
    
    #resultCount {
        text-align: center;
        width: 100%;
    }
}

.bg-light-green {
    background-color: #b9f6ca !important; /* Light green shade */
    color: #1b5e20 !important;            /* Dark green text for contrast */
}

.badge-green {
    background-color: #43a047 !important; /* Medium green */
    color: #fff !important;
}
.badge-dark-green {
    background-color: #388e3c !important; /* Dark green */
    color: #fff !important;
}
.badge-light-green {
    background-color: #b9f6ca !important; /* Light green */
    color: #1b5e20 !important;
}
.badge-yellow-green {
    background-color: #d4e157 !important; /* Yellow-green */
    color: #33691e !important;
}
.modal-header-green-gradient {
    background: linear-gradient(135deg, #388e3c, #43a047) !important;
    color: #fff !important;
}

/* Custom green badge and header classes */
.badge-green-custom {
    background-color: #4caf50 !important;
    color: #fff !important;
}
.badge-dark-green-custom {
    background-color: var(--color1) !important;
    color: #fff !important;
}
.badge-light-green-custom {
    background-color: #e8f5e9 !important;
    color: #2e7d32 !important;
}
.modal-header-green-gradient-custom {
    background: linear-gradient(135deg, var(--color1), #4caf50) !important;
    color: #fff !important;
}
</style>

</body>
</html> 