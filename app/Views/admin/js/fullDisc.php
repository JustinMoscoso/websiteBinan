<script>
    const userLevel = '<?= $user->user_lvl ?>'.toUpperCase(); // Get user level from backend and force uppercase
    console.log("Current User Role:", userLevel);

    if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') {
        $('.button-32').show();
    } else {
        $('.button-32').hide();
    }

    if (userLevel === 'VIEWER') {
        // Hide add/save buttons on the page layout level
        $('[data-bs-target="#addModal"]').hide();

        // Lock down elements inside modals when shown
        $(document).on('show.bs.modal', '.modal', function () {
            var $modal = $(this);
            // Disable all input and form controls
            $modal.find('input, select, textarea, button').prop('disabled', true);
            // Re-enable close/dismiss buttons so viewer can close the modal
            $modal.find('button[data-bs-dismiss="modal"], .btn-close, a[data-bs-dismiss="modal"]').prop('disabled', false);
            // Hide all action/save buttons except close/dismiss buttons
            $modal.find('button, input[type="submit"], input[type="button"], a.btn').not('[data-bs-dismiss="modal"], .btn-close').hide();
            // Hide file inputs
            $modal.find('input[type="file"]').hide();
        });

        // Lock down Quill editors (prevent typing and hide toolbars)
        $(document).on('show.bs.modal shown.bs.modal', '.modal', function () {
            var $modal = $(this);
            $modal.find('.ql-editor').attr('contenteditable', 'false');
            $modal.find('.ql-toolbar').hide();
            
            // Re-enforce lock down after a short delay for dynamic content loading
            setTimeout(function() {
                $modal.find('.ql-editor').attr('contenteditable', 'false');
                $modal.find('.ql-toolbar').hide();
            }, 100);
            setTimeout(function() {
                $modal.find('.ql-editor').attr('contenteditable', 'false');
                $modal.find('.ql-toolbar').hide();
            }, 500);
        });
    }

    // Initialize selectize for all selects
    $('#fileCategory, #editFileCategory').selectize({
        sortField: 'text',
        placeholder: 'Choose file category',
        allowClear: true
    });

    // Initialize yearpicker
    $('.yearpicker').yearpicker({
        year: new Date().getFullYear()
    });
    // Initially clear the add year input text box so it shows the placeholder
    $('#yr').val('');

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
            $(quarterField).closest('.col-md-6').show();
            $(quarterField).prop('required', true);
        } else {
            $(quarterField).closest('.col-md-6').hide();
            $(quarterField).prop('required', false);
            $(quarterField).val(''); // Clear the value
        }
    }

    // Handle file category change in Add Modal
    $('#fileCategory').on('change', function () {
        const selectedCategory = $(this).val();
        const isAnnual = isAnnualCategory(selectedCategory);
        toggleQuarterField(!isAnnual, 'add');
    });

    // Handle file category change in Edit Modal
    $('#editFileCategory').on('change', function () {
        const selectedCategory = $(this).val();
        const isAnnual = isAnnualCategory(selectedCategory);
        toggleQuarterField(!isAnnual, 'edit');
    });

    // Initialize quarter field visibility when Add modal opens
    $('#addModal').on('shown.bs.modal', function () {
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
                    let addYrPicker = $('#yr').data('yearpicker');
                    if (addYrPicker) {
                        addYrPicker.year = new Date().getFullYear();
                        addYrPicker.setValue();
                    }
                    // Reset add year input text box to empty so it displays placeholder
                    $('#yr').val('');
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
                    let editYrPicker = $('#edityr').data('yearpicker');
                    if (editYrPicker) {
                        editYrPicker.year = parseInt(res.year);
                        editYrPicker.setValue();
                    }
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

    // Toggle Status function
    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);
        var confirmText = newStatus === 'ACTIVE' ? 'This will be displayed in the Full Disclosure Policy section.' : 'This will not be displayed in the Full Disclosure Policy section.';

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'Content'),
            text: "Are you sure you want to " + actionText + " this content? " + confirmText,
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
                    { id: id, 'status': newStatus },
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
    function deletePol(id) {
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
                $.post("<?php echo site_url('admin/ajax/delete_fulldiscpol') ?>",
                    { id: id },
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
            "data": function (d) {
                d.search = $('input[name="search"]').val();
                d.frequency = $('select[name="frequency"]').val();
                d.file_category = $('select[name="file_category"]').val();
                d.status = $('select[name="status"]').val();
            }
        },
        initComplete: function () {
            var searchInput = $('#tblfdp_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search files...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });
            
            var lengthSelect = $('#tblfdp_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
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
                        return '<span class="status-badge status-badge-active"><span class="status-dot status-dot-active"></span>Active</span>';
                    } else if (status == 'INACTIVE') {
                        return '<span class="status-badge status-badge-inactive"><span class="status-dot status-dot-inactive"></span>Inactive</span>';
                    } else {
                        return '<span class="status-badge status-badge-archived"><span class="status-dot status-dot-archived"></span>Archived</span>';
                    }
                }
            },
            {
                "title": "Actions",
                "data": "ID",
                "className": "dt-center",
                "render": function (data, type, row) {
                    if (userLevel === 'VIEWER') {
                        return `<a class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" href="#" onclick="edit(${row.ID}); return false;" style="width: 32px; height: 32px; border-radius: 50%;" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>`;
                    }
                    console.log("Rendering actions for row:", row.ID, "detected userLevel:", userLevel);
                    var fileUrl = "<?php echo base_url('admin/preview_file/FULLDISC/'); ?>" + encodeURIComponent(row.file_name);

                    let actionHtml = `
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                            <i class="bi bi-list"></i> Actions
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="${fileUrl}" target="_blank"><i class="bi bi-eye me-1"></i>View File</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal" onclick="edit(${row.ID})"><i class="bi bi-pencil me-1"></i>Edit</a></li>`;

                    // Developer, Superadmin, and Admin have full access (Toggle Status, Delete)
                    if ((userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') && row.status !== 'ARCHIVED') {
                        var statusIcon = row.status === 'ACTIVE' ? 'bi-toggle-on' : 'bi-toggle-off';
                        var statusText = row.status === 'ACTIVE' ? 'Deactivate' : 'Activate';

                        actionHtml += `
                            <li><a class="dropdown-item" href="#" onclick="toggleStatus(${row.ID}, '${row.status}')"><i class="bi ${statusIcon} me-1"></i>${statusText}</a></li>`;
                    }
                    actionHtml += renderArchiveRestoreAction(userLevel, row, 'toggleStatus');
                    actionHtml += renderDeleteAction(userLevel, row.ID, 'deletePol');

                    actionHtml += `</ul></div>`;
                    return actionHtml;
                }
            }
        ]
    });

    var sltdRow = null;

    $('#tblfdp tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Attach a submit handler to the Document form
    $('#docSearchForm').on('submit', function (e) {
        e.preventDefault(); // stop page reload

        // Grab values
        const query = $('[name="search"]').val().trim();
        const frequency = $('[name="frequency"]').val();
        const category = $('[name="file_category"]').val();
        const status = $('[name="status"]').val();

        console.log("Searching for:", query, "Frequency:", frequency, "Category:", category, "Status:", status);

        // Example: reload your DataTable with filters
        tbl.ajax.reload();
    });

    // Clear Filters button
    $('#docSearchForm button[type="reset"]').on('click', function () {
        // reset form fields
        $('#docSearchForm')[0].reset();

        // also clear individual inputs if needed
        $('[name="search"]').val('');
        $('[name="frequency"]').val('');
        $('[name="file_category"]').val('');
        $('[name="status"]').val('');

        // reload table back to default
        tbl.ajax.reload();
    });


</script>