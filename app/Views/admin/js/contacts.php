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

    // Initialize selectize for all selects
    $('#txtDept, #editDept, #txtBrgy, #editBrgy').selectize({
        sortField: 'text',
        allowClear: true
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
            populateDepartmentDropdown($('#txtDept'));
        } else if (selectedCategory === 'BRGY') {
            $('#deptGroup').hide();
            $('#brgyGroup').show();
            $('#othersGrp').hide();
            populateBrgyDropdown($('#txtBrgy'));
        } else if (selectedCategory === 'Others') {
            $('#deptGroup').hide();
            $('#brgyGroup').hide();
            $('#othersGrp').show();
        } else {
            $('#deptGroup, #brgyGroup').hide();
        }
    });

    // Same for edit modal
    $('#editcategory').on('change', function () {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'DEPT') {
            $('#editdeptGroup').show();
            $('#editbrgyGroup').hide();
            $('#editothersGrp').hide();
            populateDepartmentDropdown($('#editDept'));
        } else if (selectedCategory === 'BRGY') {
            $('#editdeptGroup').hide();
            $('#editbrgyGroup').show();
            $('#editothersGrp').hide();
            populateBrgyDropdown($('#editBrgy'));
        } else if (selectedCategory === 'Others') {
            $('#editdeptGroup').hide();
            $('#editbrgyGroup').hide();
            $('#editothersGrp').show();
        }
        else {
            $('#editdeptGroup, #editbrgyGroup').hide();
        }
    });

    // Populate departments dropdown
    $('#addModal').on('show.bs.modal', function (e) {
        // Reset the category selection
        $('#category').val('').trigger('change');
    });

    $('#editModal').on('show.bs.modal', function (e) {
        $('#category').val('').trigger('change');
    });

    // Save new hotlines
    $('#btnAdd').on('click', function () {
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('category') || !formData.get('telco') || !formData.get('contact') || !formData.get('smart') || !formData.get('globe')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
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


        // Validate contact fields (xxx-xxxx for landline, xxxx-xxx-xxxx for mobile, or single hyphen)
        const landlinePattern = /^(\d{3}-\d{4}|-)$/;
        const mobilePattern = /^(\d{4}-\d{3}-\d{4}|-)$/;

        // Check PLDT and Intelco (landline format)
        if (!landlinePattern.test(formData.get('telco')) || !landlinePattern.test(formData.get('contact'))) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'PLDT and Intelco numbers must be in the format xxx-xxxx or a single hyphen.'
            });
            return;
        }

        // Check Smart and Globe (mobile format)
        if (!mobilePattern.test(formData.get('smart')) || !mobilePattern.test(formData.get('globe'))) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Smart and Globe numbers must be in the format xxxx-xxx-xxxx or a single hyphen.'
            });
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
            url: '<?php echo site_url('admin/ajax/create_contact'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    $('#txtDept')[0].selectize.clear();
                    $('#txtBrgy')[0].selectize.clear();
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
            url: '<?php echo site_url('admin/ajax/get_contact'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    let res = response.data; // Directly access the data object
                    $('#editId').val(res.ID);
                    $('#editTelco').val(res.telco);
                    $('#editContact').val(res.number);
                    $('#editSmart').val(res.smart);
                    $('#editGlobe').val(res.globe);

                    if (res.brgy_name) {
                        $('#editcategory').val('BRGY').trigger('change'); // Set the category and trigger change
                        $('#editdeptGroup').hide();
                        $('#editbrgyGroup').show();
                        $('#editothersGrp').hide();
                        populateBrgyDropdown($('#editBrgy'), res.content_ref_id);
                        $('#editDept').val(null); // Set editDept to null
                        $('#editOthers').val(null); // Set editOthers to null
                    } else if (res.dept_name) {
                        $('#editcategory').val('DEPT').trigger('change');
                        $('#editdeptGroup').show();
                        $('#editothersGrp').hide();
                        populateDepartmentDropdown($('#editDept'), res.content_ref_id);
                        $('#editBrgy').val(null); // Set editBrgy to null
                        $('#editOthers').val(null); // Set editOthers to null
                    } else {
                        $('#editcategory').val('Others').trigger('change');
                        $('#editdeptGroup').hide();
                        $('#editbrgyGroup').hide();
                        $('#editothersGrp').show();
                        $('#editOthers').val(res.content_ref_id);
                        $('#editBrgy').val(null); // Set editBrgy to null
                        $('#editDept').val(null); // Set editDept to null
                    }
                    $('#editModal').modal('show');
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


    $('#btnEdit').click(function () {
        let form = $('#editForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('editTelco') || !formData.get('editContact') || !formData.get('editSmart') || !formData.get('editGlobe')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
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

        if (formData.get('category') === 'Others' && !formData.get('editOthers')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill all fields.'
            });
            return;
        }

        // Validate contact fields (xxx-xxxx for landline, xxxx-xxx-xxxx for mobile, or single hyphen)
        const landlinePattern = /^(\d{3}-\d{4}|-)$/;
        const mobilePattern = /^(\d{4}-\d{3}-\d{4}|-)$/;

        // Check PLDT and Intelco (landline format)
        if (!landlinePattern.test(formData.get('editTelco')) || !landlinePattern.test(formData.get('editContact'))) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'PLDT and Intelco numbers must be in the format xxx-xxxx or a single hyphen.'
            });
            return;
        }

        // Check Smart and Globe (mobile format)
        if (!mobilePattern.test(formData.get('editSmart')) || !mobilePattern.test(formData.get('editGlobe'))) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Smart and Globe numbers must be in the format xxxx-xxx-xxxx or a single hyphen.'
            });
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
                    text: 'Unable to update. Please try again later.'
                });
            }
        });
    });

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
            { "title": "PLDT LOCAL ", "data": "number" },
            { "title": "SMART", "data": "smart" },
            { "title": "GLOBE", "data": "globe" },
            { "title": "INTELCO", "data": "telco" },
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