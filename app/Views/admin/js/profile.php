<script>
$(document).ready(function () {
    $('#showPasswordFormBtn').on('click', function () {
        $('#passwordChangePanel').toggleClass('d-none');
        if (!$('#passwordChangePanel').hasClass('d-none')) {
            $('#profileOldPassword').trigger('focus');
        }
    });

    $('#changeDepartmentBtn').on('click', function () {
        $('#profileDepartment').prop('readonly', false).trigger('focus');
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
                showProfileMessage(response.status == 1, response.message || 'Profile saved.');
                if (response.status == 1 && response.data) {
                    $('#profileFullName').val(response.data.fullName || '');
                    $('#profileEmail').val(response.data.email || '');
                    $('#profileUsername').val(response.data.username || '');
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
});
</script>
