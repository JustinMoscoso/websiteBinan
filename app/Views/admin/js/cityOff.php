<script>
    var userLevel = '<?= $user->user_lvl ?>';
    var tbl = null;
    var cityOffState = {
        mode: 'add',
        recordId: '',
        currentImages: [],
        currentImage: ''
    };
    var cityOffImageBaseUrl = '<?= base_url('admin/image/CITYOFFICIAL/') ?>';
    var cityOffStatusOrder = {
        ACTIVE: 1,
        INACTIVE: 2,
        ARCHIVED: 3
    };
    var cityOffPositionOrder = {
        CONGRESS: 1,
        'CITY MAYOR': 2,
        'CITY VICE MAYOR': 3,
        'CITY COUNCILOR': 4,
        'ABC PRESIDENT': 5,
        'SK FEDERATION PRESIDENT': 6
    };

    if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') {
        $('.button-32').show();
    } else {
        $('.button-32').hide();
    }

    if (userLevel === 'VIEWER') {
        $('[onclick="openCityOffModal(\'add\')"]').hide();

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

    var quillYearsOfService = new Quill('#years_of_service', {
        theme: 'snow',
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
        }
    });

    var quillPersonalData = new Quill('#personal_data', {
        theme: 'snow',
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
        }
    });

    var quillAwards = new Quill('#awards', {
        theme: 'snow',
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
        }
    });

    function normalizeCityOffRecord(record) {
        if (!record) {
            return {};
        }

        if (record.ID == null && record.id != null) {
            record.ID = record.id;
        }

        if (record.carouselimages == null && record.carouselImages != null) {
            record.carouselimages = record.carouselImages;
        }

        return record;
    }

    function splitCarouselImages(value) {
        if (!value) {
            return [];
        }

        return String(value)
            .split(',')
            .map(function (item) {
                return item.trim();
            })
            .filter(function (item) {
                return item !== '';
            });
    }

    function isEmptyEditorHtml(html) {
        return !html || html === '<p><br></p>' || html.replace(/<[^>]+>/g, '').trim() === '';
    }

    function getCityOffStatusSort(status) {
        return cityOffStatusOrder[String(status || '').toUpperCase()] || 99;
    }

    function getCityOffPositionSort(position) {
        return cityOffPositionOrder[String(position || '').toUpperCase()] || 99;
    }

    function getCityOffRankSort(rank) {
        var rankNumber = parseInt(rank, 10);
        return Number.isFinite(rankNumber) ? rankNumber : 999;
    }

    function setEditorHtml(editor, html) {
        editor.root.innerHTML = html || '';
    }

    function clearEditor(editor) {
        editor.setText('');
    }

    function getMainImagePreviewHtml(existingImage) {
        if (!existingImage) {
            return '<small class="text-muted">No image available.</small>';
        }

        return '<img src="' + cityOffImageBaseUrl + existingImage + '" alt="Current Image" class="img-fluid rounded" style="max-width: 120px; margin-top: 5px;">';
    }

    function renderMainImagePreview(file) {
        var preview = $('#mainImagePreview');
        if (file && file.type && file.type.indexOf('image/') === 0) {
            var url = URL.createObjectURL(file);
            preview.html('<img src="' + url + '" alt="Selected Image" class="img-fluid rounded" style="max-width: 120px; margin-top: 5px;">');
            return;
        }

        preview.html(getMainImagePreviewHtml(cityOffState.currentImage));
    }

    function renderCarouselPreview() {
        var preview = $('#carouselPreview');
        var html = [];
        var mode = cityOffState.mode;
        var files = Array.from($('#offcaroimg')[0].files || []);

        if (mode === 'edit') {
            cityOffState.currentImages.forEach(function (image) {
                html.push(
                    '<div style="display: inline-block; margin: 5px;" class="image-container">' +
                    '<img src="' + cityOffImageBaseUrl + image + '" alt="Carousel Image" style="max-width: 100px; margin: 5px;">' +
                    '<br><small>' + image + '</small>' +
                    '<br><button type="button" class="btn btn-danger btn-sm remove-image" data-image="' + image + '" style="margin-top: 5px;">Remove</button>' +
                    '</div>'
                );
            });
        }

        files.forEach(function (file) {
            if (!file || !file.type || file.type.indexOf('image/') !== 0) {
                return;
            }

            var url = URL.createObjectURL(file);
            html.push(
                '<div style="display: inline-block; margin: 5px;" class="image-container">' +
                '<img src="' + url + '" alt="Selected Carousel Image" style="max-width: 100px; margin: 5px;">' +
                '<br><small>' + file.name + '</small>' +
                '</div>'
            );
        });

        if (!html.length) {
            preview.html('<small class="text-muted">No carousel images selected.</small>');
            return;
        }

        preview.html(html.join(''));
    }

    function syncRankField() {
        var selectedPosition = $('#offpos').val();
        var rankField = $('#rankField');
        var rankInput = $('#offrank');

        if (selectedPosition === 'CITY COUNCILOR') {
            rankField.show();
            rankInput.prop('required', true);
            rankInput.attr('max', 12);
        } else {
            rankField.hide();
            rankInput.prop('required', false);
            rankInput.val('');
        }
    }

    function resetCityOffModalState() {
        cityOffState.mode = 'add';
        cityOffState.recordId = '';
        cityOffState.currentImages = [];
        cityOffState.currentImage = '';

        var form = $('#cityOffForm')[0];
        if (form) {
            form.reset();
        }

        $('#cityOffId').val('');
        $('#cityOffMode').val('add');
        $('#cityOffModalTitle').text('Add City Official');
        $('#btnCityOffSave').text('Save');
        $('#offimg').prop('required', true);
        $('#offimgRequiredMark').removeClass('d-none');
        $('#rankField').hide();
        $('#offrank').prop('required', false).val('');
        $('#offcaroimg').val('');
        $('#mainImagePreview').html('');
        $('#carouselPreview').html('<small class="text-muted">No carousel images selected.</small>');

        clearEditor(quillYearsOfService);
        clearEditor(quillPersonalData);
        clearEditor(quillAwards);
    }

    function validateImageFile(file, label, allowEmpty) {
        if (!file || !file.size) {
            return allowEmpty ? true : label + ' is required.';
        }

        var maxImageSizeMB = 4;
        var validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (file.size > maxImageSizeMB * 1024 * 1024) {
            return label + ' must not exceed ' + maxImageSizeMB + ' MB.';
        }

        if (validImageTypes.indexOf(file.type) === -1) {
            return label + ' must be a jpg, png, or gif image.';
        }

        return true;
    }

    function validateCarouselFiles(files) {
        var maxImageSizeMB = 4;
        var validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        var totalImages = cityOffState.currentImages.length + files.length;

        if (totalImages > 3) {
            return 'You can only have a maximum of 3 carousel images.';
        }

        for (var i = 0; i < files.length; i++) {
            if (files[i].size > maxImageSizeMB * 1024 * 1024) {
                return 'Each carousel image must not exceed ' + maxImageSizeMB + ' MB.';
            }

            if (validImageTypes.indexOf(files[i].type) === -1) {
                return 'Carousel images must be jpg, png, or gif.';
            }
        }

        return true;
    }

    function openCityOffModal(mode, record) {
        var isEdit = mode === 'edit';
        var data = isEdit ? normalizeCityOffRecord(record || {}) : {};

        cityOffState.mode = mode;
        cityOffState.recordId = isEdit ? String(data.ID || '').trim() : '';
        cityOffState.currentImages = isEdit ? splitCarouselImages(data.carouselimages) : [];
        cityOffState.currentImage = isEdit ? (data.img_loc || '') : '';

        var form = $('#cityOffForm')[0];
        if (form) {
            form.reset();
        }

        $('#cityOffId').val(cityOffState.recordId);
        $('#cityOffMode').val(mode);
        $('#cityOffModalTitle').text(isEdit ? 'Edit City Official' : 'Add City Official');
        $('#btnCityOffSave').text(isEdit ? 'Update' : 'Save');
        $('#offimg').prop('required', !isEdit);
        $('#offimgRequiredMark').toggleClass('d-none', isEdit);

        if (isEdit) {
            $('#offname').val(data.off_name || '');
            $('#offpos').val(data.off_position || '');
            $('#offrank').val(data.ranking || '');
            setEditorHtml(quillYearsOfService, data.years_of_service || '');
            setEditorHtml(quillPersonalData, data.personal_data || '');
            setEditorHtml(quillAwards, data.awards || '');
            syncRankField();
        } else {
            $('#offpos').val('');
            $('#offrank').val('');
            $('#rankField').hide();
            clearEditor(quillYearsOfService);
            clearEditor(quillPersonalData);
            clearEditor(quillAwards);
        }

        $('#offimg').val('');
        $('#offcaroimg').val('');
        renderMainImagePreview(null);
        renderCarouselPreview();

        $('#addModal').modal('show');
    }

    function submitCityOffForm() {
        var form = $('#cityOffForm')[0];
        var mode = $('#cityOffMode').val() || 'add';
        var isEdit = mode === 'edit';

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        var yearsHtml = quillYearsOfService.root.innerHTML;
        var personalHtml = quillPersonalData.root.innerHTML;
        var awardsHtml = quillAwards.root.innerHTML;

        if (isEmptyEditorHtml(yearsHtml) || isEmptyEditorHtml(personalHtml) || isEmptyEditorHtml(awardsHtml)) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all biography fields.'
            });
            return;
        }

        var mainFile = $('#offimg')[0].files[0];
        var carouselFiles = Array.from($('#offcaroimg')[0].files || []);

        if (!isEdit) {
            if (!mainFile) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please upload a primary profile image.'
                });
                return;
            }
        }

        if (mainFile && mainFile.size) {
            var mainImageValidation = validateImageFile(mainFile, 'Primary profile image', false);
            if (mainImageValidation !== true) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: mainImageValidation
                });
                return;
            }
        }

        if (carouselFiles.length) {
            var carouselValidation = validateCarouselFiles(carouselFiles);
            if (carouselValidation !== true) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Image Limit Exceeded',
                    text: carouselValidation
                });
                $('#offcaroimg').val('');
                renderCarouselPreview();
                return;
            }
        }

        var formData = new FormData(form);
        formData.set('mode', mode);
        formData.set('id', $('#cityOffId').val());
        formData.set('years_of_service', yearsHtml);
        formData.set('personal_data', personalHtml);
        formData.set('awards', awardsHtml);

        Swal.fire({
            title: 'Please wait...',
            showConfirmButton: false,
            backdrop: true,
            scrollbarPadding: false,
            allowEscapeKey: function () { return !Swal.isLoading(); },
            allowOutsideClick: function () { return !Swal.isLoading(); },
            willOpen: function () {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: isEdit ? '<?php echo site_url('admin/ajax/update_cityoff'); ?>' : '<?php echo site_url('admin/ajax/create_cityoff'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (result) {
                if (result.status == 1) {
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: isEdit ? 'City Official updated successfully.' : 'City Official data saved!'
                    });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.message || 'Request failed.'
                    });
                }
            },
            error: function (xhr, statusText, errorThrown) {
                var debugMessage = xhr.responseText || errorThrown || statusText || 'An unknown error occurred.';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: debugMessage.length > 220 ? debugMessage.substring(0, 220) + '...' : debugMessage
                });
            }
        });
    }

    function edit(coId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_cityoff'); ?>',
            method: 'POST',
            dataType: 'json',
            data: { id: coId },
            success: function (response) {
                if (response.status === 1) {
                    openCityOffModal('edit', response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Official not found.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch official details. Please try again later.'
                });
            }
        });
    }

    function removeCarouselImage(coId, imageName) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/remove_carousel_image'); ?>',
            method: 'POST',
            dataType: 'json',
            data: { id: coId, image: imageName },
            success: function (response) {
                if (response.status === 1) {
                    cityOffState.currentImages = cityOffState.currentImages.filter(function (img) {
                        return img !== imageName;
                    });
                    renderCarouselPreview();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Image removed successfully.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to remove image.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to remove image. Please try again.'
                });
            }
        });
    }

    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'City Official Content'),
            text: 'Are you sure you want to ' + actionText + ' this content?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#c0392b',
            confirmButtonText: 'Yes'
        }).then(function (result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Please wait...',
                    showConfirmButton: false,
                    backdrop: true,
                    scrollbarPadding: false,
                    allowEscapeKey: function () { return !Swal.isLoading(); },
                    allowOutsideClick: function () { return !Swal.isLoading(); },
                    willOpen: function () {
                        Swal.showLoading();
                    }
                });
                $.post("<?php echo site_url('admin/ajax/set_status_cityoff') ?>", { id: id, status: newStatus }, function (result) {
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
                            text: result.msg || result.message || 'Failed to update status.'
                        });
                    }
                }, 'json');
            }
        });
    }

    function deleteCityOff(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Delete City Official',
            text: 'Are you sure you want to delete this city official? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#7f8c8d',
            confirmButtonText: 'Yes, Delete'
        }).then(function (result) {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    showConfirmButton: false,
                    backdrop: true,
                    scrollbarPadding: false,
                    allowEscapeKey: function () { return !Swal.isLoading(); },
                    allowOutsideClick: function () { return !Swal.isLoading(); },
                    willOpen: function () {
                        Swal.showLoading();
                    }
                });
                $.post("<?php echo site_url('admin/ajax/delete_cityoff') ?>", { id: id }, function (result) {
                    if (result.status == 1) {
                        tbl.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: 'City Official deleted successfully'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message || 'Failed to delete city official.'
                        });
                    }
                }, 'json');
            }
        });
    }

    $(document).on('click', '#carouselPreview .remove-image', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var imageName = $(this).data('image');
        var recordId = $('#cityOffId').val();

        if (!recordId) {
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure?',
            text: 'Do you really want to remove the image "' + imageName + '"?',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, keep it',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then(function (result) {
            if (result.isConfirmed) {
                removeCarouselImage(recordId, imageName);
            }
        });
    });

    $('#offpos').on('change', syncRankField);

    $('#offimg').on('change', function () {
        renderMainImagePreview(this.files[0]);
    });

    $('#offcaroimg').on('change', function () {
        var files = Array.from(this.files || []);
        if (files.length) {
            var validation = validateCarouselFiles(files);
            if (validation !== true) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Image Limit Exceeded',
                    text: validation
                });
                this.value = '';
            }
        }
        renderCarouselPreview();
    });

    $('#cityOffForm').on('submit', function (e) {
        e.preventDefault();
        submitCityOffForm();
    });

    $('#addModal').on('hidden.bs.modal', function () {
        resetCityOffModalState();
    });

    $(document).ready(function () {
        syncRankField();
        resetCityOffModalState();
        $('#addModal').modal('hide');
    });

    tbl = $('#tbloff').DataTable({
        select: false,
        searching: true,
        ordering: true,
        order: [[8, 'asc'], [2, 'asc'], [3, 'asc'], [1, 'asc']],
        pageLength: 10,
        processing: true,
        ajax: {
            url: '<?php echo base_url('admin/ajax/get_cityoff'); ?>',
            type: 'POST',
            data: function (d) {
                d.search_kw = $('form#cityoffSearchForm input[name="search"]').val();
                d.position = $('form#cityoffSearchForm select[name="position"]').val();
                d.status = $('form#cityoffSearchForm select[name="status"]').val();
            },
            dataSrc: function (json) {
                if (json.data && Array.isArray(json.data)) {
                    return json.data;
                }
                return [];
            }
        },
        initComplete: function () {
            var searchInput = $('#tbloff_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search city officials...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                width: '250px',
                'margin-left': '0.5rem'
            });

            var lengthSelect = $('#tbloff_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                width: 'auto',
                margin: '0 0.5rem'
            });
        },
        columns: [
            { title: 'City Official ID', data: 'ID', visible: false },
            {
                title: "Title",
                data: "off_name",
                className: "align-middle",
                render: function (data) {
                    return '<div class="d-flex justify-content-start">' + data + '</div>';
                }
            },
            {
                title: 'Position',
                data: 'off_position',
                width: '15%',
                className: 'align-middle',
                render: function (data, type) {
                    if (type === 'sort' || type === 'type') {
                        return getCityOffPositionSort(data);
                    }
                    return data || '-';
                }
            },
            {
                title: 'Rank',
                data: 'ranking',
                className: 'dt-center align-middle',
                width: '5%',
                render: function (data, type) {
                    if (type === 'sort' || type === 'type') {
                        return getCityOffRankSort(data);
                    }
                    return data ? data : '-';
                }
            },
            {
                title: 'Years of Service',
                data: 'years_of_service',
                visible: false,
                width: '10%',
                render: function (data) {
                    if (!data) {
                        return '-';
                    }
                    var text = data.replace(/<[^>]+>/g, '');
                    return text.length > 80 ? text.substring(0, 80) + '...' : text;
                }
            },
            {
                title: 'Personal Data',
                data: 'personal_data',
                visible: false,
                width: '20%',
                render: function (data) {
                    if (!data) {
                        return '-';
                    }
                    var text = data.replace(/<[^>]+>/g, '');
                    return text.length > 80 ? text.substring(0, 80) + '...' : text;
                }
            },
            {
                title: 'Awards',
                data: 'awards',
                visible: false,
                width: '20%',
                render: function (data) {
                    if (!data) {
                        return '-';
                    }
                    var text = data.replace(/<[^>]+>/g, '');
                    return text.length > 80 ? text.substring(0, 80) + '...' : text;
                }
            },
            {
                title: 'Picture',
                data: 'img_loc',
                className: 'dt-center',
                width: '10%',
                render: function (data) {
                    if (!data) {
                        return '<span class="text-muted">-</span>';
                    }
                    return '<img id="img_loc" class="img-fluid mt-3" src="' + cityOffImageBaseUrl + data + '">';
                }
            },
            {
                title: 'Status',
                data: 'status',
                className: 'dt-center align-middle',
                width: '10%',
                render: function (data, type) {
                    if (type === 'sort' || type === 'type') {
                        return getCityOffStatusSort(data);
                    }
                    if (data == 'ACTIVE') {
                        return '<span class="status-badge status-badge-active"><span class="status-dot status-dot-active"></span>Active</span>';
                    } else if (data == 'INACTIVE') {
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
                    var recordId = row.ID || row.id;

                    if (userLevel === 'VIEWER') {
                        return '<a class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" href="#" onclick="edit(' + recordId + '); return false;" style="width: 32px; height: 32px; border-radius: 50%;" title="View Details"><i class="fas fa-eye"></i></a>';
                    }

                    var actionHtml = '<div class="dropdown">' +
                        '<button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">' +
                        '<i class="bi bi-list"></i> Actions</button>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a class="dropdown-item" href="#" onclick="edit(' + recordId + '); return false;"><i class="bi bi-pencil me-1"></i> Edit</a></li>';

                    actionHtml += renderStatusToggleAction(userLevel, row, 'toggleStatus');
                    actionHtml += '</ul></div>';
                    return actionHtml;
                }
            }
        ]
    });

    var sltdRow = null;

    $('#tbloff tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    $('#cityoffSearchForm').on('submit', function (e) {
        e.preventDefault();
        tbl.ajax.reload();
    });

    $('#cityoffSearchForm').on('reset', function () {
        setTimeout(function () {
            tbl.ajax.reload();
        }, 0);
    });
</script>
