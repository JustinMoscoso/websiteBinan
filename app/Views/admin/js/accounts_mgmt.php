<!-- js/accounts_mgmt.php -->
<script>
    const userLevel = '<?= $user->user_lvl ?>'; // Get user level from backend

    if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN') {
        // Developer and Super Admin can see the add user button
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
    $('#txtDept, #editDept').selectize({
        sortField: 'text',
        placeholder: 'Choose a department',
        allowClear: true
    });

    // Function to populate departments dropdown
    function populateDepartmentDropdown(selectElement) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_dept'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 1 && Array.isArray(response.data)) {
                    let selectizeControl = selectElement[0].selectize;
                    selectizeControl.clearOptions();
                    response.data.forEach(function (department) {
                        selectizeControl.addOption({ value: department.dept_name, text: department.dept_name });
                    });
                    selectizeControl.refreshOptions(false); // Refresh the options in the selectize control
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

    // Populate departments dropdown
    $('#addModal').on('show.bs.modal', function (e) {
        populateDepartmentDropdown($('#txtDept'));
    });

    $('#editModal').on('show.bs.modal', function (e) {
        populateDepartmentDropdown($('#editDept'));
    });

    // Handle form submission for adding a user
    $('#btnAdd').on('click', function () {
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Form validation
        const fields = [
            { name: 'txtFirstName', label: 'First Name' },
            { name: 'txtLastName', label: 'Last Name' },
            { name: 'txtUsername', label: 'Username' },
            { name: 'txtEmail', label: 'Email' },
            { name: 'txtPassword', label: 'Password' },
            { name: 'txtAccLevel', label: 'Account level' },
            { name: 'txtDept', label: 'Department' }
        ];

        for (let field of fields) {
            if (!formData.get(field.name)) {
                Swal.fire('Validation Error', `${field.label} is required`, 'warning');
                return;
            }
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
            url: '<?php echo site_url('admin/ajax/create_user'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    $('#txtDept')[0].selectize.clear(); // Clear the selectize control
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Department data saved!'
                    });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'warning' || 'error',
                        title: 'Error',
                        text: result.message || 'Data not created. Refresh the page or try logging in again.',
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

    // Function to open the edit user modal and populate data
    function edit(userId) {

        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_users'); ?>',
            method: 'POST',
            data: { id: userId },
            success: function (response) {
                if (response.data) {
                    let res = response.data;
                    $('#editUserId').val(res.ID);
                    $('#editFirstName').val(res.fname);
                    $('#editLastName').val(res.lname);
                    $('#editUsername').val(res.username);
                    $('#editEmail').val(res.email);
                    $('#editAccLevel').val(res.user_lvl);
                    $('#editDept')[0].selectize.setValue(res.dept); // Set the value for selectize
                    $('#editPassword').val('');
                    $('#editUserModal').modal('show');

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'User not found.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch user details. Please try again later.'
                });
            }
        });
    }


    // Function to submit the edit user form
    $('#btnEdit').click(function () {
        let form = $('#editForm')[0];
        let formData = new FormData(form);

        // Form validation
        const fields = [
            { name: 'editFirstName', Label: 'First Name' },
            { name: 'editLastName', Label: 'Last Name' },
            { name: 'editUsername', Label: 'Username' },
            { name: 'editEmail', Label: 'Email' },
            { name: 'editAccLevel', Label: 'Account Level' },
            { name: 'editDept', Label: 'Department' }
        ];

        for (let field of fields) {
            if (!formData.get(field.name)) {
                Swal.fire('Validation Error', `${field.Label} is required`, 'warning');
                return;
            }
        }



        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_user'); ?>',
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
                    text: 'Unable to update user. Please try again later.'
                });
            }
        });
    });

    function deactivate(userId) {
        Swal.fire({
            heightAuto: false,
            title: 'Deactivate User',
            text: "Are you sure you want to deactivate this user? This user will fail at log in.",
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
                $.post("<?php echo site_url('admin/ajax/set_status_user') ?>",
                    { id: userId, 'status': 'INACTIVE' },
                    function (result) {
                        if (result.status == 1) {
                            $('.modal').modal('hide');
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'User deactivated successfully'
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

    function activate(userId) {
        Swal.fire({
            heightAuto: false,
            title: 'Activate User',
            text: "Are you sure you want to activate this user? This user will be able to log in.",
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
                $.post("<?php echo site_url('admin/ajax/set_status_user') ?>",
                    { id: userId, 'status': 'ACTIVE' },
                    function (result) {
                        if (result.status == 1) {
                            $('.modal').modal('hide');
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'User activated successfully'
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

    function reset_password(userId) {
        Swal.fire({
            title: 'Reset Password',
            text: "Are you sure you want to reset the password for this user?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Reset Password',
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#c0392b'
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
                $.post("<?php echo site_url('admin/ajax/reset_password') ?>",
                    { id: userId },
                    function (result) {
                        if (result.status === 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: `Password reset successfully. Temporary password: ${result.message}`
                            }).then(() => {
                                tbl.ajax.reload(null, false);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'An error occurred. Please try again.'
                            });
                        }
                    }
                ).fail(function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to process your request. Please try again later.'
                    });
                });
            }
        });
    }


    function del(userId) {
        Swal.fire({
            heightAuto: false,
            title: 'Delete User',
            text: "Are you sure you want to delete this user?",
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
                $.post("<?php echo site_url('admin/ajax/set_status_user') ?>",
                    { id: userId, 'status': 'ARCHIVED' },
                    function (result) {
                        if (result.status == 1) {
                            $('.modal').modal('hide');
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'User deleted successfully'
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

    // Datatable
    var tbl = $('#tbluser').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_users'); ?>",
            "type": "POST",
            "data": function (d) {
                return {
                    searchUser: $('#searchUser').val(),
                    searchStatus: $('#searchStatus').val(),
                    searchUserLevel: $('#searchUserLevel').val()
                };
            },
            "dataSrc": function (json) {
                return json.data;
            }
        },
        columns: [
            { "title": "User ID", "data": "ID", "visible": false },
            { "title": "username", "data": "username" },
            {
                "title": "Name",
                "data": "fname",
                "className": "dt-head-center dt-body-justify",
                width: '15%',
                "render": function (data, type, row) {
                    return row.fname + " " + row.lname;
                }
                
            },
            initComplete: function() {
            var searchInput = $('#tblfdp_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search Category...');
            searchInput.removeClass('form-control-sm'); // Standard size is more visible than small
            searchInput.css({
                'width': '350px',           // Make it wider
                'border': '2px solid #388e3c', // Distinct brand-green border
                'margin-left': '10px'       // Add space from the "Search:" label
            });
        },
            columns: [
                { "title": "User ID", "data": "ID", "visible": false },
                { "title": "username", "data": "username"},
                { 
                    "title": "Name", 
                    "data": "fname",
                    "className": "dt-head-center dt-body-justify",
                    width: '15%',
                    "render": function (data, type, row) {
                        return row.fname + " " + row.lname;
                    }
                },
                { 
                    "title": "Department", 
                    "data": "dept",
                    "className": "dt-head-center dt-body-justify",
                    width: '30%',
                },
                { "title": "Email", "data": "email" },
                { "title": "User level", "data": "user_lvl" },
                { 
                    "title": "Status", 
                    "data": "status",
                    "className": "dt-center", 
                    width: '10%',
                    "render": function (data, type, row) {
                        var status = data;
                        if (status == 'ACTIVE') {
                            return '<span class="badge bg-success">Active</span>';
                        } else if (status == 'INACTIVE') {
                            return '<span class="badge bg-danger">Inactive</span>';
                        } else {
                            return '<span class="badge bg-secondary">Archived</span>';
                        }
                    }
                }
            },
            {
                "title": "Actions",
                "data": "ID",
                "className": "dt-center",
                "render": function (data, type, row) {
                    if (userLevel !== 'VIEWER') {
                        var acter = '<div class="btn-group">' +
                            '<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">' +
                            'Actions' +
                            '</button>' +
                            '<ul class="dropdown-menu">' +
                            '<li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal" onclick="edit(' + row.ID + ')"><i class="fa-solid fa-pen-to-square"></i> Manage</button></li>';
                        if (userLevel !== 'ENCODER') {
                            // Add buttons for all levels except ENCODER
                            acter += '<li><button type="button" class="dropdown-item" onclick="activate(' + row.ID + ')"><i class="fa-solid fa-check"></i> Activate</button></li>' +
                                '<li><button type="button" class="dropdown-item" onclick="deactivate(' + row.ID + ')"><i class="fa-solid fa-xmark"></i> Deactivate</button></li>' +
                                '<li><button type="button" class="dropdown-item" onclick="reset_password(' + row.ID + ')"><i class="fa-solid fa-lock"></i> Reset password</button></li>' +
                                '<li><button type="button" class="dropdown-item" onclick="del(' + row.ID + ')"><i class="fa-solid fa-trash"></i> Delete user</button></li>';
                        }

                        acter += '</ul>' +
                            '</div>';
                        return acter;
                    } else {
                        return '-'; // Return blank for VIEWER level users
                    }
                }
            },
        ]
    });
    var sltdRow = null;

    $('#tbluser tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

// Attach a submit handler to the form
$('#userSearchForm').on('submit', function(e) {
    e.preventDefault(); // stop page reload
    tbl.ajax.reload();  // unified reload logic
});

// Clear Filters button
$('#userSearchForm button[type="reset"]').on('click', function() {
    // reset form fields
    $('#userSearchForm')[0].reset();

    // also clear individual inputs if needed
    $('#searchUser').val('');
    $('#searchStatus').val('');
    $('#searchUserLevel').val('');

    // reload table back to default
    tbl.ajax.reload();
});


</script>