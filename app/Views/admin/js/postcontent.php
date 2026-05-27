<script src="<?= base_url('assets/admin/js/quill-init.js') ?>"></script>
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
        // Viewer can only read
        $('input, select, button').prop('disabled', true);
        $('.btn-close').prop('disabled', false); // Allow closing modals
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
                $.post("<?php echo site_url('admin/ajax/set_status_postcontent') ?>",
                    {id: id, 'status': newStatus},
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

    // Delete function
    function deletePostContent(id) {
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
                $.post("<?php echo site_url('admin/ajax/delete_postcontent') ?>",
                    {id: id},
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

    // Initialize Quill editors for this page
    QuillManager.initPageQuillEditors({
        editors: [
            {
                elementId: 'quillDesc',
                instanceName: 'postcontentAddDesc',
                modalId: 'addModal'
            },
            {
                elementId: 'editQuillDesc',
                instanceName: 'postcontentEditDesc',
                modalId: 'editModal'
            }
        ]
    });

    // Setup form submission handlers
    QuillManager.setupQuillFormHandlers({
        formHandlers: [
            {
                buttonId: 'btnAdd',
                instanceName: 'postcontentAddDesc',
                hiddenInputId: 'desc'
            },
            {
                buttonId: 'btnEdit',
                instanceName: 'postcontentEditDesc',
                hiddenInputId: 'editDesc'
            }
        ]
    });

    // Setup edit content population
    QuillManager.setupQuillEditHandlers({
        editHandlers: [
            {
                modalId: 'editModal',
                instanceName: 'postcontentEditDesc',
                contentField: 'editDesc'
            }
        ]
    });

    // Datatable
    var tbl = $('#tblnews').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_postcontent'); ?>",
            "type": "POST",
            "data": function(d) {
                d.search = $('input[name="search"]').val();
                d.category = $('select[name="category"]').val();
                d.status = $('select[name="status"]').val();
            },
            "dataSrc": function (json) {
                if (json.data && Array.isArray(json.data)) {
                    return json.data;
                } else {
                    return [];
                }
            }
        },
        initComplete: function() {
            var searchInput = $('#tblnews_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search Category...');
            searchInput.removeClass('form-control-sm'); // Standard size is more visible than small
            searchInput.css({
                'width': '350px',           // Make it wider
                'border': '2px solid #388e3c', // Distinct brand-green border
                'margin-left': '10px'       // Add space from the "Search:" label
            });
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            {
                "title": "Date Created", "data": "created_date",
                "render": function (data, type, row) {
                    var date = new Date(data);
                    return formatDate(date);
                }
            },
            { "title": "Title", "data": "title"},
            { "title": "Author Name", "data": "author", width: '15%'},
            { "title": "Category", "data": "category", "className": "dt-center" },
            {
                "title": "Image", "data": "file_loc", "className": "dt-center", width: '15%',
                "render": function (data, type, row) {
                    return '<img id="img_loc" class="img-fluid mt-3" src="<?php echo base_url('admin/image/POSTCONTENT/') ?>' + data + '">';
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
                        return '<span class="badge bg-success">Active</span>';
                    } else if (status == 'INACTIVE') {
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
                "visible": userLevel !== 'VIEWER',
                "render": function (data, type, row) {
                    if (userLevel !== 'VIEWER') {
                        let actionHtml = `
                            <div class="dropdown">
                              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
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
                        actionHtml += renderDeleteAction(userLevel, row.ID, 'deletePostContent');
                        
                        actionHtml += `</ul></div>`;
                        return actionHtml;
                    } else {
                        return '-';
                    }
                }
            },
        ]
    });

        var sltdRow = null;

        $('#tblnews tbody').on('mouseover', 'tr', function () {
            sltdRow = tbl.row(this).data();
    });

    // Search form submit: reload table with filters
    $('#docSearchForm').on('submit', function(e) {
        e.preventDefault();
        console.log('Search button clicked, reloading table...');
        tbl.ajax.reload();
    });
    // Clear filters: reload table
    $('#docSearchForm').on('reset', function() {
        console.log('Clear Filters button clicked, reloading table...');
        setTimeout(function() {
            tbl.ajax.reload();
        }, 0);
    });

    // Add new post content
    $('#btnAdd').on('click', function() {
        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();
        
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('title') || !formData.get('desc') || !formData.get('newsImg') || !formData.get('content_category')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
        }
        // Image validation
        let imageFile = formData.get('newsImg');
        if (!imageFile || imageFile.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload an image.'
            });
            return; 
        }
        
        const maxImageSizeMB = 4; 
        if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: `Image size should not exceed ${maxImageSizeMB} MB.`
            });
            return; 
        }

        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validImageTypes.includes(imageFile.type)) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload a valid image file (jpg, png, gif).'
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
            url: '<?php echo site_url('admin/ajax/create_postcontent'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
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
            error: function(xhr, status, error) {
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
            url: '<?php echo site_url('admin/ajax/get_postcontent'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    let res = response.data; // Directly access the data object
                    $('#editNewsId').val(res.ID);
                    $('#edit_content_category').val(res.category);
                    $('#editTitle').val(res.title);
                    $('#editAuthor').val(res.author);
                    $('#editDesc').val(res.description);
                    //$('#').val(res.content_ref_id);

                    $('#editModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Data not found.'
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


    // Function to submit the edit user form
    $('#btnEdit').click(function() {
        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();
        
        let form = $('#editForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('editTitle') || !formData.get('editDesc') || !formData.get('editNewsImg') || !formData.get('edit_content_category')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
        }

        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_postcontent'); ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
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
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to update content. Please try again later.'
                });
            }
        });
    });
</script>
