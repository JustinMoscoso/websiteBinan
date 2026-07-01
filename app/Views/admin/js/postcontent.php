<script src="<?= base_url('assets/admin/js/quill-init.js') ?>"></script>
<script>
    const userLevel = '<?= $user->user_lvl ?>'.toUpperCase(); // Get user level from backend and force uppercase
    const isMayorDeptAdmin = <?= json_encode($is_mayor_dept_admin ?? false) ?>;
    console.log("Current User Role:", userLevel, "| Mayor Dept Admin:", isMayorDeptAdmin);

    if ((userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') && !isMayorDeptAdmin) {
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
            setTimeout(function () {
                $modal.find('.ql-editor').attr('contenteditable', 'false');
                $modal.find('.ql-toolbar').hide();
            }, 100);
            setTimeout(function () {
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
            title: statusActionTitle(newStatus, 'Content'),
            text: "Are you sure you want to " + actionText + " this content?",
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
                $.post("<?php echo site_url('admin/ajax/set_status_postcontent') ?>",
                    { id: id, 'status': newStatus },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: statusSuccessText('Content', actionText)
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
    function deletePostContent(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Delete Content',
            text: "Are you sure you want to delete this content? This action cannot be undone.",
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
                $.post("<?php echo site_url('admin/ajax/delete_postcontent') ?>",
                    { id: id },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Content deleted successfully'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to delete content',
                            });
                        }
                    }
                );
            }
        });
    }

    // Initialize Quill editors — single shared instance on addModal
    QuillManager.initPageQuillEditors({
        editors: [
            {
                elementId: 'quillDesc',
                instanceName: 'postcontentDesc',
                modalId: 'addModal'
            }
        ]
    });

    // Sync Quill content into hidden input before any submit
    QuillManager.setupQuillFormHandlers({
        formHandlers: [
            {
                buttonId: 'btnAdd',
                instanceName: 'postcontentDesc',
                hiddenInputId: 'addDescHidden'
            }
        ]
    });

    function formatPostContentDateTime(value) {
        if (!value) {
            return '';
        }

        var raw = String(value).trim();
        var mysqlMatch = raw.match(/^(\d{4}-\d{2}-\d{2})(?:[ T](\d{2}:\d{2}:\d{2}))?/);
        if (mysqlMatch) {
            return mysqlMatch[1] + (mysqlMatch[2] ? ' ' + mysqlMatch[2] : '');
        }

        var date = new Date(raw);
        if (isNaN(date.getTime())) {
            return raw;
        }

        var pad = function (num) {
            return String(num).padStart(2, '0');
        };

        return date.getFullYear() + '-' +
            pad(date.getMonth() + 1) + '-' +
            pad(date.getDate()) + ' ' +
            pad(date.getHours()) + ':' +
            pad(date.getMinutes()) + ':' +
            pad(date.getSeconds());
    }

    // Datatable
    var tbl = $('#tblnews').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_postcontent'); ?>",
            "type": "POST",
            "data": function (d) {
                d.search = $('input[name="search"]').val();
                d.category = $('select[name="category"]').val();
                d.status = $('select[name="status"]').val();
            },
            "dataSrc": function (json) {
                if (json.data && Array.isArray(json.data)) {
                    return json.data;
                } else {
                    return [];
                }
            }
        },
        initComplete: function () {
            var searchInput = $('#tblnews_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search posts...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });

            var lengthSelect = $('#tblnews_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            { "title": "Title", "data": "title" },
            { "title": "Author Name", "data": "author", width: '15%' },
            {
                "title": "Date Created", "data": "created_date",
                "render": function (data, type, row) {
                    var date = new Date(data);
                    return formatDate(date);
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
            { "title": "Category", "data": "category", "className": "dt-center" },
            {
                "title": "Image", "data": "file_loc", "className": "dt-center", width: '15%',
                "render": function (data, type, row) {
                    return '<img id="img_loc" class="img-fluid mt-3" src="<?php echo base_url('admin/image/POSTCONTENT/') ?>' + data + '">';
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
                    actionHtml += renderDeleteAction(userLevel, row.ID, 'deletePostContent');

                    actionHtml += `</ul></div>`;
                    return actionHtml;
                }
            },
        ]
    });

    var sltdRow = null;

    $('#tblnews tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Search form submit: reload table with filters
    $('#docSearchForm').on('submit', function (e) {
        e.preventDefault();
        console.log('Search button clicked, reloading table...');
        tbl.ajax.reload();
    });
    // Clear filters: reload table
    $('#docSearchForm').on('reset', function () {
        console.log('Clear Filters button clicked, reloading table...');
        setTimeout(function () {
            tbl.ajax.reload();
        }, 0);
    });

    // ── openPostModal helper ──────────────────────────────────────────────────
    function openPostModal(mode, record) {
        $('#recordMode').val(mode);

        if (mode === 'add') {
            $('#recordModalTitle').text('Add Post');
            $('#btnAddLabel').text('Save');
            $('#recordId').val('');
            $('#addForm')[0].reset();
            $('.edit-only-field').addClass('d-none');
            var quill = QuillManager.getQuillInstance('postcontentDesc');
            if (quill) quill.setContents([]);
        } else {
            $('#recordModalTitle').html('<i class="bi bi-pencil-square me-2"></i>Edit Post');
            $('#btnAddLabel').text('Update');
            $('#recordId').val(record.ID || record.id);
            $('#content_category').val(record.category);
            $('#title').val(record.title);
            $('#addDescHidden').val(record.description || '');
            $('#editCreatedDate').val(formatPostContentDateTime(record.created_date));
            $('#editUpdatedDate').val(formatPostContentDateTime(record.updated_date));

            // Show date fields
            $('.edit-only-field').removeClass('d-none');

            // Load Quill content after modal is shown
            $('#addModal').one('shown.bs.modal', function () {
                var quill = QuillManager.getQuillInstance('postcontentDesc');
                if (quill) {
                    quill.root.innerHTML = record.description || '';
                }
            });
        }
        $('#addModal').modal('show');
    }

    // Reset modal on close
    $('#addModal').on('hidden.bs.modal', function () {
        $('#recordMode').val('add');
        $('#recordId').val('');
        $('#recordModalTitle').text('Add Post');
        $('#btnAddLabel').text('Save');
        $('#addForm')[0].reset();
        $('.edit-only-field').addClass('d-none');
        var quill = QuillManager.getQuillInstance('postcontentDesc');
        if (quill) quill.setContents([]);
    });

    // Add new post content
    $('#btnAdd').on('click', function () {
        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();

        var mode = $('#recordMode').val();
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('title') || !formData.get('desc') || !formData.get('content_category')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }

        // Image required only for add
        if (mode === 'add') {
            let imageFile = formData.get('newsImg');
            if (!imageFile || imageFile.size === 0) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please upload an image.' });
                return;
            }
            const maxImageSizeMB = 4;
            if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: `Image size should not exceed ${maxImageSizeMB} MB.` });
                return;
            }
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validImageTypes.includes(imageFile.type)) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please upload a valid image file (jpg, png, gif).' });
                return;
            }
        } else {
            // validate edit image only if a file was selected
            let editImg = formData.get('newsImg');
            if (editImg && editImg.size > 0) {
                const maxImageSizeMB = 4;
                if (editImg.size > maxImageSizeMB * 1024 * 1024) {
                    Swal.fire({ icon: 'warning', title: 'Validation Error', text: `Image size should not exceed ${maxImageSizeMB} MB.` });
                    return;
                }
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validImageTypes.includes(editImg.type)) {
                    Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please upload a valid image file (jpg, png, gif).' });
                    return;
                }
            }
        }

        var url, successText;
        if (mode === 'edit') {
            formData.set('id', $('#recordId').val());
            // Map shared field names to what the update endpoint expects
            formData.set('edit_content_category', formData.get('content_category'));
            formData.set('editTitle', formData.get('title'));
            formData.set('editDesc', formData.get('desc'));
            // Rename file field for the update endpoint
            var imgFile = formData.get('newsImg');
            if (imgFile && imgFile.size > 0) {
                formData.set('editNewsImg', imgFile);
            }
            url = '<?php echo site_url('admin/ajax/update_postcontent'); ?>';
            successText = 'Content updated successfully.';
        } else {
            url = '<?php echo site_url('admin/ajax/create_postcontent'); ?>';
            successText = 'Data saved!';
        }

        Swal.fire({
            title: 'Please wait...',
            showConfirmButton: false,
            backdrop: true,
            scrollbarPadding: false,
            allowEscapeKey: () => !Swal.isLoading(),
            allowOutsideClick: () => !Swal.isLoading(),
            willOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Success', text: successText });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.message || 'Data not created. Refresh the page or try logging in again.'
                    });
                    tbl.ajax.reload(null, false);
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while processing your request. Please try again.' });
            }
        });
    });

    function edit(id) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_postcontent'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    openPostModal('edit', response.data);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'Data not found.' });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to fetch details. Please try again later.' });
            }
        });
    }
</script>
