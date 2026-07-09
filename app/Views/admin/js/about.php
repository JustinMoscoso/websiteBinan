<script src="<?= base_url('assets/admin/js/quill-init.js') ?>"></script>
<script>
    const userLevel = '<?= $user->user_lvl ?>'.toUpperCase();

    if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') {
        $('.button-32').show();
    } else {
        $('.button-32').hide();
    }

    if (userLevel === 'VIEWER') {
        $('[data-bs-target="#addModal"]').hide();

        $(document).on('show.bs.modal', '.modal', function () {
            var $modal = $(this);
            $modal.find('input, select, textarea, button').prop('disabled', true);
            $modal.find('button[data-bs-dismiss="modal"], .btn-close, a[data-bs-dismiss="modal"]').prop('disabled', false);
            $modal.find('button, input[type="submit"], input[type="button"], a.btn').not('[data-bs-dismiss="modal"], .btn-close').hide();
            $modal.find('input[type="file"]').hide();
        });

        $(document).on('show.bs.modal shown.bs.modal', '.modal', function () {
            var $modal = $(this);
            $modal.find('.ql-editor').attr('contenteditable', 'false');
            $modal.find('.ql-toolbar').hide();

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

    QuillManager.setupQuillFormHandlers({
        formHandlers: [
            {
                buttonId: 'btnAdd',
                instanceName: 'aboutDesc',
                hiddenInputId: 'addTxtDesc'
            }
        ]
    });

    function populateHistoryYears(selectedYear) {
        var currentYear = new Date().getFullYear();
        var startYear = currentYear + 5;
        var endYear = 1800;
        var options = '<option value="">Select year</option>';

        for (var year = startYear; year >= endYear; year--) {
            var selected = String(year) === String(selectedYear || '') ? ' selected' : '';
            options += '<option value="' + year + '"' + selected + '>' + year + '</option>';
        }

        $('#HistoryYearPicker').html(options);
    }

    function syncTitleFieldUI(selectedCategory, selectedTitle) {
        if (selectedCategory === 'History') {
            populateHistoryYears(selectedTitle || $('#TxtTitle').val());
            $('#TxtTitleLabel').html('Year <span class="text-danger">*</span>');
            $('#TxtTitle').addClass('d-none').prop('required', false);
            $('#HistoryYearPicker').removeClass('d-none').prop('required', true);
            $('#TxtTitle').val($('#HistoryYearPicker').val());
            return;
        }

        $('#TxtTitleLabel').html('Title <span class="text-danger">*</span>');
        $('#HistoryYearPicker').addClass('d-none').prop('required', false).val('');
        $('#TxtTitle').removeClass('d-none').prop('required', true);
        if (selectedTitle !== undefined) {
            $('#TxtTitle').val(selectedTitle);
        }
    }

    function syncAboutImageUI(selectedCategory) {
        var showImageUpload = selectedCategory === 'History' || selectedCategory === 'Emergency Hotlines';
        var labelText = selectedCategory === 'Emergency Hotlines'
            ? 'Logo Image'
            : 'Feature Illustration / Banner Image';

        $('#AboutImgLabel').text(labelText);
        $('#AboutImgGrp').toggle(showImageUpload);

        if (!showImageUpload) {
            $('#AboutImg').val('');
            $('#edit_img_preview').addClass('d-none').html('');
        }
    }

    $('#HistoryYearPicker').on('change', function () {
        $('#TxtTitle').val($(this).val());
    });

    $('#content_category').on('change', function () {
        var selectedCategory = $(this).val();
        syncTitleFieldUI(selectedCategory);
        syncAboutImageUI(selectedCategory);

        if (selectedCategory === 'Content' || selectedCategory === 'Home Page' || selectedCategory === 'Emergency Hotlines' || selectedCategory === 'History') {
            $('#DescGroup').show();
            if (!QuillManager.getQuillInstance('aboutDesc')) {
                QuillManager.initQuillEditor('quillDesc', 'aboutDesc');
            }
        } else {
            $('#DescGroup').hide();
        }
    });

    function resetAboutModal() {
        $('#recordMode').val('add');
        $('#recordId').val('');
        $('#recordModalTitle').text('Add Content');
        $('#btnAddLabel').text('Save');
        $('#addForm')[0].reset();
        $('#DescGroup, #AboutImgGrp').hide();
        $('#AboutImgLabel').text('Feature Illustration / Banner Image');
        $('#edit_img_preview').addClass('d-none').html('');
        syncTitleFieldUI('');

        var quill = QuillManager.getQuillInstance('aboutDesc');
        if (quill) {
            quill.setContents([]);
        }
    }

    function openAboutModal(mode, record) {
        resetAboutModal();
        $('#recordMode').val(mode);

        if (mode === 'add') {
            $('#recordModalTitle').text('Add Content');
            $('#btnAddLabel').text('Save');
        } else {
            $('#recordModalTitle').html('<i class="bi bi-pencil-square me-2"></i>Edit Content');
            $('#btnAddLabel').text('Update');
            $('#recordId').val(record.ID || record.id);
            $('#content_category').val(record.section).trigger('change');
            syncTitleFieldUI(record.section, record.title);
            $('#TxtTitle').val(record.title);
            if (record.section === 'History') {
                $('#HistoryYearPicker').val(record.title);
            }
            $('#addTxtDesc').val(record.description || '');

            $('#addModal').one('shown.bs.modal', function () {
                if (!QuillManager.getQuillInstance('aboutDesc')) {
                    QuillManager.initQuillEditor('quillDesc', 'aboutDesc');
                }
                setTimeout(function () {
                    var quill = QuillManager.getQuillInstance('aboutDesc');
                    if (quill) {
                        quill.root.innerHTML = record.description || '';
                    }
                }, 100);
            });

            if (record.about_img) {
                var imgSrc = '<?php echo base_url("admin/image/ABOUT/"); ?>' + record.about_img;
                $('#edit_img_preview').removeClass('d-none').html('<img src="' + imgSrc + '" class="img-fluid">');
            }
        }

        $('#addModal').modal('show');
    }

    $('#addModal').on('hidden.bs.modal', resetAboutModal);

    $('#btnAdd').on('click', function (event) {
        event.preventDefault();
        QuillManager.updateQuillFormContent();

        var mode = $('#recordMode').val();
        var form = $('#addForm')[0];
        var formData = new FormData(form);
        var selectedCategory = formData.get('content_category');

        if (selectedCategory === 'History') {
            $('#TxtTitle').val($('#HistoryYearPicker').val());
            formData.set('TxtTitle', $('#HistoryYearPicker').val());
        }

        var title = formData.get('TxtTitle');
        var description = formData.get('TxtDesc');
        var imageFile = formData.get('AboutImg');

        if (!selectedCategory || !title) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please fill in all required fields.' });
            return;
        }

        if (selectedCategory === 'History' && !/^\d{4}$/.test(String(title))) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please select a valid year.' });
            return;
        }

        if (selectedCategory === 'History') {
            if (!description) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please enter a description.' });
                return;
            }
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
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validImageTypes.includes(imageFile.type)) {
                    Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please upload a valid image file (jpg, png, gif, webp).' });
                    return;
                }
            }
        } else if (selectedCategory === 'Emergency Hotlines') {
            if (!description) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please enter a description.' });
                return;
            }
            if (imageFile && imageFile.size > 0) {
                const maxImageSizeMB = 4;
                if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
                    Swal.fire({ icon: 'warning', title: 'Validation Error', text: `Image size should not exceed ${maxImageSizeMB} MB.` });
                    return;
                }
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validImageTypes.includes(imageFile.type)) {
                    Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please upload a valid image file (jpg, png, gif, webp).' });
                    return;
                }
            }
        } else if (selectedCategory === 'Home Page' || selectedCategory === 'Content') {
            if (!description) {
                Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please enter a description.' });
                return;
            }
            formData.set('AboutImg', '');
        } else {
            formData.set('TxtDesc', '');
            formData.set('AboutImg', '');
        }

        var url;
        var successText;

        if (mode === 'edit') {
            formData.set('id', $('#recordId').val());
            formData.set('edit_content_category', selectedCategory);
            formData.set('EditTxtTitle', title);
            formData.set('EditTxtDesc', description || '');

            var imgFile = formData.get('AboutImg');
            if (imgFile && imgFile.size > 0) {
                formData.set('EditAboutImg', imgFile);
            } else {
                formData.delete('EditAboutImg');
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

    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);
        var confirmText = newStatus === 'ACTIVE' ? 'This will be displayed in the About/History section.' : 'This will not be displayed in the About/History section.';

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'Content'),
            text: 'Are you sure you want to ' + actionText + ' this content? ' + confirmText,
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

    var tbl = $('#tblabout').DataTable({
        select: false,
        searching: true,
        ordering: true,
        order: [[5, 'asc'], [1, 'asc'], [2, 'asc']],
        pageLength: 10,
        processing: true,
        ajax: {
            url: "<?php echo base_url('admin/ajax/get_about'); ?>",
            type: 'POST',
            data: function (d) {
                d.search_kw = $('form#aboutSearchForm input[name="search"]').val();
                d.section = $('form#aboutSearchForm select[name="section"]').val();
                d.status = $('form#aboutSearchForm select[name="status"]').val();
            },
            dataSrc: function (json) {
                return json.data && Array.isArray(json.data) ? json.data : [];
            }
        },
        initComplete: function () {
            var searchInput = $('#tblabout_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search about info...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                width: '250px',
                'margin-left': '0.5rem'
            });

            var lengthSelect = $('#tblabout_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                width: 'auto',
                margin: '0 0.5rem'
            });
        },
        columns: [
            { title: 'ID', data: 'ID', visible: false },
            { title: 'Section', data: 'section', className: 'dt-body-justify align-middle', width: '10%' },
            {
                title: 'Title',
                data: 'title',
                className: 'align-middle',
                render: function (data) {
                    return '<div class="d-flex justify-content-start">' + (data || '-') + '</div>';
                }
            },
            {
                title: 'Description',
                data: 'description',
                className: 'dt-head-center dt-body-justify align-middle',
                width: '30%',
                render: function (data) {
                    return data ? data : '-';
                }
            },
            {
                title: 'Image',
                data: 'about_img',
                className: 'dt-center align-middle',
                width: '15%',
                render: function (data) {
                    return data ? '<img id="img_loc" class="img-fluid mt-3" src="<?php echo base_url('admin/image/ABOUT/') ?>' + data + '">' : '-';
                }
            },
            {
                title: 'Status',
                data: 'status',
                className: 'dt-center align-middle',
                width: '10%',
                render: function (data) {
                    if (data == 'ACTIVE') {
                        return '<span class="status-badge status-badge-active"><span class="status-dot status-dot-active"></span>Active</span>';
                    }
                    if (data == 'INACTIVE') {
                        return '<span class="status-badge status-badge-inactive"><span class="status-dot status-dot-inactive"></span>Inactive</span>';
                    }
                    return '<span class="status-badge status-badge-archived"><span class="status-dot status-dot-archived"></span>Archived</span>';
                }
            },
            {
                title: 'Actions',
                data: 'ID',
                className: 'dt-center align-middle',
                render: function (data, type, row) {
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
                    actionHtml += '</ul></div>';

                    return actionHtml;
                }
            },
        ]
    });

    var sltdRow = null;

    $('#tblabout tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    $('#aboutSearchForm').on('submit', function (e) {
        e.preventDefault();
        tbl.ajax.reload();
    });

    $('#aboutSearchForm').on('reset', function () {
        setTimeout(function () { tbl.ajax.reload(); }, 0);
    });
</script>
