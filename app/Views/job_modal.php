<!-- Job Details Modal -->
<div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="jobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
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
              <div id="modalDescription" class="p-3 bg-light rounded" style="white-space: pre-wrap; line-height: 1.6;"></div>
            </div>
            <div id="modalEmailSection" class="border-top pt-3">
              <h6 class="fw-bold text-dark mb-2">How to Apply:</h6>
              <p class="mb-2">Send your resume and application to:</p>
              <a id="modalEmailLink" href="#" class="btn btn-outline-success">
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
        $('#modalDescription').text(jobData.description);
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
    // Add hover effect to job cards
    $(document).on('mouseenter', '.job-card', function() {
        $(this).addClass('shadow-lg').css('transform', 'translateY(-2px)');
    }).on('mouseleave', '.job-card', function() {
        $(this).removeClass('shadow-lg').css('transform', 'translateY(0)');
    });
});
</script>

<style>
.job-card {
    transition: all 0.3s ease;
}

.job-card:hover {
    border-color: #28a745 !important;
}

#modalDescription {
    max-height: 300px;
    overflow-y: auto;
}

.modal-header {
    background: linear-gradient(135deg, #28a745, #20c997);
}

/* Responsive adjustments */
@media (max-width: 768px) {
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
</style> 