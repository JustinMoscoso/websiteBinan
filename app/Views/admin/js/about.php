<script src="<?= base_url('assets/admin/js/quill-init.js') ?>"></script>
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
<script src="<?= base_url('assets/admin/js/quill-init.js') ?>"></script>
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

    // Initialize Quill editors for this page — single shared instance
    QuillManager.initPageQuillEditors({
        editors: [
            {
                elementId: 'quillDesc',
                instanceName: 'aboutDesc',
                modalId: 'addModal',
                shouldInit: function () {
                    return $('#DescGroup').is(':visible');
                }
            }
        ]
    });

    // Sync Quill content into the hidden input before any submit
    QuillManager.setupQuillFormHandlers({
        formHandlers: [
            {
                buttonId: 'btnAdd',
                instanceName: 'aboutDesc',
                hiddenInputId: 'addTxtDesc'
            }
        ]
    });

    // Category change handler (shared modal)
    // Always reset image group first to avoid stale-visibility when switching categories
    $('#content_category').on('change', function () {
        var selectedCategory = $(this).val();

        // Unconditionally hide & clear image upload every time category changes
        $('#AboutImgGrp').hide();
        // Clear the file input by replacing it (val('') alone doesn't reliably clear file inputs)
        $('#AboutImg').val('');
        $('#edit_img_preview').addClass('d-none').html('');

        if (selectedCategory === 'Content' || selectedCategory === 'Home Page' || selectedCategory === 'Emergency Hotlines') {
            $('#DescGroup').show();
            // Image group stays hidden (already hidden above)
            if (!QuillManager.getQuillInstance('aboutDesc')) {
                QuillManager.initQuillEditor('quillDesc', 'aboutDesc');
            }
        } else if (selectedCategory === 'History') {
            $('#DescGroup').show();
            $('#AboutImgGrp').show(); // Only History reveals the image upload
            if (!QuillManager.getQuillInstance('aboutDesc')) {
                QuillManager.initQuillEditor('quillDesc', 'aboutDesc');
            }
        } else {
            // No category selected — hide everything
            $('#DescGroup').hide();
        }
    });

    // ── openAboutModal helper ──────────────────────────────────────────────────
    function openAboutModal(mode, record) {
        $('#recordMode').val(mode);

        if (mode === 'add') {
            $('#recordModalTitle').text('Add Content');
            $('#btnAddLabel').text('Save');
            $('#recordId').val('');
            $('#addForm')[0].reset();
            $('#DescGroup, #AboutImgGrp').hide();
            $('#edit_img_preview').addClass('d-none').html('');
            var quill = QuillManager.getQuillInstance('aboutDesc');
            if (quill) quill.setContents([]);
        } else {
            // edit mode — populate shared fields from record
            $('#recordModalTitle').html('<i class="bi bi-pencil-square me-2"></i>Edit Content');
            $('#btnAddLabel').text('Update');
            $('#recordId').val(record.ID || record.id);
            $('#content_category').val(record.section).trigger('change');
            $('#TxtTitle').val(record.title);
            $('#addTxtDesc').val(record.description || '');

            // Load Quill content after modal is shown
            $('#addModal').one('shown.bs.modal', function () {
                if (record.section !== 'Header') {
                    if (!QuillManager.getQuillInstance('aboutDesc')) {
                        QuillManager.initQuillEditor('quillDesc', 'aboutDesc');
                    }
                    setTimeout(function () {
                        var quill = QuillManager.getQuillInstance('aboutDesc');
                        if (quill && record.description) {
                            quill.root.innerHTML = record.description;
                        }
                    }, 100);
                }
            });

            // Show image preview if available
            if (record.about_img) {
                $('#edit_img_preview').removeClass('d-none').html(
                    '<img src="<?php echo base_url('admin/image/ABOUT/'); ?>" style="max-width:100%">'
                        .replace('"', '"') // placeholder; actual image built below
                );
                var imgSrc = '<?php echo base_url("admin/image/ABOUT/"); ?>' + record.about_img;
                $('#edit_img_preview').removeClass('d-none').html('<img src="' + imgSrc + '" class="img-fluid">');
            } else {
                $('#edit_img_preview').addClass('d-none').html('');
            }
        }
        $('#addModal').modal('show');
    }

    // Reset shared modal on close
    $('#addModal').on('hidden.bs.modal', function () {
        $('#recordMode').val('add');
        $('#recordId').val('');
        $('#recordModalTitle').text('Add Content');
        $('#btnAddLabel').text('Save');
        $('#addForm')[0].reset();
        $('#DescGroup, #AboutImgGrp').hide();
        $('#edit_img_preview').addClass('d-none').html('');
        var quill = QuillManager.getQuillInstance('aboutDesc');
        if (quill) quill.setContents([]);
    });

    // ── Shared submit handler ──────────────────────────────────────────────────
    $('#btnAdd').on('click', function () {
        // Sync Quill content
        QuillManager.updateQuillFormContent();

        var mode = $('#recordMode').val();
        let form = $('#addForm')[0];
        let formData = new FormData(form);
        let selectedCategory = formData.get('content_category');
        let title = formData.get('TxtTitle');
        let description = formData.get('TxtDesc');
        let imageFile = formData.get('AboutImg');

        // Basic required fields
        if (!selectedCategory || !title) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please fill in all required fields.' });
            return;
        }

        if (selectedCategory === 'History') {
            if (!description) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please enter a description.' });
                return;
            }
            // Image required only on add
            if (mode === 'add' && (!imageFile || imageFile.size === 0)) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please upload an image.' });
                return;
            }
            if (imageFile && imageFile.size > 0) {
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
            }
        } else if (selectedCategory === 'Home Page' || selectedCategory === 'Content' || selectedCategory === 'Emergency Hotlines') {
            if (!description) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please enter a description.' });
                return;
            }
            formData.set('AboutImg', '');
        } else {
            formData.set('TxtDesc', '');
            formData.set('AboutImg', '');
        }

        var url, successText;
        if (mode === 'edit') {
            formData.set('id', $('#recordId').val());
            // Map shared field names to what the update endpoint expects
            formData.set('edit_content_category', selectedCategory);
            formData.set('EditTxtTitle', title);
            formData.set('EditTxtDesc', description || '');
            // File: rename AboutImg -> EditAboutImg for the update endpoint
            var imgFile = formData.get('AboutImg');
            if (imgFile && imgFile.size > 0) {
                formData.set('EditAboutImg', imgFile);
            }
            url = '<?php echo site_url("admin/ajax/update_about"); ?>';
            successText = 'Content updated successfully.';
        } else {
            url = '<?php echo site_url("admin/ajax/create_about"); ?>';
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
                        text: result.message || 'Operation failed. Refresh the page or try logging in again.'
                    });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred while processing your request. Please try again.' });
            }
        });
    });

    // ── Edit trigger ───────────────────────────────────────────────────────────
    function edit(id) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_about'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    openAboutModal('edit', response.data);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'Data not found.' });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to fetch details. Please try again later.' });
            }
        });
    }

    // Toggle Status function
    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);
        var confirmText = newStatus === 'ACTIVE' ? 'This will be displayed in the About/History section.' : 'This will not be displayed in the About/History section.';

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'Content'),
            text: "Are you sure you want to " + actionText + " this content? " + confirmText,
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
                $.post("<?php echo site_url('admin/ajax/set_status_about') ?>",
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

    function deleteAbout(id) {
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
                $.post("<?php echo site_url('admin/ajax/delete_about') ?>",
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

    // Datatable
    var tbl = $('#tblabout').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_about'); ?>",
            "type": "POST",
            "data": function (d) {
                d.search_kw = $('form#aboutSearchForm input[name="search"]').val();
                d.section   = $('form#aboutSearchForm select[name="section"]').val();
                d.status    = $('form#aboutSearchForm select[name="status"]').val();
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
            var searchInput = $('#tblabout_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search about info...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });
            
            var lengthSelect = $('#tblabout_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            { "title": "Section", "data": "section" },
            { "title": "Title", "data": "title", width: '15%' },
            {
                "title": "Description",
                "data": "description",
                "className": "dt-head-center dt-body-justify",
                width: '30%',
                "render": function (data, type, row) {
                    return data ? data : '-';
                }
            },
            {
                "title": "Image",
                "data": "about_img",
                "className": "dt-center",
                width: '15%',
                "render": function (data, type, row) {
                    return data ? '<img id="img_loc" class="img-fluid mt-3" src="<?php echo base_url('admin/image/ABOUT/') ?>' + data + '">' : '-';
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
                    actionHtml += renderDeleteAction(userLevel, row.ID, 'deleteAbout');

                    actionHtml += `</ul></div>`;
                    return actionHtml;
                }
            },
        ]
    });

    var sltdRow = null;

    $('#tblabout tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Advanced Search form — submit reloads table with filters
    $('#aboutSearchForm').on('submit', function (e) {
        e.preventDefault();
        tbl.ajax.reload();
    });
    // Clear filters — reset then reload
    $('#aboutSearchForm').on('reset', function () {
        setTimeout(function () { tbl.ajax.reload(); }, 0);
    });


</script>
