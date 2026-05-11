<script>
    $(document).ready(function(){ 
        // $('#searchCategory').click(function(){ 
        //     if ($('#searchCategory').val() == ""){
        //         $("#div_searchbtn").removeClass("col-md-6").addClass("col-md-2");
        //     }
        //     else{
        //         $("#div_searchbtn").removeClass("col-md-2").addClass("col-md-6");
        //     }
        // });
    });

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
    $('#txtDept, #editDept, #txtBrgy, #editBrgy, #searchBrgy, #searchDept').selectize({
        sortField: 'text',
        allowClear: true
    });

    $('#searchDept').selectize({
        placeholder: '- Select Department -',
        sortField: 'text',
        allowClear: true
    });

    $('#searchBrgy').selectize({
        placeholder: '- Select Barangay -',
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
    $('#category').on('change', function() {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'DEPT') {
            $('#deptGroup').show();
            $('#brgyGroup').hide();
            populateDepartmentDropdown($('#txtDept'));
        } else if (selectedCategory === 'BRGY') {
            $('#deptGroup').hide();
            $('#brgyGroup').show();
            populateBrgyDropdown($('#txtBrgy'));
        } else {
            $('#deptGroup, #brgyGroup').hide();
        }
    });

    // Same for edit modal
    $('#editcategory').on('change', function() {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'DEPT') {
            $('#editdeptGroup').show();
            $('#editbrgyGroup').hide();
            populateDepartmentDropdown($('#editDept'));
        } else if (selectedCategory === 'BRGY') {
            $('#editdeptGroup').hide();
            $('#editbrgyGroup').show();
            populateBrgyDropdown($('#editBrgy'));
        } else {
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

    // Quill toolbar options
    var quillToolbarOptions = [
      ['bold', 'italic', 'underline', 'strike'],
      [{ 'align': [] }],
      [{ 'list': 'ordered'}, { 'list': 'bullet' }],
      ['link'],
      ['clean']
    ];

    // Add Modal Quill editor
    var quillContent;
    $('#addModal').on('shown.bs.modal', function () {
      if (!quillContent) {
        quillContent = new Quill('#quillContent', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
    });

    // Edit Modal Quill editor
    var editQuillContent;
    $('#editModal').on('shown.bs.modal', function () {
      if (!editQuillContent) {
        editQuillContent = new Quill('#editQuillContent', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
      }
    });

    // Save new announcement
    $('#btnAdd').on('click', function() {
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('category') || !formData.get('serviceName') || !formData.get('content')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
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
            url: '<?php echo site_url('admin/ajax/create_services'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(result) {
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
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request. Please try again.'
                });
            }
        });
    });

    // Function to handle edit button click
    function edit(id) {
    $.ajax({
        url: '<?php echo site_url('admin/ajax/get_services'); ?>',
        method: 'POST',
        data: { id: id },
        success: function (response) {
            if (response.status === 1) {
                let res = response.data; // Directly access the data object
                $('#editId').val(res.ID);
                $('#editServiceName').val(res.serv_name);
                $('#editContent').val(res.content);
                // Set Quill editor content after initialization
                $('#editModal').on('shown.bs.modal', function () {
                    if (editQuillContent) editQuillContent.root.innerHTML = res.content || '';
                });
                if (res.brngy_cont_ID) {
                    $('#editcategory').val('BRGY').trigger('change'); // Set the category and trigger change
                    $('#editdeptGroup').hide();
                    $('#editbrgyGroup').show();
                    populateBrgyDropdown($('#editBrgy'), res.brngy_cont_ID);
                    $('#editDept').val(null); // Set editDept to null
                } else if (res.dept_cont_ID) {
                    $('#editcategory').val('DEPT').trigger('change');
                    $('#editdeptGroup').show();
                    $('#editbrgyGroup').hide();
                    populateDepartmentDropdown($('#editDept'), res.dept_cont_ID);
                    $('#editBrgy').val(null); // Set editBrgy to null
                }
                $('#editModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Service not found.'
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
    // Function to submit the edit form
    $('#btnEdit').click(function () {
        // Always update hidden input with Quill content before creating FormData
        if (editQuillContent) {
            $('#editContent').val(editQuillContent.root.innerHTML);
        }
        let form = $('#editForm')[0];
        let formData = new FormData(form);

        // Form validation
        if (!formData.get('editServiceName') || !formData.get('editContent')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return; // Stop further execution if validation fails
        }
        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_services'); ?>',
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

    function activate(servId) {
        Swal.fire({
            heightAuto: false,
            title: 'Activate Service',
            text: "Are you sure you want to activate this service?",
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
                $.post("<?php echo site_url('admin/ajax/set_status_services') ?>",
                    {id: servId, 'status': 'ACTIVE'},
                    function (result) {
                        if (result.status == 1) {
                            $('.modal').modal('hide');
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Service activated successfully'
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
    // Toggle Status function
    function toggleStatus(id, currentStatus) {
        var newStatus = currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
        var actionText = newStatus === 'ACTIVE' ? 'activate' : 'deactivate';

        Swal.fire({
            heightAuto: false,
            title: (newStatus === 'ACTIVE' ? 'Activate' : 'Deactivate') + ' Service',
            text: "Are you sure you want to " + actionText + " this service?",
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
                $.post("<?php echo site_url('admin/ajax/set_status_services') ?>",
                    {id: id, 'status': newStatus},
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Service ' + actionText + 'd successfully'
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

    function deleteService(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Delete Service',
            text: "Are you sure you want to delete this service? This action cannot be undone.",
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
                $.post("<?php echo site_url('admin/ajax/delete_services') ?>",
                    {id: id},
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: 'Service deleted successfully'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: result.message || 'Failed to delete service',
                            });
                        }
                    }
                );
            }
        });
    }


    //datatable
    // Initialize DataTable (keep your existing configuration)
    var tbl = $('#tblservice').DataTable({
    select: false,
    searching: true,
    ordering: true,
    "order": [],
    pageLength: 10,
    processing: true,
    ajax: {
        "url": "<?php echo base_url('admin/ajax/get_services'); ?>",
        "type": "POST",
        "data": function(d) {
            // Add your custom filter parameters
            d.service_name = $('#service_name').val();
            d.category = $('#searchCategory').val();
            d.brgy = $('#searchBrgy').val();
            d.dept = $('#searchDept').val();
            d.status = $('#status').val();
        },
        "dataSrc": function(json) {
            return json.data;
        }
    },
    initComplete: function() {
            var searchInput = $('#tblservice_filter input[type="search"]');
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
                "title": "Created", "data": "created_date",
                "render": function (data, type, row) {
                    var date = new Date(data);
                    return formatDate(date);
                },
                "visible": false
            },
            { "title": "Category", "data": "brngy_cont_ID", width: '25%',
                "render": function (data, type, row) {
                    if (row.brgy_name === null)
                        return row.dept_name;
                    else 
                        return row.brgy_name;
                    }
             },
            { "title": "Services", "data": "serv_name"},
            { "title": "Content", "data": "content", "className": "dt-head-center dt-body-justify",  width: '35%' },
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
                        let actionHtml = `
                            <div class="dropdown">
                              <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                                <i class="bi bi-list"></i> Actions
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal" onclick="edit(${row.ID})"><i class="bi bi-pencil me-1"></i> Edit</a></li>`;

                        if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') {
                            var statusIcon = row.status === 'ACTIVE' ? 'bi-toggle-on' : 'bi-toggle-off';
                            var statusText = row.status === 'ACTIVE' ? 'Deactivate' : 'Activate';

                            actionHtml += `
                                <li><a class="dropdown-item" href="#" onclick="toggleStatus(${row.ID}, '${row.status}')"><i class="bi ${statusIcon} me-1"></i> ${statusText}</a></li>`;
                        }

                        if (userLevel === 'DEVELOPER') {
                             actionHtml += `
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteService(${row.ID})"><i class="bi bi-trash me-1"></i> Delete</a></li>`;
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

    $('#tblservice tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });



  // Search filter functionality
$('#searchCategory').on('change', function() {
    var selectedCategory = $(this).val();
    $('#searchDeptGroup, #searchBrgyGroup, #searchDefaultGroup').hide();
    if (selectedCategory === 'BARANGAY') {
        $('#searchBrgyGroup').show();
        populateBrgyDropdown($('#searchBrgy'));
    } else if (selectedCategory === 'DEPARTMENT') {
        $('#searchDeptGroup').show();
        populateDepartmentDropdown($('#searchDept'));
    } else {
        // Show default disabled dropdown when no category is selected
        $('#searchDefaultGroup').show();
    }
});

// Submit handler for search form
$('#serviceSearchForm').on('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const searchBtn = $(this).find('button[type="submit"]');
    const originalBtnText = searchBtn.html();
    searchBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...');
    
    // Reload table with new filters
    tbl.ajax.reload(function() {
        searchBtn.html(originalBtnText);
    });
});

// Reset handler - fixed version
$('#serviceSearchForm').on('reset', function() {
    // Hide filter dropdowns
    $('#searchDeptGroup, #searchBrgyGroup').hide();
    
    // Show default dropdown
    $('#searchDefaultGroup').show();
    
    // Reset category dropdown
    $('#searchCategory').val('').trigger('change');
    
    // Clear Selectize dropdowns if they exist
    if ($('#searchBrgy')[0].selectize) $('#searchBrgy')[0].selectize.clear();
    if ($('#searchDept')[0].selectize) $('#searchDept')[0].selectize.clear();
    
    // Clear all input fields
    $('#service_name').val('');
    $('#status').val('');
    
    // Reset the table - IMPORTANT FIX HERE
    tbl.ajax.url('<?php echo base_url('admin/ajax/get_services'); ?>').load();
    
    // Prevent default form reset behavior that might interfere
    return false;
});

// Reusable function for both search mechanisms
function reloadTableWithFilters(filters, searchBtn) {
    // Show loading state
    const originalBtnText = searchBtn.html();
    searchBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...');
    
    // Convert filters to FormData for proper handling
    let formData = new FormData();
    for (const key in filters) {
        if (filters[key]) {
            formData.append(key, filters[key]);
        }
    }
    
    // Reload DataTable with filters
    tbl.ajax.url('<?php echo base_url('admin/ajax/get_services'); ?>')
        .data(formData)
        .load(function(json) {
            // Restore button text
            searchBtn.html(originalBtnText);
            
            // Debugging - check what was returned
            console.log("Filtered results:", json.data);
        }, function(xhr, status, error) {
            // Restore button text on error
            searchBtn.html(originalBtnText);
            console.error("Search error:", status, error);
        });
}
</script>