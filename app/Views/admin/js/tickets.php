<script>
    const ticketUserLevel = '<?= $user->user_lvl ?>'.toUpperCase();
    const currentAdminId  = <?= $user->ID ?>;
    let activeTicketId    = null;

    // ── DataTable ─────────────────────────────────────────────────────────
    var tbl = $('#tbltickets').DataTable({
        select:     false,
        searching:  true,
        ordering:   true,
        order:      [[4, 'desc']], // newest first
        pageLength: 15,
        processing: true,
        responsive: true,
        ajax: {
            url:  '<?= base_url('admin/ajax/get_tickets') ?>',
            type: 'POST',
            data: function (d) {
                d.searchConcern = $('#searchConcern').val();
                d.searchStatus  = $('#searchTicketStatus').val();
                d.dateFrom      = $('#searchDateFrom').val();
                d.dateTo        = $('#searchDateTo').val();
                d.searchAdmin   = $('#searchAdmin').val();
                d.is_datatable  = 1;
            },
            dataSrc: function (json) { return json.data || []; }
        },
        columns: [
            { title: 'ID',        data: 'id',            visible: false },
            {
                title: 'Ticket #', data: 'ticket_number',
                render: function(data) {
                    return `<span class="fw-semibold text-primary">${data}</span>`;
                }
            },
            { title: 'Username',  data: 'username',      defaultContent: '—' },
            {
                title: 'Concern', data: 'concern', width: '35%',
                render: function(data) {
                    return `<span class="text-truncate d-inline-block" style="max-width:280px;" title="${data}">${data}</span>`;
                }
            },
            {
                title: 'Created At', data: 'created_at',
                render: function(data) {
                    return data ? formatDate(new Date(data)) : '—';
                }
            },
            {
                title: 'Status', data: 'status', className: 'dt-center',
                render: function(data) {
                    const map = {
                        'OPEN':        '<span class="badge bg-success">Open</span>',
                        'IN_PROGRESS': '<span class="badge bg-warning text-dark">In Progress</span>',
                        'RESOLVED':    '<span class="badge bg-secondary">Resolved</span>',
                        'REJECTED':    '<span class="badge bg-danger">Rejected</span>'
                    };
                    return map[data] || `<span class="badge bg-light text-dark">${data}</span>`;
                }
            },
            {
                title: 'Assigned To', data: 'admin_fname',
                render: function(data, type, row) {
                    if (!data) return '<span class="text-muted">—</span>';
                    return `${data} ${row.admin_lname || ''}`.trim();
                }
            },
            {
                title: 'Taken At', data: 'taken_at',
                render: function(data) {
                    return data ? `<span style="font-size: 0.85rem;">${formatDate(new Date(data))}</span>` : '<span class="text-muted">—</span>';
                }
            },
            {
                title: 'Resolved At', data: 'resolved_at',
                render: function(data) {
                    return data ? `<span style="font-size: 0.85rem;">${formatDate(new Date(data))}</span>` : '<span class="text-muted">—</span>';
                }
            },
            {
                title: 'Rejected At', data: 'rejected_at',
                render: function(data) {
                    return data ? `<span style="font-size: 0.85rem;">${formatDate(new Date(data))}</span>` : '<span class="text-muted">—</span>';
                }
            },
            {
                title: 'Actions', data: 'id', className: 'dt-center',
                orderable: false,
                render: function(data, type, row) {
                    let html = `
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" data-bs-boundary="viewport">
                            <i class="bi bi-list"></i> Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" onclick="viewTicket(${row.id})">
                                    <i class="bi bi-eye me-1"></i>View
                                </a>
                            </li>`;

                    if (row.status === 'OPEN') {
                        html += `
                            <li>
                                <a class="dropdown-item text-primary" href="#" onclick="quickTake(${row.id})">
                                    <i class="bi bi-hand-index me-1"></i>Take It
                                </a>
                            </li>`;
                    }

                    if (row.status === 'IN_PROGRESS' && parseInt(row.assigned_admin_id) === currentAdminId) {
                        html += `
                            <li>
                                <a class="dropdown-item text-success" href="#" onclick="quickResolve(${row.id})">
                                    <i class="bi bi-check-circle me-1"></i>Resolve
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="quickReject(${row.id})">
                                    <i class="bi bi-x-circle me-1"></i>Reject
                                </a>
                            </li>`;
                    }

                    html += `</ul></div>`;
                    return html;
                }
            }
        ],
        initComplete: function () {
            var searchInput = $('#tbltickets_filter input[type="search"]');
            searchInput.attr('placeholder', 'Quick search…');
            searchInput.removeClass('form-control-sm');
            searchInput.css({ width: '300px', border: '2px solid #388e3c', marginLeft: '10px' });
        }
    });

    // ── Search form ───────────────────────────────────────────────────────
    $('#ticketSearchForm').on('submit', function(e) {
        e.preventDefault();
        tbl.ajax.reload();
    });

    $('#ticketSearchForm').on('reset', function() {
        setTimeout(function() { tbl.ajax.reload(); }, 50);
    });

    // ── View ticket detail ─────────────────────────────────────────────
    function viewTicket(id) {
        activeTicketId = id;
        $.post('<?= site_url('admin/ajax/get_tickets') ?>', { id: id }, function(res) {
            if (res.status !== 1 || !res.data) return;
            const t = res.data;

            $('#dtTicketNumber').text(t.ticket_number || '—');
            $('#dtUsername').text(t.username || '—');
            $('#dtCreatedAt').text(t.created_at ? formatDate(new Date(t.created_at)) : '—');
            $('#dtConcern').text(t.concern || '—');

            // Audit timestamps
            if (t.taken_at) {
                $('#dtTakenAt').text(formatDate(new Date(t.taken_at)));
                $('#dtTakenAtRow').show();
            } else {
                $('#dtTakenAtRow').hide();
            }

            if (t.resolved_at) {
                $('#dtResolvedAt').text(formatDate(new Date(t.resolved_at)));
                $('#dtResolvedAtRow').show();
            } else {
                $('#dtResolvedAtRow').hide();
            }

            if (t.rejected_at) {
                $('#dtRejectedAt').text(formatDate(new Date(t.rejected_at)));
                $('#dtRejectedAtRow').show();
            } else {
                $('#dtRejectedAtRow').hide();
            }

            // Status badge
            const badgeMap = {
                'OPEN':        '<span class="badge bg-success fs-6">Open</span>',
                'IN_PROGRESS': '<span class="badge bg-warning text-dark fs-6">In Progress</span>',
                'RESOLVED':    '<span class="badge bg-secondary fs-6">Resolved</span>',
                'REJECTED':    '<span class="badge bg-danger fs-6">Rejected</span>'
            };
            $('#dtStatus').html(badgeMap[t.status] || t.status);

            // Admin row
            if (t.admin_fname) {
                $('#dtAdmin').text(`${t.admin_fname} ${t.admin_lname || ''}`.trim());
                $('#dtAdminRow').show();
            } else {
                $('#dtAdminRow').hide();
            }

            // Action buttons
            $('#dtTakeBtn').hide();
            $('#dtResolveBtn').hide();
            $('#dtRejectBtn').hide();
            if (t.status === 'OPEN') {
                $('#dtTakeBtn').show();
            } else if (t.status === 'IN_PROGRESS' && parseInt(t.assigned_admin_id) === currentAdminId) {
                $('#dtResolveBtn').show();
                $('#dtRejectBtn').show();
            }

            $('#ticketDetailModal').modal('show');
        });
    }

    function takeTicketFromDetail() {
        if (!activeTicketId) return;
        quickTake(activeTicketId);
        $('#ticketDetailModal').modal('hide');
    }

    function resolveTicketFromDetail() {
        if (!activeTicketId) return;
        quickResolve(activeTicketId);
        $('#ticketDetailModal').modal('hide');
    }

    function quickTake(id) {
        $.post('<?= site_url('admin/ajax/take_ticket') ?>', { id: id }, function(res) {
            if (res.status === 1) {
                tbl.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'Claimed', text: res.message, timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    }

    function quickResolve(id) {
        Swal.fire({
            title: 'Mark as Resolved?',
            text:  'This will close the ticket and notify the user.',
            icon:  'question',
            showCancelButton:    true,
            confirmButtonColor:  '#28a745',
            cancelButtonColor:   '#d33',
            confirmButtonText:   'Yes, resolve!'
        }).then(result => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                $.post('<?= site_url('admin/ajax/resolve_ticket') ?>', { id: id }, function(res) {
                    if (res.status === 1) {
                        tbl.ajax.reload(null, false);
                        Swal.fire('Resolved!', res.message, 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }

    function rejectTicketFromDetail() {
        if (!activeTicketId) return;
        quickReject(activeTicketId);
        $('#ticketDetailModal').modal('hide');
    }

    function quickReject(id) {
        Swal.fire({
            title: 'Reject Ticket?',
            text:  'This will close the ticket as rejected for legitimate concern purposes and notify the user.',
            icon:  'warning',
            showCancelButton:    true,
            confirmButtonColor:  '#d33',
            cancelButtonColor:   '#6c757d',
            confirmButtonText:   'Yes, reject it!'
        }).then(result => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Processing…', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                $.post('<?= site_url('admin/ajax/reject_ticket') ?>', { id: id }, function(res) {
                    if (res.status === 1) {
                        tbl.ajax.reload(null, false);
                        Swal.fire('Rejected!', res.message, 'success');
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }
</script>
