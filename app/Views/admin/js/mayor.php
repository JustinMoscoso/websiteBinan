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
                $.post("<?php echo site_url('admin/ajax/set_status_mayor') ?>",
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
                                text: result.message || result.msg || 'Unable to update status.',
                            });
                        }
                    }
                );
            }
        });
    }

    // Delete function
    function deleteMayor(id) {
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
                $.post("<?php echo site_url('admin/ajax/delete_mayor') ?>",
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

    // ─── Single Quill instance ───────────────────────────────────────────────
    var quillPerData = new Quill('#addPerdataEditor', {
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub' }, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'indent': '-1' }, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        },
        theme: 'snow'
    });

    // ─── Mayor Name field visibility helper ──────────────────────────────────
    function shouldShowMayorNameField(section) {
        return section === 'Personal Data';
    }

    function shouldShowMayorImageField(section) {
        return ['Personal Data', 'Gallery', 'Home Page'].includes(section);
    }

    function syncMayorNameField(section, mayorName) {
        var showNameField = shouldShowMayorNameField(section);
        var $group = $('#mayorNameGroup');
        var $field = $('#myrname');
        var nextValue = typeof mayorName === 'undefined' ? $field.val() : (mayorName || '');

        $group.toggle(showNameField);
        $field.prop('required', showNameField);
        $field.val(nextValue);
    }

    function syncMayorImageField(section, images) {
        var showImageField = shouldShowMayorImageField(section);
        var $group = $('#mayorImageGroup');

        $group.toggle(showImageField);

        if (!showImageField) {
            $('#mayorimg').val('');
            $('#existing_mayor_images').val('');
            $('#add_img_preview').html('');
            return;
        }

        if (typeof images !== 'undefined') {
            renderMayorExistingImages(images);
        }
    }

    function syncMayorCategoryFields(section, mayorName, images) {
        syncMayorNameField(section, mayorName);
        syncMayorImageField(section, images);
    }

    // ─── Shared modal open helper ─────────────────────────────────────────────
    function openMayorModal(mode, record) {
        $('#mayorMode').val(mode);

        if (mode === 'add') {
            $('#mayorModalTitle').text('Add Record');
            $('#btnAdd').text('Save');
            $('#mayorRecordId').val('');
            $('#addForm')[0].reset();
            $('#add_img_preview').html('');
            $('#existing_mayor_images').val('');
            quillPerData.root.innerHTML = '';
            syncMayorCategoryFields('');
        } else {
            $('#mayorModalTitle').html('<i class="bi bi-pencil-square me-2"></i>Edit Record');
            $('#btnAdd').text('Update');
            $('#mayorRecordId').val(record.ID);
            $('#content_category').val(record.section);
            syncMayorCategoryFields(record.section, record.mayor_name, record.mayor_img);

            // Load content into the single Quill editor after modal is shown
            $('#addModal').one('shown.bs.modal', function () {
                quillPerData.root.innerHTML = record.content || '';
            });

            $('#mayorimg').val('');
        }

        $('#addModal').modal('show');
    }

    // ─── Category change handler ──────────────────────────────────────────────
    $('#content_category').on('change', function () {
        syncMayorCategoryFields($(this).val());
    });

    // ─── Unified submit handler ───────────────────────────────────────────────
    $('#btnAdd').on('click', function (e) {
        e.preventDefault();
        var mode = $('#mayorMode').val();
        var form = $('#addForm')[0];
        var formData = new FormData(form);

        // Sync Quill content into hidden field
        formData.set('perdata', quillPerData.root.innerHTML);

        if (!shouldShowMayorImageField($('#content_category').val())) {
            formData.delete('mayorimg[]');
            formData.delete('mayorimg');
            formData.set('existing_mayor_images', '[]');
        }

        // Explicitly set ID for edit mode
        if (mode === 'edit') {
            formData.set('id', $('#mayorRecordId').val());
        }

        // Validate image file (add mode only validates the new selection)
        var mayorImg = $('#mayorimg')[0].files[0];
        if (mayorImg) {
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validImageTypes.includes(mayorImg.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Image Type',
                    text: 'Only JPG, PNG, GIF, and WEBP files are allowed.'
                });
                return;
            }
            if (mayorImg.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Image Too Large',
                    text: 'The image size should not exceed 2MB.'
                });
                return;
            }
        }

        var url = mode === 'edit'
            ? '<?php echo site_url('admin/ajax/update_mayor'); ?>'
            : '<?php echo site_url('admin/ajax/create_mayor'); ?>';

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
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message || (mode === 'edit' ? 'Content updated successfully.' : "Mayor's Content data saved!")
                    });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.message || 'Operation failed. Refresh the page or try logging in again.',
                    });
                    tbl.ajax.reload(null, false);
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request. Please try again.'
                });
            }
        });
    });

    // ─── Edit record fetch then open modal ───────────────────────────────────
    function edit(mayId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_mayor'); ?>',
            method: 'POST',
            data: { id: mayId },
            success: function (response) {
                if (response.status === 1) {
                    var official = response.data;
                    // Normalize ID key
                    if (!official.ID && official.id) {
                        official.ID = official.id;
                    }
                    // Normalize mayor_img
                    official.mayor_img = parseMayorImages(official.mayor_img);
                    openMayorModal('edit', official);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Content not found.'
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

    // ─── Image preview helpers ────────────────────────────────────────────────
    function setFileInputFiles(input, files) {
        var dt = new DataTransfer();
        files.forEach(function (file) {
            dt.items.add(file);
        });
        input.files = dt.files;
    }

    function renderFilePreview(inputSelector, previewSelector) {
        var input = $(inputSelector)[0];
        var preview = $(previewSelector);
        var files = Array.from(input.files || []);

        if (!files.length) {
            preview.html('<small class="text-muted">No images selected.</small>');
            return;
        }

        var html = '';
        files.forEach(function (file, index) {
            html += '<div class="text-center d-inline-block" style="width: 100px; vertical-align: top; margin: 10px;">' +
                '<div class="position-relative" style="width: 100px; height: 100px;">' +
                '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center remove-selected-image" data-input="' + inputSelector + '" data-index="' + index + '" style="width: 22px; height: 22px; padding: 0; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.25); transform: translate(35%, -35%); z-index: 2; font-size: 14px; font-weight: bold;">&times;</button>' +
                '<img src="" class="img-thumbnail selected-image-preview" data-file-index="' + index + '" style="width: 100px; height: 100px; object-fit: contain; background-color: #f8f9fa;" alt="Selected image">' +
                '</div>' +
                '<div class="text-truncate mt-1" style="font-size: 0.75rem; color: #6c757d;" title="' + file.name + '">' + file.name + '</div>' +
                '</div>';
        });

        preview.html(html);

        files.forEach(function (file, index) {
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.find('img.selected-image-preview[data-file-index="' + index + '"]').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });
    }

    function removeSelectedFile(inputSelector, index) {
        var input = $(inputSelector)[0];
        var files = Array.from(input.files || []);
        files.splice(index, 1);
        setFileInputFiles(input, files);
    }

    function renderMayorExistingImages(images) {
        var list = parseMayorImages(images);
        $('#existing_mayor_images').val(JSON.stringify(list));
        renderMayorPreview();
    }

    function renderMayorPreview() {
        var preview = $('#add_img_preview');
        var existing = parseMayorImages($('#existing_mayor_images').val());
        var selected = Array.from($('#mayorimg')[0].files || []);
        var html = '';

        if (!existing.length && !selected.length) {
            preview.html('<small class="text-muted">No existing images available.</small>');
            return;
        }

        existing.forEach(function (image) {
            html += '<div class="text-center d-inline-block" style="width: 100px; vertical-align: top; margin: 10px;">' +
                '<div class="position-relative" style="width: 100px; height: 100px;">' +
                '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center remove-existing-mayor-image" data-image="' + image + '" style="width: 22px; height: 22px; padding: 0; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.25); transform: translate(35%, -35%); z-index: 2; font-size: 14px; font-weight: bold;">&times;</button>' +
                '<img src="<?php echo base_url('admin/image/MAYOR/') ?>' + image + '" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: contain; background-color: #f8f9fa;" alt="Mayor image">' +
                '</div>' +
                '<div class="text-truncate mt-1" style="font-size: 0.75rem; color: #6c757d;" title="Existing: ' + image + '">Existing: ' + image + '</div>' +
                '</div>';
        });

        selected.forEach(function (file, index) {
            html += '<div class="text-center d-inline-block" style="width: 100px; vertical-align: top; margin: 10px;">' +
                '<div class="position-relative" style="width: 100px; height: 100px;">' +
                '<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle d-flex align-items-center justify-content-center remove-selected-image" data-input="#mayorimg" data-index="' + index + '" style="width: 22px; height: 22px; padding: 0; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.25); transform: translate(35%, -35%); z-index: 2; font-size: 14px; font-weight: bold;">&times;</button>' +
                '<img src="" class="img-thumbnail selected-image-preview" data-file-index="' + index + '" style="width: 100px; height: 100px; object-fit: contain; background-color: #f8f9fa;" alt="Selected image">' +
                '</div>' +
                '<div class="text-truncate mt-1" style="font-size: 0.75rem; color: #6c757d;" title="New: ' + file.name + '">New: ' + file.name + '</div>' +
                '</div>';
        });

        preview.html(html);

        selected.forEach(function (file, index) {
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.find('img.selected-image-preview[data-file-index="' + index + '"]').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });
    }

    function parseMayorImages(value) {
        if (!value) {
            return [];
        }

        if (Array.isArray(value)) {
            return value.filter(function (image) {
                return image && String(image).trim() !== '';
            });
        }

        if (typeof value === 'string') {
            try {
                var parsed = JSON.parse(value);
                if (Array.isArray(parsed)) {
                    return parsed.filter(function (image) {
                        return image && String(image).trim() !== '';
                    });
                }
            } catch (e) {
                // fall through
            }

            return value.split(',').map(function (image) {
                return image.trim();
            }).filter(function (image) {
                return image !== '';
            });
        }

        return [];
    }

    // Image change events
    $('#mayorimg').on('change', function () {
        if ($('#mayorMode').val() === 'edit') {
            renderMayorPreview();
        } else {
            renderFilePreview('#mayorimg', '#add_img_preview');
        }
    });

    // Remove selected new file
    $(document).on('click', '.remove-selected-image', function () {
        var inputSelector = $(this).data('input');
        var index = parseInt($(this).data('index'), 10);
        removeSelectedFile(inputSelector, index);
        if ($('#mayorMode').val() === 'edit') {
            renderMayorPreview();
        } else {
            renderFilePreview('#mayorimg', '#add_img_preview');
        }
    });

    // Remove existing image
    $(document).on('click', '.remove-existing-mayor-image', function () {
        var imageName = String($(this).data('image') || '');
        var existing = parseMayorImages($('#existing_mayor_images').val());
        existing = existing.filter(function (image) {
            return image !== imageName;
        });
        renderMayorExistingImages(existing);
    });

    // Reset modal state on close
    $('#addModal').on('hidden.bs.modal', function () {
        $('#addForm')[0].reset();
        $('#add_img_preview').html('');
        $('#existing_mayor_images').val('');
        $('#mayorimg').val('');
        $('#mayorRecordId').val('');
        $('#mayorMode').val('add');
        $('#mayorModalTitle').text('Add Record');
        $('#btnAdd').text('Save');
        quillPerData.root.innerHTML = '';
        syncMayorCategoryFields('');
    });

    // ─── DataTable ────────────────────────────────────────────────────────────
    var tbl = $('#tblmayor').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_mayor'); ?>",
            "type": "POST",
            "data": function (d) {
                d.search_kw = $('form#mayorSearchForm input[name="search"]').val();
                d.category = $('form#mayorSearchForm select[name="category"]').val();
                d.status = $('form#mayorSearchForm select[name="status"]').val();
            },
            "dataSrc": function (json) {
                if (json.data && Array.isArray(json.data)) {
                    return json.data.map(function (item) {
                        item.mayor_img = parseMayorImages(item.mayor_img);
                        return item;
                    });
                } else {
                    return [];
                }
            }
        },
        initComplete: function () {
            var searchInput = $('#tblmayor_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search executive profile...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });

            var lengthSelect = $('#tblmayor_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
        },
        columns: [
            { "title": "ID", "data": "ID", "className": "dt-center", width: '20%', "visible": false },
            { "title": "Section", "data": "section", "className": "dt-body-justify align-middle", width: '10%' },
            {
                "title": "Content", "data": "content", "className": "dt-body-justify align-middle ", width: '50%',
                "render": function (data, type, row) {
                    var text = data.replace(/<[^>]*>?/gm, '');
                    if (typeof text === 'string' && text.length > 500) {
                        text = text.substring(0, 500) + '...';
                    }
                    return '<div class="quill-editor-default" style="height: auto;">' + text + '</div>';
                }
            },
            {
                "title": "Image", "data": "mayor_img", "className": "dt-center align-middle", width: '20%',
                "render": function (data, type, row) {
                    var imageHtml = '';
                    if (data && data.length > 0) {
                        data.forEach(function (image) {
                            imageHtml += '<img src="<?php echo base_url('admin/image/MAYOR/') ?>' + image + '" class="img-thumbnail" style="width: 100px; height: auto; margin-right: 5px;">';
                        });
                    } else {
                        imageHtml = '-';
                    }
                    return imageHtml;
                }
            },
            {
                "title": "Status",
                "data": "status",
                "className": "dt-center align-middle",
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
                "className": "dt-center align-middle",
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

                    actionHtml += renderStatusToggleAction(userLevel, row, 'toggleStatus');
                    actionHtml += renderDeleteAction(userLevel, row.ID, 'deleteMayor');
                    actionHtml += `</ul></div>`;
                    return actionHtml;
                }
            }
        ]
    });

    var sltdRow = null;

    $('#tblmayor tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Advanced Search form — submit reloads table with filters
    $('#mayorSearchForm').on('submit', function (e) {
        e.preventDefault();
        tbl.ajax.reload();
    });
    // Clear filters — reset then reload
    $('#mayorSearchForm').on('reset', function () {
        setTimeout(function () { tbl.ajax.reload(); }, 0);
    });
</script>
