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

    // Initialize Quill editors for Add form
    var quillPersonalData = new Quill('#personal_data', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Add this for Years of Service
    var quillYearsOfService = new Quill('#years_of_service', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        }
    });

    var quillAwards = new Quill('#awards', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Initialize Quill editors for Edit form
    var quillEditPersonalData = new Quill('#edit_personal_data', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        }
    });

    var quillEditYearsOfServiceData = new Quill('#edit_years_of_service', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        }
    });


    var quillEditAwards = new Quill('#edit_awards', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'header': 1 }, { 'header': 2 }, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Function to handle position selection change
    $('#offpos').change(function () {
        var selectedPosition = $(this).val();
        var rankField = $('#rankField');

        if (selectedPosition === 'CITY COUNCILOR') {
            rankField.show();
            $('#offrank').prop('required', true);
            $('#offrank').attr('max', 12); // Set max attribute to 12 for City Councilor
        } else {
            rankField.hide();
            $('#offrank').prop('required', false);
        }
    });

    $('#editoffpos').change(function () {
        var selectedPosition = $(this).val();
        var rankField = $('#editRankField');

        if (selectedPosition === 'CITY COUNCILOR') {
            rankField.show();
            $('#editoffrank').prop('required', true);
            $('#editoffrank').attr('max', 12); // Set max attribute to 12 for City Councilor
        } else {
            rankField.hide();
            $('#editoffrank').prop('required', false);
        }
    });

    // Add Carousel Images Preview and Validation for Add Form
    $('#offcaroimg').on('change', function() {
        const files = this.files;
        const preview = $('#addCarouselPreview');
        preview.empty();
        if (files.length > 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Image Limit Exceeded',
                text: 'You can only upload up to 3 images.',
            });
            this.value = '';
            return;
        }
        Array.from(files).forEach(file => {
            if (file && file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.append('<img src="' + e.target.result + '" style="max-width: 100px; margin: 5px;">');
                };
                reader.readAsDataURL(file);
            }
        });
    });

    $('#btnAdd').off('click').on('click', function() {
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Extract Quill content
        let quillContentPersonalData = quillPersonalData.root.innerHTML;
        let quillContentYearsOfService = quillYearsOfService.root.innerHTML;
        let quillContentAwards = quillAwards.root.innerHTML;
        formData.set('personal_data', quillContentPersonalData);
        formData.set('years_of_service', quillContentYearsOfService);
        formData.set('awards', quillContentAwards);

        // Field validation
        if (!formData.get('offname') || !formData.get('offpos') ||
            quillContentPersonalData === '<p><br></p>' ||
            quillContentYearsOfService === '<p><br></p>' ||
            quillContentAwards === '<p><br></p>') {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }
        // Image validation
        let imageFile = formData.get('offimg');
        if (!imageFile || imageFile.size === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please upload an image.'
            });
            return;
        }
        const maxImageSizeMB = 4;
        if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: `Image size should not exceed ${maxImageSizeMB} MB.`
            });
            return;
        }
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validImageTypes.includes(imageFile.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please upload a valid image file (jpg, png, gif).'
            });
            return;
        }
        // Carousel images validation
        let caroInput = document.getElementById('offcaroimg');
        let caroFiles = caroInput.files;
        if (caroFiles.length > 3) {
            Swal.fire({
                icon: 'warning',
                title: 'Image Limit Exceeded',
                text: 'You can only upload up to 3 carousel images.'
            });
            return;
        }
        for (let i = 0; i < caroFiles.length; i++) {
            if (!validImageTypes.includes(caroFiles[i].type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Carousel images must be jpg, png, or gif.'
                });
                return;
            }
            if (caroFiles[i].size > maxImageSizeMB * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: `Each carousel image must not exceed ${maxImageSizeMB} MB.`
                });
                return;
            }
            formData.append('carouselimages[]', caroFiles[i]);
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
            url: '<?php echo site_url('admin/ajax/create_cityoff'); ?>',
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
                        text: 'City Official data saved!'
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

  // Edit function
