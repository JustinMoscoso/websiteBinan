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

    // Initialize Quill editors
    var quillPerData = new Quill('#perdata', {
        modules: {
            toolbar: [
                // Removed font and size dropdowns
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'], // removed image, video
                ['clean']
            ]
        },
        theme: 'snow'
    });

    var quillEditPerData = new Quill('#editperdata', {
        modules: {
            toolbar: [
                // Removed font and size dropdowns
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'], // removed image, video
                ['clean']
            ]
        },
        theme: 'snow'
    });

    

    // Show/hide the "Name of Mayor" input based on the selected category
    $('#content_category').on('change', function() {
        if ($(this).val() === 'Personal Data') {
            $('#myrname').closest('.form-group').show();
        } else {
            $('#myrname').closest('.form-group').hide();
        }
    }).trigger('change');


    // Add Mayor's Content
    $('#btnAdd').on('click', function() {
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Validate image file
        let mayorImg = $('#mayorimg')[0].files[0];
        if (mayorImg) {
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validImageTypes.includes(mayorImg.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Image Type',
                    text: 'Only JPG, PNG, and GIF files are allowed.'
                });
                return;
            }
            if (mayorImg.size > 2 * 1024 * 1024) { // 2MB
                Swal.fire({
                    icon: 'error',
                    title: 'Image Too Large',
                    text: 'The image size should not exceed 2MB.'
                });
                return;
            }
        }
        // Extract the content of the Quill editors
        let quillContentPerData = quillPerData.root.innerHTML;
        formData.append('perdata', quillContentPerData);

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
            url: '<?php echo site_url('admin/ajax/create_mayor'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Mayor\'s Content data saved!'
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

    // Edit Mayor's Content
    function edit(mayId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_mayor'); ?>',
            method: 'POST',
            data: { id: mayId },
            success: function (response) {
                if (response.status === 1) {
                    let official = response.data;
                    $('#editMayorId').val(official.ID);
                    $('#edit_content_category').val(official.section);

                    // Show or hide the 'Name of Mayor' field based on the selected category
                    if (official.section === 'Personal Data') {
                        $('#editmyrname').closest('.form-group').show();
                        $('#editmyrname').val(official.mayor_name); // Set mayor's name
                    } else {
                        $('#editmyrname').closest('.form-group').hide();
                        $('#editmyrname').val(''); // Ensure mayor's name is cleared for other sections
                    }

                    // Set the content of the Quill editor
                    quillEditPerData.root.innerHTML = official.content;

                    $('#editModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Content not found.'
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

    let formData = new FormData($('#editForm')[0]);

    // Extract the content from the Quill editors
    let quillContentPerData = quillEditPerData.root.innerHTML;
    formData.append('editperdata', quillContentPerData);

    $.ajax({
        url: '<?php echo site_url('admin/ajax/update_mayor'); ?>',
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
                text: 'Unable to update Mayor\'s Content. Please try again later.'
            });
        }
    });
});


    // Deactivate function
    function deactivate(mayId) {
        Swal.fire({
            heightAuto: false,
            title: 'Deactivate Mayor\'s Content',
            text: "Are you sure you want to deactivate this content? This will not be displayed in the department section.",
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
                $.post("<?php echo site_url('admin/ajax/set_status_mayor') ?>",
                    {id: mayId, 'status': 'INACTIVE'},
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
    function activate(mayId) {
        Swal.fire({
            heightAuto: false,
            title: 'Activate Mayor\'s Content',
            text: "Are you sure you want to activate this content? This will be displayed in the department section.",
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
                $.post("<?php echo site_url('admin/ajax/set_status_mayor') ?>",
                    {id: mayId, 'status': 'ACTIVE'},
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

    // Function to preview selected images in edit modal
    function previewImages(input, previewContainer) {
        if (input.files) {
            var files = input.files;
            $(previewContainer).html(''); // Clear previous previews
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(previewContainer).append('<img src="' + e.target.result + '" class="img-thumbnail" style="width: 100px; height: auto; margin: 5px;">');
                }
                reader.readAsDataURL(files[i]);
            }
        }
    }

    // Preview images in edit modal
    $('#editmayorimg').on('change', function () {
        previewImages(this, '#edit_img_preview');
    });


    // DataTable initialization
    var tbl = $('#tblmayor').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_mayor'); ?>",
            "type": "POST",
            "dataSrc": function (json) {
                if (json.data && Array.isArray(json.data)) {
                    return json.data.map(function (item) {
                        // Parse JSON-encoded mayor_img field to array of image names
                        item.mayor_img = item.mayor_img ? JSON.parse(item.mayor_img) : [];
                        return item;
                    });
                } else {
                    return [];
                }
            }
        },
        columns: [
            { "title": "ID", "data": "ID", "className": "dt-center", width: '20%', "visible": false },
            { "title": "Section", "data": "section", "className": "dt-body-justify", width: '10%' },
            { 
                "title": "Content", "data": "content", "className": "dt-body-justify", width: '50%',
                "render": function (data, type, row) {
                    // Strip HTML tags for length check
                    var text = data.replace(/<[^>]*>?/gm, '');
                    if (typeof text === 'string' && text.length > 500) {
                        text = text.substring(0, 500) + '...';
                    }
                    return '<div class="quill-editor-default" style="height: auto;">' + text + '</div>';
                }
            },
            { 
                "title": "Image", "data": "mayor_img", "className": "dt-center", width: '20%',
                "render": function (data, type, row) {
                    var imageHtml = '';
                    if (data && data.length > 0) {
                        data.forEach(function (image) {
                            imageHtml += '<img src="<?php echo base_url('admin/image/MAYOR/') ?>' + image + '" class="img-thumbnail" style="width: 100px; height: auto; margin-right: 5px;">';
                        });
                    } else {
                        imageHtml = '-';
                    }
                    return imageHtml;
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
            }
        ]
    });

    var sltdRow = null;

    $('#tblmayor tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });
</script>
