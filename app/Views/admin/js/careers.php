<script>
    const userLevel = '<?= $user->user_lvl ?>'.toUpperCase(); // Get user level from backend and force uppercase
    console.log("Current User Role:", userLevel);

    if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') {
        $('.button-32').show();
    } else {
        $('.button-32').hide();
    }

    if (userLevel === 'VIEWER') {
        // Hide add/save buttons on the page layout level
        $('[data-bs-target="#careerModal"]').hide();

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

    // Toggle Status function
    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'Career'),
            text: "Are you sure you want to " + actionText + " this career?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#c0392b',
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Please wait...',
                    showConfirmButton: false,
                    backdrop: true,
                    scrollbarPadding: false,
                    allowEscapeKey: () => !Swal.isLoading(),
                    allowOutsideClick: () => !Swal.isLoading(),
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.post("<?php echo site_url('admin/ajax/set_status_career') ?>",
                    { id: id, 'status': newStatus },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: statusSuccessText('Career', actionText)
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.msg,
                            });
                        }
                    }
                );
            }
        });
    }

    // Delete function
    function deleteCareer(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Delete Career',
            text: "Are you sure you want to delete this career? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#7f8c8d',
            confirmButtonText: 'Yes, Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    showConfirmButton: false,
                    backdrop: true,
                    scrollbarPadding: false,
                    allowEscapeKey: () => !Swal.isLoading(),
                    allowOutsideClick: () => !Swal.isLoading(),
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                $.post("<?php echo site_url('admin/ajax/delete_careers') ?>",
                    { id: id },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Career deleted successfully'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to delete career',
                            });
                        }
                    }
                );
            }
        });
    }

    // Set maximum date for input
    if (document.getElementById('publication')) {
        document.getElementById('publication').max = new Date().toISOString().split("T")[0];
    }

    function isPdfFile(file) {
        if (!file) {
            return false;
        }

        var fileName = (file.name || '').toLowerCase();
        var fileType = (file.type || '').toLowerCase();

        return fileName.endsWith('.pdf') || fileType === 'application/pdf';
    }

    function resetCareerModalState() {
        $('#careerForm')[0].reset();
        $('#careerId').val('');
        $('#careerMode').val('add');
        $('#careerModalTitle').text('Add Career Entry');
        $('#btnCareerSave').text('Save');
        $('#careerFile').prop('required', true);
        $('#currentCareerFileWrap').addClass('d-none');
        $('#currentCareerFileName').text('');
    }

    function openCareerModal(mode, record) {
        resetCareerModalState();

        if (mode === 'edit' && record) {
            $('#careerMode').val('edit');
            $('#careerModalTitle').text('Modify Career Entry');
            $('#btnCareerSave').text('Update');
            $('#careerId').val(record.ID || record.id || '');
            $('#publication').val(record.publication_date || '');
            $('#level').val(record.level || '');
            $('#careerFile').prop('required', false);
            if (record.file_name) {
                $('#currentCareerFileName').text(record.file_name);
                $('#currentCareerFileWrap').removeClass('d-none');
            }
        }

        $('#careerModal').modal('show');
    }

    function submitCareerForm() {
        let mode = ($('#careerMode').val() || 'add').toLowerCase();
        let form = $('#careerForm')[0];
        let formData = new FormData(form);
        let careerFile = formData.get('careerFile');

        formData.set('id', $('#careerId').val());

        if (!formData.get('publication') || !formData.get('level')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }

        if (mode === 'add') {
            if (!careerFile || !careerFile.name) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload a PDF file.'
                });
                return;
            }
        }

        if (careerFile && careerFile.name && !isPdfFile(careerFile)) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload a PDF file only.'
            });
            return;
        }

        let url = mode === 'edit'
            ? '<?php echo site_url('admin/ajax/update_career'); ?>'
            : '<?php echo site_url('admin/ajax/create_career'); ?>';

        Swal.fire({
            title: 'Please wait...',
            showConfirmButton: false,
            backdrop: true,
            scrollbarPadding: false,
            allowEscapeKey: () => !Swal.isLoading(),
            allowOutsideClick: () => !Swal.isLoading(),
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#careerModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message || (mode === 'edit' ? 'Career updated successfully.' : 'Data saved!')
                    });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Request failed.'
                    });
                    tbl.ajax.reload(null, false);
                }
            },
            error: function (xhr, statusText, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseText || errorThrown || statusText || 'An error occurred while processing your request.'
                });
            }
        });
    }

    $('#careerForm').on('submit', function (e) {
        e.preventDefault();
        submitCareerForm();
    });

    $('#careerModal').on('hidden.bs.modal', function () {
        resetCareerModalState();
    });

    function edit(id) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_career'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    let res = response.data; // Directly access the data object
                    openCareerModal('edit', res);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Career not found.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch details. Please try again later.'
                });
            }
        });
    }

    var tbl = $('#tblcareer').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_career'); ?>",
            "type": "POST",
            "data": function (d) {
                d.search_kw = $('form#careerSearchForm input[name="search"]').val();
                d.level     = $('form#careerSearchForm select[name="level"]').val();
                d.status    = $('form#careerSearchForm select[name="status"]').val();
            }
        },
        initComplete: function () {
            var searchInput = $('#tblcareer_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search careers...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });
            
            var lengthSelect = $('#tblcareer_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            {
                "title": "Level", "data": "level",
                "render": function (data, type, row) {
                    if (data == 1) return 'Level 1';
                    if (data == 2) return 'Level 2';
                    if (data == 3) return 'Level 1 & 2';
                    return '-';
                }
            },
            {
                "title": "Publication Date", "data": "publication_date",
                "render": function (data, type, row) {
                    return moment(data).format('MMMM D, YYYY');
                }
            },
            {
                "title": "File", "data": "file_name",
                "render": function (data, type, row) {
                    var fileUrl = "<?php echo base_url('admin/preview_file/CAREERS/'); ?>" + encodeURIComponent(data);
                    return '<a href="' + fileUrl + '" target="_blank">Preview</a>';
                }
            },
            {
                "title": "Status",
                "data": "status",
                "className": "dt-center",
                width: '10%',
                "render": function (data, type, row) {
                    var status = data;
                    if (status == 'ACTIVE') {
                        return '<span class="status-badge status-badge-active"><span class="status-dot status-dot-active"></span>Active</span>';
                    } else if (status == 'INACTIVE') {
                        return '<span class="status-badge status-badge-inactive"><span class="status-dot status-dot-inactive"></span>Inactive</span>';
                    } else {
                        return '<span class="status-badge status-badge-archived"><span class="status-dot status-dot-archived"></span>Archived</span>';
                    }
                }
            },
            {
                "title": "Actions",
                "data": "ID",
                "className": "dt-center",
                "render": function (data, type, row) {
                    if (userLevel === 'VIEWER') {
                        return `<a class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" href="#" onclick="edit(${row.ID}); return false;" style="width: 32px; height: 32px; border-radius: 50%;" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>`;
                    }
                    let actionHtml = `
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                            <i class="bi bi-list"></i> Actions
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="edit(${row.ID}); return false;"><i class="bi bi-pencil me-1"></i> Edit</a></li>`;

                    if ((userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') && row.status !== 'ARCHIVED') {
                        var statusIcon = row.status === 'ACTIVE' ? 'bi-toggle-on' : 'bi-toggle-off';
                        var statusText = row.status === 'ACTIVE' ? 'Deactivate' : 'Activate';

                        actionHtml += `
                            <li><a class="dropdown-item" href="#" onclick="toggleStatus(${row.ID}, '${row.status}')"><i class="bi ${statusIcon} me-1"></i> ${statusText}</a></li>`;
                    }
                    actionHtml += renderArchiveRestoreAction(userLevel, row, 'toggleStatus');
                    actionHtml += renderDeleteAction(userLevel, row.ID, 'deleteCareer');

                    actionHtml += `</ul></div>`;
                    return actionHtml;
                }
            }
        ]
    });

    var sltdRow = null;

    $('#tblcareer tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Advanced Search form — submit reloads table with filters
    $('#careerSearchForm').on('submit', function (e) {
        e.preventDefault();
        tbl.ajax.reload();
    });
    // Clear filters — reset then reload
    $('#careerSearchForm').on('reset', function () {
        setTimeout(function () { tbl.ajax.reload(); }, 0);
    });

</script>