function edit(coId) {
    $.ajax({
        url: '<?php echo site_url('admin/ajax/get_cityoff'); ?>',
        method: 'POST',
        data: { id: coId },
        success: function (response) {
            if (response.status === 1) {
                let official = response.data;
                $('#editCOId').val(official.ID);
                $('#editoffname').val(official.off_name);
                $('#editoffpos').val(official.off_position).change();
                
                if (official.ranking) {
                    $('#editRankField').show();
                    $('#editoffrank').val(official.ranking);
                } else {
                    $('#editRankField').hide();
                }
                $('#edit_years_of_service').val(official.years_of_service);
                $('#edit_personal_data').val(official.personal_data);
                $('#edit_awards').val(official.awards);

                let carouselPreview = $('#carouselPreview');
                carouselPreview.empty();
                
                // Initialize current images count and store existing images
                window.currentCarouselImages = official.carouselimages ? official.carouselimages.split(',').filter(image => image.trim() !== '') : [];
                window.currentCarouselImagesCount = window.currentCarouselImages.length;
                
                if (window.currentCarouselImages.length > 0) {
                    window.currentCarouselImages.forEach((image, index) => {
                        if (image) {
                            let imgElement = `
                                <div style="display: inline-block; margin: 5px;" class="image-container">
                                    <img src="<?php echo base_url('admin/image/CITYOFFICIAL/') ?>${image}" alt="Carousel Image" style="max-width: 100px; margin: 5px;">
                                    <br><small>${image}</small>
                                    <button class="btn btn-danger btn-sm remove-image" data-image="${image}" style="margin-top: 5px;">Remove</button>
                                </div>
                            `;
                            carouselPreview.append(imgElement);
                        }
                    });
                } else {
                    carouselPreview.html('<small>No carousel images available.</small>');
                }

                // Use event delegation to handle remove button clicks with confirmation
                carouselPreview.off('click', '.remove-image').on('click', '.remove-image', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let imageName = $(this).data('image');
                    console.log('Remove button clicked for image:', imageName);

                    // Show confirmation prompt
                    Swal.fire({
                        icon: 'warning',
                        title: 'Are you sure?',
                        text: `Do you really want to remove the image "${imageName}"?`,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, remove it!',
                        cancelButtonText: 'No, keep it',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            removeImage(coId, imageName);
                        }
                    });
                });

                $('#editModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Official not found.'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to fetch official details. Please try again later.'
            });
        }
    });
}

// Example removeImage function (adjust based on your actual implementation)
function removeImage(coId, imageName) {
    $.ajax({
        url: '<?php echo site_url('admin/ajax/remove_carousel_image'); ?>',
        method: 'POST',
        data: { id: coId, image: imageName },
        dataType: 'json',
        success: function (response) {
            console.log('Remove image response:', response);
            if (response.status === 1) {
                // Remove the image element from the preview
                $(`.image-container:contains(${imageName})`).remove();
                // Update the current images array and count
                window.currentCarouselImages = window.currentCarouselImages.filter(img => img !== imageName);
                window.currentCarouselImagesCount = window.currentCarouselImages.length;
                console.log('Updated current images:', window.currentCarouselImages);
                console.log('Updated count:', window.currentCarouselImagesCount);
                // If no images remain, show the placeholder
                if (window.currentCarouselImagesCount === 0) {
                    $('#carouselPreview').html('<small>No carousel images available.</small>');
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Image removed successfully.'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to remove image.'
                });
            }
        },
        error: function (xhr, status, error) {
            console.log('AJAX Error:', status, error, xhr.responseText);
            // Attempt to parse the response manually
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.status === 1) {
                    // Handle success even if jQuery failed to parse
                    $(`.image-container:contains(${imageName})`).remove();
                    window.currentCarouselImages = window.currentCarouselImages.filter(img => img !== imageName);
                    window.currentCarouselImagesCount = window.currentCarouselImages.length;
                    console.log('Updated current images:', window.currentCarouselImages);
                    console.log('Updated count:', window.currentCarouselImagesCount);
                    if (window.currentCarouselImagesCount === 0) {
                        $('#carouselPreview').html('<small>No carousel images available.</small>');
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Image removed successfully (manual parse).'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to remove image.'
                    });
                }
            } catch (e) {
                console.log('Manual parse failed:', e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to remove image. Check console for details.'
                });
            }
        }
    });
}

