<!-- js/accounts_mgmt.php -->
<script>
    const userLevel = "<?= $user->user_lvl ?>".toUpperCase();
    const phpAccType = "<?= $user->account_type ?? '' ?>".toUpperCase();

    // ── Show/hide Add button ───────────────────────────────────────────────
    if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') {
        $('.button-32').show();
    } else {
        $('.button-32').hide();
    }

    if (userLevel === 'VIEWER') {
        $('input, select, button').prop('disabled', true);
        $('.btn-close').prop('disabled', false);
    }

    // ── Roles that never need Account Type ──────────────────────────────
    const HIGH_PRIV = ['DEVELOPER', 'SUPERADMIN'];

    /**
     * Toggle the Account Type row visibility based on selected role.
     * @param {string} role - selected user_lvl value
     * @param {string} rowId - '#accountTypeRow' or '#editAccountTypeRow'
     */
    function toggleAccountTypeRow(role, rowId) {
        if (HIGH_PRIV.includes(role.toUpperCase())) {
            $(rowId).hide();
        } else {
            $(rowId).show();
        }
    }

    // ── Add modal: hide/show Account Type row when role changes ─────────
    $('#txtAccLevel').on('change', function () {
        toggleAccountTypeRow($(this).val(), '#accountTypeRow');
        // When shown, trigger a load of the currently selected type
        if (!HIGH_PRIV.includes($(this).val().toUpperCase())) {
            loadEntityOptions($('#txtAccountType').val(), '#txtEntityRef', '#entityRefHint', null);
        }
    });

    // ── Edit modal: hide/show Account Type row when role changes ────────
    $('#editAccLevel').on('change', function () {
        toggleAccountTypeRow($(this).val(), '#editAccountTypeRow');
    });

    // ── Entity Ref helpers (with Selectize autofill) ─────────────────────
    /**
     * Populate the entity dropdown and initialise Selectize with search.
     */
    function loadEntityOptions(type, selectId, hintId, currentVal) {
        var $raw = $(selectId);
        var $hint = $(hintId);

        // Destroy existing Selectize before rebuilding
        if ($raw[0] && $raw[0].selectize) {
            $raw[0].selectize.destroy();
        }

        $raw.empty().append('<option value="" disabled selected>Loading\u2026</option>');

        var url, labelKey;
        if (type === 'DEPARTMENT') {
            url = '<?= site_url('admin/ajax/get_dept') ?>';
            labelKey = 'dept_name';
            if ($hint && $hint.length) $hint.text('Select the department this account manages.');
        } else if (type === 'BARANGAY') {
            url = '<?= site_url('admin/ajax/get_barangay') ?>';
            labelKey = 'brgy_name';
            if ($hint && $hint.length) $hint.text('Select the barangay this account manages.');
        } else {
            return;
        }

        // Use $.post — controller reads getPost(), GET would return nothing
        $.post(url, {}, function (res) {
            $raw.empty();
            var items = (res.status === 1 && Array.isArray(res.data)) ? res.data : [];

            if (items.length === 0) {
                $raw.append('<option value="" disabled>No records found</option>');
            } else {
                items.forEach(function (item) {
                    $raw.append(new Option(item[labelKey], item.ID));
                });
            }

            // Init Selectize with search
            $raw.selectize({
                sortField: 'text',
                searchField: 'text',
                placeholder: '\u2014 Type to search \u2014',
                allowClear: true,
                onInitialize: function () {
                    if (currentVal) { this.setValue(String(currentVal)); }
                }
            });
        }, 'json').fail(function () {
            $raw.empty().append('<option value="" disabled>Failed to load. Please retry.</option>');
        });
    }

    // ── Add modal: Account Type change ──────────────────────────────────
    $('#txtAccountType').on('change', function () {
        loadEntityOptions($(this).val(), '#txtEntityRef', '#entityRefHint', null);
    });

    // ── Edit modal: Account Type change ────────────────────────────────
    $('#editAccountType').on('change', function () {
        loadEntityOptions($(this).val(), '#editEntityRef', '#editEntityRefHint', null);
    });

    // ── Initialise on Add modal open ────────────────────────────────────
    $('#addModal').on('show.bs.modal', function () {
        // Reset form
        $('#addForm')[0].reset();

        // Filter Account Level options based on who is logged in
        var $lvl = $('#txtAccLevel');
        $lvl.empty();
        $lvl.append('<option value="" selected disabled>Select Level</option>');

        if (userLevel === 'DEVELOPER') {
            $lvl.append('<option value="SUPERADMIN">Super Admin</option>');
        }
        if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN') {
            $lvl.append('<option value="ADMIN">Admin</option>');
        }
        $lvl.append('<option value="ENCODER">Encoder</option>');
        $lvl.append('<option value="VIEWER">Viewer</option>');

        // For ADMIN: hide Account Type row (entity auto-assigned server-side)
        if (userLevel === 'ADMIN') {
            $('#accountTypeRow').hide();
        } else {
            // Default to DEPARTMENT and load options
            $('#txtAccountType').val('DEPARTMENT');
            loadEntityOptions('DEPARTMENT', '#txtEntityRef', '#entityRefHint', null);
            $('#accountTypeRow').show();
        }
    });

    // ── Handle form submission for adding a user ─────────────────────────
    $('#btnAdd').on('click', function () {
        let form = $('#addForm')[0];
        let formData = new FormData(form);

        // Selectize replaces the native <select> with a custom widget.
        // Read the selected value directly from the Selectize API to be safe.
        var txtEntityRefSelectize = document.getElementById('txtEntityRef');
        if (txtEntityRefSelectize && txtEntityRefSelectize.selectize) {
            formData.set('txtEntityRef', txtEntityRefSelectize.selectize.getValue() || '');
        }

        // Basic required fields
        const basicFields = [
            { name: 'txtFirstName', label: 'First Name' },
            { name: 'txtMiddleName', label: 'Middle Name' },
            { name: 'txtLastName', label: 'Last Name' },
            { name: 'txtUsername', label: 'Username' },
            { name: 'txtEmail', label: 'Email' },
            { name: 'txtPassword', label: 'Password' },
            { name: 'txtAccLevel', label: 'Account Level' }
        ];
        for (let f of basicFields) {
            if (!formData.get(f.name)) {
                Swal.fire('Validation Error', `${f.label} is required`, 'warning');
                return;
            }
        }

        // Entity required for DEPT/BRGY account types, but only when Account Type row is visible
        const acctType = formData.get('txtAccountType');
        const acctLevel = formData.get('txtAccLevel') || '';
        const needsEntity = !HIGH_PRIV.includes(acctLevel.toUpperCase())
            && userLevel !== 'ADMIN'
            && ['DEPARTMENT', 'BARANGAY'].includes(acctType);
        if (needsEntity && !formData.get('txtEntityRef')) {
            Swal.fire('Validation Error', 'Please select a Linked Entity.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Please wait\u2026',
            showConfirmButton: false,
            backdrop: true,
            scrollbarPadding: false,
            allowEscapeKey: () => !Swal.isLoading(),
            allowOutsideClick: () => !Swal.isLoading(),
            willOpen: () => { Swal.showLoading(); }
        });

        $.ajax({
            url: '<?php echo site_url('admin/ajax/create_user'); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addForm').trigger('reset');
                    $('#addModal').modal('hide');
                    Swal.fire({ icon: 'success', title: 'Success', text: 'User account created!' });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: result.message || 'Could not create user.' });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
            }
        });
    });

    // ── Open edit modal and populate data ──────────────────────────────
    function edit(userId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_users'); ?>',
            method: 'POST',
            data: { id: userId },
            success: function (response) {
                if (response.data) {
                    let res = response.data;
                    let accType = res.account_type || '';

                    $('#editUserId').val(res.ID);
                    $('#editFirstName').val(res.fname);
                    $('#editMiddleName').val(res.mname || '');
                    $('#editLastName').val(res.lname);
                    $('#editSuffix').val(res.suffix || '');
                    $('#editUsername').val(res.username);
                    $('#editEmail').val(res.email);
                    $('#editPassword').val('');

                    // Set role and toggle account type row
                    var targetRole = (res.user_lvl || '').toUpperCase();

                    // Filter edit Account Level dropdown
                    var $editLvl = $('#editAccLevel');
                    $editLvl.empty();
                    $editLvl.append('<option value="" disabled>Select Level</option>');
                    if (userLevel === 'DEVELOPER') {
                        $editLvl.append('<option value="SUPERADMIN">Super Admin</option>');
                    }
                    if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN') {
                        $editLvl.append('<option value="ADMIN">Admin</option>');
                    }
                    $editLvl.append('<option value="ENCODER">Encoder</option>');
                    $editLvl.append('<option value="VIEWER">Viewer</option>');
                    $editLvl.val(res.user_lvl);

                    // Account Type row visibility:
                    // Admin editing another Admin → hide; SuperAdmin/Dev → always show; Admin editing Enc/Viewer → show
                    var canEditAccountType = (userLevel === 'SUPERADMIN' || userLevel === 'DEVELOPER')
                        || (userLevel === 'ADMIN' && !['ADMIN', 'SUPERADMIN', 'DEVELOPER'].includes(targetRole));

                    if (canEditAccountType && !HIGH_PRIV.includes(targetRole)) {
                        var dispType = accType || 'DEPARTMENT';
                        $('#editAccountType').val(dispType);
                        loadEntityOptions(dispType, '#editEntityRef', '#editEntityRefHint', res.entity_ref_id);
                        $('#editAccountTypeRow').show();
                    } else {
                        $('#editAccountTypeRow').hide();
                    }

                    $('#editModal').modal('show');
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'User not found.' });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to fetch user details.' });
            }
        });
    }

    // ── Submit edit user form ────────────────────────────────────────────
    $('#btnEdit').click(function () {
        let form = $('#editForm')[0];
        let formData = new FormData(form);

        // Selectize replaces the native <select> with a custom widget.
        // FormData may not capture the Selectize-managed value reliably,
        // so we read it directly from the Selectize API and override formData.
        var editEntityRefSelectize = document.getElementById('editEntityRef');
        if (editEntityRefSelectize && editEntityRefSelectize.selectize) {
            var selectizeVal = editEntityRefSelectize.selectize.getValue();
            formData.set('editEntityRef', selectizeVal || '');
        }

        // Also ensure editAccountType is captured (it's a plain select, but be safe)
        var editAccountTypeEl = document.getElementById('editAccountType');
        if (editAccountTypeEl) {
            formData.set('editAccountType', editAccountTypeEl.value || '');
        }

        const basicFields = [
            { name: 'editFirstName', label: 'First Name' },
            { name: 'editMiddleName', label: 'Middle Name' },
            { name: 'editLastName', label: 'Last Name' },
            { name: 'editUsername', label: 'Username' },
            { name: 'editEmail', label: 'Email' },
            { name: 'editAccLevel', label: 'Account Level' }
        ];
        for (let f of basicFields) {
            if (!formData.get(f.name)) {
                Swal.fire('Validation Error', `${f.label} is required`, 'warning');
                return;
            }
        }

        const acctType = formData.get('editAccountType');
        if (['DEPARTMENT', 'BARANGAY'].includes(acctType) && !formData.get('editEntityRef')) {
            Swal.fire('Validation Error', 'Please select a Linked Entity.', 'warning');
            return;
        }

        $.ajax({
            url: '<?php echo site_url('admin/ajax/update_user'); ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === 1) {
                    Swal.fire({ icon: 'success', title: 'Success', text: response.message })
                        .then(() => {
                            $('#editModal').modal('hide');
                            tbl.ajax.reload();
                        });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
            }
        });
    });

    function toggleStatus(userId, currentStatus, forcedStatus) {
        var newStatus = nextRecordStatus(currentStatus, forcedStatus);
        var actionText = statusActionText(newStatus);

        Swal.fire({
            heightAuto: false,
            title: statusActionTitle(newStatus, 'User'),
            text: 'Are you sure you want to ' + actionText + ' this user?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#27ae60',
            cancelButtonColor: '#c0392b',
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Please wait\u2026', showConfirmButton: false, willOpen: () => Swal.showLoading() });
                $.post('<?php echo site_url('admin/ajax/set_status_user') ?>',
                    { id: userId, status: newStatus },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({ icon: 'success', title: 'Done', text: statusSuccessText('User', actionText) });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: result.message });
                        }
                    }
                );
            }
        });
    }

    function reset_password(userId, fullName) {
        Swal.fire({
            title: 'Reset Password',
            text: `Reset password for ${fullName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reset',
            confirmButtonColor: '#e67e22',
            cancelButtonColor: '#7f8c8d',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Please wait\u2026', showConfirmButton: false, willOpen: () => Swal.showLoading() });
                $.post('<?php echo site_url('admin/ajax/reset_password') ?>',
                    { id: userId },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Password Reset',
                                html: `The password for <b>${fullName}</b> has been reset.<br><br>` +
                                    `Temporary Password: <b style="font-size: 1.25rem; color: #d35400; letter-spacing: 1px;">${result.message}</b><br><br>` +
                                    `<small class="text-muted">An email has also been sent to the user.</small>`
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: result.message });
                        }
                    }
                );
            }
        });
    }

    function del(userId) {
        Swal.fire({
            title: 'Delete User',
            text: 'This will permanently delete the user account.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c0392b',
            cancelButtonColor: '#7f8c8d',
            confirmButtonText: 'Yes, Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting\u2026', showConfirmButton: false, willOpen: () => Swal.showLoading() });
                $.post('<?php echo site_url('admin/ajax/set_status_user') ?>',
                    { id: userId, status: 'DELETED' },
                    function (result) {
                        if (result.status == 1) {
                            tbl.ajax.reload(null, false);
                            Swal.fire({ icon: 'success', title: 'Deleted', text: 'User account deleted.' });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: result.message });
                        }
                    }
                );
            }
        });
    }

    // ── DataTable ────────────────────────────────────────────────────────
    var tbl = $('#tbluser').DataTable({
        select: false,
        searching: true,
        ordering: true,
        order: [],
        pageLength: 10,
        processing: true,
        ajax: {
            url: '<?php echo base_url('admin/ajax/get_users'); ?>',
            type: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            },
            data: function (d) {
                d.searchUser = $('#searchUser').val();
                d.searchStatus = $('#searchStatus').val();
                d.searchUserLevel = $('#searchUserLevel').val();
            },
            dataSrc: function (json) {
                if (json.status === 1 && Array.isArray(json.data)) return json.data;
                return [];
            }
        },
        columns: [
            { title: 'ID', data: 'ID', visible: false },
            { title: 'Username', data: 'username' },
            {
                title: 'Name', data: 'fname',
                className: 'dt-head-center dt-body-justify', width: '15%',
                render: function (data, type, row) { 
                    let fullName = row.fname;
                    if (row.mname) fullName += ' ' + row.mname;
                    fullName += ' ' + row.lname;
                    if (row.suffix) fullName += ' ' + row.suffix;
                    return fullName;
                }
            },
            { title: 'Email', data: 'email' },
            { title: 'User Level', data: 'user_lvl' },
            {
                title: 'Account Type', data: 'account_type', className: 'dt-center', defaultContent: 'System',
                render: function (data) {
                    if (data === 'DEPARTMENT') {
                        return '<span class="status-badge acctype-badge-dept"><span class="status-dot acctype-dot-dept"></span>Department</span>';
                    }
                    if (data === 'BARANGAY') {
                        return '<span class="status-badge acctype-badge-brgy"><span class="status-dot acctype-dot-brgy"></span>Barangay</span>';
                    }
                    return '<span class="status-badge acctype-badge-system"><span class="status-dot acctype-dot-system"></span>System</span>';
                }
            },
            {
                title: 'Status', data: 'status', className: 'dt-center', width: '10%',
                render: function (data) {
                    if (data === 'ACTIVE') return '<span class="status-badge status-badge-active"><span class="status-dot status-dot-active"></span>Active</span>';
                    if (data === 'INACTIVE') return '<span class="status-badge status-badge-inactive"><span class="status-dot status-dot-inactive"></span>Inactive</span>';
                    return '<span class="status-badge status-badge-archived"><span class="status-dot status-dot-archived"></span>Archived</span>';
                }
            },
            {
                title: 'Actions', data: 'ID', className: 'dt-center',
                visible: userLevel !== 'VIEWER',
                render: function (data, type, row) {
                    if (userLevel === 'VIEWER') return '-';

                    let html = `
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                            <i class="bi bi-list"></i> Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#editModal" onclick="edit(${row.ID})">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                            </li>`;

                    if ((userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') && row.status !== 'ARCHIVED') {
                        var statusIcon = row.status === 'ACTIVE' ? 'bi-toggle-on' : 'bi-toggle-off';
                        var statusText = row.status === 'ACTIVE' ? 'Deactivate' : 'Activate';
                        html += `
                            <li>
                                <a class="dropdown-item" href="#"
                                    onclick="toggleStatus(${row.ID}, '${row.status}')">
                                    <i class="bi ${statusIcon} me-1"></i> ${statusText}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    onclick="reset_password(${row.ID}, \`${row.fname} ${row.lname}\`)">
                                    <i class="bi bi-shield-lock me-1"></i> Reset Password
                                </a>
                            </li>`;
                    }
                    if (row.status === 'ARCHIVED' && adminCanRestore(userLevel)) {
                        html += `<li><a class="dropdown-item" href="#" onclick="toggleStatus(${row.ID}, '${row.status}', 'ACTIVE')"><i class="bi bi-arrow-counterclockwise me-1"></i> Restore</a></li>`;
                    } else if (row.status !== 'ARCHIVED' && adminCanArchive(userLevel)) {
                        html += `<li><a class="dropdown-item text-warning" href="#" onclick="toggleStatus(${row.ID}, '${row.status}', 'ARCHIVED')"><i class="bi bi-archive me-1"></i> Archive</a></li>`;
                    }
                    if (adminCanDelete(userLevel)) {
                        html += `<li><hr class="dropdown-divider"></li><li><a class="dropdown-item text-danger" href="#" onclick="del(${row.ID})"><i class="bi bi-trash me-1"></i> Delete User</a></li>`;
                    }

                    html += `</ul></div>`;
                    return html;
                }
            }
        ],
        initComplete: function () {
            var searchInput = $('#tbluser_filter input[type="search"]');
            searchInput.attr('placeholder', 'Search accounts...');
            searchInput.addClass('form-control form-control-sm d-inline-block');
            searchInput.css({
                'width': '250px',
                'margin-left': '0.5rem'
            });
            
            var lengthSelect = $('#tbluser_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({
                'width': 'auto',
                'margin': '0 0.5rem'
            });
        }
    });

    var sltdRow = null;
    $('#tbluser tbody').on('mouseover', 'tr', function () { sltdRow = tbl.row(this).data(); });

    $('#userSearchForm').on('submit', function (e) { e.preventDefault(); tbl.ajax.reload(); });
    $('#userSearchForm button[type="reset"]').on('click', function () {
        $('#userSearchForm')[0].reset();
        tbl.ajax.reload();
    });

</script>
