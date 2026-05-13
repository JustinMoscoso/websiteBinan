<script>
    let siteurl = "<?php echo site_url('assets');?>";
    let baseurl = "<?php echo base_url('writable/uploads/public_content/');?>";

    function findIndexByFirstColumnValue(array, value) {
        for (var i = 0; i < array.length; i++) {
            if (array[i][0] === value) {
                return i; // Return the index if the value is found in the first column
            }
        }
        return -1; // Return -1 if the value is not found
    }

    function formatDate(date) {
        var monthNames = [
            "Jan", "Feb", "Mar",
            "Apr", "May", "Jun", "Jul",
            "Aug", "Sep", "Oct",
            "Nov", "Dec"
        ];

        var monthIndex = date.getMonth();
        var day = date.getDate();
        var year = date.getFullYear();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // 0 should be displayed as 12
        hours = hours < 10 ? '0' + hours : hours; // Add leading zero if single digit
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        return monthNames[monthIndex] + ' ' + day + ', ' + year + ' ' + hours + ':' + minutes + ':' + seconds + ' ' + ampm;
    }

    // Support Ticket Logic
    function fetchTickets() {
        $.post("<?= site_url('admin/ajax/get_tickets') ?>", {}, function(result) {
            if (result.status === 1) {
                let tickets = result.data;
                let totalCount = tickets.length;

                // Update badge and header text
                if (totalCount > 0) {
                    $('#ticketBadge').text(totalCount).show();
                } else {
                    $('#ticketBadge').hide();
                }
                $('#ticketCountText').text(totalCount);

                let html = '';
                if (tickets.length === 0) {
                    html = '<li class="notification-item"><div class="p-3 text-center text-muted">No new tickets</div></li>';
                } else {
                    tickets.forEach(ticket => {
                        let statusBadge = '';
                        let actionButton = '';
                        let adminText = '';

                        if (ticket.status === 'OPEN') {
                            statusBadge = '<span class="badge bg-success">Open</span>';
                            actionButton = `<button class="btn btn-sm btn-primary mt-2" onclick="takeTicket(${ticket.id})">Take It</button>`;
                        } else if (ticket.status === 'IN_PROGRESS') {
                            statusBadge = '<span class="badge bg-warning">In Progress</span>';
                            adminText = `<p class="mb-0 small text-muted">Claimed by: ${ticket.admin_fname} ${ticket.admin_lname}</p>`;
                            
                            // Check if current user is the one who took it
                            const currentAdminId = <?= $user->ID ?>;
                            if (parseInt(ticket.assigned_admin_id) === currentAdminId) {
                                actionButton = `
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-success me-1" onclick="resolveTicket(${ticket.id})">Resolve</button>
                                        <button class="btn btn-sm btn-danger" onclick="rejectTicket(${ticket.id})">Reject</button>
                                    </div>`;
                            }
                        }

                        html += `
                            <li class="notification-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div style="flex: 1;">
                                        <h4 class="mb-1" style="font-size: 14px; color: #012970;">${ticket.ticket_number}</h4>
                                        <p class="mb-1 text-dark" style="font-size: 13px;">${ticket.concern}</p>
                                        <p class="mb-0 small text-muted">From: ${ticket.username} | ${ticket.created_at}</p>
                                        ${adminText}
                                    </div>
                                    ${statusBadge}
                                </div>
                                ${actionButton}
                            </li>
                        `;
                    });
                }
                $('#ticketItems').html(html);
            }
        }, 'json');
    }

    function takeTicket(id) {
        $.post("<?= site_url('admin/ajax/take_ticket') ?>", { id: id }, function(result) {
            if (result.status === 1) {
                fetchTickets();
                Swal.fire({
                    icon: 'success',
                    title: 'Ticket Claimed',
                    text: result.message,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        }, 'json');
    }

    function resolveTicket(id) {
        Swal.fire({
            title: 'Mark as Resolved?',
            text: "This will close the ticket and notify the user via email.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, resolve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we notify the user.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.post("<?= site_url('admin/ajax/resolve_ticket') ?>", { id: id }, function(res) {
                    if (res.status === 1) {
                        fetchTickets();
                        Swal.fire('Resolved!', res.message, 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }, 'json');
            }
        });
    }

    function rejectTicket(id) {
        Swal.fire({
            title: 'Reject Ticket?',
            text: "This will mark the ticket as rejected for legitimately concern purposes and notify the user via email.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we notify the user.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.post("<?= site_url('admin/ajax/reject_ticket') ?>", { id: id }, function(res) {
                    if (res.status === 1) {
                        fetchTickets();
                        Swal.fire('Rejected!', res.message, 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }, 'json');
            }
        });
    }

    // Initial fetch and set interval
    $(document).ready(function() {
        const userLvl = "<?= $user->user_lvl ?>";
        if (["DEVELOPER", "SUPERADMIN", "ADMIN"].includes(userLvl)) {
            fetchTickets();
            setInterval(fetchTickets, 5000); // Refresh every 5 seconds (real-time)
        }
    });
</script>