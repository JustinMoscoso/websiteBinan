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

    var contactModalMode = 'add';

    function resetContactModalState() {
        contactModalMode = 'add';
        const form = document.getElementById('addForm');
        if (form) {
            form.reset();
        }

        const deptSelectEl = $('#txtDept')[0];
        const brgySelectEl = $('#txtBrgy')[0];
        const deptSelect = deptSelectEl ? deptSelectEl.selectize : null;
        const brgySelect = brgySelectEl ? brgySelectEl.selectize : null;
        if (deptSelect) {
            deptSelect.clear(true);
        }
        if (brgySelect) {
            brgySelect.clear(true);
        }

        $('#contactId').val('');
        $('#contactMode').val('add');
        $('#contactModalTitle').text('Add Contact');
        $('#btnAdd').text('Save');
        $('#deptGroup, #brgyGroup, #othersGrp').hide();
        setPhilippineMobilePrefix('#smart');
        setPhilippineMobilePrefix('#globe');
    }

    function setContactModalMode(mode) {
        contactModalMode = mode;
        $('#contactMode').val(mode);
        $('#contactModalTitle').text(mode === 'edit' ? '' : 'Add Contact');
        $('#btnAdd').text(mode === 'edit' ? 'Update' : 'Save');
    }

    function openContactModal(mode, res) {
        setContactModalMode(mode);

        if (mode === 'edit' && res) {
            $('#contactId').val(res.ID);
            $('#contact').val(res.number ? formatPhilippineLandline(res.number) : '');
            $('#smart').val(res.smart ? formatPhilippineMobile(res.smart) : '+63 9');
            $('#globe').val(res.globe ? formatPhilippineMobile(res.globe) : '+63 9');
            $('#telco').val(res.telco ? formatPhilippineLandline(res.telco) : '');

            if (res.brgy_name) {
                $('#category').val('BRGY');
                $('#deptGroup').hide();
                $('#brgyGroup').show();
                $('#othersGrp').hide();
                populateBrgyDropdown($('#txtBrgy'), res.content_ref_id);
                $('#txtDept')[0].selectize.clear(true);
                $('#txtOthers').val('');
            } else if (res.dept_name) {
                $('#category').val('DEPT');
                $('#deptGroup').show();
                $('#brgyGroup').hide();
                $('#othersGrp').hide();
                populateDepartmentDropdown($('#txtDept'), res.content_ref_id);
                $('#txtBrgy')[0].selectize.clear(true);
                $('#txtOthers').val('');
            } else {
                $('#category').val('Others');
                $('#deptGroup').hide();
                $('#brgyGroup').hide();
                $('#othersGrp').show();
                $('#txtOthers').val(res.content_ref_id);
                $('#txtDept')[0].selectize.clear(true);
                $('#txtBrgy')[0].selectize.clear(true);
            }
        } else {
            resetContactModalState();
        }

        $('#addModal').modal('show');
    }

    // Initialize selectize for all selects
    // Placeholder must be passed in the config — Selectize ignores the HTML placeholder attribute
    // dropdownParent: 'body' appends the dropdown list to <body> so it fully escapes
    // the modal's stacking context and always renders on top without bleed-through
    $('#txtDept').selectize({
        sortField: 'text',
        allowClear: true,
        placeholder: 'Select Department',
        dropdownParent: 'body'
    });

    $('#txtBrgy').selectize({
        sortField: 'text',
        allowClear: true,
        placeholder: 'Select Barangay',
        dropdownParent: 'body'
    });

    function formatPhilippineMobile(value) {
        const raw = String(value || '').trim();
        if (!raw) {
            return '';
        }

        let digits = raw.replace(/\D/g, '');
        if (!digits) {
            return raw;
        }

        if (digits.startsWith('63')) {
            digits = digits.slice(2);
        } else if (digits.startsWith('0')) {
            digits = digits.slice(1);
        }

        digits = digits.slice(0, 10);

        if (!digits) {
            return '+63';
        }

        let formatted = '+63 ' + digits.slice(0, 3);
        if (digits.length > 3) {
            formatted += ' ' + digits.slice(3, 6);
        }
        if (digits.length > 6) {
            formatted += ' ' + digits.slice(6, 10);
        }

        return formatted;
    }

    function formatPhilippineLandline(value) {
        const raw = String(value || '').trim();
        if (!raw) {
            return '';
        }

        let digits = raw.replace(/\D/g, '');
        if (!digits) {
            return '';
        }

        if (digits.startsWith('63') && digits.length > 2) {
            digits = digits.slice(2);
        }

        if (digits.startsWith('049')) {
            const subscriber = digits.slice(3, 10);
            if (digits.length <= 3) {
                return '(049)';
            }
            if (subscriber.length <= 3) {
                return '(049) ' + subscriber;
            }
            return '(049) ' + subscriber.slice(0, 3) + '-' + subscriber.slice(3);
        }

        if (digits.startsWith('02')) {
            const subscriber = digits.slice(2, 10);
            if (digits.length <= 2) {
                return '(02)';
            }
            if (subscriber.length <= 4) {
                return '(02) ' + subscriber;
            }
            return '(02) ' + subscriber.slice(0, 4) + '-' + subscriber.slice(4);
        }

        return raw;
    }

    function mobilePrefixOnly(value) {
        return String(value || '').trim() === '+63 9';
    }

    function landlinePrefixOnly(value) {
        const trimmed = String(value || '').trim();
        return trimmed === '(' || trimmed === '(0' || trimmed === '(02' || trimmed === '(049' || trimmed === '(02)' || trimmed === '(049)' || trimmed === '(02) ' || trimmed === '(049) ';
    }

    function setPhilippineLandlinePrefix(selector) {
        return;
    }

    function setPhilippineMobilePrefix(selector) {
        const $field = $(selector);
        if (!$field.val()) {
            $field.val('+63 9');
        }
    }

    function isValidPhilippineMobile(value) {
        return /^\+63\s9\d{2}\s\d{3}\s\d{4}$/.test(String(value || '').trim());
    }

    function isValidPhilippineLandline(value) {
        const normalized = String(value || '').trim();
        return /^\(049\)\s\d{3}-\d{4}$/.test(normalized) || /^\(02\)\s\d{4}-\d{4}$/.test(normalized);
    }

    function bindPhilippineMobileFormatting(selector) {
        $(document).on('input', selector, function () {
            this.value = formatPhilippineMobile(this.value);
        });

        $(document).on('blur', selector, function () {
            this.value = formatPhilippineMobile(this.value);
        });
    }

    function bindPhilippineLandlineFormatting(selector) {
        $(document).on('keydown', selector, function (e) {
            const allowedKeys = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
            const isShortcut = e.ctrlKey || e.metaKey;

            if (allowedKeys.includes(e.key) || isShortcut) {
                return;
            }

            if (/^\d$/.test(e.key)) {
                return;
            }

            e.preventDefault();
        });

        $(document).on('input', selector, function () {
            this.value = formatPhilippineLandline(this.value);
        });

        $(document).on('blur', selector, function () {
            this.value = formatPhilippineLandline(this.value);
        });
    }

    bindPhilippineMobileFormatting('#smart, #globe');
    bindPhilippineLandlineFormatting('#contact, #telco');

    $('#addModal').on('hidden.bs.modal', function () {
        resetContactModalState();
    });


    // Toggle Status function
    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'Contact'),
            text: "Are you sure you want to " + actionText + " this contact?",
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
                $.post("<?php echo site_url('admin/ajax/set_status_contact') ?>",
                    { id: id, 'status': newStatus },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: statusSuccessText('Contact', actionText)
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
    function deleteContact(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Delete Contact',
            text: "Are you sure you want to delete this contact? This action cannot be undone.",
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
                $.post("<?php echo site_url('admin/ajax/delete_contacts') ?>",
                    { id: id },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Contact deleted successfully'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to delete contact',
                            });
                        }
                    }
                );
            }
        });
    }

    // Function to populate departments dropdown
    function populateDepartmentDropdown(selectElement, selectedValue = null) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_dept'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 1 && Array.isArray(response.data)) {
                    let selectizeControl = selectElement[0].selectize;
                    selectizeControl.clearOptions();
                    response.data.forEach(function (department) {
                        selectizeControl.addOption({ value: department.ID, text: department.dept_name });
                    });
                    selectizeControl.refreshOptions(false); // Refresh the options in the selectize control
                    if (selectedValue) {
                        selectizeControl.setValue(selectedValue); // Set the selected value
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unexpected response format.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch departments. Please try again later.'
                });
            }
        });
    }

    // Function to populate barangay dropdown
    function populateBrgyDropdown(selectElement, selectedValue = null) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_barangay'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 1 && Array.isArray(response.data)) {
                    let selectizeControl = selectElement[0].selectize;
                    selectizeControl.clearOptions();
                    response.data.forEach(function (barangay) {
                        selectizeControl.addOption({ value: barangay.ID, text: barangay.brgy_name });
                    });
                    selectizeControl.refreshOptions(false); // Refresh the options in the selectize control
                    if (selectedValue) {
                        selectizeControl.setValue(selectedValue); // Set the selected value
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unexpected response format.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch barangays. Please try again later.'
                });
            }
        });
    }

    // Show/hide department or barangay dropdowns based on category selection
    $('#category').on('change', function () {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'DEPT') {
            $('#deptGroup').show();
            $('#brgyGroup').hide();
            $('#othersGrp').hide();
            $('#txtBrgy')[0].selectize.clear(true);
            $('#txtOthers').val('');
            populateDepartmentDropdown($('#txtDept'));
        } else if (selectedCategory === 'BRGY') {
            $('#deptGroup').hide();
            $('#brgyGroup').show();
            $('#othersGrp').hide();
            $('#txtDept')[0].selectize.clear(true);
            $('#txtOthers').val('');
            populateBrgyDropdown($('#txtBrgy'));
        } else if (selectedCategory === 'Others') {
            $('#deptGroup').hide();
            $('#brgyGroup').hide();
            $('#othersGrp').show();
            $('#txtDept')[0].selectize.clear(true);
            $('#txtBrgy')[0].selectize.clear(true);
        } else {
            $('#deptGroup, #brgyGroup').hide();
        }
    });

    // Populate departments dropdown
    $('#addModal').on('show.bs.modal', function (e) {
        if (contactModalMode !== 'edit') {
            resetContactModalState();
        }
    });

    // Save new hotlines
    $('#btnAdd').on('click', function (e) {
        e.preventDefault();
        let form = $('#addForm')[0];

        $('#contact').val(formatPhilippineLandline($('#contact').val()));
        $('#telco').val(formatPhilippineLandline($('#telco').val()));
        $('#smart').val(formatPhilippineMobile($('#smart').val()));
        $('#globe').val(formatPhilippineMobile($('#globe').val()));
        if (landlinePrefixOnly($('#contact').val())) {
            $('#contact').val('');
        }
        if (landlinePrefixOnly($('#telco').val())) {
            $('#telco').val('');
        }
        if (mobilePrefixOnly($('#smart').val())) {
            $('#smart').val('');
        }
        if (mobilePrefixOnly($('#globe').val())) {
            $('#globe').val('');
        }

        let formData = new FormData(form);
        const isEdit = $('#contactMode').val() === 'edit';

        // Form validation — category is always required
        if (!formData.get('category')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a directory section.'
            });
            return;
        }

        if (formData.get('category') === 'DEPT' && !formData.get('txtDept')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a department.'
            });
            return;
        }

        if (formData.get('category') === 'BRGY' && !formData.get('txtBrgy')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a barangay.'
            });
            return;
        }

        if (formData.get('category') === 'Others' && !formData.get('txtOthers')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill all fields.'
            });
            return;
        }

        // At least one telecom field must be filled
        const addContact = formData.get('contact') || '';
        const addSmart   = formData.get('smart')   || '';
        const addGlobe   = formData.get('globe')   || '';
        const addTelco   = formData.get('telco')   || '';

        if (!addContact && !addSmart && !addGlobe && !addTelco) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in at least one contact number.'
            });
            return;
        }

        // Validate format only for fields that have a value
        if (addContact && !isValidPhilippineLandline(addContact)) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'PLDT Landline must be in Philippine landline format, for example (049) 123-4567 or (02) 1234-5678.' });
            return;
        }
        if (addTelco && !isValidPhilippineLandline(addTelco)) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'INTELCO Line must be in Philippine landline format, for example (049) 123-4567 or (02) 1234-5678.' });
            return;
        }
        if (addSmart && !isValidPhilippineMobile(addSmart)) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'SMART number must be in the format +63 9XX XXX XXXX.' });
            return;
        }
        if (addGlobe && !isValidPhilippineMobile(addGlobe)) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'GLOBE number must be in the format +63 9XX XXX XXXX.' });
            return;
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
            url: isEdit ? '<?php echo site_url('admin/ajax/update_contact'); ?>' : '<?php echo site_url('admin/ajax/create_contact'); ?>',
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
                        text: isEdit ? 'Contact updated successfully.' : 'Data saved!'
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
            url: '<?php echo site_url('admin/ajax/get_contact'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    openContactModal('edit', response.data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Hotline not found.'
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

    /*
    if (false) {
        let form = $('#editForm')[0];

        $('#editContact').val(formatPhilippineLandline($('#editContact').val()));
        $('#editTelco').val(formatPhilippineLandline($('#editTelco').val()));
        $('#editSmart').val(formatPhilippineMobile($('#editSmart').val()));
        $('#editGlobe').val(formatPhilippineMobile($('#editGlobe').val()));
        if (landlinePrefixOnly($('#editContact').val())) {
            $('#editContact').val('');
        }
        if (landlinePrefixOnly($('#editTelco').val())) {
            $('#editTelco').val('');
        }
        if (mobilePrefixOnly($('#editSmart').val())) {
            $('#editSmart').val('');
        }
        if (mobilePrefixOnly($('#editGlobe').val())) {
            $('#editGlobe').val('');
        }

        let formData = new FormData(form);

        // Form validation — at least one telecom field must be filled
        const editContact = formData.get('editContact') || '';
        const editSmart   = formData.get('editSmart')   || '';
        const editGlobe   = formData.get('editGlobe')   || '';
        const editTelco   = formData.get('editTelco')   || '';

        if (!editContact && !editSmart && !editGlobe && !editTelco) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in at least one contact number.'
            });
            return;
        }

        if (formData.get('editcategory') === 'DEPT' && !formData.get('editDept')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a department.'
            });
            return;
        }

        if (formData.get('editcategory') === 'BRGY' && !formData.get('editBrgy')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please select a barangay.'
            });
            return;
        }

        if (formData.get('editcategory') === 'Others' && !formData.get('editOthers')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill all fields.'
            });
            return;
        }

        // Validate format only for fields that have a value
        if (editContact && !isValidPhilippineLandline(editContact)) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'PLDT Landline must be in Philippine landline format, for example (049) 123-4567 or (02) 1234-5678.' });
            return;
        }
        if (editTelco && !isValidPhilippineLandline(editTelco)) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'INTELCO Line must be in Philippine landline format, for example (049) 123-4567 or (02) 1234-5678.' });
            return;
        }
        if (editSmart && !isValidPhilippineMobile(editSmart)) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'SMART number must be in the format +63 9XX XXX XXXX.' });
            return;
        }
        if (editGlobe && !isValidPhilippineMobile(editGlobe)) {
            Swal.fire({ icon: 'warning', title: 'Validation Error', text: 'GLOBE number must be in the format +63 9XX XXX XXXX.' });
            return;
        }

        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_contact'); ?>',
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
                        $('#addModal').modal('hide');
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
                    text: 'Unable to update. Please try again later.'
                });
            }
        });
    }
    */

    // datatable init
    var tbl = $('#tblhotlines').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_contact'); ?>",
            "type": "POST",
            "data": function (d) {
                d.query = $('#searchContact').val();
                d.category = $('[name="contactCategory"]').val();
                d.status = $('[name="contactStatus"]').val();
            },
            "dataSrc": function (json) {
                return json.data;
            }
        },
        initComplete: function () {
            var searchInput = $('#tblhotlines_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search contacts...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });
            
            var lengthSelect = $('#tblhotlines_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            {
                "title": "Office", "data": "office", width: '25%',
                "render": function (data, type, row) {
                    if (row.brgy_name) {
                        return row.brgy_name;
                    } else if (row.dept_name) {
                        return row.dept_name;
                    } else if (row.section == 'Others') {
                        return row.content_ref_id;
                    } else {
                        return '-';
                    }
                }
            },
            {
                "title": "PLDT LOCAL ",
                "data": "number",
                "render": function (data) {
                    return data ? formatPhilippineLandline(data) : '-';
                }
            },
            {
                "title": "SMART",
                "data": "smart",
                "render": function (data) {
                    return data ? formatPhilippineMobile(data) : '-';
                }
            },
            {
                "title": "GLOBE",
                "data": "globe",
                "render": function (data) {
                    return data ? formatPhilippineMobile(data) : '-';
                }
            },
            {
                "title": "INTELCO",
                "data": "telco",
                "render": function (data) {
                    return data ? formatPhilippineLandline(data) : '-';
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
                    actionHtml += renderDeleteAction(userLevel, row.ID, 'deleteContact');

                    actionHtml += `</ul></div>`;
                    return actionHtml;
                }
            }
        ]
    });
    var sltdRow = null;

    $('#tblhotlines tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Attach a submit handler to the Contact form
    $('#contactSearchForm').on('submit', function (e) {
        e.preventDefault(); // stop page reload

        // Grab values
        const query = $('#searchContact').val().trim();
        const category = $('[name="contactCategory"]').val();
        const status = $('[name="contactStatus"]').val();

        console.log("Searching for:", query, "Category:", category, "Status:", status);

        // Example: reload your DataTable with filters
        tbl.ajax.reload();
    });

    // Clear Filters button
    $('#contactSearchForm button[type="reset"]').on('click', function () {
        // reset form fields
        $('#contactSearchForm')[0].reset();

        // also clear individual inputs if needed
        $('#searchContact').val('');
        $('[name="contactCategory"]').val('');
        $('[name="contactStatus"]').val('');

        // reload table back to default
        tbl.ajax.reload();
    });

</script>
