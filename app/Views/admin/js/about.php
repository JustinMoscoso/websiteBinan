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
                elementId: 'quillDesc',
                instanceName: 'aboutAddDesc',
                modalId: 'addModal',
                shouldInit: function() {
                    return $('#DescGroup').is(':visible');
                }
            },
            {
                elementId: 'editQuillDesc',
                instanceName: 'aboutEditDesc',
                modalId: 'editModal',
                shouldInit: function() {
                    return $('#EditDescGroup').is(':visible');
                }
            }
        ]
    });

    // Setup form submission handlers
    QuillManager.setupQuillFormHandlers({
        formHandlers: [
            {
                buttonId: 'btnAdd',
                instanceName: 'aboutAddDesc',
                hiddenInputId: 'TxtDesc'
            },
            {
                buttonId: 'btnEdit',
                instanceName: 'aboutEditDesc',
                hiddenInputId: 'EditTxtDesc'
            }
        ]
    });

    // Setup edit content population
    QuillManager.setupQuillEditHandlers({
        editHandlers: [
            {
                modalId: 'editModal',
                instanceName: 'aboutEditDesc',
                contentField: 'EditTxtDesc'
            }
        ]
    });

    // Hide show input fields depending on chosen section
    $('#content_category').on('change', function() {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'Content' || selectedCategory === 'Home Page' || selectedCategory === 'Emergency Hotlines') {
            $('#DescGroup').show();
            // Initialize Quill editor if not already initialized
            if (!QuillManager.getQuillInstance('aboutAddDesc')) {
                QuillManager.initQuillEditor('quillDesc', 'aboutAddDesc');
            }
        } else if (selectedCategory === 'History') {
            $('#DescGroup').show();
            $('#AboutImgGrp').show();
            // Initialize Quill editor if not already initialized
            if (!QuillManager.getQuillInstance('aboutAddDesc')) {
                QuillManager.initQuillEditor('quillDesc', 'aboutAddDesc');
            }
        } else {
            $('#DescGroup, #AboutImgGrp').hide();
        }
    });

    $('#edit_content_category').on('change', function() {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'Content' || selectedCategory === 'Home Page' || selectedCategory === 'Emergency Hotlines') {
            $('#EditDescGroup').show();
            // Initialize Quill editor if not already initialized
            if (!QuillManager.getQuillInstance('aboutEditDesc')) {
                QuillManager.initQuillEditor('editQuillDesc', 'aboutEditDesc');
            }
        } else if (selectedCategory === 'History') {
            $('#EditDescGroup').show();
            $('#EditAboutImgGrp').show();
            // Initialize Quill editor if not already initialized
            if (!QuillManager.getQuillInstance('aboutEditDesc')) {
                QuillManager.initQuillEditor('editQuillDesc', 'aboutEditDesc');
            }
        } else {
            $('#EditDescGroup, #EditAboutImgGrp').hide();
        }
    });

    $('#btnAdd').on('click', function() {
        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();
        
        let form = $('#addForm')[0];
        let formData = new FormData(form);
        let selectedCategory = formData.get('content_category');
        let title = formData.get('TxtTitle');
        let description = formData.get('TxtDesc');
        let imageFile = formData.get('AboutImg');

        // Form validation
        if (!selectedCategory || !title) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
        }

        if (selectedCategory === 'History') {
            // Description validation
            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please enter a description.'
                });
                return;
            }

            // Image validation
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
        } else if (selectedCategory === 'Home Page' || selectedCategory === 'Content' || selectedCategory === 'Emergency Hotlines') {
            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please enter a description.'
                });
                return;
            }
            formData.set('AboutImg', '');
        } else {
            formData.set('TxtDesc', '');
            formData.set('AboutImg', '');
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
            url: '<?php echo site_url('admin/ajax/create_about'); ?>',
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
            url: '<?php echo site_url('admin/ajax/get_about'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    let res = response.data; // Directly access the data object
                    $('#editAboutId').val(res.ID);
                    $('#edit_content_category').val(res.section);
                    $('#EditTxtTitle').val(res.title);

                    if(res.section != 'Header') {
                        $('#EditDescGroup').show();
                        if(res.section === 'History') {
                            $('#EditAboutImgGrp').show();
                        } else {
                            $('#EditAboutImgGrp').hide();
                        }
                        $('#EditTxtDesc').val(res.description);
                    } else {
                        $('#EditDescGroup, #EditAboutImgGrp').hide();
                    }
                    $('#editModal').modal('show');
                    
                    // Set Quill editor content after modal is shown
                    $('#editModal').on('shown.bs.modal', function () {
                        if (res.section != 'Header') {
                            // Initialize Quill editor if not already initialized
                            if (!QuillManager.getQuillInstance('aboutEditDesc')) {
                                QuillManager.initQuillEditor('editQuillDesc', 'aboutEditDesc');
                            }
                            // Set content after a short delay to ensure Quill is ready
                            setTimeout(() => {
                                const quill = QuillManager.getQuillInstance('aboutEditDesc');
                                if (quill && res.description) {
                                    quill.root.innerHTML = res.description;
                                }
                            }, 100);
                        }
                    });
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

    $('#btnEdit').click(function() {
        // Update Quill content before form submission
        QuillManager.updateQuillFormContent();
        
        let form = $('#editForm')[0];
        let formData = new FormData(form);
        let selectedCategory = formData.get('edit_content_category');
        let title = formData.get('EditTxtTitle');
        let description = formData.get('EditTxtDesc');
        let imageFile = formData.get('EditAboutImg');

        // Form validation
        if (!selectedCategory || !title) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
        }
        
        if (selectedCategory === 'History') {
            // Description validation
            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please enter a description.'
                });
                return;
            }

            // Image validation
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
        } else if (selectedCategory === 'Home Page' || selectedCategory === 'Content' || selectedCategory === 'Emergency Hotlines') {
            if (!description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please enter a description.'
                });
                return;
            }
            formData.set('EditAboutImg', '');
        } else {
            formData.set('EditDescGroup', '');
            formData.set('EditAboutImg', '');
        }

        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_about'); ?>',
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

    // Deactivate function
    function deactivate(aboutId) {
        Swal.fire({
            heightAuto: false,
            title: 'Deactivate Content',
            text: "Are you sure you want to deactivate this content? This will not be displayed in the About/History section.",
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
                    {id: aboutId, 'status': 'INACTIVE'},
                    function (result) {
                        if (result.status == 1) {
                            $('.modal').modal('hide');
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Content deactivated successfully'
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

    // Activate function
    function activate(aboutId) {
        Swal.fire({
            heightAuto: false,
            title: 'Activate Content',
            text: "Are you sure you want to activate this content? This will be displayed in the About/History section.",
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
                    {id: aboutId, 'status': 'ACTIVE'},
                    function (result) {
                        if (result.status == 1) {
                            $('.modal').modal('hide');
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Content activated successfully'
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
    var tbl = $('#tblabout').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_about'); ?>",
            "type": "POST",
            "dataSrc": function (json) {
                if (json.data && Array.isArray(json.data)) {
                    return json.data;
                } else {
                    return [];
                }
            }
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            { "title": "Section", "data": "section"},
            { "title": "Title", "data": "title", width: '15%'},
            { 
                "title": "Description", 
                "data": "description", 
                "className": "dt-head-center dt-body-justify",  
                width: '30%',
                "render": function (data, type, row) {
                    return data ? data : '-';
                }
            },
            {
                "title": "Image", 
                "data": "about_img", 
                "className": "dt-center", 
                width: '15%',
                "render": function (data, type, row) {
                    return data ? '<img id="img_loc" class="img-fluid mt-3" src="<?php echo base_url('admin/image/ABOUT/') ?>' + data + '">' : '-';
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
            },
        ]
    });

    var sltdRow = null;

    $('#tblabout tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });


</script>