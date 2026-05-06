<script>
    const userLevel = '<?= $user->user_lvl ?>'; // Get user level from backend

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


    function activate(hot_id) {
        Swal.fire({
            heightAuto: false,
            title: 'Activate Hotline',
            text: "Are you sure you want to activate this hotline?",
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
                    { id: hot_id, 'status': 'ACTIVE' },
                    function (result) {
                        if (result.status == 1) {
                            $('.modal').modal('hide');
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Hotline activated successfully'
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
    function deactivate(hot_id) {
        Swal.fire({
            heightAuto: false,
            title: 'Deactivate Hotline',
            text: "Are you sure you want to deactivate this hotline? ",
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
                    { id: hot_id, 'status': 'INACTIVE' },
                    function (result) {
                        if (result.status == 1) {
                            $('.modal').modal('hide');
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Hotline deactivated successfully'
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

    //datatable
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
            "dataSrc": function (json) {
                return json.data;
            }
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            /*{ 
                "title": "Date Created", "data": "created_date",
                "render": function (data, type, row) {
                    var date = new Date(data);
                    return formatDate(date);
                }
            },*/
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
                "render": function (data, type, row) {
                    var status = data;
                    if (status === 'ACTIVE') {
                        return '<span class="badge bg-success">Active</span>';
                    } else if (status === 'INACTIVE') {
                        return '<span class="badge bg-danger">Inactive</span>';
                    } else {
                        return '<span class="badge bg-secondary">Archived</span>';
                    }
                }
            },
            {
                "title": "Actions",
                "data": "ID",
                "render": function (data, type, row) {
                    if (userLevel !== 'VIEWER') {
                        var acter = '<div class="btn-group">' +
                            '<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">' +
                            'Actions' +
                            '</button>' +
                            '<ul class="dropdown-menu">' +
                            '<li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal" onclick="edit(' + row.ID + ')"><i class="fa-solid fa-pen-to-square"></i> Manage</button></li>';
                        if (userLevel !== 'ENCODER') {
                            // Add Activate and Deactivate buttons for all levels except ENCODER
                            acter += '<li><button type="button" class="dropdown-item" onclick="activate(' + row.ID + ')"><i class="fa-solid fa-check"></i> Activate</button></li>' +
                                '<li><button type="button" class="dropdown-item" onclick="deactivate(' + row.ID + ')"><i class="fa-solid fa-xmark"></i> Deactivate</button></li>';
                        }
                        acter += '</ul>' +
                            '</div>';
                        return acter;
                    } else {
                        return '-'; // Return blank for VIEWER level users
                    }
                }
            }
        ]
    });
    var sltdRow = null;

    $('#tblhotlines tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });
</script>