$('#btnEdit').click(function() {
    let form = $('#editForm')[0];
    let formData = new FormData(form);

    // Extract Quill content
    let quillContentPersonalData = quillEditPersonalData.root.innerHTML;
    let quillContentYearsOfService = quillEditYearsOfServiceData.root.innerHTML;
    let quillContentEditAwards = quillEditAwards.root.innerHTML;
    formData.append('edit_personal_data', quillContentPersonalData);
    formData.append('edit_years_of_service', quillContentYearsOfService);
    formData.append('edit_awards', quillContentEditAwards);

    // Field validation
    if (!formData.get('editoffname') || !formData.get('editoffpos') || 
        quillContentPersonalData === '<p><br></p>' || 
        quillContentYearsOfService === '<p><br></p>' || 
        quillContentEditAwards === '<p><br></p>') {
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Please fill in all required fields.'
        });
        return;
    }

    // Carousel image validation
    const newCarouselImages = formData.getAll('carousel_images[]');
    const validNewImages = newCarouselImages.filter(file => file.size > 0);
    const currentImagesCount = window.currentCarouselImagesCount || 0;
    const totalImagesAfterUpload = currentImagesCount + validNewImages.length;

    if (totalImagesAfterUpload > 3) {
        const allowedNewImages = 3 - currentImagesCount;
        Swal.fire({
            icon: 'warning',
            title: 'Image Limit Exceeded',
            text: `You can only have a maximum of 3 carousel images. You currently have ${currentImagesCount} image(s) and can only add ${allowedNewImages} more.`,
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Please wait...',
        showConfirmButton: false,
        backdrop: true,
        allowEscapeKey: () => !Swal.isLoading(),
        allowOutsideClick: () => !Swal.isLoading(),
        willOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '<?php echo site_url('admin/ajax/update_cityoff'); ?>',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message || 'City Official updated successfully.'
                }).then(() => {
                    $('#editModal').modal('hide');
                    tbl.ajax.reload(null, false);
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to update city official.'
                });
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', xhr.responseText, status, error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to update city official. Please try again.'
            });
        }
    });
});

