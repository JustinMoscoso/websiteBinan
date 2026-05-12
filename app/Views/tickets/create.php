<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Ticket - Compose</title>
    <link href="<?= site_url('assets/img/binanlogo.png'); ?>" rel="icon">
    <?php pre_styles('admin'); ?>
    <style>
        body { background-color: #f6f8fc; }
        .compose-container { max-width: 600px; margin: 50px auto; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24); overflow: hidden; }
        .compose-header { background: #f2f6fc; padding: 10px 15px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; }
        .compose-header h6 { margin: 0; color: #444; font-weight: 500; }
        .compose-body { padding: 20px; }
        .form-group-custom { border-bottom: 1px solid #e0e0e0; padding: 10px 0; }
        .form-group-custom label { color: #777; width: 80px; font-size: 14px; }
        .form-group-custom input { border: none; outline: none; width: calc(100% - 85px); font-size: 14px; }
        .compose-body textarea { border: none; outline: none; width: 100%; height: 250px; resize: none; margin-top: 15px; font-size: 14px; }
        .compose-footer { padding: 15px; border-top: 1px solid #e0e0e0; display: flex; align-items: center; }
        .btn-send { background-color: #0b57d0; color: #fff; border-radius: 20px; padding: 8px 24px; font-weight: 500; border: none; }
        .btn-send:hover { background-color: #0842a0; color: #fff; }
        .login-link { color: #0b57d0; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="compose-container">
    <div class="compose-header">
        <h6>New Message</h6>
        <a href="<?= base_url('login') ?>" class="btn-close" aria-label="Close"></a>
    </div>
    <form id="ticketForm">
        <div class="compose-body">
            <div class="form-group-custom d-flex align-items-center">
                <label for="username">To:</label>
                <span>Support Team</span>
            </div>
            <div class="form-group-custom d-flex align-items-center">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username or email" required>
            </div>
            <textarea id="concern" name="concern" placeholder="Write your concern here..." required></textarea>
        </div>
        <div class="compose-footer">
            <button type="submit" class="btn-send me-3" id="btnSubmit">Send</button>
            <a href="<?= base_url('login') ?>" class="login-link">Back to Login</a>
        </div>
    </form>
</div>

<?php pre_scripts('admin'); ?>
<script>
    $(document).ready(function() {
        $('#ticketForm').submit(function(e) {
            e.preventDefault();
            
            let username = $('#username').val();
            let concern = $('#concern').val();

            if (!username || !concern) {
                Swal.fire('Warning', 'Please fill in all fields', 'warning');
                return;
            }

            Swal.fire({
                title: 'Sending ticket...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.post("<?= site_url('ticket/submit') ?>", {
                username: username,
                concern: concern
            }, function(result) {
                Swal.close();
                if (result.status === 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Ticket Submitted',
                        text: `Your ticket number is: ${result.ticket_number}. A confirmation email has been sent.`,
                    }).then(() => {
                        window.location.href = "<?= base_url('login') ?>";
                    });
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            }).fail(function() {
                Swal.fire('Error', 'Unable to process your request. Please try again later.', 'error');
            });
        });
    });
</script>

</body>
</html>
