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

        var jobsModalMode = 'add';
        var pendingJobDescription = '';

        function resetJobsModalState() {
            jobsModalMode = 'add';
            pendingJobDescription = '';
            $('#addForm')[0].reset();
            $('#jobId').val('');
            $('#jobMode').val('add');
            $('#jobModalTitle').text('Add Job');
            $('#btnAdd').text('Save');
            $('#publication_date').val(moment().format('YYYY-MM-DD'));
            if (QuillManager.getQuillInstance('jobsDesc')) {
                QuillManager.setQuillContent('jobsDesc', '');
            }
        }

        function openJobModal(mode, job) {
            jobsModalMode = mode;
            $('#jobMode').val(mode);
            $('#jobModalTitle').text(mode === 'edit' ? 'Edit Job' : 'Add Job');
            $('#btnAdd').text(mode === 'edit' ? 'Update' : 'Save');

            if (mode === 'edit' && job) {
                $('#jobId').val(job.ID || job.id || '');
                $('#title').val(job.title || '');
                $('#company').val(job.company || '');
                $('#type').val(job.type || '');
                $('#publication_date').val(job.publication_date || moment().format('YYYY-MM-DD'));
                $('#email').val(job.email || '');
                pendingJobDescription = job.description || '';
            } else {
                resetJobsModalState();
            }

            $('#addModal').modal('show');
        }

        // Initialize Quill editors for this page
        QuillManager.initPageQuillEditors({
            editors: [
                {
                    elementId: 'quillDescription',
                    instanceName: 'jobsDesc',
                    modalId: 'addModal'
                }
            ]
        });

        // Setup form submission handlers
        QuillManager.setupQuillFormHandlers({
            formHandlers: [
                {
                    buttonId: 'btnAdd',
                    instanceName: 'jobsDesc',
                    hiddenInputId: 'description'
                }
            ]
        });

        $('#addModal').on('shown.bs.modal', function () {
            if (jobsModalMode === 'edit') {
                setTimeout(function () {
                    QuillManager.setQuillContent('jobsDesc', pendingJobDescription);
                    pendingJobDescription = '';
                    QuillManager.updateQuillFormContent();
                }, 0);
            }
        });

        $('#addForm').on('submit', function (e) {
            e.preventDefault();
        });

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
                            return  data;
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
            "responsive": false,
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
        $('#btnAdd').click(function (e) {
            e.preventDefault();
            // Update Quill content before form submission
            QuillManager.updateQuillFormContent();

            let form = $('#addForm')[0];
            let formData = new FormData(form);
            let isEdit = $('#jobMode').val() === 'edit';
            let jobId = $('#jobId').val();

            if (isEdit) {
                formData.set('id', jobId);
            }

            $.ajax({
                url: isEdit ? '<?php echo site_url('admin/ajax/update_job'); ?>' : '<?php echo site_url('admin/ajax/create_job'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === 1) {
                        $('#addModal').modal('hide');
                        resetJobsModalState();
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

            $.ajax({
                url: '<?php echo site_url('admin/ajax/get_job'); ?>',
                type: 'POST',
                data: { id: jobId },
                success: function (response) {
                    if (response.status === 1) {
                        var job = response.data;
                        openJobModal('edit', job);
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

        // Set today's date as default for publication date
        $('#publication_date').val(moment().format('YYYY-MM-DD'));

        // Clear form when modal is closed
        $('#addModal').on('hidden.bs.modal', function () {
            resetJobsModalState();
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
