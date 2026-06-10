<script src="<?= base_url('assets/admin/js/quill-init.js') ?>"></script>
<script>
    $(document).ready(function () {
        const userLevel = '<?= $user->user_lvl ?>'.toUpperCase(); // Get user level from backend and force uppercase
        console.log("Current User Role:", userLevel);

        if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') {
            $('.button-32').show();
        } else {
            $('.button-32').hide();
        }

        if (userLevel === 'VIEWER') {
            // Hide add/save buttons on the page layout level
            $('[data-bs-target="#addModal"]').hide();

            // Lock down elements inside modals when shown
            $(document).on('show.bs.modal', '.modal', function () {
                var $modal = $(this);
                // Disable all input and form controls
                $modal.find('input, select, textarea, button').prop('disabled', true);
                // Re-enable close/dismiss buttons so viewer can close the modal
                $modal.find('button[data-bs-dismiss="modal"], .btn-close, a[data-bs-dismiss="modal"]').prop('disabled', false);
                // Hide all action/save buttons except close/dismiss buttons
                $modal.find('button, input[type="submit"], input[type="button"], a.btn').not('[data-bs-dismiss="modal"], .btn-close').hide();
                // Hide file inputs
                $modal.find('input[type="file"]').hide();
            });

            // Lock down Quill editors (prevent typing and hide toolbars)
            $(document).on('show.bs.modal shown.bs.modal', '.modal', function () {
                var $modal = $(this);
                $modal.find('.ql-editor').attr('contenteditable', 'false');
                $modal.find('.ql-toolbar').hide();
                
                // Re-enforce lock down after a short delay for dynamic content loading
                setTimeout(function() {
                    $modal.find('.ql-editor').attr('contenteditable', 'false');
                    $modal.find('.ql-toolbar').hide();
                }, 100);
                setTimeout(function() {
                    $modal.find('.ql-editor').attr('contenteditable', 'false');
                    $modal.find('.ql-toolbar').hide();
                }, 500);
            });
        }

        // Initialize Quill editors for this page
        QuillManager.initPageQuillEditors({
            editors: [
                {
                    elementId: 'quillDescription',
                    instanceName: 'jobsAddDesc',
                    modalId: 'addModal'
                },
                {
                    elementId: 'editQuillDescription',
                    instanceName: 'jobsEditDesc',
                    modalId: 'editModal'
                }
            ]
        });

        // Setup form submission handlers
        QuillManager.setupQuillFormHandlers({
            formHandlers: [
                {
                    buttonId: 'btnAdd',
                    instanceName: 'jobsAddDesc',
                    hiddenInputId: 'description'
                },
                {
                    buttonId: 'btnEdit',
                    instanceName: 'jobsEditDesc',
                    hiddenInputId: 'editDescription'
                }
            ]
        });

        // Note: Edit content population is handled manually in the edit function

        // Initialize DataTable
        var table = $('#tbljobs').DataTable({
            "ajax": {
                "url": "<?php echo site_url('admin/ajax/get_jobs'); ?>",
                "type": "POST",
                "data": function (d) {
                    d.search_kw = $('form#jobsSearchForm input[name="search"]').val();
                    d.type      = $('form#jobsSearchForm select[name="type"]').val();
                    d.status    = $('form#jobsSearchForm select[name="status"]').val();
                },
                "dataSrc": function (json) {
                    if (json.status === 1) {
                        return json.data;
                    } else {
                        console.error('Error loading jobs:', json.message);
                        return [];
                    }
                }
            },
            initComplete: function () {
                var searchInput = $('#tbljobs_filter input[type="search"]');
                searchInput.attr('placeholder', 'Search jobs...');
                searchInput.addClass('form-control form-control-sm d-inline-block');
                searchInput.css({
                    'width': '250px',
                    'margin-left': '0.5rem'
                });
                
                var lengthSelect = $('#tbljobs_length select');
                lengthSelect.addClass('form-select form-select-sm d-inline-block');
                lengthSelect.css({
                    'width': 'auto',
                    'margin': '0 0.5rem'
                });
            },
            "columns": [
                {
                    "title": "Job Title",
                    "data": "title",
                    "render": function (data, type, row) {
                        if (type === 'display') {
                            return '<strong>' + data + '</strong>';
                        }
                        return data;
                    }
                },
                {
                    "title": "Company",
                    "data": "company",
                    "render": function (data, type, row) {
                        return data || 'N/A';
                    }
                },
                {
                    "title": "Job Type",
                    "data": "type",
                    "className": "dt-center",
                    "render": function (data, type, row) {
                        if (type === 'display') {
                            if (!data) return '<div class="d-flex justify-content-center">N/A</div>';
                            if (data === 'Full Time') {
                                return '<div class="d-flex justify-content-center"><span class="status-badge jobtype-badge-fulltime"><span class="status-dot jobtype-dot-fulltime"></span>Full Time</span></div>';
                            } else if (data === 'Part Time') {
                                return '<div class="d-flex justify-content-center"><span class="status-badge jobtype-badge-parttime"><span class="status-dot jobtype-dot-parttime"></span>Part Time</span></div>';
                            } else {
                                return '<div class="d-flex justify-content-center"><span class="status-badge status-badge-archived"><span class="status-dot status-dot-archived"></span>' + data + '</span></div>';
                            }
                        }
                        return data;
                    }
                },
                {
                    "title": "Publication Date",
                    "data": "publication_date",
                    "render": function (data, type, row) {
                        if (type === 'display' && data) {
                            return moment(data).format('MMM DD, YYYY');
                        }
                        return data;
                    }
                },
                {
                    "title": "Status",
                    "data": "status",
                    "className": "dt-center",
                    width: '10%',
                    "render": function (data, type, row) {
                        if (type === 'display') {
                            var status = data;
                            if (status == 'ACTIVE') {
                                return '<span class="status-badge status-badge-active"><span class="status-dot status-dot-active"></span>Active</span>';
                            } else if (status == 'INACTIVE') {
                                return '<span class="status-badge status-badge-inactive"><span class="status-dot status-dot-inactive"></span>Inactive</span>';
                            } else {
                                return '<span class="status-badge status-badge-archived"><span class="status-dot status-dot-archived"></span>Archived</span>';
                            }
                        }
                        return data;
                    }
                },
                {
                    "title": "Actions",
                    "data": null,
                    "orderable": false,
                    "render": function (data, type, row) {
                        if (userLevel === 'VIEWER') {
                            return `<a class="btn btn-sm btn-outline-success view-job d-inline-flex align-items-center justify-content-center" href="#" data-id="${row.ID}" style="width: 32px; height: 32px; border-radius: 50%;" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>`;
                        }
                        let actionHtml = `
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                            <i class="bi bi-list"></i> Actions
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item view-job" href="#" data-id="${row.ID}"><i class="bi bi-eye me-1"></i>View Details</a></li>`;

                        if (userLevel !== 'VIEWER') {
                            actionHtml += `<li><a class="dropdown-item edit-job" href="#" data-id="${row.ID}"><i class="bi bi-pencil me-1"></i>Edit</a></li>`;

                            if ((userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') && row.status !== 'ARCHIVED') {
                                actionHtml += `
                                <li><a class="dropdown-item toggle-status" href="#" data-id="${row.ID}" data-status="${row.status}"><i class="bi bi-toggle-${row.status === 'ACTIVE' ? 'on' : 'off'} me-1"></i>${row.status === 'ACTIVE' ? 'Deactivate' : 'Activate'}</a></li>`;
                            }
                            if (row.status === 'ARCHIVED' && adminCanRestore(userLevel)) {
                                actionHtml += `<li><a class="dropdown-item toggle-status" href="#" data-id="${row.ID}" data-status="${row.status}" data-forced-status="ACTIVE"><i class="bi bi-arrow-counterclockwise me-1"></i>Restore</a></li>`;
                            } else if (row.status !== 'ARCHIVED' && adminCanArchive(userLevel)) {
                                actionHtml += `<li><a class="dropdown-item text-warning toggle-status" href="#" data-id="${row.ID}" data-status="${row.status}" data-forced-status="ARCHIVED"><i class="bi bi-archive me-1"></i>Archive</a></li>`;
                            }
                            if (adminCanDelete(userLevel)) {
                                actionHtml += `<li><hr class="dropdown-divider"></li><li><a class="dropdown-item text-danger delete-job" href="#" data-id="${row.ID}"><i class="bi bi-trash me-1"></i>Delete</a></li>`;
                            }
                        }

                        actionHtml += `
                          </ul>
                        </div>
                    `;
                        return actionHtml;
                    }
                }
            ],
            "order": [[2, "desc"]], // Sort by publication date descending
            "responsive": true,
            "language": {
                "emptyTable": "No jobs found",
                "info": "Showing _START_ to _END_ of _TOTAL_ jobs",
                "infoEmpty": "Showing 0 to 0 of 0 jobs",
                "infoFiltered": "(filtered from _MAX_ total jobs)",
                "lengthMenu": "Show _MENU_ jobs per page",
                "loadingRecords": "Loading...",
                "processing": "Processing...",
                "search": "Search jobs:",
                "zeroRecords": "No matching jobs found"
            }
        });

        // Add Job
        $('#btnAdd').click(function () {
            // Update Quill content before form submission
            QuillManager.updateQuillFormContent();

            let form = $('#addForm')[0];
            let formData = new FormData(form);

            $.ajax({
                url: '<?php echo site_url('admin/ajax/create_job'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === 1) {
                        $('#addModal').modal('hide');
                        $('#addForm')[0].reset();
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while creating the job.'
                    });
                }
            });
        });

        // View Job
        $(document).on('click', '.view-job', function () {
            var jobId = $(this).data('id');

            $.ajax({
                url: '<?php echo site_url('admin/ajax/get_job'); ?>',
                type: 'POST',
                data: { id: jobId },
                success: function (response) {
                    if (response.status === 1) {
                        var job = response.data;
                        $('#viewTitle').text(job.title);
                        $('#viewCompany').text(job.company || 'N/A');
                        $('#viewType').text(job.type || 'N/A');
                        $('#viewPublicationDate').text(moment(job.publication_date).format('MMMM DD, YYYY'));
                        $('#viewEmail').text(job.email || 'N/A');
                        $('#viewStatus').html('<span class="badge ' + (job.status === 'ACTIVE' ? 'bg-success' : 'bg-secondary') + '">' + job.status + '</span>');
                        $('#viewCreatedDate').text(moment(job.created_date).format('MMMM DD, YYYY h:mm A'));
                        $('#viewDescription').html(job.description);
                        $('#viewModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while loading job details.'
                    });
                }
            });
        });

        // Edit Job
        $(document).on('click', '.edit-job', function () {
            var jobId = $(this).data('id');

            // Store job ID globally for debugging
            window.currentEditJobId = jobId;

            $.ajax({
                url: '<?php echo site_url('admin/ajax/get_job'); ?>',
                type: 'POST',
                data: { id: jobId },
                success: function (response) {
                    if (response.status === 1) {
                        var job = response.data;

                        // Set all form fields BEFORE showing modal
                        $('#editJobId').val(job.ID);
                        $('#editTitle').val(job.title);
                        $('#editCompany').val(job.company);
                        $('#editType').val(job.type);
                        $('#editPublicationDate').val(job.publication_date);
                        $('#editEmail').val(job.email);
                        $('#editDescription').val(job.description);

                        // Debug: Log the job ID being set
                        console.log('Setting job ID:', job.ID);
                        console.log('Job ID field value after setting:', $('#editJobId').val());

                        // Show modal AFTER setting all values
                        $('#editModal').modal('show');

                        // Set Quill editor content after modal is shown
                        $('#editModal').one('shown.bs.modal', function () {
                            setTimeout(function () {
                                const quill = QuillManager.getQuillInstance('jobsEditDesc');
                                if (quill) {
                                    QuillManager.setQuillContent('jobsEditDesc', job.description);
                                } else {
                                    console.error('Quill instance not found for jobsEditDesc');
                                }
                            }, 300);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while loading job details.'
                    });
                }
            });
        });

        // Update Job
        $('#btnEdit').click(function () {
            // Update Quill content before form submission
            QuillManager.updateQuillFormContent();

            // Get the job ID from the hidden field or global variable
            var jobId = $('#editJobId').val() || window.currentEditJobId;

            // Debug: Log the job ID
            console.log('Job ID from hidden field:', $('#editJobId').val());
            console.log('Job ID from global variable:', window.currentEditJobId);
            console.log('Final job ID being used:', jobId);

            // Ensure job ID is available
            if (!jobId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Job ID is missing. Please try again.'
                });
                return;
            }

            var formData = new FormData($('#editForm')[0]);

            // Always ensure job ID is in form data
            formData.set('id', jobId);

            // Debug: Log the form data
            console.log('Final form data:', Object.fromEntries(formData));

            $.ajax({
                url: '<?php echo site_url('admin/ajax/update_job'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === 1) {
                        $('#editModal').modal('hide');
                        $('#editForm')[0].reset();
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred while updating the job.'
                    });
                }
            });
        });

        // Toggle Status
        $(document).on('click', '.toggle-status', function () {
            var jobId = $(this).data('id');
            var currentStatus = $(this).data('status');
            var newStatus = nextRecordStatus(currentStatus, $(this).data('forced-status'));

            Swal.fire({
                title: 'Confirm Status Change',
                text: `Are you sure you want to change the status to ${newStatus}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo site_url('admin/ajax/set_status_job'); ?>',
                        type: 'POST',
                        data: {
                            id: jobId,
                            status: newStatus
                        },
                        success: function (response) {
                            if (response.status === 1) {
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while updating the status.'
                            });
                        }
                    });
                }
            });
        });

        // Delete Job
        $(document).on('click', '.delete-job', function () {
            var jobId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo site_url('admin/ajax/delete_job'); ?>',
                        type: 'POST',
                        data: { id: jobId },
                        success: function (response) {
                            if (response.status === 1) {
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while deleting the job.'
                            });
                        }
                    });
                }
            });
        });

        // Set today's date as default for publication date
        $('#publication_date').val(moment().format('YYYY-MM-DD'));

        // Form validation
        $('#addForm, #editForm').on('submit', function (e) {
            e.preventDefault();
        });

        // Clear form when modal is closed
        $('#addModal').on('hidden.bs.modal', function () {
            $('#addForm')[0].reset();
            $('#publication_date').val(moment().format('YYYY-MM-DD'));
        });

        $('#editModal').on('hidden.bs.modal', function () {
            // Don't reset the form immediately, just clear the job ID
            $('#editJobId').val('');
            window.currentEditJobId = null;
        });

        // Advanced Search form — submit reloads table with filters
        $('#jobsSearchForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });
        // Clear filters — reset then reload
        $('#jobsSearchForm').on('reset', function () {
            setTimeout(function () { table.ajax.reload(); }, 0);
        });

    });
</script>