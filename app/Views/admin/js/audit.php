<script>
    var tbl = $('#tblaudit').DataTable({
    select: false,
    searching: true,
    ordering: true,
    "order": [[1, "desc"]], // Sort by date column (index 1) descending
    pageLength: 10,
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
    processing: true,
    serverSide: false,
    ajax: {
        "url": "<?php echo site_url('admin/ajax/get_audit'); ?>",
        "type": "POST",
        "data": function (d) {
            return {
                searchAction: $('#searchAction').val(),
                searchDate: $('#searchDate').val()
            };
        }
    },
    columns: [
        { "title": "ID", "data": "ID", "visible": false },
        {
            "title": "Date and Time", 
            "data": "created_date", 
            "className": "dt-center", 
            width: '25%',
            "type": "date", // Tell DataTables this is a date column
            "render": function (data, type, row) {
                if (type === 'sort' || type === 'type') {
                    return data; // Return raw data for sorting
                }
                var date = new Date(data);
                return formatDate(date); // Only format for display
            }
        },
        { "title": "IP Address", "data": "ipaddress" },
        { "title": "Action", "data": "action" },
        {
            "title": "Details", "data": "processDetails", "className": "dt-head-center dt-body-justify"
        },
        {
            "title": "User Name", "data": "userID", "className": "dt-center"
        },
    ],
    initComplete: function() {
        var api = this.api();
        api.on('draw', function() {
            var pageInfo = api.page.info();
            $('#tblaudit_info').html(
                'Showing ' + (pageInfo.start + 1) + ' to ' + 
                (pageInfo.end) + ' of ' + pageInfo.recordsDisplay + ' entries'
            );
        });

        var searchInput = $('#tblaudit_filter input[type="search"]');
        searchInput.attr('placeholder', 'Search audit logs...');
        searchInput.addClass('form-control form-control-sm d-inline-block');
        searchInput.css({
            'width': '250px',
            'margin-left': '0.5rem'
        });
        
        var lengthSelect = $('#tblaudit_length select');
        lengthSelect.addClass('form-select form-select-sm d-inline-block');
        lengthSelect.css({
            'width': 'auto',
            'margin': '0 0.5rem'
        });
    }
});

    // Set max date to today
    document.getElementById('searchDate').max = new Date().toISOString().split('T')[0];

    // Search button click handler
    $('#searchBtn').click(function() {
        tbl.ajax.reload();
    });

    // Clear filters handler
    $('button[type="reset"]').click(function() {
        $('#searchAction').val('');
        $('#searchDate').val('');
        tbl.ajax.reload();
    });

    // Optional: Trigger search on Enter key in search field
    $('#searchAction').keypress(function(e) {
        if(e.which == 13) {
            tbl.ajax.reload();
        }
    });
</script>