<script>
    $(document).ready(function () {
        // placeholder for ready block
    });

    const userLevel = '<?= $user->user_lvl ?>'.toUpperCase();
    const userAccountType = '<?= $user->account_type ?? '' ?>'.toUpperCase();
    const isDeptScopedAdmin = (userLevel === 'ADMIN' || userLevel === 'ENCODER') && userAccountType === 'DEPARTMENT';
    const isBrgyScopedAdmin = (userLevel === 'ADMIN' || userLevel === 'ENCODER') && userAccountType === 'BARANGAY';
    const isEntityScopedAdmin = isDeptScopedAdmin || isBrgyScopedAdmin;
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
    $('#txtDept, #editDept, #txtBrgy, #editBrgy').selectize({
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
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status === 1 && Array.isArray(response.data)) {
                    let selectizeControl = selectElement[0].selectize;
                    selectizeControl.clearOptions();
                    response.data.forEach(function (department) {
                        selectizeControl.addOption({ value: department.ID, text: department.dept_name });
                    });
                    selectizeControl.refreshOptions(false);
                    if (selectedValue) {
                        selectizeControl.setValue(selectedValue);
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
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.status === 1 && Array.isArray(response.data)) {
                    let selectizeControl = selectElement[0].selectize;
                    selectizeControl.clearOptions();
                    response.data.forEach(function (barangay) {
                        selectizeControl.addOption({ value: barangay.ID, text: barangay.brgy_name });
                    });
                    selectizeControl.refreshOptions(false);
                    if (selectedValue) {
                        selectizeControl.setValue(selectedValue);
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
    $('#category').on('change', function () {
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
    $('#editcategory').on('change', function () {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'DEPT') {
            $('#editDeptFieldGroup').show();
            $('#editBrgyFieldGroup').hide();
            if ($('#editDept').length) populateDepartmentDropdown($('#editDept'));
        } else if (selectedCategory === 'BRGY') {
            $('#editDeptFieldGroup').hide();
            $('#editBrgyFieldGroup').show();
            if ($('#editBrgy').length) populateBrgyDropdown($('#editBrgy'));
        } else {
            $('#editDeptFieldGroup, #editBrgyFieldGroup').hide();
        }
    });

    // Reset Add modal on open
    $('#addModal').on('show.bs.modal', function (e) {
        if (isEntityScopedAdmin) {
            $('#category').val(isBrgyScopedAdmin ? 'BRGY' : 'DEPT');
            $('#deptGroup, #brgyGroup').hide();
            return;
        }
        $('#category').val('').trigger('change');
    });

    $('#addModal').on('hidden.bs.modal', function (e) {
        if (typeof quillContent !== 'undefined' && quillContent) {
            quillContent.setContents([]);
        }
    });

    $('#editModal').on('show.bs.modal', function (e) {
        $('#category').val('').trigger('change');
    });

    // Quill toolbar options
    var quillToolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'align': [] }],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        ['link'],
        ['clean']
    ];

    // Add Modal Quill editor
    var quillContent;
    // Initialize on 'show' (before animation) so Quill is ready immediately
    $('#addModal').on('show.bs.modal', function () {
        if (!quillContent) {
            quillContent = new Quill('#quillContent', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
        }
    });

    // Edit Modal Quill editor
    var editQuillContent;
    $('#editModal').on('show.bs.modal', function () {
        if (!editQuillContent) {
            editQuillContent = new Quill('#editQuillContent', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
        }
    });

    // Save new service
    $('#btnAdd').on('click', function () {
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Read Quill content directly from the instance and set it in FormData.
        // Do NOT rely on the hidden #content input — it may be empty if the
        // input was not explicitly set before FormData was constructed.
        var quillHtml = (quillContent && quillContent.root)
            ? quillContent.root.innerHTML
            : '';
        // Treat Quill's empty-paragraph state as blank
        if (quillHtml === '<p><br></p>') quillHtml = '';
        formData.set('content', quillHtml);

        // Also update the hidden input so the value is visible in the DOM
        $('#content').val(quillHtml);

        // Only validate the truly required fields (category + service name).
        // Content is optional and handled server-side.
        if (!formData.get('category') || !formData.get('serviceName')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
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
            url: '<?php echo site_url('admin/ajax/create_services'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    if ($('#txtDept')[0] && $('#txtDept')[0].selectize) $('#txtDept')[0].selectize.clear();
                    if ($('#txtBrgy')[0] && $('#txtBrgy')[0].selectize) $('#txtBrgy')[0].selectize.clear();
                    if (quillContent) quillContent.setContents([]);
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
            error: function (xhr, status, error) {
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
                    let res = response.data;
                    $('#editId').val(res.ID);
                    $('#editServiceName').val(res.serv_name);
                    $('#editContent').val(res.content);
                    // Set Quill editor content after initialization
                    $('#editModal').on('shown.bs.modal', function () {
                        if (editQuillContent) editQuillContent.root.innerHTML = res.content || '';
                    });
                    if (isEntityScopedAdmin) {
                        $('#editcategory').val(isBrgyScopedAdmin ? 'BRGY' : 'DEPT');
                        $('#editDeptFieldGroup, #editBrgyFieldGroup').hide();
                    } else if (res.brngy_cont_ID) {
                        $('#editcategory').val('BRGY').trigger('change');
                        $('#editDeptFieldGroup').hide();
                        $('#editBrgyFieldGroup').show();
                        populateBrgyDropdown($('#editBrgy'), res.brngy_cont_ID);
                        $('#editDept').val(null);
                    } else if (res.dept_cont_ID) {
                        $('#editcategory').val('DEPT').trigger('change');
                        $('#editDeptFieldGroup').show();
                        $('#editBrgyFieldGroup').hide();
                        populateDepartmentDropdown($('#editDept'), res.dept_cont_ID);
                        $('#editBrgy').val(null);
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
        let form = $('#editForm')[0];
        let formData = new FormData(form);

        // Read Quill content directly from the instance and set it in FormData.
        var editQuillHtml = (editQuillContent && editQuillContent.root)
            ? editQuillContent.root.innerHTML
            : '';
        if (editQuillHtml === '<p><br></p>') editQuillHtml = '';
        formData.set('editContent', editQuillHtml);
        $('#editContent').val(editQuillHtml);

        // Form validation
        if (!formData.get('editServiceName')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
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
                    { id: servId, 'status': 'ACTIVE' },
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
    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'Service'),
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
                    { id: id, 'status': newStatus },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: statusSuccessText('Service', actionText)
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
                    { id: id },
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
            "data": function (d) {
                d.service_name = $('#service_name').val();
                d.category = $('#searchCategory').val();
                d.brgy = $('#searchBrgy').val();
                d.dept = $('#searchDept').val();
                d.status = $('#status').val();
            },
            "dataSrc": function (json) {
                return json.data;
            }
        },
        initComplete: function () {
            var searchInput = $('#tblservice_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search services...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });
            
            var lengthSelect = $('#tblservice_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
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
            {
                "title": "Category", "data": "brngy_cont_ID", width: '25%',
                "render": function (data, type, row) {
                    if (row.brgy_name === null)
                        return row.dept_name;
                    else
                        return row.brgy_name;
                }
            },
            { "title": "Services", "data": "serv_name" },
            {
                "title": "Content", "data": "content", "className": "dt-head-center dt-body-justify", width: '35%',
                "render": function (data, type, row) {
                    if (!data) return '—';
                    // Strip HTML tags from Quill-generated content for plain-text display in table
                    var tmp = document.createElement('div');
                    tmp.innerHTML = data;
                    var text = tmp.textContent || tmp.innerText || '';
                    return text.length > 120 ? text.substring(0, 120) + '…' : text;
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
                "render": function (data, type, row) {
                    if (userLevel === 'VIEWER') {
                        return `<a class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center" href="#" onclick="edit(${row.ID}); return false;" style="width: 32px; height: 32px; border-radius: 50%;" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>`;
                    }
                    let actionHtml = `
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
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
                    actionHtml += renderDeleteAction(userLevel, row.ID, 'deleteService');

                    actionHtml += `</ul></div>`;
                    return actionHtml;
                }
            }
        ]
    });
    var sltdRow = null;

    $('#tblservice tbody').on('mouseover', 'tr', function () {
        sltdRow = tbl.row(this).data();
    });

    // Search filter functionality
    $('#searchCategory').on('change', function () {
        var selectedCategory = $(this).val();
        $('#searchDeptGroup, #searchBrgyGroup, #searchDefaultGroup').hide();
        if (selectedCategory === 'BARANGAY') {
            $('#searchBrgyGroup').show();
            populateBrgyDropdown($('#searchBrgy'));
        } else if (selectedCategory === 'DEPARTMENT') {
            $('#searchDeptGroup').show();
            populateDepartmentDropdown($('#searchDept'));
        } else {
            $('#searchDefaultGroup').show();
        }
    });

    // Submit handler for search form
    $('#serviceSearchForm').on('submit', function (e) {
        e.preventDefault();

        const searchBtn = $(this).find('button[type="submit"]');
        const originalBtnText = searchBtn.html();
        searchBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...');

        tbl.ajax.reload(function () {
            searchBtn.html(originalBtnText);
        });
    });

    // Reset handler
    $('#serviceSearchForm').on('reset', function () {
        $('#searchDeptGroup, #searchBrgyGroup').hide();
        $('#searchDefaultGroup').show();
        $('#searchCategory').val('').trigger('change');

        if ($('#searchBrgy')[0].selectize) $('#searchBrgy')[0].selectize.clear();
        if ($('#searchDept')[0].selectize) $('#searchDept')[0].selectize.clear();

        $('#service_name').val('');
        $('#status').val('');

        tbl.ajax.url('<?php echo base_url('admin/ajax/get_services'); ?>').load();

        return false;
    });

    // Reusable function for both search mechanisms
    function reloadTableWithFilters(filters, searchBtn) {
        const originalBtnText = searchBtn.html();
        searchBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Searching...');

        let formData = new FormData();
        for (const key in filters) {
            if (filters[key]) {
                formData.append(key, filters[key]);
            }
        }

        tbl.ajax.url('<?php echo base_url('admin/ajax/get_services'); ?>')
            .data(formData)
            .load(function (json) {
                searchBtn.html(originalBtnText);
                console.log("Filtered results:", json.data);
            }, function (xhr, status, error) {
                searchBtn.html(originalBtnText);
                console.error("Search error:", status, error);
            });
    }
</script>