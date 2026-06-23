<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <!-- Favicons -->
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="icon" type="image/png">
    <link href="<?= base_url('assets/img/binanlogo.png'); ?>" rel="apple-touch-icon">
    <?php pre_styles('home'); ?>
</head>
<body>
    <?php include "navbar.php"; ?>
    <?php include "header.php"; ?>
    <?php include_header('Hotlines'); ?>
    <div id="app">
        <div class="container-xxl service py-5">
            <div class="container justify-content-center">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="tab-content w-100">
                            <div class="tab-pane fade show active">
                                <div class="row g-4 d-flex justify-content-center">
                                    <div class="col-md-12">
                                        <!-- Modern Carded Table Container -->
                                        <div class="hotline-card">
                                            <div class="filter-container">
                                                <select id="filterSelect" class="form-select">
                                                    <option value="all">All</option>
                                                    <option value="department">Department</option>
                                                    <option value="barangay">Barangay</option>
                                                    <option value="others">Others</option>
                                                </select>
                                                <input type="text" id="searchInput" class="form-control" placeholder="Search hotlines...">
                                            </div>
                                            <div class="table-responsive">
                                                <table id="contactTable" class="table table-modern">
                                                    <thead>
                                                        <tr id="tableHeader">
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($hotlines)): ?>
                                                            <?php foreach ($hotlines as $hotline): ?>
                                                                <tr class="hotline-row" data-category="<?= htmlspecialchars($hotline->section ?? '') ?>">
                                                                    <td>
                                                                        <?php
                                                                        // Display the appropriate name based on the section
                                                                        switch ($hotline->section ?? '') {
                                                                            case 'Barangay':
                                                                                echo htmlspecialchars($hotline->brgy_name ?? '-');
                                                                                break;
                                                                            case 'Department':
                                                                                echo htmlspecialchars($hotline->dept_name ?? '-');
                                                                                break;
                                                                            case 'Others':
                                                                                echo htmlspecialchars($hotline->content_ref_id ?? '-');
                                                                                break;
                                                                            default:
                                                                                echo '-';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td><?= htmlspecialchars($hotline->number ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($hotline->telco ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($hotline->smart ?? '-') ?></td>
                                                                    <td><?= htmlspecialchars($hotline->globe ?? '-') ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr>
                                                                <td colspan="5">No Hotlines available.</td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- End Modern Carded Table Container -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "footer.php"; ?>
    <?php pre_scripts('home'); ?>
    
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#contactTable').DataTable({
            "order": [[0, "asc"]],
            "paging": true, // Enable pagination
            "searching": true, // Enable built-in search
            "dom": "ltip", // Hide default search input, keep length changing, table, info, pagination
            "info": true,
            "autoWidth": false,
            "columnDefs": [
                { "targets": 0, "data": "name" },
                { "targets": 1, "data": "pldt" },
                { "targets": 2, "data": "intelco" },
                { "targets": 3, "data": "smart" },
                { "targets": 4, "data": "globe" }
            ]
        });

        // Function to update table headers based on filter
        function updateTableHeader(filter) {
            var headers = {
                all: ['All Hotlines', 'PLDT', 'INTELCO', 'SMART', 'GLOBE'],
                department: ['Office', 'PLDT', 'INTELCO', 'SMART', 'GLOBE'],
                barangay: ['Barangay', 'PLDT', 'INTELCO', 'SMART', 'GLOBE'],
                others: ['Others', 'PLDT', 'INTELCO', 'SMART', 'GLOBE']
            };

            // Clear existing headers
            $('#contactTable thead').empty();

            // Append new headers
            var headerHtml = '<tr>';
            headers[filter].forEach(function(header) {
                headerHtml += '<th scope="col">' + header + '</th>';
            });
            headerHtml += '</tr>';
            $('#contactTable thead').append(headerHtml);

            // Redraw DataTable to apply changes
            table.columns.adjust().draw();
        }

        // Event listener for filter change
        $('#filterSelect').on('change', function() {
            var selectedCategory = $(this).val();
            updateTableHeader(selectedCategory);

            // Filter rows based on category
            $('.hotline-row').each(function() {
                var rowCategory = $(this).data('category');
                if (selectedCategory === 'all') {
                    $(this).show();
                } else if (selectedCategory === 'department' && rowCategory === 'Department') {
                    $(this).show();
                } else if (selectedCategory === 'barangay' && rowCategory === 'Barangay') {
                    $(this).show();
                } else if (selectedCategory === 'others' && rowCategory === 'Others') {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // Redraw DataTable after filtering rows
            table.rows().invalidate().draw();
        });

        // Event listener for search input
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw(); // Search across all columns
        });

        // Initialize header based on default filter
        updateTableHeader($('#filterSelect').val());
    });
</script>

</body>
</html>
