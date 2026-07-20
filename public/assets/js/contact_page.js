$(document).ready(function () {
    $('#contactForm').submit(function (e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: form.serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message
                    }).then(function () {
                        location.reload(); // Reload page or redirect as needed
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong! Please try again later.'
                });
            }
        });
    });
}); 