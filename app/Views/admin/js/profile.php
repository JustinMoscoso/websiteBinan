<script>
    const userLevel = '<?= $user->user_lvl ?>'.toUpperCase();

    // ── Name formatting helpers ─────────────────────────────────────────────
    function toTitleCase(str) {
        if (!str) return '';
        return str.replace(/\w\S*/g, function (word) {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        });
    }

    function toPascalName(fname, mname, lname, suffix) {
        var last  = toTitleCase(lname  || '');
        var first = toTitleCase(fname  || '');
        var mid   = toTitleCase(mname  || '');
        var suf   = (suffix || '').trim();
        var full  = last + ', ' + first;
        if (mid) full += ' ' + mid;
        if (suf) full += ' ' + suf;
        return full;
    }

    // ── Real-time Title-Case on name inputs in editNameModal ───────────────
    $(document).on('input blur', '#dlgFirstName, #dlgMiddleName, #dlgLastName', function () {
        var el    = this;
        var start = el.selectionStart;
        var end   = el.selectionEnd;
        el.value  = toTitleCase(el.value);
        if (el.setSelectionRange) { el.setSelectionRange(start, end); }
    });

    if (userLevel !== 'DEVELOPER' && userLevel !== 'SUPERADMIN') {
        // Disable status toggles (activate/deactivate)
        $('.profile-dept-status-action, .profile-brgy-status-action').prop('disabled', true).css({
            'pointer-events': 'none',
            'cursor': 'default'
        });
    }

    if (userLevel === 'VIEWER') {
        // Disable status toggles and logo buttons
        $('.profile-dept-status-action, .profile-brgy-status-action, #profileDeptLogoButton, #profileBrgyLogoButton').prop('disabled', true).css({
            'pointer-events': 'none',
            'cursor': 'default'
        });

        // Lock down elements inside modals when shown
        // BUT allow the editNameModal so viewers can still edit their own name
        $(document).on('show.bs.modal', '.modal', function () {
            var $modal = $(this);
            if ($modal.attr('id') === 'editNameModal') {
                return; // Allow editNameModal for viewers
            }
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
        // BUT skip editNameModal
        $(document).on('show.bs.modal shown.bs.modal', '.modal', function () {
            var $modal = $(this);
            if ($modal.attr('id') === 'editNameModal') {
                return; // Allow editNameModal for viewers
            }
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

    $(document).ready(function () {
        $('#showPasswordFormBtn').on('click', function () {
            $('#changePasswordModal').modal('show');
        });

        $('#changePasswordModal').on('shown.bs.modal', function () {
            $('#profileOldPassword').trigger('focus');
        });

        $('#changePasswordModal').on('hidden.bs.modal', function () {
            $('#profilePasswordForm')[0].reset();
            $('#profileConfirmPassword').removeClass('is-invalid');
        });

        // ── Show / hide password toggles ────────────────────────────────────
        function bindPasswordToggle(btnId, inputId) {
            $('#' + btnId).on('click', function () {
                var $input = $('#' + inputId);
                var $icon  = $(this).find('i');
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        }
        bindPasswordToggle('toggleOldPassword',     'profileOldPassword');
        bindPasswordToggle('toggleNewPassword',     'profileNewPassword');
        bindPasswordToggle('toggleConfirmPassword', 'profileConfirmPassword');

        // ── Real-time confirm-password match check ───────────────────────────
        $('#profileConfirmPassword').on('input', function () {
            var newPwd  = $('#profileNewPassword').val();
            var confPwd = $(this).val();
            if (confPwd && newPwd !== confPwd) {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        $('#editNameModal').on('show.bs.modal', function () {
            $('#dlgFirstName, #dlgMiddleName, #dlgLastName').removeClass('is-invalid');
            
            $('#dlgFirstName').val(toTitleCase($('#profileFname').val() || ''));
            $('#dlgMiddleName').val(toTitleCase($('#profileMname').val() || ''));
            $('#dlgLastName').val(toTitleCase($('#profileLname').val() || ''));
            $('#dlgSuffix').val($('#profileSuffix').val() || '');
        });

        $('#editNameModal').on('shown.bs.modal', function () {
            $('#dlgFirstName').trigger('focus');
        });

        $('#dlgFirstName, #dlgMiddleName, #dlgLastName').on('input', function () {
            if ($(this).val().trim() !== '') {
                $(this).removeClass('is-invalid');
            }
        });

        $('#btnSaveNameDlg').on('click', function () {
            let isValid = true;
            const fname  = toTitleCase($('#dlgFirstName').val().trim());
            const mname  = toTitleCase($('#dlgMiddleName').val().trim());
            const lname  = toTitleCase($('#dlgLastName').val().trim());
            const suffix = $('#dlgSuffix').val().trim();

            if (fname === '') {
                $('#dlgFirstName').addClass('is-invalid');
                isValid = false;
            } else {
                $('#dlgFirstName').removeClass('is-invalid');
            }

            if (mname === '') {
                $('#dlgMiddleName').addClass('is-invalid');
                isValid = false;
            } else {
                $('#dlgMiddleName').removeClass('is-invalid');
            }

            if (lname === '') {
                $('#dlgLastName').addClass('is-invalid');
                isValid = false;
            } else {
                $('#dlgLastName').removeClass('is-invalid');
            }

            if (!isValid) {
                return;
            }

            // Reflect title-cased values back into the dialog inputs
            $('#dlgFirstName').val(fname);
            $('#dlgMiddleName').val(mname);
            $('#dlgLastName').val(lname);

            // Push to hidden fields
            $('#profileFname').val(fname);
            $('#profileMname').val(mname);
            $('#profileLname').val(lname);
            $('#profileSuffix').val(suffix);

            // Display Pascal format: Last, First Middle [Suffix]
            $('#profileFullName').val(toPascalName(fname, mname, lname, suffix));

            $('#editNameModal').modal('hide');
        });

        const profileDeptQuillToolbar = [
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'align': [] }],
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            ['link'],
            ['clean']
        ];
        const profileDeptEditors = {};
        const profileBrgyEditors = {};
        const profileContactInputs = window.PhilippineContactInputs;
        if (profileContactInputs) {
            profileContactInputs.bind(
                '#profileDeptPhoneNumber, #profileBrgyPhoneNumber',
                '#profileDeptLandline, #profileBrgyLandline'
            );
        }

        function initProfileDepartmentEditors() {
            if (typeof Quill === 'undefined') {
                return;
            }

            const editors = [
                { key: 'about', editor: '#profileDeptAboutEditor', input: '#profileDeptAbout' },
                { key: 'mission', editor: '#profileDeptMissionEditor', input: '#profileDeptMission' },
                { key: 'vision', editor: '#profileDeptVisionEditor', input: '#profileDeptVision' },
                { key: 'policy', editor: '#profileDeptPolicyEditor', input: '#profileDeptPolicy' }
            ];

            editors.forEach(function (config) {
                if (!$(config.editor).length || profileDeptEditors[config.key]) {
                    return;
                }

                profileDeptEditors[config.key] = new Quill(config.editor, {
                    theme: 'snow',
                    modules: { toolbar: profileDeptQuillToolbar }
                });
                profileDeptEditors[config.key].root.innerHTML = $(config.input).val() || '';
            });
        }

        function syncProfileDepartmentEditors() {
            const fieldMap = {
                about: '#profileDeptAbout',
                mission: '#profileDeptMission',
                vision: '#profileDeptVision',
                policy: '#profileDeptPolicy'
            };

            Object.keys(fieldMap).forEach(function (key) {
                if (profileDeptEditors[key]) {
                    $(fieldMap[key]).val(profileDeptEditors[key].root.innerHTML);
                }
            });
        }

        function initProfileBarangayEditors() {
            if (typeof Quill === 'undefined') {
                return;
            }

            const editors = [
                { key: 'about', editor: '#profileBrgyAboutEditor', input: '#profileBrgyAbout' },
                { key: 'mission', editor: '#profileBrgyMissionEditor', input: '#profileBrgyMission' },
                { key: 'vision', editor: '#profileBrgyVisionEditor', input: '#profileBrgyVision' }
            ];

            editors.forEach(function (config) {
                if (!$(config.editor).length || profileBrgyEditors[config.key]) {
                    return;
                }

                profileBrgyEditors[config.key] = new Quill(config.editor, {
                    theme: 'snow',
                    modules: { toolbar: profileDeptQuillToolbar }
                });
                profileBrgyEditors[config.key].root.innerHTML = $(config.input).val() || '';
            });
        }

        function syncProfileBarangayEditors() {
            const fieldMap = {
                about: '#profileBrgyAbout',
                mission: '#profileBrgyMission',
                vision: '#profileBrgyVision'
            };

            Object.keys(fieldMap).forEach(function (key) {
                if (profileBrgyEditors[key]) {
                    $(fieldMap[key]).val(profileBrgyEditors[key].root.innerHTML);
                }
            });
        }

        function renderBarangayStatus(status) {
            let badgeClass = '';
            if (status === 'ACTIVE') {
                badgeClass = 'is-active';
            } else if (status === 'INACTIVE') {
                badgeClass = 'is-inactive';
            }

            const label = status ? status.charAt(0) + status.slice(1).toLowerCase() : 'Unknown';
            return '<button type="button" class="brgy-status-toggle profile-brgy-status-action" data-current-status="' + status + '" aria-label="Toggle barangay status">' +
                '<span class="brgy-status-switch ' + badgeClass + '">' +
                '<span class="brgy-status-switch__track" aria-hidden="true">' +
                '<span class="brgy-status-switch__thumb"></span>' +
                '</span>' +
                '<span class="brgy-status-switch__label">' + label + '</span>' +
                '</span>' +
                '</button>';
        }

        function updateBarangayActionStatus(status) {
            const nextStatus = status === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
            const icon = status === 'ACTIVE' ? 'on' : 'off';

            $('.profile-brgy-status-action').attr('data-current-status', status);
            $('.profile-brgy-status-action.dropdown-item').html(
                '<i class="fas fa-toggle-' + icon + ' mr-1"></i> ' +
                (nextStatus === 'ACTIVE' ? 'Activate' : 'Deactivate')
            );
        }

        function buildProfileBarangayFormData(extraData) {
            syncProfileBarangayEditors();

            if (profileContactInputs) {
                profileContactInputs.prepare('#profileBrgyPhoneNumber', '#profileBrgyLandline');
            }

            const formData = new FormData();
            formData.set('id', $('#profileBrgyId').val() || '');
            formData.set('editBrgy', $('#profileBrgyName').val() || '');
            formData.set('editCapt', $('#profileBrgyCaptain').val() || '');
            formData.set('editAbout', $('#profileBrgyAbout').val() || '');
            formData.set('editMission', $('#profileBrgyMission').val() || '');
            formData.set('editVision', $('#profileBrgyVision').val() || '');
            formData.set('editPhoneNumber', $('#profileBrgyPhoneNumber').val() || '');
            formData.set('editLandline', $('#profileBrgyLandline').val() || '');
            formData.set('editEmailAddress', $('#profileBrgyEmailAddress').val() || '');
            formData.set('editOfficeAddress', $('#profileBrgyOfficeAddress').val() || '');

            const orgChartInput = $('#profileBrgyOrgChart')[0];
            if (orgChartInput && orgChartInput.files && orgChartInput.files[0]) {
                formData.set('editbrgyOrgChart', orgChartInput.files[0]);
            }

            if (extraData) {
                Object.keys(extraData).forEach(function (key) {
                    formData.set(key, extraData[key]);
                });
            }

            return formData;
        }

        function applyProfileBarangayCardUpdate(previewImage) {
            const brgyName = $('#profileBrgyName').val() || '';
            const brgyCaptain = $('#profileBrgyCaptain').val() || '';

            $('#profileDepartment').val(brgyName);
            $('#profileBrgyNameCell')
                .text(brgyName || 'Barangay name not set')
                .toggleClass('is-empty', !brgyName);
            $('#profileBrgyCaptainCell')
                .text(brgyCaptain || 'Unassigned')
                .toggleClass('is-unassigned', !brgyCaptain);

            if (previewImage) {
                $('#profileBrgyLogoEmpty').addClass('d-none');
                $('#profileBrgyLogoCell').attr('src', previewImage).removeClass('d-none');
            }

            $('#profileBrgyLogo').val('');
            $('#profileBrgyCardLogoInput').val('');
            $('#profileBrgyOrgChart').val('');
            $('#profileBrgyLogoClearBtn').removeClass('active');
        }

        function submitProfileBarangayUpdate(formData, options) {
            const settings = $.extend({
                successMessage: 'Barangay saved.',
                errorMessage: 'Unable to update barangay. Please try again.',
                closeModal: false,
                previewImage: null,
                $button: null
            }, options || {});

            if (settings.$button) {
                settings.$button.prop('disabled', true);
            }

            $.ajax({
                url: "<?= site_url('admin/ajax/update_barangay') ?>",
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    const saved = response.status == 1;
                    showProfileMessage(saved, response.message || settings.successMessage);
                    if (saved) {
                        applyProfileBarangayCardUpdate(settings.previewImage || $('#profileBrgyLogoPreview img').attr('src'));
                        if (settings.closeModal) {
                            $('#editLinkedBarangayModal').modal('hide');
                        }
                    }
                },
                error: function () {
                    showProfileMessage(false, settings.errorMessage);
                },
                complete: function () {
                    if (settings.$button) {
                        settings.$button.prop('disabled', false);
                    }
                }
            });
        }

        // Initialize editors when the tabs are shown, or immediately on load if tab is active
        initProfileDepartmentEditors();
        initProfileBarangayEditors();

        $('#edit-department-tab').on('shown.bs.tab', function () {
            initProfileDepartmentEditors();
        });

        $('#edit-barangay-tab').on('shown.bs.tab', function () {
            initProfileBarangayEditors();
        });
        $('#profileDeptAssignCta').on('click', function (e) {
            e.preventDefault();
            $('#profileDeptHead').trigger('focus');
        });

        $('#changeDepartmentBtn').on('click', function () {
            $('#profileDepartment').prop('readonly', false).trigger('focus');
        });

        // ── Profile picture: preview on select, revert on cancel ─────────────
        (function () {
            var _origPicSrc = $('#profilePicturePreview').attr('src') || '';
            var _origPicHasFallback = $('#profilePictureFallback').length && !$('#profilePictureFallback').hasClass('d-none');
            var _picPending = false;
            var _hasUnsavedPicture = false;

            function rememberOriginalPicture() {
                if (_hasUnsavedPicture) {
                    return;
                }
                _origPicSrc = $('#profilePicturePreview').attr('src') || '';
                _origPicHasFallback = $('#profilePictureFallback').length && !$('#profilePictureFallback').hasClass('d-none');
            }

            function restoreOriginalPicture() {
                $('#profileImage').val('');
                $('#profilePictureClearBtn').removeClass('active');

                if (_origPicHasFallback) {
                    $('#profilePictureFallback').removeClass('d-none').addClass('d-inline-flex');
                    $('#profilePicturePreview').addClass('d-none').attr('src', '');
                } else if (_origPicSrc) {
                    $('#profilePictureFallback').addClass('d-none').removeClass('d-inline-flex');
                    $('#profilePicturePreview').attr('src', _origPicSrc).removeClass('d-none');
                }
                _hasUnsavedPicture = false;
            }

            $('#profilePictureWrapper').on('click', function () {
                rememberOriginalPicture();
                _picPending = true;
                $('#profileImage').trigger('click');
                $(window).one('focus.picRevert', function () {
                    setTimeout(function () {
                        if (_picPending && (!$('#profileImage')[0].files || $('#profileImage')[0].files.length === 0)) {
                            restoreOriginalPicture();
                        }
                        _picPending = false;
                    }, 300);
                });
            });

            $('#profileImage').on('click', function () {
                rememberOriginalPicture();
            });

            $('#profileImage').on('change', function () {
                _picPending = false;
                $(window).off('focus.picRevert');
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    restoreOriginalPicture();
                    return;
                }

                if (!file.type || !file.type.match(/^image\/(png|jpeg|webp)$/)) {
                    restoreOriginalPicture();
                    showProfileMessage(false, 'Please choose a PNG, JPG, JPEG, or WEBP image.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    $('#profilePictureFallback').addClass('d-none').removeClass('d-inline-flex');
                    $('#profilePicturePreview').attr('src', event.target.result).removeClass('d-none');
                    $('#profilePictureClearBtn').addClass('active');
                    _hasUnsavedPicture = true;
                };
                reader.readAsDataURL(file);
            });

            $('#profilePictureClearBtn').on('click', function (e) {
                e.stopPropagation();
                restoreOriginalPicture();
            });

            $(document).on('profilePictureSaved', function () {
                _origPicSrc = $('#profilePicturePreview').attr('src') || '';
                _origPicHasFallback = $('#profilePictureFallback').length && !$('#profilePictureFallback').hasClass('d-none');
                _hasUnsavedPicture = false;
            });
        }());

        function showProfileMessage(status, message) {
            const icon = status ? 'success' : 'error';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: icon,
                    title: status ? 'Success' : 'Error',
                    text: message
                });
                return;
            }
            alert(message);
        }

        $('#profileDetailsForm').on('submit', function (e) {
            e.preventDefault();

            const $button = $(this).find('button[type="submit"]');
            $button.prop('disabled', true);

            $.ajax({
                url: "<?= site_url('admin/ajax/update_profile') ?>",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    const saved = response.status == 1;
                    showProfileMessage(saved, response.message || 'Profile saved.');
                    if (response.status == 1 && response.data) {
                        $('#profileFullName').val(response.data.fullName || '');
                        if (response.data.fname !== undefined) $('#profileFname').val(response.data.fname);
                        if (response.data.mname !== undefined) $('#profileMname').val(response.data.mname);
                        if (response.data.lname !== undefined) $('#profileLname').val(response.data.lname);
                        if (response.data.suffix !== undefined) $('#profileSuffix').val(response.data.suffix);
                        $('#profileEmail').val(response.data.email || '');
                        $('#profileUsername').val(response.data.username || '');
                        $('.topbar .text-gray-600.small').text((response.data.fullName || '') + ' (<?= esc($user->user_lvl ?? '') ?>)');
                    }
                },
                error: function () {
                    showProfileMessage(false, 'Unable to save profile. Please try again.');
                },
                complete: function () {
                    $button.prop('disabled', false);
                }
            });
        });

        $('#profilePasswordForm').on('submit', function (e) {
            e.preventDefault();

            // Client-side: confirm passwords match before hitting the server
            var newPwd  = $('#profileNewPassword').val();
            var confPwd = $('#profileConfirmPassword').val();
            if (newPwd !== confPwd) {
                $('#profileConfirmPassword').addClass('is-invalid');
                $('#profileConfirmPassword')[0].setCustomValidity('Passwords do not match.');
                return;
            }
            $('#profileConfirmPassword').removeClass('is-invalid');
            $('#profileConfirmPassword')[0].setCustomValidity('');

            const $button = $(this).find('button[type="submit"]');
            $button.prop('disabled', true);

            $.ajax({
                url: "<?= site_url('admin/ajax/change_profile_password') ?>",
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    showProfileMessage(response.status == 1, response.message || 'Password updated.');
                    if (response.status == 1) {
                        $('#profilePasswordForm')[0].reset();
                        $('#changePasswordModal').modal('hide');
                    }
                },
                error: function () {
                    showProfileMessage(false, 'Unable to change password. Please try again.');
                },
                complete: function () {
                    $button.prop('disabled', false);
                }
            });
        });

        $('#profilePictureForm').on('submit', function (e) {
            e.preventDefault();

            const $button = $(this).find('button[type="submit"]');
            const formData = new FormData(this);
            $button.prop('disabled', true);

            $.ajax({
                url: "<?= site_url('admin/ajax/update_profile_picture') ?>",
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    const saved = response.status == 1;
                    showProfileMessage(saved, response.message || 'Profile picture saved.');
                    if (response.status == 1 && response.data && response.data.profileImageUrl) {
                        const freshImageUrl = response.data.profileImageUrl + '?v=' + Date.now();
                        $('#profilePictureFallback').addClass('d-none').removeClass('d-inline-flex');
                        $('#profilePicturePreview')
                            .attr('src', freshImageUrl)
                            .removeClass('d-none');
                        $('#topbarProfileAvatar').html(
                            '<img class="rounded-circle" src="' + freshImageUrl + '" alt="Profile picture" style="width: 32px; height: 32px; object-fit: cover;">'
                        );
                        $('#profileImage').val('');
                        $('#profilePictureClearBtn').removeClass('active');
                        $(document).trigger('profilePictureSaved');
                    }
                },
                error: function () {
                    showProfileMessage(false, 'Unable to save profile picture. Please try again.');
                },
                complete: function () {
                    $button.prop('disabled', false);
                }
            });
        });

        // ── Dept logo: preview in circular cell, revert on cancel ────────────
        (function () {
            var _origDeptSrc = $('#profileDeptLogoCell').attr('src') || '';
            var _origDeptEmpty = !$('#profileDeptLogoEmpty').hasClass('d-none');
            var _deptPending = false;

            $('#profileDeptLogoWrapper').on('click.preview', function () {
                _origDeptSrc = $('#profileDeptLogoCell').attr('src') || '';
                _origDeptEmpty = !$('#profileDeptLogoEmpty').hasClass('d-none');
                _deptPending = true;
                $('#profileDeptLogo').trigger('click');
                $(window).one('focus.deptLogoRevert', function () {
                    setTimeout(function () {
                        if (_deptPending && (!$('#profileDeptLogo')[0].files || $('#profileDeptLogo')[0].files.length === 0)) {
                            if (_origDeptEmpty) {
                                $('#profileDeptLogoEmpty').removeClass('d-none');
                                $('#profileDeptLogoCell').addClass('d-none').attr('src', '');
                            } else {
                                $('#profileDeptLogoCell').attr('src', _origDeptSrc).removeClass('d-none');
                                $('#profileDeptLogoEmpty').addClass('d-none');
                            }
                            $('#profileDeptLogoClearBtn').removeClass('active');
                        }
                        _deptPending = false;
                    }, 300);
                });
            });

            $('#profileDeptLogo').on('change', function () {
                _deptPending = false;
                $(window).off('focus.deptLogoRevert');
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    $('#profileDeptLogoClearBtn').removeClass('active');
                    return;
                }
                const previewUrl = URL.createObjectURL(file);
                $('#profileDeptLogoEmpty').addClass('d-none');
                $('#profileDeptLogoCell')
                    .attr('src', previewUrl)
                    .removeClass('d-none')
                    .one('load', function () { URL.revokeObjectURL(previewUrl); });
                $('#profileDeptLogoClearBtn').addClass('active');
            });

            $('#profileDeptLogoClearBtn').on('click', function (e) {
                e.stopPropagation();
                $('#profileDeptLogo').val('');
                $('#profileDeptLogoClearBtn').removeClass('active');
                if (_origDeptEmpty) {
                    $('#profileDeptLogoEmpty').removeClass('d-none');
                    $('#profileDeptLogoCell').addClass('d-none').attr('src', '');
                } else {
                    $('#profileDeptLogoCell').attr('src', _origDeptSrc).removeClass('d-none');
                    $('#profileDeptLogoEmpty').addClass('d-none');
                }
            });
        }());

        $('#profileDeptOrgChart').on('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) { return; }
            const previewUrl = URL.createObjectURL(file);
            $('#profileDeptOrgChartPreview').html(
                '<img src="' + previewUrl + '" alt="Organizational chart preview" style="max-width: 160px; max-height: 160px; object-fit: contain;">'
            );
        });

        // ── Brgy logo: preview in circular cell, revert on cancel ─────────────
        (function () {
            var _origBrgySrc = $('#profileBrgyLogoCell').attr('src') || '';
            var _origBrgyEmpty = !$('#profileBrgyLogoEmpty').hasClass('d-none');
            var _brgyPending = false;

            $('#profileBrgyLogoWrapper').on('click.preview', function () {
                _origBrgySrc = $('#profileBrgyLogoCell').attr('src') || '';
                _origBrgyEmpty = !$('#profileBrgyLogoEmpty').hasClass('d-none');
                _brgyPending = true;
                $('#profileBrgyLogo').trigger('click');
                $(window).one('focus.brgyLogoRevert', function () {
                    setTimeout(function () {
                        if (_brgyPending && (!$('#profileBrgyLogo')[0].files || $('#profileBrgyLogo')[0].files.length === 0)) {
                            if (_origBrgyEmpty) {
                                $('#profileBrgyLogoEmpty').removeClass('d-none');
                                $('#profileBrgyLogoCell').addClass('d-none').attr('src', '');
                            } else {
                                $('#profileBrgyLogoCell').attr('src', _origBrgySrc).removeClass('d-none');
                                $('#profileBrgyLogoEmpty').addClass('d-none');
                            }
                            $('#profileBrgyLogoClearBtn').removeClass('active');
                        }
                        _brgyPending = false;
                    }, 300);
                });
            });

            $('#profileBrgyLogo').on('change', function () {
                _brgyPending = false;
                $(window).off('focus.brgyLogoRevert');
                const file = this.files && this.files[0] ? this.files[0] : null;
                if (!file) {
                    $('#profileBrgyLogoClearBtn').removeClass('active');
                    return;
                }
                const previewUrl = URL.createObjectURL(file);
                $('#profileBrgyLogoEmpty').addClass('d-none');
                $('#profileBrgyLogoCell')
                    .attr('src', previewUrl)
                    .removeClass('d-none')
                    .one('load', function () { URL.revokeObjectURL(previewUrl); });
                $('#profileBrgyLogoClearBtn').addClass('active');
            });

            $('#profileBrgyLogoClearBtn').on('click', function (e) {
                e.stopPropagation();
                $('#profileBrgyLogo').val('');
                $('#profileBrgyLogoClearBtn').removeClass('active');
                if (_origBrgyEmpty) {
                    $('#profileBrgyLogoEmpty').removeClass('d-none');
                    $('#profileBrgyLogoCell').addClass('d-none').attr('src', '');
                } else {
                    $('#profileBrgyLogoCell').attr('src', _origBrgySrc).removeClass('d-none');
                    $('#profileBrgyLogoEmpty').addClass('d-none');
                }
            });
        }());

        $('#profileBrgyOrgChart').on('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) { return; }

            const previewUrl = URL.createObjectURL(file);
            $('#profileBrgyOrgChartPreview').html(
                '<img src="' + previewUrl + '" alt="Organizational chart preview" style="max-width: 160px; max-height: 160px; object-fit: contain;">'
            );
        });

        $('#profileBarangayForm').on('submit', function (e) {
            e.preventDefault();
            initProfileBarangayEditors();
            syncProfileBarangayEditors();

            if (profileContactInputs) {
                profileContactInputs.prepare('#profileBrgyPhoneNumber', '#profileBrgyLandline');
            }

            const formData = new FormData(this);
            const imageFile = formData.get('editbrgyImg');
            const orgChartFile = formData.get('editbrgyOrgChart');
            const maxImageSizeMB = 4;
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!formData.get('editBrgy') || !formData.get('editCapt') || !formData.get('editAbout') ||
                !formData.get('editMission') || !formData.get('editVision')) {
                showProfileMessage(false, 'Please fill in all required fields.');
                return;
            }

            if (!formData.get('editPhoneNumber') && !formData.get('editLandline') &&
                !formData.get('editEmailAddress') && !formData.get('editOfficeAddress')) {
                showProfileMessage(false, 'Please provide at least one contact method.');
                return;
            }

            if (formData.get('editPhoneNumber') && profileContactInputs &&
                !profileContactInputs.isValidMobile(formData.get('editPhoneNumber'))) {
                showProfileMessage(false, 'Phone Number must use the format +63 9XX XXX XXXX.');
                return;
            }

            if (formData.get('editLandline') && profileContactInputs &&
                !profileContactInputs.isValidLandline(formData.get('editLandline'))) {
                showProfileMessage(false, 'Landline must use (049) 123-4567 or (02) 1234-5678.');
                return;
            }

            if (imageFile && imageFile.size > 0) {
                if (imageFile.size > maxImageSizeMB * 1024 * 1024) {
                    showProfileMessage(false, 'Logo image size should not exceed 4 MB.');
                    return;
                }

                if (!validImageTypes.includes(imageFile.type)) {
                    showProfileMessage(false, 'Please upload a valid logo image file.');
                    return;
                }
            }

            if (orgChartFile && orgChartFile.size > 0) {
                if (orgChartFile.size > maxImageSizeMB * 1024 * 1024) {
                    showProfileMessage(false, 'Organizational chart size should not exceed 4 MB.');
                    return;
                }

                if (!validImageTypes.includes(orgChartFile.type)) {
                    showProfileMessage(false, 'Please upload a valid organizational chart image.');
                    return;
                }
            }

            submitProfileBarangayUpdate(formData, {
                $button: $(this).find('button[type="submit"]')
            });
        });

        function renderDepartmentStatus(status) {
            let badgeClass = '';
            if (status === 'ACTIVE') {
                badgeClass = 'is-active';
            } else if (status === 'INACTIVE') {
                badgeClass = 'is-inactive';
            }

            const label = status ? status.charAt(0) + status.slice(1).toLowerCase() : 'Unknown';
            return '<button type="button" class="dept-status-toggle profile-dept-status-action" data-current-status="' + status + '" aria-label="Toggle department status">' +
                '<span class="dept-status-switch ' + badgeClass + '">' +
                '<span class="dept-status-switch__track" aria-hidden="true">' +
                '<span class="dept-status-switch__thumb"></span>' +
                '</span>' +
                '<span class="dept-status-switch__label">' + label + '</span>' +
                '</span>' +
                '</button>';
        }

        function updateDepartmentActionStatus(status) {
            const nextStatus = status === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
            const icon = status === 'ACTIVE' ? 'on' : 'off';

            $('.profile-dept-status-action').attr('data-current-status', status);
            $('.profile-dept-status-action.dropdown-item').html(
                '<i class="fas fa-toggle-' + icon + ' mr-1"></i> ' +
                (nextStatus === 'ACTIVE' ? 'Activate' : 'Deactivate')
            );
        }

        function buildProfileDepartmentFormData(extraData) {
            syncProfileDepartmentEditors();

            if (profileContactInputs) {
                profileContactInputs.prepare('#profileDeptPhoneNumber', '#profileDeptLandline');
            }

            const formData = new FormData();
            formData.set('deptName', $('#profileDeptName').val() || '');
            formData.set('head', $('#profileDeptHead').val() || '');
            formData.set('status', $('#profileDeptStatus').val() || 'ACTIVE');
            formData.set('about', $('#profileDeptAbout').val() || '');
            formData.set('phoneNumber', $('#profileDeptPhoneNumber').val() || '');
            formData.set('landline', $('#profileDeptLandline').val() || '');
            formData.set('emailAddress', $('#profileDeptEmailAddress').val() || '');
            formData.set('officeAddress', $('#profileDeptOfficeAddress').val() || '');
            formData.set('mission', $('#profileDeptMission').val() || '');
            formData.set('vision', $('#profileDeptVision').val() || '');
            formData.set('qualityPolicy', $('#profileDeptPolicy').val() || '');

            if (extraData) {
                Object.keys(extraData).forEach(function (key) {
                    formData.set(key, extraData[key]);
                });
            }

            return formData;
        }

        function applyProfileDepartmentResponse(response) {
            const deptName = response.data.dept_name || '';
            const deptHead = response.data.head || '';

            $('#profileDeptNameCell')
                .text(deptName || 'Department name not set')
                .toggleClass('is-empty', !deptName);
            $('#profileDeptHeadCell')
                .text(deptHead || 'Unassigned')
                .toggleClass('is-unassigned', !deptHead);
            $('#profileDeptAssignCta').toggleClass('d-none', !!deptHead);
            $('#profileDeptStatusCell').html(renderDepartmentStatus(response.data.status || ''));
            updateDepartmentActionStatus(response.data.status || '');
            $('#profileDepartment').val(response.data.dept_name || '');
            $('#profileDeptName').val(response.data.dept_name || '');
            $('#profileDeptHead').val(response.data.head || '');
            $('#profileDeptStatus').val(response.data.status || '');
            $('#profileDeptAbout').val(response.data.about || '');
            $('#profileDeptPhoneNumber').val(response.data.phone_number || '');
            $('#profileDeptLandline').val(response.data.landline || '');
            $('#profileDeptEmailAddress').val(response.data.email_address || '');
            $('#profileDeptOfficeAddress').val(response.data.office_address || '');
            $('#profileDeptMission').val(response.data.mission || '');
            $('#profileDeptVision').val(response.data.vision || '');
            $('#profileDeptPolicy').val(response.data.quality_policy || '');

            if (response.data.logoUrl) {
                const logoUrl = response.data.logoUrl + '?v=' + Date.now();
                $('#profileDeptLogoEmpty').addClass('d-none');
                $('#profileDeptLogoCell').attr('src', logoUrl).removeClass('d-none');
                $('#profileDeptLogoPreview').html(
                    '<img src="' + logoUrl + '" alt="Department logo" style="max-width: 120px; max-height: 120px; object-fit: contain;">'
                );
            }

            if (response.data.orgChartUrl) {
                const orgChartUrl = response.data.orgChartUrl + '?v=' + Date.now();
                $('#profileDeptOrgChartPreview').html(
                    '<img src="' + orgChartUrl + '" alt="Organizational chart" style="max-width: 160px; max-height: 160px; object-fit: contain;">'
                );
            }

            $('#profileDeptLogo').val('');
            $('#profileDeptCardLogoInput').val('');
            $('#profileDeptOrgChart').val('');
            $('#profileDeptLogoClearBtn').removeClass('active');
        }

        function submitProfileDepartmentUpdate(formData, options) {
            const settings = $.extend({
                successMessage: 'Department saved.',
                errorMessage: 'Unable to update department. Please try again.',
                $button: null
            }, options || {});

            if (settings.$button) {
                settings.$button.prop('disabled', true);
            }

            $.ajax({
                url: "<?= site_url('admin/ajax/update_profile_department') ?>",
                type: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    const saved = response.status == 1;
                    showProfileMessage(saved, response.message || settings.successMessage);
                    if (saved && response.data) {
                        applyProfileDepartmentResponse(response);
                    }
                },
                error: function () {
                    showProfileMessage(false, settings.errorMessage);
                },
                complete: function () {
                    if (settings.$button) {
                        settings.$button.prop('disabled', false);
                    }
                }
            });
        }

        $('#profileDepartmentForm').on('submit', function (e) {
            e.preventDefault();

            if (profileContactInputs) {
                profileContactInputs.prepare('#profileDeptPhoneNumber', '#profileDeptLandline');
            }

            const formData = new FormData(this);
            syncProfileDepartmentEditors();
            formData.set('about', $('#profileDeptAbout').val() || '');
            formData.set('mission', $('#profileDeptMission').val() || '');
            formData.set('vision', $('#profileDeptVision').val() || '');
            formData.set('qualityPolicy', $('#profileDeptPolicy').val() || '');

            if (!formData.get('phoneNumber') && !formData.get('landline') &&
                !formData.get('emailAddress') && !formData.get('officeAddress')) {
                showProfileMessage(false, 'Please provide at least one contact method.');
                return;
            }

            if (formData.get('phoneNumber') && profileContactInputs &&
                !profileContactInputs.isValidMobile(formData.get('phoneNumber'))) {
                showProfileMessage(false, 'Phone Number must use the format +63 9XX XXX XXXX.');
                return;
            }

            if (formData.get('landline') && profileContactInputs &&
                !profileContactInputs.isValidLandline(formData.get('landline'))) {
                showProfileMessage(false, 'Landline must use (049) 123-4567 or (02) 1234-5678.');
                return;
            }

            submitProfileDepartmentUpdate(formData, {
                $button: $(this).find('button[type="submit"]')
            });
        });


        $('#profileDeptLogoSaveBtn').on('click', function () {
            $('#profileDepartmentForm').trigger('submit');
        });

        $('#profileBrgyLogoSaveBtn').on('click', function () {
            $('#profileBarangayForm').trigger('submit');
        });
        $('#deleteLinkedDepartmentBtn').on('click', function (e) {
            e.preventDefault();

            const confirmDelete = function () {
                $.ajax({
                    url: "<?= site_url('admin/ajax/delete_profile_department') ?>",
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        const deleted = response.status == 1;
                        showProfileMessage(deleted, response.message || 'Department deleted.');
                        if (deleted) {
                            $('#profileDepartmentCard').remove();
                            $('#profileDepartment').val('');
                        }
                    },
                    error: function () {
                        showProfileMessage(false, 'Unable to delete department. Please try again.');
                    }
                });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Delete Department',
                    text: 'Are you sure you want to delete this linked department?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#dc3545'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        confirmDelete();
                    }
                });
                return;
            }

            if (confirm('Are you sure you want to delete this linked department?')) {
                confirmDelete();
            }
        });
    });
</script>
