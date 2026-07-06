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

    // Toggle Status function
    function toggleStatus(id, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'Department Content'),
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
                    { id: id, 'status': newStatus },
                    function (result) {
                        if (result.status == 1) {
                            deptTable.ajax.reload(null, false);
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
                    { id: id },
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
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        ['link'],
        ['clean']
    ];

    var deptState = {
        mode: 'add',
        record: null
    };

    var deptQuills = {
        about: null,
        mission: null,
        vision: null,
        policy: null,
        contact: null
    };

    function initDeptQuills() {
        if (!deptQuills.about) {
            deptQuills.about = new Quill('#quillAbout', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
        }
        if (!deptQuills.mission) {
            deptQuills.mission = new Quill('#quillMission', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
        }
        if (!deptQuills.vision) {
            deptQuills.vision = new Quill('#quillVision', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
        }
        if (!deptQuills.policy) {
            deptQuills.policy = new Quill('#quillPolicy', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
        }
        if (!deptQuills.contact) {
            deptQuills.contact = new Quill('#quillContact', { theme: 'snow', modules: { toolbar: quillToolbarOptions } });
        }
    }

    function setDeptQuillContents(record) {
        if (!record) {
            return;
        }
        if (deptQuills.about) deptQuills.about.root.innerHTML = record.about || '';
        if (deptQuills.mission) deptQuills.mission.root.innerHTML = record.mission || '';
        if (deptQuills.vision) deptQuills.vision.root.innerHTML = record.vision || '';
        if (deptQuills.policy) deptQuills.policy.root.innerHTML = record.quality_policy || '';
        if (deptQuills.contact) deptQuills.contact.root.innerHTML = record.contact || '';
    }

    function clearDeptQuillContents() {
        if (deptQuills.about) deptQuills.about.setContents([]);
        if (deptQuills.mission) deptQuills.mission.setContents([]);
        if (deptQuills.vision) deptQuills.vision.setContents([]);
        if (deptQuills.policy) deptQuills.policy.setContents([]);
        if (deptQuills.contact) deptQuills.contact.setContents([]);
        $('#txtAbout').val('');
        $('#txtMission').val('');
        $('#txtVision').val('');
        $('#txtPolicy').val('');
        $('#txtContact').val('');
    }

    function syncDeptHiddenFields() {
        if (deptQuills.about && deptQuills.mission && deptQuills.vision && deptQuills.policy && deptQuills.contact) {
            $('#txtAbout').val(deptQuills.about.root.innerHTML);
            $('#txtMission').val(deptQuills.mission.root.innerHTML);
            $('#txtVision').val(deptQuills.vision.root.innerHTML);
            $('#txtPolicy').val(deptQuills.policy.root.innerHTML);
            $('#txtContact').val(deptQuills.contact.root.innerHTML);
        }
    }

    function resetDeptModalState() {
        $('#addForm')[0].reset();
        $('#deptId').val('');
        $('#deptMode').val('add');
        $('#deptModalTitle').text('Add Department Details');
        $('#btnDeptSave').text('Save');
        $('#deptImg').prop('required', true);
        $('#deptOrgChart').prop('required', false);
        $('#addDeptLogoPreview').html('');
        $('#addDeptOrgChartPreview').html('');
        deptState.mode = 'add';
        deptState.record = null;
        clearDeptQuillContents();
    }

    function renderDeptImagePreview($preview, file, fallbackHtml) {
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $preview.html('<img src="' + e.target.result + '" style="max-width: 120px; margin-top: 5px;">');
            };
            reader.readAsDataURL(file);
            return;
        }

        $preview.html(fallbackHtml || '');
    }

    function openDeptModal(mode, record) {
        resetDeptModalState();
        deptState.mode = mode;
        deptState.record = record || null;
        $('#deptMode').val(mode);

        if (mode === 'edit' && record) {
            $('#deptModalTitle').text('Edit Department Details');
            $('#btnDeptSave').text('Update');
            $('#deptId').val(record.ID || record.id || '');
            $('#txtDept').val(record.dept_name || '');
            $('#txtHead').val(record.head || '');
            // Set hidden inputs (used for validation checks)
            $('#txtAbout').val(record.about || '');
            $('#txtContact').val(record.contact || '');
            $('#txtMission').val(record.mission || '');
            $('#txtVision').val(record.vision || '');
            $('#txtPolicy').val(record.quality_policy || '');
            $('#deptImg').prop('required', false);
            $('#deptOrgChart').prop('required', false);
            // Populate visible Quill editors with existing content
            initDeptQuills();
            setDeptQuillContents(record);
        }

        $('#addModal').modal('show');
    }

    function isValidDeptImage(file) {
        if (!file || file.size === 0) {
            return false;
        }

        const maxImageSizeMB = 4;
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (file.size > maxImageSizeMB * 1024 * 1024) {
            return 'size';
        }
        if (!validImageTypes.includes(file.type)) {
            return 'type';
        }
        return true;
    }

    $('#addModal').on('shown.bs.modal', function () {
        initDeptQuills();
        setDeptQuillContents(deptState.record);

        $('#deptImg').off('change').on('change', function () {
            renderDeptImagePreview($('#addDeptLogoPreview'), this.files[0]);
        });

        $('#deptOrgChart').off('change').on('change', function () {
            renderDeptImagePreview($('#addDeptOrgChartPreview'), this.files[0]);
        });
    });

    $('#addModal').on('hidden.bs.modal', function () {
        resetDeptModalState();
    });

    function submitDeptForm() {
        initDeptQuills();
        syncDeptHiddenFields();

        const mode = ($('#deptMode').val() || 'add').toLowerCase();
        const form = $('#addForm')[0];
        const formData = new FormData(form);
        const logoFile = formData.get('deptImg');
        const orgChartFile = formData.get('deptOrgChart');

        formData.set('id', $('#deptId').val());

        if (!formData.get('txtDept') || !formData.get('txtHead') || !formData.get('txtMission') || !formData.get('txtVision') || !formData.get('txtPolicy') || !formData.get('txtContact')) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }

        if (!deptQuills.about.root.innerHTML || deptQuills.about.root.innerHTML.trim() === '' || deptQuills.about.root.innerHTML === '<p><br></p>' ||
            !deptQuills.mission.root.innerHTML || deptQuills.mission.root.innerHTML.trim() === '' || deptQuills.mission.root.innerHTML === '<p><br></p>' ||
            !deptQuills.vision.root.innerHTML || deptQuills.vision.root.innerHTML.trim() === '' || deptQuills.vision.root.innerHTML === '<p><br></p>' ||
            !deptQuills.policy.root.innerHTML || deptQuills.policy.root.innerHTML.trim() === '' || deptQuills.policy.root.innerHTML === '<p><br></p>' ||
            !deptQuills.contact.root.innerHTML || deptQuills.contact.root.innerHTML.trim() === '' || deptQuills.contact.root.innerHTML === '<p><br></p>') {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please fill in all required fields.'
            });
            return;
        }

        if (mode === 'add' && (!logoFile || logoFile.size === 0)) {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please upload a department logo.'
            });
            return;
        }

        if (logoFile && logoFile.size > 0) {
            const logoValidation = isValidDeptImage(logoFile);
            if (logoValidation === 'size') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Image size should not exceed 4 MB.'
                });
                return;
            }
            if (logoValidation === 'type') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload a valid image file (jpg, png, gif).'
                });
                return;
            }
        }

        if (orgChartFile && orgChartFile.size > 0) {
            const orgValidation = isValidDeptImage(orgChartFile);
            if (orgValidation === 'size') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Image size should not exceed 4 MB.'
                });
                return;
            }
            if (orgValidation === 'type') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please upload a valid image file (jpg, png, gif).'
                });
                return;
            }
        }

        const url = mode === 'edit'
            ? '<?php echo site_url('admin/ajax/update_dept'); ?>'
            : '<?php echo site_url('admin/ajax/create_dept'); ?>';

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
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 1) {
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || (mode === 'edit' ? 'Department updated successfully.' : 'Department data saved!')
                    });
                    deptTable.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Request failed.'
                    });
                }
            },
            error: function (xhr, statusText, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseText || errorThrown || statusText || 'Unable to process the department request.'
                });
            }
        });
    }

    $('#addForm').on('submit', function (e) {
        e.preventDefault();
        submitDeptForm();
    });

    function edit(deptId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_dept'); ?>',
            method: 'POST',
            data: { id: deptId },
            success: function (response) {
                if (response.status === 1) {
                    openDeptModal('edit', response.data);
                    $('#addDeptLogoPreview').html(
                        response.data.img_logo
                            ? `<img src="<?php echo base_url('admin/image/DEPT/') ?>${response.data.img_logo}" alt="Current Department Logo" style="max-width: 120px; margin-top: 5px;">`
                            : '<small>No logo available.</small>'
                    );
                    $('#addDeptOrgChartPreview').html(
                        response.data.org_chart_img
                            ? `<img src="<?php echo base_url('admin/image/DEPT/') ?>${response.data.org_chart_img}" alt="Current Organizational Chart" style="max-width: 120px; margin-top: 5px;">`
                            : '<small>No org chart available.</small>'
                    );
                    $('#deptImg').val('');
                    $('#deptOrgChart').val('');
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
            dataSrc: function (json) {
                return json.data || [];
            }
        },
        initComplete: function () {
            var searchInput = $('#tbldept_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search departments...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });
            
            var lengthSelect = $('#tbldept_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
        },
        columns: [
            { "title": "Department ID", "data": "ID", "visible": false },
            { "title": "Dept. Name", "data": "dept_name", width: '30%' },
            {
                "title": "Logo",
                "data": "img_logo",
                "className": "dt-center dept-logo-cell",
                "render": function (data, type, row) {
                    if (!data) {
                        return '<div class="dept-logo-thumb"><small class="text-muted">No logo</small></div>';
                    }
                    return '<div class="dept-logo-thumb"><img id="img_logo" src="<?php echo base_url('admin/image/DEPT/') ?>' + data + '" alt="Department logo"></div>';
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
                        return '<span class="status-badge status-badge-active"><span class="status-dot status-dot-active"></span>Active</span>';
                    } else if (data == 'INACTIVE') {
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
                    let actionHtml = `
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                            <i class="bi bi-list"></i> Actions
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="edit(${row.ID}); return false;"><i class="bi bi-pencil me-1"></i> Edit</a></li>`;

                    actionHtml += renderStatusToggleAction(userLevel, row, 'toggleStatus');
                    actionHtml += `</ul></div>`;
                    return actionHtml;
                }
            }
        ]
    });

    var sltdRow = null;

    $('#tbldept tbody').on('mouseover', 'tr', function () {
        sltdRow = deptTable.row(this).data();
    });

    // Attach a submit handler to the form
    $('#departmentSearchForm').on('submit', function (e) {
        e.preventDefault(); // stop page reload
        applyDeptFilters(); // run your search logic
    });

    // Optional: Clear Filters button
    $('#departmentSearchForm button[type="reset"]').on('click', function () {
        // reset form fields
        $('#departmentSearchForm')[0].reset();
    });


    function applyDeptFilters() {
        var searchTerm = $('#searchDept').val().trim().toLowerCase();
        var statusFilter = $('select[name="deptStatus"]').val();

        // Custom filtering function for combined search
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
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
    $('#departmentSearchForm button[type="reset"]').click(function () {
        $('#departmentSearchForm')[0].reset();
        deptTable.search('').columns().search('').draw();
    });
</script>
