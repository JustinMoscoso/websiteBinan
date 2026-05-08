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
    $('#fileCategory, #editFileCategory').selectize({
        sortField: 'text',
        placeholder: 'Choose file category',
        allowClear: true
    });
    $('#yr').yearpicker();
    $('#edityr').yearpicker();

    // Patch: Reverse the year list in the yearpicker dropdown so latest years appear first
    function reverseYearpickerList(inputId) {
        $(inputId).on('focus', function() {
            setTimeout(function() {
                var $ul = $(inputId).siblings('.yearpicker-dropdown').find('.yearpicker-year');
                var $years = $ul.children('li');
                $ul.html($years.get().reverse());
            }, 10); // Wait for yearpicker to render
        });
    }
    reverseYearpickerList('#yr');
    reverseYearpickerList('#edityr');

    // Function to check if file category is annual
    function isAnnualCategory(category) {
        const annualCategories = [
            'Annual Budget Report',
            'Annual Procurement Plan or Procurement List',
            'Supplemental Procurement Plan',
            'Annual Gender and Development Accomplishment Report'
        ];
        return annualCategories.includes(category);
    }

    // Function to toggle quarter field visibility
    function toggleQuarterField(show, modalType = 'add') {
        const quarterField = modalType === 'add' ? '#qtr' : '#editqtr';
        const quarterLabel = modalType === 'add' ? 'label[for="qtr"]' : 'label[for="editqtr"]';
        
        if (show) {
            $(quarterField).closest('.form-group').show();
            $(quarterField).prop('required', true);
        } else {
            $(quarterField).closest('.form-group').hide();
            $(quarterField).prop('required', false);
            $(quarterField).val(''); // Clear the value
        }
    }

    // Handle file category change in Add Modal
    $('#fileCategory').on('change', function() {
        const selectedCategory = $(this).val();
        const isAnnual = isAnnualCategory(selectedCategory);
        toggleQuarterField(!isAnnual, 'add');
    });

    // Handle file category change in Edit Modal
    $('#editFileCategory').on('change', function() {
        const selectedCategory = $(this).val();
        const isAnnual = isAnnualCategory(selectedCategory);
        toggleQuarterField(!isAnnual, 'edit');
    });

    // Initialize quarter field visibility when Add modal opens
    $('#addModal').on('shown.bs.modal', function() {
        const selectedCategory = $('#fileCategory').val();
        if (selectedCategory) {
            const isAnnual = isAnnualCategory(selectedCategory);
            toggleQuarterField(!isAnnual, 'add');
        } else {
            // Default to showing quarter field if no category is selected
            toggleQuarterField(true, 'add');
        }
    });

    $('#btnAdd').on('click', function () {
        let form = $('#addForm')[0];
        let formData = new FormData(form);
        const selectedCategory = formData.get('fileCategory');
        const isAnnual = isAnnualCategory(selectedCategory);

        // Check required fields based on category type
        let requiredFields = ['fileCategory', 'yr', 'policyFile'];
        if (!isAnnual) {
            requiredFields.push('qtr');
        }

        // Validate required fields
        for (let field of requiredFields) {
            if (!formData.get(field) || (field === 'policyFile' && !formData.get(field).name)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields.'
                });
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
            url: '<?php echo site_url('admin/ajax/create_fulldiscpol'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    $('#fileCategory')[0].selectize.clear();
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data saved!'
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

    function edit(id) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_fulldiscpol'); ?>',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                if (response.status === 1) {
                    let res = response.data; // Directly access the data object
                    $('#editPolicyId').val(res.ID);
                    $('#editFileCategory')[0].selectize.setValue(res.file_category); // Set the value for selectize
                    $('#edityr').val(res.year);
                    $('#editqtr').val(res.quarter);
                    
                    // Handle quarter field visibility based on category
                    const isAnnual = isAnnualCategory(res.file_category);
                    toggleQuarterField(!isAnnual, 'edit');
                    
                    $('#editModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Policy not found.'
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
    $('#btnEdit').click(function () {
        let formData = new FormData($('#editForm')[0]);
        const selectedCategory = formData.get('editFileCategory');
        const isAnnual = isAnnualCategory(selectedCategory);

        // Check required fields based on category type
        let requiredFields = ['editFileCategory', 'edityr'];
        if (!isAnnual) {
            requiredFields.push('editqtr');
        }

        // Validate required fields
        for (let field of requiredFields) {
            if (!formData.get(field)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields.'
                });
                return; 
            }
        }
        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_fulldiscpol'); ?>',
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
                    text: 'Unable to update policy. Please try again later.'
                });
            }
        });
    });

    // Deactivate function
    function deactivate(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Deactivate Content',
            text: "Are you sure you want to deactivate this content? This will not be displayed in the Full Disclosure Policy section.",
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
                $.post("<?php echo site_url('admin/ajax/set_status_fulldiscpol') ?>",
                    {id: id, 'status': 'INACTIVE'},
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
    function activate(id) {
        Swal.fire({
            heightAuto: false,
            title: 'Activate Content',
            text: "Are you sure you want to activate this content? This will be displayed in the Full Disclosure Policy section.",
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
                $.post("<?php echo site_url('admin/ajax/set_status_fulldiscpol') ?>",
                    {id: id, 'status': 'ACTIVE'},
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


    var tbl = $('#tblfdp').DataTable({
        select: false,
        searching: true,
        ordering: true,
        "order": [],
        pageLength: 10,
        processing: true,
        ajax: {
            "url": "<?php echo base_url('admin/ajax/get_fulldiscpol'); ?>",
            "type": "POST",
            "data": function(d) {
                d.search = $('input[name="search"]').val();
                d.frequency = $('select[name="frequency"]').val();
                d.file_category = $('select[name="file_category"]').val();
                d.status = $('select[name="status"]').val();
            }
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            /*{ "title": "Created", "data": "created_date", 
                "render": function (data, type, row) {
                    var date = new Date(data);
                    return formatDate(date);
                }
            },*/
            { "title": "File Category", "data": "file_category" },
            { "title": "Year", "data": "year" },
            { "title": "Quarter", "data": "quarter" },
            {
                "title": "File", "data": "file_name",
                "render": function (data, type, row) {
                    var fileUrl = "<?php echo base_url('admin/preview_file/FULLDISC/'); ?>" + encodeURIComponent(data);
                    return '<a href="' + fileUrl + '" target="_blank">Preview</a>';
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

    $('#tblfdp tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Search form submit: reload table with filters
    $('#docSearchForm').on('submit', function(e) {
        e.preventDefault();
        tbl.ajax.reload();
    });
    // Clear filters: reload table
    $('#docSearchForm').on('reset', function() {
        setTimeout(function() {
            tbl.ajax.reload();
        }, 0);
    });

</script>