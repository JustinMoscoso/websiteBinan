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

    var barangayState = {
        mode: 'add',
        record: null
    };

    var quillCreateAbout, quillCreateMission, quillCreateVision;
    var barangayContactInputs = window.PhilippineContactInputs;
    if (barangayContactInputs) {
        barangayContactInputs.bind('#txtPhoneNumber', '#txtLandline');
    }

    function initBarangayEditors() {
        if (!quillCreateAbout) {
            quillCreateAbout = new Quill('#createabout', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link']
                    ]
                }
            });
        }
        if (!quillCreateMission) {
            quillCreateMission = new Quill('#txtMission', {
                theme: 'snow',
                modules: { toolbar: true }
            });
        }
        if (!quillCreateVision) {
            quillCreateVision = new Quill('#txtVision', {
                theme: 'snow',
                modules: { toolbar: true }
            });
        }
    }

    function syncBarangayEditors() {
        if (quillCreateAbout) $('#createAbout').val(quillCreateAbout.root.innerHTML);
        // Use name selector for hidden inputs (Quill replaces the containers, so IDs target the Quill div)
        if (quillCreateMission) $('input[name="txtMission"]').val(quillCreateMission.root.innerHTML);
        if (quillCreateVision) $('input[name="txtVision"]').val(quillCreateVision.root.innerHTML);
    }

    function clearBarangayEditors() {
        if (quillCreateAbout) quillCreateAbout.setContents([]);
        if (quillCreateMission) quillCreateMission.setContents([]);
        if (quillCreateVision) quillCreateVision.setContents([]);
        $('#createAbout').val('');
        $('input[name="txtMission"]').val('');
        $('input[name="txtVision"]').val('');
        $('#txtPhoneNumber, #txtLandline, #txtEmailAddress, #txtOfficeAddress').val('');
    }

    function resetBarangayModalState() {
        $('#addForm')[0].reset();
        $('#brgyId').val('');
        $('#brgyMode').val('add');
        $('#brgyModalTitle').text('Add Record');
        $('#btnBrgySave').text('Save');
        $('#brgyImg').prop('required', true);
		$('#brgyOrgChart').prop('required', false);
        $('#addBrgyLogoPreview').html('');
		$('#addBrgyOrgChartPreview').html('');
        barangayState.mode = 'add';
        barangayState.record = null;
        clearBarangayEditors();
        $('#txtPhoneNumber').val('+63 9');
    }

    function renderBarangayImagePreview(preview, file, fallbackHtml) {
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
            };
            reader.readAsDataURL(file);
            return;
        }
        preview.html(fallbackHtml || '');
    }

    function openBarangayModal(mode, record) {
        resetBarangayModalState();
        barangayState.mode = mode;
        barangayState.record = record || null;
        $('#brgyMode').val(mode);

        initBarangayEditors();

        if (mode === 'edit' && record) {
            $('#brgyModalTitle').text('Edit Record');
            $('#btnBrgySave').text('Update');
            $('#brgyId').val(record.ID || record.id || '');
            $('#txtBrgy').val(record.brgy_name || '');
            $('#txtCapt').val(record.brngy_capt || '');
            // Populate Quill editors with existing record data
            if (quillCreateAbout) quillCreateAbout.root.innerHTML = record.about || '';
            if (quillCreateMission) quillCreateMission.root.innerHTML = record.mission || '';
            if (quillCreateVision) quillCreateVision.root.innerHTML = record.vision || '';
            // Also sync hidden inputs
            $('#createAbout').val(record.about || '');
            $('#txtPhoneNumber').val(record.phone_number && barangayContactInputs
                ? barangayContactInputs.formatMobile(record.phone_number)
                : (record.phone_number || '+63 9'));
            $('#txtLandline').val(record.landline && barangayContactInputs
                ? barangayContactInputs.formatLandline(record.landline)
                : (record.landline || ''));
            $('#txtEmailAddress').val(record.email_address || '');
            $('#txtOfficeAddress').val(record.office_address || '');
            $('#brgyImg').prop('required', false);
        }

        $('#addModal').modal('show');
    }

    function isValidBarangayImage(file) {
        if (!file || file.size === 0) {
            return false;
        }

        const maxImageSizeMB = 4;
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (file.size > maxImageSizeMB * 1024 * 1024) {
            return 'size';
        }
        if (!validImageTypes.includes(file.type)) {
            return 'type';
        }
        return true;
    }

    $('#addModal').on('shown.bs.modal', function () {
        initBarangayEditors();

        if (barangayState.mode === 'edit' && barangayState.record) {
            quillCreateAbout.root.innerHTML = barangayState.record.about || '';
            quillCreateMission.root.innerHTML = barangayState.record.mission || '';
            quillCreateVision.root.innerHTML = barangayState.record.vision || '';
        }

        $('#brgyImg').off('change').on('change', function () {
			renderBarangayImagePreview($('#addBrgyLogoPreview'), this.files[0], barangayState.mode === 'edit' && barangayState.record && barangayState.record.img_logo
                ? `<img src="<?php echo base_url('admin/image/BARANGAY/') ?>${barangayState.record.img_logo}" alt="Current Barangay Logo" style="max-width: 120px; margin-top: 5px;">`
                : '');
        });

		$('#brgyOrgChart').off('change').on('change', function () {
			renderBarangayImagePreview($('#addBrgyOrgChartPreview'), this.files[0], barangayState.mode === 'edit' && barangayState.record && barangayState.record.org_chart_img
				? `<img src="<?php echo base_url('admin/image/BARANGAY/') ?>${barangayState.record.org_chart_img}" alt="Current Organizational Chart" style="max-width: 160px; max-height: 160px; object-fit: contain; margin-top: 5px;">`
				: '');
		});
    });

    $('#addModal').on('hidden.bs.modal', function () {
        resetBarangayModalState();
    });

    function submitBarangayForm() {
        initBarangayEditors();
        syncBarangayEditors();

        if (barangayContactInputs) {
            barangayContactInputs.prepare('#txtPhoneNumber', '#txtLandline');
        }

        const mode = ($('#brgyMode').val() || 'add').toLowerCase();
        const form = $('#addForm')[0];
        const formData = new FormData(form);
        const imageFile = formData.get('brgyImg');
		const orgChartFile = formData.get('brgyOrgChart');

        formData.set('id', $('#brgyId').val());
        formData.set('createAbout', quillCreateAbout ? quillCreateAbout.root.innerHTML : '');
        formData.set('txtMission', quillCreateMission ? quillCreateMission.root.innerHTML : '');
        formData.set('txtVision', quillCreateVision ? quillCreateVision.root.innerHTML : '');

        if (!formData.get('txtBrgy') || !formData.get('txtCapt') || !formData.get('createAbout') || !formData.get('txtMission') || !formData.get('txtVision')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }

        if (!formData.get('txtPhoneNumber') && !formData.get('txtLandline') &&
            !formData.get('txtEmailAddress') && !formData.get('txtOfficeAddress')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please provide at least one contact method.'
            });
            return;
        }

        if (formData.get('txtPhoneNumber') && barangayContactInputs &&
            !barangayContactInputs.isValidMobile(formData.get('txtPhoneNumber'))) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Phone Number must use the format +63 9XX XXX XXXX.' });
            return;
        }

        if (formData.get('txtLandline') && barangayContactInputs &&
            !barangayContactInputs.isValidLandline(formData.get('txtLandline'))) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Landline must use (049) 123-4567 or (02) 1234-5678.' });
            return;
        }

        if (mode === 'add' && (!imageFile || imageFile.size === 0)) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload a barangay logo.'
            });
            return;
        }

        if (imageFile && imageFile.size > 0) {
            const imageValidation = isValidBarangayImage(imageFile);
            if (imageValidation === 'size') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Barangay logo size should not exceed 4 MB.'
                });
                return;
            }
            if (imageValidation === 'type') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload a valid barangay logo (jpg, png, gif, webp).'
                });
                return;
            }
        }

		if (orgChartFile && orgChartFile.size > 0) {
			const orgChartValidation = isValidBarangayImage(orgChartFile);
			if (orgChartValidation === 'size') {
				Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Organizational chart size should not exceed 4 MB.' });
				return;
			}
			if (orgChartValidation === 'type') {
				Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'Please upload a valid organizational chart (jpg, png, gif, webp).' });
				return;
			}
		}

        const url = mode === 'edit'
            ? '<?php echo site_url('admin/ajax/update_barangay'); ?>'
            : '<?php echo site_url('admin/ajax/create_barangay'); ?>';

        Swal.fire({
            title: 'Please wait...',
            showConfirmButton: false,
            backdrop: true,
            willOpen: () => Swal.showLoading()
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
                        text: result.message || (mode === 'edit' ? 'Barangay updated successfully!' : 'Barangay data saved!')
                    });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Failed to save data.'
                    });
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

    $('#addForm').on('submit', function (e) {
        e.preventDefault();
        submitBarangayForm();
    });

    function edit(brgyId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_barangay'); ?>',
            method: 'POST',
            data: { id: brgyId },
            success: function (response) {
                if (response.status === 1) {
                    const barangay = response.data;
                    openBarangayModal('edit', barangay);
                    $('#addBrgyLogoPreview').html(
                        barangay.img_logo
                            ? `<img src="<?php echo base_url('admin/image/BARANGAY/') ?>${barangay.img_logo}" alt="Current Barangay Logo" style="max-width: 120px; margin-top: 5px;">`
                            : '<small>No logo available.</small>'
                    );
                    $('#brgyImg').val('');
					$('#addBrgyOrgChartPreview').html(
						barangay.org_chart_img
							? `<img src="<?php echo base_url('admin/image/BARANGAY/') ?>${barangay.org_chart_img}" alt="Current Organizational Chart" style="max-width: 160px; max-height: 160px; object-fit: contain; margin-top: 5px;">`
							: '<small>No organizational chart available.</small>'
					);
					$('#brgyOrgChart').val('');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Barangay not found.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch barangay details. Please try again later.'
                });
            }
        });
    }

    // Toggle Status function
    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);
        var confirmText = newStatus === 'ACTIVE' ? 'This will be displayed in the barangay section.' : 'This will not be displayed in the barangay section.';

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'Barangay Content'),
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
                $.post("<?php echo site_url('admin/ajax/set_status_barangay') ?>",
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

    function deleteBrgy(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Delete Barangay',
            text: 'Are you sure you want to permanently delete this barangay? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#7f8c8d',
            confirmButtonText: 'Yes, Delete'
        }).then(function (result) {
            if (!result.isConfirmed) {
                return;
            }

            Swal.fire({
                title: 'Deleting...',
                showConfirmButton: false,
                allowEscapeKey: function () { return !Swal.isLoading(); },
                allowOutsideClick: function () { return !Swal.isLoading(); },
                willOpen: function () { Swal.showLoading(); }
            });

            $.post("<?php echo site_url('admin/ajax/delete_barangay') ?>", { id: id }, function (response) {
                if (response.status == 1) {
                    tbl.ajax.reload(null, false);
                    Swal.fire({ icon: 'success', title: 'Deleted', text: response.message || 'Barangay deleted successfully.' });
                } else {
                    Swal.fire({ icon: 'error', title: 'Delete Failed', text: response.message || response.msg || 'Unable to delete the barangay.' });
                }
            }).fail(function (xhr) {
                var response = xhr.responseJSON || {};
                Swal.fire({ icon: 'error', title: 'Delete Failed', text: response.message || 'The server could not process the delete request.' });
            });
        });
    }

    var tbl = $('#tblbrgy').DataTable({
        select: false,
        searching: true,
        ordering: true,
        order: [],
        pageLength: 10,
        processing: true,
        scrollX: true,
        autoWidth: false,
        ajax: {
            url: "<?php echo base_url('admin/ajax/get_barangay'); ?>",
            type: "POST",
            data: function (request) {
                request.searchQuery = $('#searchBrgy').val() || '';
                request.status = $('#barangaySearchForm select[name="status"]').val() || '';
            },
            dataSrc: function (json) {
                return json.data || [];
            }
        },
        initComplete: function () {
            var searchInput = $('#tblbrgy_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search barangays...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });

            var lengthSelect = $('#tblbrgy_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
        },
        columns: [
            { "title": "Barangay ID", "data": "ID", "visible": false },
            { "title": "Barangay", "data": "brgy_name", width: '18%', "className": "align-middle" },
            {
                "title": "Logo",
                "data": "img_logo",
                "className": "dt-center brgy-logo-cell",
                width: '12%',
                "render": function (data, type, row) {
                    if (!data) {
                        return '<div class="brgy-logo-thumb"><small class="text-muted">No logo</small></div>';
                    }
                    return '<div class="brgy-logo-thumb"><img id="img_logo" src="<?php echo base_url('admin/image/BARANGAY/') ?>' + data + '" alt="Barangay logo"></div>';
                }
            },
            { "title": "Captain", "data": "brngy_capt", width: '15%', "className": "text-center align-middle brgy-captain-cell" },
        // Captain image column - commented out as not needed
        /*
        {
            "title": "Captain image", "data": "img_capt", "className": "dt-center", width: '15%',
            "render": function (data, type, row) {
                return '<img id="img_capt" class="img-fluid mt-3" src="<?php echo base_url('admin/image/BARANGAY/') ?>' + data + '">';
            }
        },
        */
    {
        "title": "Status",
            "data": "status",
                "className": "dt-center text-center align-middle brgy-status-cell",
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
                    "width": '10%',
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
                            actionHtml += renderDeleteAction(userLevel, row.ID, 'deleteBrgy');

                            actionHtml += `</ul></div>`;
                            return actionHtml;
                        }
    },
    ]
});

    var sltdRow = null;

    $('#tblbrgy tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Called directly by both the form's Enter submit and the Search button.
    window.runBarangayAdvancedSearch = function (event) {
        if (event) {
            event.preventDefault();
        }
        applyFilters();
        return false;
    };

    // Reload from the database so filtering is not restricted to the ten rows
    // currently loaded in DataTables.
    function applyFilters() {
        tbl.search('').columns().search('');
        var barangayFilterQuery = $.param({
            searchQuery: $('#searchBrgy').val() || '',
            status: $('#barangaySearchForm select[name="status"]').val() || ''
        });

        $.ajax({
            url: "<?php echo base_url('admin/ajax/get_barangay'); ?>?" + barangayFilterQuery,
            type: 'POST',
            dataType: 'json',
            cache: false,
            success: function (response) {
                if (response.status == 1) {
                    tbl.clear();
                    tbl.rows.add(response.data || []);
                    tbl.draw();
                    return;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Search Failed',
                    text: response.message || 'Unable to search barangays.'
                });
            },
            error: function (xhr) {
                var response = xhr.responseJSON || {};
                Swal.fire({
                    icon: 'error',
                    title: 'Search Failed',
                    text: response.message || 'The barangay search request could not be completed.'
                });
            }
        });
    }

    // Clear filters
    $('#barangaySearchForm button[type="reset"]').click(function () {
        // Wait until the browser has reset the form before building AJAX data.
        setTimeout(function () {
            applyFilters();
        }, 0);
    });

</script>
