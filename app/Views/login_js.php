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

            console.log(result.status);
            console.log(result.message);

            if (result.status !== 1) {
                Swal.fire({
                    icon: 'warning',
                    title: "Warning",
                    text: result.message,
                });
            } else {
                let urlsearch = new URLSearchParams(window.location.search);
                let param = urlsearch.get('redir');
                location.href = param ?? '<?php echo base_url('admin/dashboard'); ?>';
            }
        }).fail(function (e) {
            if (e.status === 0) {
                return;
            }
            console.log("Error: " + e.status);
            console.log("Status: " + e.statusText);
            Swal.fire({
                icon: 'error',
                title: e.status + ' ' + e.statusText,
                text: 'Please contact your system administrator if the error persists.',
            });
        });
    });
});


</script>