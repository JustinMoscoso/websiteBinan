<script>
    const userLevel = '<?= $user->user_lvl ?>'.toUpperCase();

    if (userLevel === 'VIEWER') {
        // Disable status toggles and logo buttons
        $('.profile-dept-status-action, .profile-brgy-status-action, #profileDeptLogoButton, #profileBrgyLogoButton').prop('disabled', true).css({
            'pointer-events': 'none',
            'cursor': 'default'
        });

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

    $(document).ready(function () {
        $('#showPasswordFormBtn').on('click', function () {
            $('#changePasswordModal').modal('show');
        });

        $('#changePasswordModal').on('shown.bs.modal', function () {
            $('#profileOldPassword').trigger('focus');
        });

        $('#changePasswordModal').on('hidden.bs.modal', function () {
            $('#profilePasswordForm')[0].reset();
        });

        $('#editNameModal').on('show.bs.modal', function () {
            $('#dlgFirstName, #dlgMiddleName, #dlgLastName').removeClass('is-invalid');
            
            $('#dlgFirstName').val($('#profileFname').val() || '');
            $('#dlgMiddleName').val($('#profileMname').val() || '');
            $('#dlgLastName').val($('#profileLname').val() || '');
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
            const fname = $('#dlgFirstName').val().trim();
            const mname = $('#dlgMiddleName').val().trim();
            const lname = $('#dlgLastName').val().trim();
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

            $('#profileFname').val(fname);
            $('#profileMname').val(mname);
            $('#profileLname').val(lname);
            $('#profileSuffix').val(suffix);

            const fullNameParts = [];
            if (fname) fullNameParts.push(fname);
            if (mname) fullNameParts.push(mname);
            if (lname) fullNameParts.push(lname);
            if (suffix) fullNameParts.push(suffix);
            
            $('#profileFullName').val(fullNameParts.join(' '));

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

        function initProfileDepartmentEditors() {
            if (typeof Quill === 'undefined') {
                return;
            }

            const editors = [
                { key: 'about', editor: '#profileDeptAboutEditor', input: '#profileDeptAbout' },
                { key: 'contact', editor: '#profileDeptContactEditor', input: '#profileDeptContact' },
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
                contact: '#profileDeptContact',
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
                { key: 'vision', editor: '#profileBrgyVisionEditor', input: '#profileBrgyVision' },
                { key: 'contact', editor: '#profileBrgyContactEditor', input: '#profileBrgyContact' },
                { key: 'staff', editor: '#profileBrgyStaffEditor', input: '#profileBrgyStaff' }
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
                vision: '#profileBrgyVision',
                contact: '#profileBrgyContact',
                staff: '#profileBrgyStaff'
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

            const formData = new FormData();
            formData.set('id', $('#profileBrgyId').val() || '');
            formData.set('editBrgy', $('#profileBrgyName').val() || '');
            formData.set('editCapt', $('#profileBrgyCaptain').val() || '');
            formData.set('editAbout', $('#profileBrgyAbout').val() || '');
            formData.set('editMission', $('#profileBrgyMission').val() || '');
            formData.set('editVision', $('#profileBrgyVision').val() || '');
            formData.set('editContact', $('#profileBrgyContact').val() || '');
            formData.set('editStaff', $('#profileBrgyStaff').val() || '');

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

        $('#editLinkedBarangayActionBtn').on('click', function (e) {
            e.preventDefault();
            $('#editLinkedBarangayModal').modal('show');
        });

        $('#editLinkedBarangayModal').on('shown.bs.modal', function () {
            initProfileBarangayEditors();
        });

        $('#editLinkedDepartmentActionBtn').on('click', function (e) {
            e.preventDefault();
            $('#editLinkedDepartmentModal').modal('show');
        });

        $('#profileDeptAssignCta').on('click', function (e) {
            e.preventDefault();
            $('#editLinkedDepartmentModal').modal('show');
            $('#profileDeptHead').trigger('focus');
        });

        $('#editLinkedDepartmentModal').on('shown.bs.modal', function () {
            initProfileDepartmentEditors();
        });

        $('#changeDepartmentBtn').on('click', function () {
            $('#profileDepartment').prop('readonly', false).trigger('focus');
        });

        $('#profileImage').on('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            $('#profilePictureFallback').addClass('d-none');
            $('#profilePicturePreview')
                .attr('src', previewUrl)
                .removeClass('d-none')
                .one('load', function () {
                    URL.revokeObjectURL(previewUrl);
                });
        });

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
                        $('#profilePictureFallback').addClass('d-none');
                        $('#profilePicturePreview')
                            .attr('src', freshImageUrl)
                            .removeClass('d-none');
                        $('#topbarProfileAvatar').html(
                            '<img class="rounded-circle" src="' + freshImageUrl + '" alt="Profile picture" style="width: 32px; height: 32px; object-fit: cover;">'
                        );
                        $('#profileImage').val('');
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

        $('#profileDeptLogo').on('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            $('#profileDeptLogoPreview').html(
                '<img src="' + previewUrl + '" alt="Department logo preview" style="max-width: 120px; max-height: 120px; object-fit: contain;">'
            );
        });

        $('#profileDeptOrgChart').on('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            $('#profileDeptOrgChartPreview').html(
                '<img src="' + previewUrl + '" alt="Organizational chart preview" style="max-width: 160px; max-height: 160px; object-fit: contain;">'
            );
        });

        $('#profileBrgyLogo').on('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            $('#profileBrgyLogoPreview').html(
                '<img src="' + previewUrl + '" alt="Barangay logo preview" style="max-width: 120px; margin-top: 5px;">'
            );
        });

        $('#profileBarangayForm').on('submit', function (e) {
            e.preventDefault();
            initProfileBarangayEditors();
            syncProfileBarangayEditors();

            const formData = new FormData(this);
            const imageFile = formData.get('editbrgyImg');
            const maxImageSizeMB = 4;
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!formData.get('editBrgy') || !formData.get('editCapt') || !formData.get('editAbout') ||
                !formData.get('editMission') || !formData.get('editVision') || !formData.get('editContact')) {
                showProfileMessage(false, 'Please fill in all required fields.');
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

            submitProfileBarangayUpdate(formData, {
                closeModal: true,
                $button: $('#btnProfileBarangay')
            });
        });

        $('#profileBrgyLogoButton').on('click', function () {
            $('#profileBrgyCardLogoInput').trigger('click');
        });

        $('#profileBrgyCardLogoInput').on('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                return;
            }

            const maxImageSizeMB = 4;
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (file.size > maxImageSizeMB * 1024 * 1024) {
                showProfileMessage(false, 'Logo image size should not exceed 4 MB.');
                return;
            }

            if (!validImageTypes.includes(file.type)) {
                showProfileMessage(false, 'Please upload a valid logo image file.');
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            submitProfileBarangayUpdate(buildProfileBarangayFormData({ editbrgyImg: file }), {
                successMessage: 'Barangay logo updated.',
                errorMessage: 'Unable to update barangay logo. Please try again.',
                previewImage: previewUrl
            });
        });

        $(document).on('click', '.profile-brgy-status-action', function (e) {
            e.preventDefault();

            const currentStatus = $(this).attr('data-current-status') || '';
            const nextStatus = currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
            const brgyId = $('#profileBrgyId').val() || '';

            $.ajax({
                url: "<?= site_url('admin/ajax/set_status_barangay') ?>",
                type: 'POST',
                data: { id: brgyId, status: nextStatus },
                dataType: 'json',
                success: function (response) {
                    const saved = response.status == 1;
                    showProfileMessage(saved, response.message || 'Barangay status updated.');
                    if (saved) {
                        const savedStatus = response.data && response.data.status ? response.data.status : nextStatus;
                        $('#profileBrgyStatusCell').html(renderBarangayStatus(savedStatus));
                        updateBarangayActionStatus(savedStatus);
                    }
                },
                error: function () {
                    showProfileMessage(false, 'Unable to update barangay status. Please try again.');
                }
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

            const formData = new FormData();
            formData.set('deptName', $('#profileDeptName').val() || '');
            formData.set('head', $('#profileDeptHead').val() || '');
            formData.set('status', $('#profileDeptStatus').val() || 'ACTIVE');
            formData.set('about', $('#profileDeptAbout').val() || '');
            formData.set('contact', $('#profileDeptContact').val() || '');
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
            $('#profileDeptContact').val(response.data.contact || '');
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
        }

        function submitProfileDepartmentUpdate(formData, options) {
            const settings = $.extend({
                successMessage: 'Department saved.',
                errorMessage: 'Unable to update department. Please try again.',
                closeModal: false,
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
                        if (settings.closeModal) {
                            $('#editLinkedDepartmentModal').modal('hide');
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

        $('#profileDepartmentForm').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            syncProfileDepartmentEditors();
            formData.set('about', $('#profileDeptAbout').val() || '');
            formData.set('contact', $('#profileDeptContact').val() || '');
            formData.set('mission', $('#profileDeptMission').val() || '');
            formData.set('vision', $('#profileDeptVision').val() || '');
            formData.set('qualityPolicy', $('#profileDeptPolicy').val() || '');

            submitProfileDepartmentUpdate(formData, {
                closeModal: true,
                $button: $(this).find('button[type="submit"]')
            });
        });

        $('#profileDeptLogoButton').on('click', function () {
            $('#profileDeptCardLogoInput').trigger('click');
        });

        $('#profileDeptCardLogoInput').on('change', function () {
            const file = this.files && this.files[0] ? this.files[0] : null;
            if (!file) {
                return;
            }

            submitProfileDepartmentUpdate(buildProfileDepartmentFormData({ deptLogo: file }), {
                successMessage: 'Department logo updated.',
                errorMessage: 'Unable to update department logo. Please try again.'
            });
        });

        $(document).on('click', '.profile-dept-status-action', function (e) {
            e.preventDefault();

            const currentStatus = $(this).attr('data-current-status') || '';
            const nextStatus = currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';

            $.ajax({
                url: "<?= site_url('admin/ajax/set_status_profile_department') ?>",
                type: 'POST',
                data: { status: nextStatus },
                dataType: 'json',
                success: function (response) {
                    const saved = response.status == 1;
                    showProfileMessage(saved, response.message || 'Department status updated.');
                    if (saved && response.data) {
                        $('#profileDeptStatusCell').html(renderDepartmentStatus(response.data.status || ''));
                        $('#profileDeptStatus').val(response.data.status || '');
                        updateDepartmentActionStatus(response.data.status || '');
                    }
                },
                error: function () {
                    showProfileMessage(false, 'Unable to update department status. Please try again.');
                }
            });
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