function edit(coId) {
    // Clear edit form Quill editors to prevent stale data
    quillEditPersonalData.setText('');
    quillEditYearsOfServiceData.setText('');
    quillEditAwards.setText('');

    $.ajax({
        url: '<?php echo site_url('admin/ajax/get_cityoff'); ?>',
        method: 'POST',
        data: { id: coId },
        success: function (response) {
            if (response.status === 1) {
                let official = response.data;
                $('#editCOId').val(official.ID);
                $('#editoffname').val(official.off_name);
                $('#editoffpos').val(official.off_position).trigger('change');
                
                if (official.ranking) {
                    $('#editRankField').show();
                    $('#editoffrank').val(official.ranking);
                } else {
                    $('#editRankField').hide();
                    $('#editoffrank').val('');
                }

                // Populate Quill editors for Edit form
                quillEditPersonalData.root.innerHTML = official.personal_data || '';
                quillEditYearsOfServiceData.root.innerHTML = official.years_of_service || '';
                quillEditAwards.root.innerHTML = official.awards || '';

                let carouselPreview = $('#carouselPreview');
                carouselPreview.empty();
                
                window.currentCarouselImages = official.carouselimages ? official.carouselimages.split(',').filter(image => image.trim() !== '') : [];
                window.currentCarouselImagesCount = window.currentCarouselImages.length;
                
                if (window.currentCarouselImages.length > 0) {
                    window.currentCarouselImages.forEach((image) => {
                        if (image) {
                            let imgElement = `
                                <div style="display: inline-block; margin: 5px;" class="image-container">
                                    <img src="<?php echo base_url('admin/image/CITYOFFICIAL/') ?>${image}" alt="Carousel Image" style="max-width: 100px; margin: 5px;">
                                    <br><small>${image}</small>
                                    <button class="btn btn-danger btn-sm remove-image" data-image="${image}" style="margin-top: 5px;">Remove</button>
                                </div>
                            `;
                            carouselPreview.append(imgElement);
                        }
                    });
                } else {
                    carouselPreview.html('<small>No carousel images available.</small>');
                }

                carouselPreview.off('click', '.remove-image').on('click', '.remove-image', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let imageName = $(this).data('image');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Are you sure?',
                        text: `Do you really want to remove the image "${imageName}"?`,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, remove it!',
                        cancelButtonText: 'No, keep it',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            removeImage(coId, imageName);
                        }
                    });
                });

                // Show current image in preview
                $('#editImagePreview').html(
                    official.img_loc
                        ? `<img src="<?php echo base_url('admin/image/CITYOFFICIAL/') ?>${official.img_loc}" alt="Current Image" style="max-width: 120px; margin-top: 5px;">`
                        : '<small>No image available.</small>'
                );
                // Reset file input
                $('#editoffimg').val('');
                // Add image preview on file input change
                $('#editoffimg').off('change').on('change', function() {
                    const file = this.files[0];
                    const preview = $('#editImagePreview');
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // If no file, show the original image again (if any)
                        if (official.img_loc) {
                            preview.html(`<img src="<?php echo base_url('admin/image/CITYOFFICIAL/') ?>${official.img_loc}" alt="Current Image" style="max-width: 120px; margin-top: 5px;">`);
                        } else {
                            preview.html('<small>No image available.</small>');
                        }
                    }
                });

                $('#editModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Official not found.'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to fetch official details. Please try again later.'
            });
        }
    });
}
    // Deactivate function
    function deactivate(coId) {
        Swal.fire({
            heightAuto: false,
            title: 'Deactivate City Official Content',
            text: "Are you sure you want to deactivate this content? This will not be displayed in the city official section anymore.",
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
                $.post("<?php echo site_url('admin/ajax/set_status_cityoff') ?>",
                    {id: coId, 'status': 'INACTIVE'},
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
    function activate(coId) {
        Swal.fire({
            heightAuto: false,
            title: 'Activate City Official Content',
            text: "Are you sure you want to activate this content? This will be displayed in the city official section.",
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
                $.post("<?php echo site_url('admin/ajax/set_status_cityoff') ?>",
                    {id: coId, 'status': 'ACTIVE'},
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

    function deleteCityOfficial(coId) {
    Swal.fire({
        heightAuto: false,
        title: 'Delete City Official',
        text: "Are you sure you want to delete this city official? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
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

            $.post("<?php echo site_url('admin/ajax/delete_cityoff'); ?>", 
                { id: coId },
                function (result) {
                    if (result.status == 1) {
                        $('.modal').modal('hide');
                        tbl.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'City Official deleted successfully'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message || 'Failed to delete city official.'
                        });
                    }
                }
            ).fail(function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while deleting the city official. Please try again.'
                });
            });
        }
    });
}

    // Datatable
    var tbl = $('#tbloff').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [[1, 'asc']],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_cityoff'); ?>",
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
            { "title": "City Official ID", "data": "ID", "visible": false },
            { "title": "Rank", "data": "ranking", "className": "dt-center", width: '5%',
                "render": function (data, type, row) {
                    return data ? data : '-'; // Display "-" if data is null
                }
            },
            { "title": "Official Name", "data": "off_name" },
            { "title": "Position",  "data": "off_position", width: '15%' },
            { "title": "Years of Service", "data": "years_of_service", width: '10%',
                "render": function(data, type, row) {
                    if (!data) return '-';
                    let text = data.replace(/<[^>]+>/g, ''); // Remove HTML tags
                    return text.length > 80 ? text.substring(0, 80) + '...' : text;
                }
            },
            { "title": "Personal Data", "data": "personal_data", width: '20%',
                "render": function(data, type, row) {
                    if (!data) return '-';
                    let text = data.replace(/<[^>]+>/g, ''); // Remove HTML tags
                    return text.length > 80 ? text.substring(0, 80) + '...' : text;
                }
            },
            { "title": "Awards", "data": "awards", width: '20%',
                "render": function(data, type, row) {
                    if (!data) return '-';
                    let text = data.replace(/<[^>]+>/g, ''); // Remove HTML tags
                    return text.length > 80 ? text.substring(0, 80) + '...' : text;
                }
            },
            {
                "title": "Picture", "data": "img_loc", "className": "dt-center", width: '10%',
                "render": function (data, type, row) {
                    return '<img id="img_loc" class="img-fluid mt-3" src="<?php echo base_url('admin/image/CITYOFFICIAL/') ?>' + data + '">';
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
                                '<li><button type="button" class="dropdown-item" onclick="deactivate(' + row.ID + ')"><i class="fa-solid fa-xmark"></i> Deactivate</button></li>'+
                                '<li><button type="button" class="dropdown-item" onclick="deleteCityOfficial(' + row.ID + ')"><i class="fa-solid fa-trash"></i> Delete</button></li>';
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

    $('#tbloff tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });
</script>