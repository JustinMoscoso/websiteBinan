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

    // Toggle Status function
    function toggleStatus(id, currentStatus) {
        var newStatus = currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
        var actionText = newStatus === 'ACTIVE' ? 'activate' : 'deactivate';

        Swal.fire({
            heightAuto: false,
            title: (newStatus === 'ACTIVE' ? 'Activate' : 'Deactivate') + ' Department Content',
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
                $.post("<?php echo site_url('admin/ajax/set_status_dept') ?>",
                    {id: id, 'status': newStatus},
                    function (result) {
                        if (result.status == 1) {
                            deptTable.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Content ' + actionText + 'd successfully'
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
    function deleteDept(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Delete Department',
            text: "Are you sure you want to delete this department? This action cannot be undone.",
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
                $.post("<?php echo site_url('admin/ajax/delete_dept') ?>",
                    {id: id},
                    function (result) {
                        if (result.status == 1) {
                            deptTable.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Department deleted successfully'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to delete department',
                            });
                        }
                    }
                );
            }
        });
    }

    // Quill toolbar options
    var quillToolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'align': [] }],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['link'],
      ['clean']
    ];

    // Add Modal Quill editors
    var quillAbout, quillMission, quillVision, quillPolicy, quillContact;
    $('#addModal').on('shown.bs.modal', function () {
      if (!quillAbout) {
        quillAbout = new Quill('#quillAbout', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      if (!quillMission) {
        quillMission = new Quill('#quillMission', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      if (!quillVision) {
        quillVision = new Quill('#quillVision', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      if (!quillPolicy) {
        quillPolicy = new Quill('#quillPolicy', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      if (!quillContact) {
        quillContact = new Quill('#quillContact', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      
      // Add image preview on file input change for Add modal
      $('#deptImg').off('change').on('change', function() {
          const file = this.files[0];
          const preview = $('#addDeptLogoPreview');
          if (file) {
              const reader = new FileReader();
              reader.onload = function(e) {
                  preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
              };
              reader.readAsDataURL(file);
          } else {
              preview.html('');
          }
      });
      
      // Add org chart preview on file input change for Add modal
      $('#deptOrgChart').off('change').on('change', function() {
          const file = this.files[0];
          const preview = $('#addDeptOrgChartPreview');
          if (file) {
              const reader = new FileReader();
              reader.onload = function(e) {
                  preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
              };
              reader.readAsDataURL(file);
          } else {
              preview.html('');
          }
      });
    });
    
    // Clear image preview when Add Modal closes
    $('#addModal').on('hidden.bs.modal', function() {
        $('#addDeptLogoPreview').html('');
        $('#addDeptOrgChartPreview').html('');
    });

    // Edit Modal Quill editors
    var editQuillAbout, editQuillMission, editQuillVision, editQuillPolicy, editQuillContact;
    $('#editModal').on('shown.bs.modal', function () {
      if (!editQuillAbout) {
        editQuillAbout = new Quill('#editQuillAbout', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      if (!editQuillMission) {
        editQuillMission = new Quill('#editQuillMission', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      if (!editQuillVision) {
        editQuillVision = new Quill('#editQuillVision', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      if (!editQuillPolicy) {
        editQuillPolicy = new Quill('#editQuillPolicy', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
      if (!editQuillContact) {
        editQuillContact = new Quill('#editQuillContact', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
    });

    // On Add submit, copy Quill HTML to hidden inputs
    $('#btnAdd').on('click', function () {
      if (quillAbout && quillMission && quillVision && quillPolicy && quillContact) {
        $('#txtAbout').val(quillAbout.root.innerHTML);
        $('#txtMission').val(quillMission.root.innerHTML);
        $('#txtVision').val(quillVision.root.innerHTML);
        $('#txtPolicy').val(quillPolicy.root.innerHTML);
        $('#txtContact').val(quillContact.root.innerHTML);
      }
      // Validation: check Quill content is not empty
      if (
        quillAbout.root.innerHTML.trim() === '' ||
        quillAbout.root.innerHTML === '<p><br></p>' ||
        quillMission.root.innerHTML.trim() === '' ||
        quillMission.root.innerHTML === '<p><br></p>' ||
        quillVision.root.innerHTML.trim() === '' ||
        quillVision.root.innerHTML === '<p><br></p>' ||
        quillPolicy.root.innerHTML.trim() === '' ||
        quillPolicy.root.innerHTML === '<p><br></p>' ||
        quillContact.root.innerHTML.trim() === '' ||
        quillContact.root.innerHTML === '<p><br></p>'
      ) {
        Swal.fire({
          icon: 'warning',
          title: 'Validation Error',
          text: 'Please fill in all required fields.'
        });
        return;
      }

        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('txtDept') || !formData.get('txtHead') || !formData.get('txtMission') || !formData.get('txtVision') || !formData.get('txtPolicy') || !formData.get('txtContact')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; 
        }

        // Image validation
        let imageFile = formData.get('deptImg');
        if (!imageFile || imageFile.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload a department logo.'
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
            url: '<?php echo site_url('admin/ajax/create_dept'); ?>',
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
                        text: 'Department data saved!'
                    });
                    deptTable.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'warning' || 'error',
                        title: 'Error',
                        text: result.message || 'Data not created. Refresh the page or try logging in again.',
                    });
                    deptTable.ajax.reload(null, false);
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

    function edit(deptId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_dept'); ?>',
            method: 'POST',
            data: { id: deptId },
            success: function (response) {
                if (response.status === 1) {
                    let res = response.data; // Directly access the data object
                    $('#editDeptId').val(res.ID);
                    $('#editDept').val(res.dept_name);
                    $('#editHead').val(res.head);
                    $('#editTitle').val(res.post_title);
                    $('#editMission').val(res.mission);
                    $('#editVision').val(res.vision);
                    $('#editPolicy').val(res.quality_policy);
                    $('#editAbout').val(res.about || '');
                    $('#editContact').val(res.contact || '');
                    // Set Quill editor content after initialization
                    $('#editModal').on('shown.bs.modal', function () {
                        if (editQuillAbout) editQuillAbout.root.innerHTML = res.about || '';
                        if (editQuillMission) editQuillMission.root.innerHTML = res.mission || '';
                        if (editQuillVision) editQuillVision.root.innerHTML = res.vision || '';
                        if (editQuillPolicy) editQuillPolicy.root.innerHTML = res.quality_policy || '';
                        if (editQuillContact) editQuillContact.root.innerHTML = res.contact || '';
                    });
                    
                    // Show current image in preview
                    $('#editDeptLogoPreview').html(
                        res.img_logo
                            ? `<img src="<?php echo base_url('admin/image/DEPT/') ?>${res.img_logo}" alt="Current Department Logo" style="max-width: 120px; margin-top: 5px;">`
                            : '<small>No logo available.</small>'
                    );
                    
                    // Show current org chart in preview
                    $('#editDeptOrgChartPreview').html(
                        res.org_chart_img
                            ? `<img src="<?php echo base_url('admin/image/DEPT/') ?>${res.org_chart_img}" alt="Current Organizational Chart" style="max-width: 120px; margin-top: 5px;">`
                            : '<small>No org chart available.</small>'
                    );
                    
                    // Reset file inputs
                    $('#editdeptImg').val('');
                    $('#editdeptOrgChart').val('');
                    
                    // Add image preview on file input change for Edit modal
                    $('#editdeptImg').off('change').on('change', function() {
                        const file = this.files[0];
                        const preview = $('#editDeptLogoPreview');
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // If no file, show the original image again (if any)
                            if (res.img_logo) {
                                preview.html(`<img src="<?php echo base_url('admin/image/DEPT/') ?>${res.img_logo}" alt="Current Department Logo" style="max-width: 120px; margin-top: 5px;">`);
                            } else {
                                preview.html('<small>No logo available.</small>');
                            }
                        }
                    });
                    
                    // Add org chart preview on file input change for Edit modal
                    $('#editdeptOrgChart').off('change').on('change', function() {
                        const file = this.files[0];
                        const preview = $('#editDeptOrgChartPreview');
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
                            };
                            reader.readAsDataURL(file);
                        } else {
                            // If no file, show the original image again (if any)
                            if (res.org_chart_img) {
                                preview.html(`<img src="<?php echo base_url('admin/image/DEPT/') ?>${res.org_chart_img}" alt="Current Organizational Chart" style="max-width: 120px; margin-top: 5px;">`);
                            } else {
                                preview.html('<small>No org chart available.</small>');
                            }
                        }
                    });
                    
                    $('#editModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Department not found.'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to fetch department details. Please try again later.'
                });
            }
        });
    }


    // Function to submit the edit user form
    $('#btnEdit').click(function () {
        // Always update hidden inputs with Quill content before creating FormData
        if (editQuillAbout && editQuillMission && editQuillVision && editQuillPolicy && editQuillContact) {
            $('#editAbout').val(editQuillAbout.root.innerHTML);
            $('#editMission').val(editQuillMission.root.innerHTML);
            $('#editVision').val(editQuillVision.root.innerHTML);
            $('#editPolicy').val(editQuillPolicy.root.innerHTML);
            $('#editContact').val(editQuillContact.root.innerHTML);
        }
        let form = $('#editForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('editDept') || !formData.get('editHead') || !formData.get('editMission') || !formData.get('editVision') || !formData.get('editPolicy') || !formData.get('editContact')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
        }
        // Image validation
        let imageFile = formData.get('editdeptImg');
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
        // Validation: check Quill content is not empty
        if (
            editQuillAbout.root.innerHTML.trim() === '' ||
            editQuillAbout.root.innerHTML === '<p><br></p>' ||
            editQuillMission.root.innerHTML.trim() === '' ||
            editQuillMission.root.innerHTML === '<p><br></p>' ||
            editQuillVision.root.innerHTML.trim() === '' ||
            editQuillVision.root.innerHTML === '<p><br></p>' ||
            editQuillPolicy.root.innerHTML.trim() === '' ||
            editQuillPolicy.root.innerHTML === '<p><br></p>' ||
            editQuillContact.root.innerHTML.trim() === '' ||
            editQuillContact.root.innerHTML === '<p><br></p>'
        ) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }
        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_dept'); ?>',
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
                        deptTable.ajax.reload();
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
                    text: 'Unable to update department. Please try again later.'
                });
            }
        });
    });

    var deptTable = $('#tbldept').DataTable({
    select: false,
    searching: true,
    ordering: true,
    "order": [],
    pageLength: 10,
    processing: true,
    ajax: {
        url: "<?php echo base_url('admin/ajax/get_departments'); ?>",
        type: "POST",
        dataSrc: function(json) {
            return json.data || [];
        }
    },
    initComplete: function() {
            var searchInput = $('#tbldept_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search Category...');
            searchInput.removeClass('form-control-sm'); // Standard size is more visible than small
            searchInput.css({
                'width': '350px',           // Make it wider
                'border': '2px solid #388e3c', // Distinct brand-green border
                'margin-left': '10px'       // Add space from the "Search:" label
            });
        },
    columns: [
        { "title": "Department ID", "data": "ID", "visible": false },
        { "title": "Dept. Name", "data": "dept_name", width: '30%' },
        { 
            "title": "Logo", "data": "img_logo", "className": "dt-center",
            "render": function (data, type, row) {
                return '<img id="img_logo" class="img-fluid mt-3" src="<?php echo base_url('admin/image/DEPT/') ?>' + data + '">';
            }
        },
        { "title": "Officer in Charge", "data": "head", width: '25%' },
        {
            "title": "Status",
            "data": "status",
            "className": "dt-center",
            width: '10%',
            "render": function (data, type, row) {
                if (data == 'ACTIVE') {
                    return '<span class="badge bg-success">Active</span>';
                } else if (data == 'INACTIVE') {
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

                        if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN' || userLevel === 'ENCODER') {
                            var statusIcon = row.status === 'ACTIVE' ? 'bi-toggle-on' : 'bi-toggle-off';
                            var statusText = row.status === 'ACTIVE' ? 'Deactivate' : 'Activate';
                            
                            actionHtml += `
                                <li><a class="dropdown-item" href="#" onclick="toggleStatus(${row.ID}, '${row.status}')"><i class="bi ${statusIcon} me-1"></i> ${statusText}</a></li>`;
                                
                            if (userLevel !== 'ENCODER') {
                                actionHtml += `
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteDept(${row.ID})"><i class="bi bi-trash me-1"></i> Delete</a></li>`;
                            }
                        }
                        
                        actionHtml += `</ul></div>`;
                        return actionHtml;
                    } else {
                        return '-';
                    }
                }
            }
        ]
    });

    var sltdRow = null;

    $('#tbldept tbody').on('mouseover', 'tr', function () {
        sltdRow = deptTable.row(this).data();
    });

// Attach a submit handler to the form
$('#departmentSearchForm').on('submit', function(e) {
    e.preventDefault(); // stop page reload
    applyDeptFilters(); // run your search logic
});

// Optional: Clear Filters button
$('#departmentSearchForm button[type="reset"]').on('click', function() {
    // reset form fields
    $('#departmentSearchForm')[0].reset();
});


function applyDeptFilters() {
    var searchTerm = $('#searchDept').val().trim().toLowerCase();
    var statusFilter = $('select[name="deptStatus"]').val();
    
    // Custom filtering function for combined search
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var row = deptTable.row(dataIndex).data();
            var searchMatch = true;
            var statusMatch = true;
            
            // Combined search for both department name and officer (case-insensitive)
            if (searchTerm) {
                var deptName = row.dept_name.toLowerCase();
                var officerName = row.head.toLowerCase();
                
                // Check if search term matches either department name or officer name
                if (!deptName.includes(searchTerm) && !officerName.includes(searchTerm)) {
                    searchMatch = false;
                }
            }
            
            // Exact match for status
            if (statusFilter) {
                statusMatch = row.status === statusFilter;
            }
            
            return searchMatch && statusMatch;
        }
    );
    
    deptTable.draw();
    $.fn.dataTable.ext.search.pop(); // Remove filter after applying
}

// Clear filters button
$('#departmentSearchForm button[type="reset"]').click(function() {
    $('#departmentSearchForm')[0].reset();
    deptTable.search('').columns().search('').draw();
});
</script>