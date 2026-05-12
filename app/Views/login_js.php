<script>

$(document).ready(function () {
    $("#btnLoad").hide();
    $('#btnLogin').show();

    $('#txtPass').on('keypress', function (e) {
        if (e.which === 13) {
            $('#btnLogin').click();
        }
    });

    $('#btnLogin').click(function (e) {
        e.preventDefault();

        let username = $('#txtUser').val();
        let password = $('#txtPass').val();

        if (username === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Empty username',
                text: 'Username can\'t be empty.',
            });
            return;
        }

        if (password === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Empty password',
                text: 'Password can\'t be empty.',
            });
            return;
        }

        Swal.fire({
            title: 'Please wait...',
            showConfirmButton: false,
            backdrop: true,
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.post("<?php echo site_url('auth/ajax/login') ?>", {
            'usern': username,
            'passw': password
        }, function (result) {
            Swal.close();

            if (result.status === 1) {
                let urlsearch = new URLSearchParams(window.location.search);
                let param = urlsearch.get('redir');
                location.href = param ?? '<?php echo base_url('admin/dashboard'); ?>';
            } else if (result.status === 2) {
                // Forced password change
                Swal.fire({
                    title: 'Change Password',
                    text: 'You are using a temporary password. Please set a new one.',
                    icon: 'info',
                    html: `
                        <input type="password" id="newPass" class="swal2-input" placeholder="New Password">
                        <input type="password" id="confirmPass" class="swal2-input" placeholder="Confirm Password">
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Update and Login',
                    preConfirm: () => {
                        const newPass = Swal.getPopup().querySelector('#newPass').value;
                        const confirmPass = Swal.getPopup().querySelector('#confirmPass').value;
                        if (!newPass || !confirmPass) {
                            Swal.showValidationMessage(`Please enter both password fields`);
                        }
                        if (newPass !== confirmPass) {
                            Swal.showValidationMessage(`Passwords do not match`);
                        }
                        return { newPassword: newPass };
                    }
                }).then((resetResult) => {
                    if (resetResult.isConfirmed) {
                        Swal.fire({
                            title: 'Updating password...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.post("<?php echo site_url('auth/ajax/change_temp_password') ?>", {
                            userId: result.data.userId,
                            newPassword: resetResult.value.newPassword
                        }, function(finalResult) {
                            if (finalResult.status === 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: finalResult.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.href = '<?php echo base_url('admin/dashboard'); ?>';
                                });
                            } else {
                                Swal.fire('Error', finalResult.message, 'error');
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: "Warning",
                    text: result.message,
                });
            }
        }).fail(function (e) {
            Swal.fire({
                icon: 'error',
                title: e.status + ' ' + e.statusText,
                text: 'Please contact your system administrator if the error persists.',
            });
        });
    });
});


</script>