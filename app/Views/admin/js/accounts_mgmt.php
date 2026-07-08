<!-- js/accounts_mgmt.php -->
<script>
    const userLevel = "<?= $user->user_lvl ?>".toUpperCase();
    const phpAccType = "<?= $user->account_type ?? '' ?>".toUpperCase();

    // ── Name formatting helpers ────────────────────────────────────────────
    function toTitleCase(str) {
        if (!str) return '';
        return str.replace(/\w\S*/g, function (word) {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        });
    }

    function toPascalName(fname, mname, lname, suffix) {
        var last = toTitleCase(lname || '');
        var first = toTitleCase(fname || '');
        var mid = toTitleCase(mname || '');
        var suf = toTitleCase(suffix || '');
        var full = last + ', ' + first;
        if (mid) full += ' ' + mid;
        if (suf) full += ' ' + suf;
        return full;
    }

    // ── Auto Title-Case name inputs ───────────────────────────────────────
    $(document).on('input blur', '#txtFirstName, #txtMiddleName, #txtLastName', function () {
        var el = this;
        var start = el.selectionStart;
        var end = el.selectionEnd;
        el.value = toTitleCase(el.value);
        if (el.setSelectionRange) { el.setSelectionRange(start, end); }
    });

    // ── Show/hide Add button ───────────────────────────────────────────────
    if (userLevel === 'DEVELOPER' || userLevel === 'SUPERADMIN' || userLevel === 'ADMIN') {
        $('.button-32').show();
    } else {
        $('.button-32').hide();
    }

    if (userLevel === 'VIEWER') {
        $('[onclick="openUserModal(\'add\')"]').hide();

        $(document).on('show.bs.modal', '.modal', function () {
            var $modal = $(this);
            $modal.find('input, select, textarea, button').prop('disabled', true);
            $modal.find('button[data-bs-dismiss="modal"], .btn-close, a[data-bs-dismiss="modal"]').prop('disabled', false);
            $modal.find('button, input[type="submit"], input[type="button"], a.btn').not('[data-bs-dismiss="modal"], .btn-close').hide();
            $modal.find('input[type="file"]').hide();
        });

        $(document).on('show.bs.modal shown.bs.modal', '.modal', function () {
            var $modal = $(this);
            $modal.find('.ql-editor').attr('contenteditable', 'false');
            $modal.find('.ql-toolbar').hide();
            setTimeout(function () {
                $modal.find('.ql-editor').attr('contenteditable', 'false');
                $modal.find('.ql-toolbar').hide();
            }, 100);
            setTimeout(function () {
                $modal.find('.ql-editor').attr('contenteditable', 'false');
                $modal.find('.ql-toolbar').hide();
            }, 500);
        });
    }

    // ── Roles that never need Account Type ──────────────────────────────
    const HIGH_PRIV = ['DEVELOPER', 'SUPERADMIN'];

    function toggleAccountTypeRow(role) {
        if (HIGH_PRIV.includes(role.toUpperCase())) {
            $('#accountTypeRow').hide();
        } else {
            $('#accountTypeRow').show();
        }
    }

    // ── Account Level change ─────────────────────────────────────────────
    $('#txtAccLevel').on('change', function () {
        toggleAccountTypeRow($(this).val());
        if (!HIGH_PRIV.includes($(this).val().toUpperCase())) {
            loadEntityOptions($('#txtAccountType').val(), '#txtEntityRef', '#entityRefHint', null);
        }
    });

    // ── Entity Ref helpers ────────────────────────────────────────────────
    function loadEntityOptions(type, selectId, hintId, currentVal) {
        var $raw = $(selectId);
        var $hint = $(hintId);

        if ($raw[0] && $raw[0].selectize) {
            $raw[0].selectize.destroy();
        }

        $raw.empty().append('<option value="" disabled selected>Select department / barangay</option>');

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

            $raw.selectize({
                sortField: 'text',
                searchField: 'text',
                placeholder: 'Select department / barangay',
                allowEmptyOption: true,
                allowClear: true,
                onInitialize: function () {
                    if (currentVal) {
                        this.setValue(String(currentVal));
                    } else {
                        this.clear(true);
                    }
                }
            });
        }, 'json').fail(function () {
            $raw.empty().append('<option value="" disabled selected>Failed to load. Please retry.</option>');
        });
    }

    // ── Account Type change ───────────────────────────────────────────────
    $('#txtAccountType').on('change', function () {
        loadEntityOptions($(this).val(), '#txtEntityRef', '#entityRefHint', null);
    });

    // ── Shared modal open helper ──────────────────────────────────────────
    function openUserModal(mode, record) {
        $('#userMode').val(mode);

        if (mode === 'add') {
            $('#userModalTitle').text('Add Account');
            $('#userModalIcon').attr('class', 'bi bi-person-plus');
            $('#btnAdd').text('Save');
            $('#userRecordId').val('');
            $('#addForm')[0].reset();

            // Show password as required
            $('#passwordRequiredStar').show();
            $('#passwordOptionalHint').hide();
            $('#txtPassword').attr('required', true);

            // Populate Account Level options
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
                $('#txtAccountType').val('DEPARTMENT');
                $('#txtEntityRef').val('');
                loadEntityOptions('DEPARTMENT', '#txtEntityRef', '#entityRefHint', null);
                $('#accountTypeRow').show();
            }

        } else {
            // edit mode
            $('#userModalTitle').text('Modify Account');
            $('#userModalIcon').attr('class', 'bi bi-pencil-square');
            $('#btnAdd').text('Update');
            $('#userRecordId').val(record.ID);

            $('#txtFirstName').val(toTitleCase(record.fname));
            $('#txtMiddleName').val(toTitleCase(record.mname || ''));
            $('#txtLastName').val(toTitleCase(record.lname));
            $('#txtSuffix').val(record.suffix || '');
            $('#txtUsername').val(record.username);
            $('#txtEmail').val(record.email);
            $('#txtPassword').val('');

            // Password optional in edit mode
            $('#passwordRequiredStar').hide();
            $('#passwordOptionalHint').show();
            $('#txtPassword').removeAttr('required');

            // Populate Account Level dropdown
            var targetRole = (record.user_lvl || '').toUpperCase();
            var $editLvl = $('#txtAccLevel');
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
            $editLvl.val(record.user_lvl);

            // Account Type row visibility
            var canEditAccountType = (userLevel === 'SUPERADMIN' || userLevel === 'DEVELOPER')
                || (userLevel === 'ADMIN' && !['ADMIN', 'SUPERADMIN', 'DEVELOPER'].includes(targetRole));

            if (canEditAccountType && !HIGH_PRIV.includes(targetRole)) {
                var dispType = record.account_type || 'DEPARTMENT';
                $('#txtAccountType').val(dispType);
                loadEntityOptions(dispType, '#txtEntityRef', '#entityRefHint', record.entity_ref_id);
                $('#accountTypeRow').show();
            } else {
                $('#accountTypeRow').hide();
            }
        }

        $('#addModal').modal('show');
    }

    // ── Unified submit handler ────────────────────────────────────────────
    $('#btnAdd').on('click', function () {
        var mode = $('#userMode').val();
        var form = $('#addForm')[0];
        var formData = new FormData(form);

        // Explicitly set ID for edit
        if (mode === 'edit') {
            formData.set('id', $('#userRecordId').val());
        }

        // Read Selectize value
        var entityRefEl = document.getElementById('txtEntityRef');
        if (entityRefEl && entityRefEl.selectize) {
            formData.set('txtEntityRef', entityRefEl.selectize.getValue() || '');
        }

        var entityTypeEl = document.getElementById('txtAccountType');
        if (entityTypeEl) {
            formData.set('txtAccountType', entityTypeEl.value || '');
        }

        // Validation
        var basicFields = [
            { name: 'txtFirstName', label: 'First Name' },
            { name: 'txtMiddleName', label: 'Middle Name' },
            { name: 'txtLastName', label: 'Last Name' },
            { name: 'txtUsername', label: 'Username' },
            { name: 'txtEmail', label: 'Email' },
            { name: 'txtAccLevel', label: 'Account Level' }
        ];
        for (var f of basicFields) {
            if (!formData.get(f.name)) {
                Swal.fire('Validation Error', f.label + ' is required', 'warning');
                return;
            }
        }

        // Password required only for add
        if (mode === 'add' && !formData.get('txtPassword')) {
            Swal.fire('Validation Error', 'Password is required', 'warning');
            return;
        }

        var acctType = formData.get('txtAccountType');
        var acctLevel = formData.get('txtAccLevel') || '';
        var needsEntity = !HIGH_PRIV.includes(acctLevel.toUpperCase())
            && userLevel !== 'ADMIN'
            && ['DEPARTMENT', 'BARANGAY'].includes(acctType)
            && ($('#accountTypeRow').is(':visible'));
        if (needsEntity && !formData.get('txtEntityRef')) {
            Swal.fire('Validation Error', 'Please select a Linked Entity.', 'warning');
            return;
        }

        var url = mode === 'edit'
            ? '<?php echo site_url('admin/ajax/update_user'); ?>'
            : '<?php echo site_url('admin/ajax/create_user'); ?>';

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
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.status == 1) {
                    $('#addModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message || (mode === 'edit' ? 'User updated successfully.' : 'User account created!')
                    });
                    tbl.ajax.reload(null, false);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: result.message || 'Operation failed.' });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred. Please try again.' });
            }
        });
    });

    // ── Fetch record then open modal in edit mode ─────────────────────────
    function edit(userId) {
        $.ajax({
            url: '<?php echo site_url('admin/ajax/get_users'); ?>',
            method: 'POST',
            data: { id: userId },
            success: function (response) {
                if (response.data) {
                    var rec = response.data;
                    // Normalize ID
                    if (!rec.ID && rec.id) rec.ID = rec.id;
                    openUserModal('edit', rec);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'User not found.' });
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Unable to fetch user details.' });
            }
        });
    }

    // ── Reset modal state on close ────────────────────────────────────────
    $('#addModal').on('hidden.bs.modal', function () {
        $('#addForm')[0].reset();
        $('#userRecordId').val('');
        $('#userMode').val('add');
        $('#userModalTitle').text('Add Account');
        $('#userModalIcon').attr('class', 'bi bi-person-plus');
        $('#btnAdd').text('Save');
        $('#passwordRequiredStar').show();
        $('#passwordOptionalHint').hide();
        $('#txtPassword').attr('required', true);
        $('#accountTypeRow').hide();

        // Destroy any Selectize on close
        var entityRefEl = document.getElementById('txtEntityRef');
        if (entityRefEl && entityRefEl.selectize) {
            entityRefEl.selectize.destroy();
        }
    });

    // ── toggleStatus ──────────────────────────────────────────────────────
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

    // ── reset_password ────────────────────────────────────────────────────
    function reset_password(userId, fullName) {
        Swal.fire({
            title: 'Reset Password',
            text: `Reset password for ${fullName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            confirmButtonColor: '#27ae60',
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
                                    `Temporary Password: <b style="font-size: 1.25rem; color: #1b4d3e; letter-spacing: 1px;">${result.message}</b><br><br>` +
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

    // ── DataTable ─────────────────────────────────────────────────────────
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
            {
                title: 'Name', data: 'fname',
                className: 'dt-head-center dt-body-justify d-flex justify-content-start', width: '15%',
                render: function (data, type, row) {
                    return toPascalName(row.fname, row.mname, row.lname, row.suffix);
                }
            },
            {
                title: "Title",
                data: "username",
                className: "align-middle",
                render: function (data) {
                    return '<div class="d-flex justify-content-start">' + data + '</div>';
                }
            },
            { title: 'Account Level', data: 'user_lvl', 'className': 'align-middle' },
            {
                title: 'Account Type', data: 'account_type', className: 'dt-center align-middle', defaultContent: 'System',
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
            { title: 'Email', data: 'email', visible: false },
            {
                title: 'Status', data: 'status', className: 'dt-center align-middle', width: '10%',
                render: function (data) {
                    if (data === 'ACTIVE') return '<span class="status-badge status-badge-active"><span class="status-dot status-dot-active"></span>Active</span>';
                    if (data === 'INACTIVE') return '<span class="status-badge status-badge-inactive"><span class="status-dot status-dot-inactive"></span>Inactive</span>';
                    return '<span class="status-badge status-badge-archived"><span class="status-dot status-dot-archived"></span>Archived</span>';
                }
            },
            {
                title: 'Actions', data: 'ID', className: 'dt-center align-middle',
                render: function (data, type, row) {
                    if (userLevel === 'VIEWER') {
                        return `<a class="btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center" href="#" onclick="edit(${row.ID}); return false;" style="width: 32px; height: 32px; border-radius: 50%;" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>`;
                    }

                    let html = `
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                            <i class="bi bi-list"></i> Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" onclick="edit(${row.ID}); return false;">
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
                                    onclick="reset_password(${row.ID}, \`${toPascalName(row.fname, row.mname, row.lname, row.suffix)}\`)">
                                    <i class="bi bi-shield-lock me-1"></i> Reset Password
                                </a>
                            </li>`;
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
            searchInput.css({ 'width': '250px', 'margin-left': '0.5rem' });

            var lengthSelect = $('#tbluser_length select');
            lengthSelect.addClass('form-select form-select-sm d-inline-block');
            lengthSelect.css({ 'width': 'auto', 'margin': '0 0.5rem' });
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