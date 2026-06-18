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

    //for edit function
    var quillCreatAbout, quillEditAbout, quillMission, quillVision, quillContact, quillStaff;

    //for add function
    var quillCreateAbout, quillCreateMission, quillCreateVision, quillCreateContact, quillCreateStaff;

    // Initialize all Quill editors
    function initializeQuillEditors() {
        if (!quillEditAbout) {
            quillEditAbout = new Quill('#editabout', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link']
                    ]
                }
            });
        }

        if (!quillMission) {
            quillMission = new Quill('#editMission', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link']
                    ]
                }
            });
        }

        if (!quillVision) {
            quillVision = new Quill('#editVision', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link']
                    ]
                }
            });
        }

        if (!quillContact) {
            quillContact = new Quill('#editContact', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link']
                    ]
                }
            });
        }

        if (!quillStaff) {
            quillStaff = new Quill('#editStaff', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link']
                    ]
                }
            });
        }
    }

    function initializeAddQuillEditors() {
        if (!quillCreateAbout) {
            quillCreateAbout = new Quill('#createabout', {
                theme: 'snow',
                modules: { toolbar: true } // Customize toolbar as needed
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
        if (!quillCreateContact) {
            quillCreateContact = new Quill('#txtContact', {
                theme: 'snow',
                modules: { toolbar: true }
            });
        }
        if (!quillCreateStaff) {
            quillCreateStaff = new Quill('#txtStaff', {
                theme: 'snow',
                modules: { toolbar: true }
            });
        }
    }

    // Initialize when Add Modal opens
    $('#addModal').on('shown.bs.modal', function () {
        initializeAddQuillEditors();

        // Add image preview on file input change for Add modal
        $('#brgyImg').off('change').on('change', function () {
            const file = this.files[0];
            const preview = $('#addBrgyLogoPreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
                };
                reader.readAsDataURL(file);
            } else {
                preview.html('');
            }
        });

        $('#brgyImgCapt').off('change').on('change', function () {
            const file = this.files[0];
            const preview = $('#addBrgyCaptPreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
                };
                reader.readAsDataURL(file);
            } else {
                preview.html('');
            }
        });
    });

    // Clear Quill when Add Modal closes
    $('#addModal').on('hidden.bs.modal', function () {
        if (quillCreateAbout) {
            quillCreateAbout.root.innerHTML = '';
        }
        if (quillCreateContact) {
            quillCreateContact.root.innerHTML = '';
        }
        if (quillCreateStaff) {
            quillCreateStaff.root.innerHTML = '';
        }
        // Clear image previews
        $('#addBrgyLogoPreview').html('');
        // $('#addBrgyCaptPreview').html(''); // Captain image preview - commented out as not needed
    });

    $('#btnAdd').on('click', function (e) {
        e.preventDefault();
        // Ensure all Quill editors are initialized
        if (!quillCreateAbout || !quillCreateMission || !quillCreateVision || !quillCreateContact || !quillCreateStaff) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Editors not properly initialized. Please refresh the page.'
            });
            return;
        }

        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Add Quill content to formData (using set() to avoid duplicates)
        formData.set('createAbout', quillCreateAbout.root.innerHTML);
        formData.set('txtMission', quillCreateMission.root.innerHTML);
        formData.set('txtVision', quillCreateVision.root.innerHTML);
        formData.set('txtContact', quillCreateContact.root.innerHTML);
        formData.set('txtStaff', quillCreateStaff.root.innerHTML);

        // Form validation - check both form fields and Quill content
        if (!formData.get('txtBrgy') || !formData.get('txtCapt') ||
            quillCreateAbout.root.innerHTML.trim() === '' ||
            quillCreateMission.root.innerHTML.trim() === '' ||
            quillCreateVision.root.innerHTML.trim() === '' ||
            quillCreateContact.root.innerHTML.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }

        // Image validation
        let imageFile = formData.get('brgyImg');
        // let imageFile2 = formData.get('brgyImgCapt'); // Captain image - commented out as not needed
        const maxImageSizeMB = 4;
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!imageFile || imageFile.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload a barangay logo.'
            });
            return;
        }

        // Captain image validation - commented out as not needed
        /*
        if (!imageFile2 || imageFile2.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload a captain image.'
            });
            return;
        }
        */

        if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: `Barangay logo size should not exceed ${maxImageSizeMB} MB.`
            });
            return;
        }

        // Captain image size validation - commented out as not needed
        /*
        if (imageFile2.size > maxImageSizeMB * 1024 * 1024) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: `Captain image size should not exceed ${maxImageSizeMB} MB.`
            });
            return;
        }
        */

        if (!validImageTypes.includes(imageFile.type)) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload a valid barangay logo (jpg, png, gif).'
            });
            return;
        }


        Swal.fire({
            title: 'Please wait...',
            showConfirmButton: false,
            backdrop: true,
            willOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: '<?php echo site_url('admin/ajax/create_barangay'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    quillCreateAbout.root.innerHTML = '';
                    quillCreateMission.root.innerHTML = '';
                    quillCreateVision.root.innerHTML = '';
                    quillCreateContact.root.innerHTML = '';
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Barangay data saved!'
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
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error); // Log error for debugging
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request. Please check console for details.'
                });
            }
        });
    });

    function edit(brgyId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_barangay'); ?>',
            method: 'POST',
            data: { id: brgyId },
            success: function (response) {
                if (response.status === 1) {
                    let barangay = response.data;

                    // Set basic form values
                    $('#editBrgyId').val(barangay.ID);
                    $('#editBrgy').val(barangay.brgy_name);
                    $('#editCapt').val(barangay.brngy_capt);

                    // Initialize Quill editors
                    initializeQuillEditors();

                    // Set Quill content
                    if (barangay.about) {
                        quillEditAbout.root.innerHTML = barangay.about;
                        $('#editAbout').val(barangay.about);
                    }

                    if (barangay.mission) {
                        quillMission.root.innerHTML = barangay.mission;
                        $('#editMissionInput').val(barangay.mission);
                    }

                    if (barangay.vision) {
                        quillVision.root.innerHTML = barangay.vision;
                        $('#editVisionInput').val(barangay.vision);
                    }

                    if (barangay.contact) {
                        quillContact.root.innerHTML = barangay.contact;
                        $('#editContactInput').val(barangay.contact);
                    }

                    if (barangay.barangay_staff) {
                        quillStaff.root.innerHTML = barangay.barangay_staff;
                        $('#editStaffInput').val(barangay.barangay_staff);
                    }

                    // Show current images in preview
                    $('#editBrgyLogoPreview').html(
                        barangay.img_logo
                            ? `<img src="<?php echo base_url('admin/image/BARANGAY/') ?>${barangay.img_logo}" alt="Current Barangay Logo" style="max-width: 120px; margin-top: 5px;">`
                            : '<small>No logo available.</small>'
                    );

                // Captain image preview - commented out as not needed
                /*
                $('#editBrgyCaptPreview').html(
                    barangay.img_capt
                        ? `<img src="<?php echo base_url('admin/image/BARANGAY/') ?>${ barangay.img_capt } " alt="Current Captain Image" style="max - width: 120px; margin - top: 5px; ">`
                        : '<small>No captain image available.</small>'
                );
                */

        // Reset file inputs
        $('#editbrgyImg').val('');
        // $('#editbrgyImgCapt').val(''); // Captain image input - commented out as not needed

        // Add image preview on file input change for Barangay Logo
        $('#editbrgyImg').off('change').on('change', function () {
            const file = this.files[0];
            const preview = $('#editBrgyLogoPreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
                };
                reader.readAsDataURL(file);
            } else {
                // If no file, show the original image again (if any)
                if (barangay.img_logo) {
                    preview.html(`<img src="<?php echo base_url('admin/image/BARANGAY/') ?>${barangay.img_logo}" alt="Current Barangay Logo" style="max-width: 120px; margin-top: 5px;">`);
                } else {
                    preview.html('<small>No logo available.</small>');
                }
            }
        });

                // Captain image preview functionality - commented out as not needed
                /*
                // Add image preview on file input change for Captain Image
                $('#editbrgyImgCapt').off('change').on('change', function() {
                    const file = this.files[0];
                    const preview = $('#editBrgyCaptPreview');
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // If no file, show the original image again (if any)
                        if (barangay.img_capt) {
                            preview.html(`<img src="<?php echo base_url('admin/image/BARANGAY/') ?>${ barangay.img_capt } " alt="Current Captain Image" style="max - width: 120px; margin - top: 5px; ">`);
    } else {
        preview.html('<small>No captain image available.</small>');
    }
                    }
                });
                */

    $('#editModal').modal('show');
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

    // Update function
    $('#btnEdit').click(function (e) {
        e.preventDefault();
        let form = $('#editForm')[0];
        let formData = new FormData(form);

        // Add Quill content to formData
        formData.set('editAbout', quillEditAbout.root.innerHTML);
        formData.set('editMission', quillMission.root.innerHTML);
        formData.set('editVision', quillVision.root.innerHTML);
        formData.set('editContact', quillContact.root.innerHTML);
        formData.set('editStaff', quillStaff.root.innerHTML);

        // Form validation
        if (!formData.get('editBrgy') || !formData.get('editCapt') ||
            !formData.get('editAbout') || !formData.get('editMission') ||
            !formData.get('editVision') || !formData.get('editContact')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }

        // Image validation
        let imageFile = formData.get('editbrgyImg');
        // let imageFile2 = formData.get('editbrgyImgCapt'); // Captain image - commented out as not needed
        const maxImageSizeMB = 4;
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (imageFile && imageFile.size > 0) {
            if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: `Logo image size should not exceed ${maxImageSizeMB} MB.`
                });
                return;
            }
            if (!validImageTypes.includes(imageFile.type)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload a valid logo image file (jpg, png, gif).'
                });
                return;
            }
        }

        // Captain image validation - commented out as not needed
        /*
        if (imageFile2 && imageFile2.size > 0) {
            if (imageFile2.size > maxImageSizeMB * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: `Captain image size should not exceed ${maxImageSizeMB} MB.`
                });
                return;
            }
            if (!validImageTypes.includes(imageFile2.type)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload a valid captain image file (jpg, png, gif).'
                });
                return;
            }
        }
        */

        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_barangay'); ?>',
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
                    text: 'Unable to update barangay. Please try again later.'
                });
            }
        });
    });

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
            text: "Are you sure you want to delete this barangay? This action cannot be undone.",
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
                $.post("<?php echo site_url('admin/ajax/delete_barangay') ?>",
                    { id: id },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Barangay deleted successfully'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to delete barangay',
                            });
                        }
                    }
                );
            }
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
            { "title": "Barangay", "data": "brgy_name", width: '18%' },
            {
                "title": "Logo", "data": "img_logo", "className": "dt-center", width: '12%',
                "render": function (data, type, row) {
                    return '<img id="img_logo" class="img-fluid mt-3" src="<?php echo base_url('admin/image/BARANGAY/') ?>' + data + '">';
                }
            },
            { "title": "Captain", "data": "brngy_capt", width: '15%', "className": "dt-body-left" },
            {
                "title": "Barangay Staff", "data": "barangay_staff", width: '30%',
                "render": function (data, type, row) {
                    if (data && data.trim() !== '') {
                        // Strip HTML tags and limit text length for table display
                        var text = data.replace(/<[^>]*>?/gm, '');
                        if (text.length > 80) {
                            text = text.substring(0, 80) + '...';
                        }
                        return '<div class="text-muted small">' + text + '</div>';
                    } else {
                        return '<span class="text-muted small">No staff info</span>';
                    }
                }
            },
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
        "width": '15%',
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

    // Handle Search Form Submission (Enables Enter key support)
    $('#barangaySearchForm').on('submit', function (e) {
        e.preventDefault();
        applyFilters();
    });

    // Enhanced filtering with combined search
    function applyFilters() {
        var searchTerm = $('#searchBrgy').val().trim().toLowerCase();
        var statusFilter = $('select[name="status"]').val();

        // Clear previous filters
        tbl.search('').columns().search('').draw();

        // Apply new filters
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var row = tbl.row(dataIndex).data();
                var matches = true;

                // Combined search for both barangay name and captain (case-insensitive)
                if (searchTerm) {
                    var brgyName = row.brgy_name.toLowerCase();
                    var captainName = row.brngy_capt.toLowerCase();

                    // Check if search term matches either barangay name or captain name
                    if (!brgyName.includes(searchTerm) && !captainName.includes(searchTerm)) {
                        matches = false;
                    }
                }

                // Status exact match
                if (statusFilter && row.status !== statusFilter) {
                    matches = false;
                }

                return matches;
            }
        );

        tbl.draw();
        $.fn.dataTable.ext.search.pop();
    }

    // Clear filters
    $('#barangaySearchForm button[type="reset"]').click(function () {
        $('#barangaySearchForm')[0].reset();
        tbl.search('').columns().search('').draw();
    });

</script>