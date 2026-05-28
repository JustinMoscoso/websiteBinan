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
        // Viewer can only read
        $('input, select, button').prop('disabled', true);
        $('.btn-close').prop('disabled', false); // Allow closing modals
    }

    // Initialize Quill editors for this page
    QuillManager.initPageQuillEditors({
        editors: [
            {
                elementId: 'quillDesc',
                instanceName: 'aboutAddDesc',
                modalId: 'addModal',
                shouldInit: function () {
                    return $('#DescGroup').is(':visible');
                }
            },
            {
                elementId: 'editQuillDesc',
                instanceName: 'aboutEditDesc',
                modalId: 'editModal',
                shouldInit: function () {
                    return $('#EditDescGroup').is(':visible');
                }
            }
        ]
    });

    // Setup form submission handlers
    QuillManager.setupQuillFormHandlers({
        formHandlers: [
            {
                buttonId: 'btnAdd',
                instanceName: 'aboutAddDesc',
                hiddenInputId: 'TxtDesc'
            },
            {
                buttonId: 'btnEdit',
                instanceName: 'aboutEditDesc',
                hiddenInputId: 'EditTxtDesc'
            }
        ]
    });

    // Setup edit content population
    QuillManager.setupQuillEditHandlers({
        editHandlers: [
            {
                modalId: 'editModal',
                instanceName: 'aboutEditDesc',
                contentField: 'EditTxtDesc'
            }
        ]
    });

    // Hide show input fields depending on chosen section
    $('#content_category').on('change', function () {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'Content' || selectedCategory === 'Home Page' || selectedCategory === 'Emergency Hotlines') {
            $('#DescGroup').show();
            // Initialize Quill editor if not already initialized
            if (!QuillManager.getQuillInstance('aboutAddDesc')) {
                QuillManager.initQuillEditor('quillDesc', 'aboutAddDesc');
            }
        } else if (selectedCategory === 'History') {
            $('#DescGroup').show();
            $('#AboutImgGrp').show();
            // Initialize Quill editor if not already initialized
            if (!QuillManager.getQuillInstance('aboutAddDesc')) {
                QuillManager.initQuillEditor('quillDesc', 'aboutAddDesc');
            }
        } else {
            $('#DescGroup, #AboutImgGrp').hide();
        }
    });

    $('#edit_content_category').on('change', function () {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'Content' || selectedCategory === 'Home Page' || selectedCategory === 'Emergency Hotlines') {
            $('#EditDescGroup').show();
            // Initialize Quill editor if not already initialized
            if (!QuillManager.getQuillInstance('aboutEditDesc')) {
                QuillManager.initQuillEditor('editQuillDesc', 'aboutEditDesc');
            }
        } else if (selectedCategory === 'History') {
            $('#EditDescGroup').show();
            $('#EditAboutImgGrp').show();
            // Initialize Quill editor if not already initialized
            if (!QuillManager.getQuillInstance('aboutEditDesc')) {
                QuillManager.initQuillEditor('editQuillDesc', 'aboutEditDesc');
            }
        } else {
            $('#EditDescGroup, #EditAboutImgGrp').hide();
        }
    });

    $('#btnAdd').on('click', function () {
        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();

        let form = $('#addForm')[0];
        let formData = new FormData(form);
        let selectedCategory = formData.get('content_category');
        let title = formData.get('TxtTitle');
        let description = formData.get('TxtDesc');
        let imageFile = formData.get('AboutImg');

        // Form validation
        if (!selectedCategory || !title) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
        }

        if (selectedCategory === 'History') {
            // Description validation
            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please enter a description.'
                });
                return;
            }

            // Image validation
            if (!imageFile || imageFile.size === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload an image.'
                });
                return;
            }

            const maxImageSizeMB = 4;
            if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: `Image size should not exceed ${maxImageSizeMB} MB.`
                });
                return;
            }

            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validImageTypes.includes(imageFile.type)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload a valid image file (jpg, png, gif).'
                });
                return;
            }
        } else if (selectedCategory === 'Home Page' || selectedCategory === 'Content' || selectedCategory === 'Emergency Hotlines') {
            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please enter a description.'
                });
                return;
            }
            formData.set('AboutImg', '');
        } else {
            formData.set('TxtDesc', '');
            formData.set('AboutImg', '');
        }

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
            url: '<?php echo site_url('admin/ajax/create_about'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data saved!'
                    });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.message || 'Data not created. Refresh the page or try logging in again.',
                    });
                    tbl.ajax.reload(null, false);
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request. Please try again.'
                });
            }
        });
    });


    function edit(id) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_about'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    let res = response.data; // Directly access the data object
                    $('#editAboutId').val(res.ID);
                    $('#edit_content_category').val(res.section);
                    $('#EditTxtTitle').val(res.title);

                    if (res.section != 'Header') {
                        $('#EditDescGroup').show();
                        if (res.section === 'History') {
                            $('#EditAboutImgGrp').show();
                        } else {
                            $('#EditAboutImgGrp').hide();
                        }
                        $('#EditTxtDesc').val(res.description);
                    } else {
                        $('#EditDescGroup, #EditAboutImgGrp').hide();
                    }
                    $('#editModal').modal('show');

                    // Set Quill editor content after modal is shown
                    $('#editModal').on('shown.bs.modal', function () {
                        if (res.section != 'Header') {
                            // Initialize Quill editor if not already initialized
                            if (!QuillManager.getQuillInstance('aboutEditDesc')) {
                                QuillManager.initQuillEditor('editQuillDesc', 'aboutEditDesc');
                            }
                            // Set content after a short delay to ensure Quill is ready
                            setTimeout(() => {
                                const quill = QuillManager.getQuillInstance('aboutEditDesc');
                                if (quill && res.description) {
                                    quill.root.innerHTML = res.description;
                                }
                            }, 100);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Data not found.'
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

    $('#btnEdit').click(function () {
        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();

        let form = $('#editForm')[0];
        let formData = new FormData(form);
        let selectedCategory = formData.get('edit_content_category');
        let title = formData.get('EditTxtTitle');
        let description = formData.get('EditTxtDesc');
        let imageFile = formData.get('EditAboutImg');

        // Form validation
        if (!selectedCategory || !title) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
        }

        if (selectedCategory === 'History') {
            // Description validation
            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please enter a description.'
                });
                return;
            }

            // Image validation
            if (!imageFile || imageFile.size === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload an image.'
                });
                return;
            }

            const maxImageSizeMB = 4;
            if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: `Image size should not exceed ${maxImageSizeMB} MB.`
                });
                return;
            }

            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validImageTypes.includes(imageFile.type)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload a valid image file (jpg, png, gif).'
                });
                return;
            }
        } else if (selectedCategory === 'Home Page' || selectedCategory === 'Content' || selectedCategory === 'Emergency Hotlines') {
            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please enter a description.'
                });
                return;
            }
            formData.set('EditAboutImg', '');
        } else {
            formData.set('EditDescGroup', '');
            formData.set('EditAboutImg', '');
        }

        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_about'); ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => {
                        $('#editModal').modal('hide');
                        tbl.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to update content. Please try again later.'
                });
            }
        });
    });

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
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal" onclick="edit(${row.ID})"><i class="bi bi-pencil me-1"></i> Edit</a></li>`;

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