<script src="<?= base_url('assets/admin/js/quill-init.js') ?>"></script>
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

    // Initialize Quill editors for this page
    QuillManager.initPageQuillEditors({
        editors: [
            {
                elementId: 'quillDetails',
                instanceName: 'mapAddDetails',
                modalId: 'addModal'
            },
            {
                elementId: 'editQuillDetails',
                instanceName: 'mapEditDetails',
                modalId: 'editModal'
            }
        ]
    });

    // Setup form submission handlers
    QuillManager.setupQuillFormHandlers({
        formHandlers: [
            {
                buttonId: 'btnAdd',
                instanceName: 'mapAddDetails',
                hiddenInputId: 'details'
            },
            {
                buttonId: 'btnEdit',
                instanceName: 'mapEditDetails',
                hiddenInputId: 'edit_details'
            }
        ]
    });

    // Setup edit content population
    QuillManager.setupQuillEditHandlers({
        editHandlers: [
            {
                modalId: 'editModal',
                instanceName: 'mapEditDetails',
                contentField: 'edit_details'
            }
        ]
    });

    // Add Record
    $('#btnAdd').click(function() {
        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();
        
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Validation: Check required fields
        let brgy_name = formData.get('brgy_name');
        let top_loc = formData.get('top_loc');
        let left_loc = formData.get('left_loc');

        if (!brgy_name || !top_loc || !left_loc) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }

        // Validation: Check percentage format for top_loc and left_loc
        if (!top_loc.includes('%') || !left_loc.includes('%')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Top Location and Left Location must include a percentage ("%").'
            });
            return;
        }

        let topLocNumeric = parseFloat(top_loc.replace('%', ''));
        let leftLocNumeric = parseFloat(left_loc.replace('%', ''));

        if (isNaN(topLocNumeric) || isNaN(leftLocNumeric) || topLocNumeric < 0 || topLocNumeric > 100 || leftLocNumeric < 0 || leftLocNumeric > 100) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Top Location and Left Location must be numeric percentages between 0 and 100.'
            });
            return;
        }

        // Add mode to formData
        formData.append('mode', 'create_map');

        Swal.fire({
            title: 'Please wait...',
            showConfirmButton: false,
            backdrop: true,
            willOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: '<?= base_url('admin/ajax/create_map') ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(result) {
                console.log("Create Map - Server Response:", result);
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data saved!'
                    });
                    tblmap.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Data not created.'
                    });
                    tblmap.ajax.reload(null, false);
                }
            },
            error: function(xhr, status, error) {
                console.error("Create Map - AJAX error:", xhr.responseText, status, error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request. Check the console for details.'
                });
            }
        });
    });



    // Edit Function
    function edit(id) {
        $.post("<?= site_url('admin/ajax/get_map_details') ?>", { id: id }, function(result) {
            console.log("Editing Map Record - Server Response:", result);

            if (result.status == 1) {
                // ✅ Auto-fill modal fields
                $('#editId').val(result.data.ID);
                $('#edit_brgy_name').val(result.data.brgy_name);
                $('#edit_top_loc').val(result.data.top_loc);
                $('#edit_left_loc').val(result.data.left_loc);
                $('#edit_details').val(result.data.details);
                $('#edit_created_date').val(result.data.created_date); // ✅ Read-only timestamp
                $('#edit_updated_date').val(result.data.updated_date); // ✅ Read-only timestamp

                            // ✅ Open modal
            $('#editModal').modal('show');
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: result.msg });
            }
        }, "json").fail(function(xhr) {
            console.error("Edit Map Record - AJAX error:", xhr.responseText);
            Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Try again.' });
        });
    }

    // Update Record
    $('#btnEdit').click(function () {
        console.log("Update button clicked!"); // ✅ Debugging log

        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();

        let topLocValue = $('#edit_top_loc').val();
        let leftLocValue = $('#edit_left_loc').val();

        // ✅ Check if values contain a percentage
        if (!topLocValue.includes('%') || !leftLocValue.includes('%')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Top Location and Left Location must include a percentage ("%").'
            });
            return; // 🚫 Stop submission
        }

            let formData = {
            id: $('#editId').val(),
            brgy_name: $('#edit_brgy_name').val(),
            top_loc: topLocValue,
            left_loc: leftLocValue,
            details: $('#edit_details').val()
        };

        console.log("Submitting update:", formData); // ✅ Debugging log

        $.post("<?= site_url('admin/ajax/update_map_record') ?>", formData, function(response) {
            console.log("Save Edit - Server Response:", response); // ✅ Debugging log

            if (response.status === 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.msg
                }).then(() => {
                    $('#editModal').modal('hide'); // ✅ Close modal
                    tblmap.ajax.reload(null, false); // ✅ Refresh table dynamically
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.msg
                });
            }
        }, "json").fail(function(xhr) {
            console.error("AJAX error:", xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to update. Please try again later.'
            });
        });
    });


    //Activate Function
    function activate(id) {
        Swal.fire({
            title: 'Activate Content',
            text: "Are you sure you want to activate this content?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#c0392b',
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    showConfirmButton: false,
                    backdrop: true,
                    scrollbarPadding: false, // ✅ Matches Invest
                    allowEscapeKey: () => !Swal.isLoading(), // ✅ Matches Invest
                    allowOutsideClick: () => !Swal.isLoading(), // ✅ Matches Invest
                    willOpen: () => Swal.showLoading()
                });

                $.post("<?= site_url('admin/ajax/set_status_map') ?>", 
                    { id: id, status: 'ACTIVE' },
                    function(result) {
                        console.log("Map Activation - Server response:", result);

                        if (result.status == 1) {
                            $('.modal').modal('hide');

                            console.log("Map Table Reload Triggered");
                            tblmap.ajax.reload(null, false); // ✅ Force refresh

                            Swal.fire({ icon: 'success', title: 'Success', text: 'Content activated successfully!' });
                        } else {
                            console.error("Activation failed:", result.msg);
                            Swal.fire({ icon: 'error', title: 'Error', text: result.msg });
                        }
                    },
                    "json"
                ).fail(function(xhr) {
                    console.error("Map Activation - AJAX error:", xhr.responseText);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Try again.' });
                });
            }
        });
    }


    //Deactivate Function
    function deactivate(id) {
        Swal.fire({
            title: 'Deactivate Content',
            text: "Are you sure you want to deactivate this content?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#27ae60',
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    showConfirmButton: false,
                    backdrop: true,
                    scrollbarPadding: false, // ✅ Matches Invest
                    allowEscapeKey: () => !Swal.isLoading(), // ✅ Matches Invest
                    allowOutsideClick: () => !Swal.isLoading(), // ✅ Matches Invest
                    willOpen: () => Swal.showLoading()
                });

                $.post("<?= site_url('admin/ajax/set_status_map') ?>", 
                    { id: id, status: 'INACTIVE' },
                    function(result) {
                        console.log("Map Deactivation - Server response:", result);

                        if (result.status == 1) {
                            $('.modal').modal('hide');

                            console.log("Map Table Reload Triggered");
                            tblmap.ajax.reload(null, false); // ✅ Force refresh

                            Swal.fire({ icon: 'success', title: 'Success', text: 'Content deactivated successfully!' });
                        } else {
                            console.error("Deactivation failed:", result.msg);
                            Swal.fire({ icon: 'error', title: 'Error', text: result.msg });
                        }
                    },
                    "json"
                ).fail(function(xhr) {
                    console.error("Map Deactivation - AJAX error:", xhr.responseText);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Try again.' });
                });
            }
        });
    }

    function setMapStatus(id, status) {
        const actionText = statusActionText(status);
        Swal.fire({
            title: statusActionTitle(status, 'Content'),
            text: "Are you sure you want to " + actionText + " this content?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#c0392b',
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= site_url('admin/ajax/set_status_map') ?>",
                    { id: id, status: status },
                    function(result) {
                        if (result.status == 1) {
                            tblmap.ajax.reload(null, false);
                            Swal.fire({ icon: 'success', title: 'Success', text: statusSuccessText('Content', actionText) });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: result.msg || result.message });
                        }
                    },
                    "json"
                );
            }
        });
    }

    function deleteMap(id) {
        Swal.fire({
            title: 'Delete Map Record',
            text: "Are you sure you want to delete this map record? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#7f8c8d',
            confirmButtonText: 'Yes, Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= site_url('admin/ajax/delete_map') ?>",
                    { id: id },
                    function(result) {
                        if (result.status == 1) {
                            tblmap.ajax.reload(null, false);
                            Swal.fire({ icon: 'success', title: 'Deleted', text: 'Map record deleted successfully' });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: result.message || result.msg });
                        }
                    },
                    "json"
                );
            }
        });
    }
        var tblmap = $('#tblmap').DataTable({
            select: false,
            searching: true,
            ordering: true,
            "order": [], // Removes default sorting
            pageLength: 10,
            processing: true,
            ajax: {
                "url": "<?= base_url('admin/ajax/get_map') ?>",
                "type": "POST"
            },
            columns: [
                { "title": "ID", "data": "ID", "visible": false },
                { "title": "Barangay Name", "data": "brgy_name", width: '30%' },
                { "title": "Top Location", "data": "top_loc", width: '15%' },
                { "title": "Left Location", "data": "left_loc", width: '15%' },
                { "title": "Details", "data": "details", width: '20%' },
                {
                    "title": "Status",
                    "data": "status",
                    "className": "dt-center",
                    width: '10%',
                    "render": function (data) {
                        if (data === 'ACTIVE') {
                            return '<span class="badge bg-success">Active</span>';
                        } else if (data === 'INACTIVE') {
                            return '<span class="badge bg-danger">Inactive</span>';
                        } else {
                            return '<span class="badge bg-secondary">Archived</span>';
                        }
                    }
                },
                {
                    "title": "Actions",
                    "data": "ID",
                    "className": "dt-center",
                    "render": function (data, type, row) {
                        if (userLevel !== 'VIEWER') {
                            var actions = '<div class="btn-group">' +
                                '<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown">' +
                                'Actions' +
                                '</button>' +
                                '<ul class="dropdown-menu">' +
                                '<li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal" onclick="edit(' + row.ID + ')"><i class="fa-solid fa-pen-to-square"></i> Manage</button></li>';

                            if ((userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') && row.status !== 'ARCHIVED') {
                                actions += '<li><button type="button" class="dropdown-item" onclick="activate(' + row.ID + ')"><i class="fa-solid fa-check"></i> Activate</button></li>' +
                                           '<li><button type="button" class="dropdown-item" onclick="deactivate(' + row.ID + ')"><i class="fa-solid fa-xmark"></i> Deactivate</button></li>';
                            }
                            if (row.status === 'ARCHIVED' && adminCanRestore(userLevel)) {
                                actions += '<li><button type="button" class="dropdown-item" onclick="setMapStatus(' + row.ID + ', &quot;ACTIVE&quot;)"><i class="bi bi-arrow-counterclockwise me-1"></i> Restore</button></li>';
                            } else if (row.status !== 'ARCHIVED' && adminCanArchive(userLevel)) {
                                actions += '<li><button type="button" class="dropdown-item text-warning" onclick="setMapStatus(' + row.ID + ', &quot;ARCHIVED&quot;)"><i class="bi bi-archive me-1"></i> Archive</button></li>';
                            }
                            if (adminCanDelete(userLevel)) {
                                actions += '<li><hr class="dropdown-divider"></li><li><button type="button" class="dropdown-item text-danger" onclick="deleteMap(' + row.ID + ')"><i class="bi bi-trash me-1"></i> Delete</button></li>';
                            }

                            actions += '</ul></div>';
                            return actions;
                        } else {
                            return '-';
                        }
                    }
                }
            ]
        });

        var sltdRow = null;

        $('#tblmap tbody').on('mouseover', 'tr', function () {
            sltdRow = tblmap.row(this).data();
        });

            console.log('DataTable for #tblmap initialized successfully');
</script>
