// Careers Page JavaScript

function openPreviewModal(fileUrl, extension) {
    const iframe = document.getElementById('previewIframe');
    const msg = document.getElementById('unsupportedMsg');
    const supported = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    extension = extension.toLowerCase();
    if (supported.includes(extension)) {
        iframe.style.display = 'block';
        msg.style.display = 'none';
        iframe.src = fileUrl;
    } else {
        iframe.style.display = 'none';
        msg.style.display = 'block';
        iframe.src = '';
    }
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    previewModal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize DataTable
    var table = $('#careersTable').DataTable({
        order: [[0, 'desc']],
        paging: true,
        dom: '<"top"f>rt<"bottom"ip><"clear">',
        searching: true,
        info: true,
        autoWidth: false,
        pageLength: 10,
        lengthChange: true,
        responsive: true,
        columnDefs: [
            {
                targets: 0,
                type: 'num',
                className: "dt-center"
            },
            {
                targets: [1, 2, 3], // Level, Preview, and Download
                orderable: false,
                className: "dt-center"
            }
        ]
    });

    // 2. Global Filter for Level and Month
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var row = table.row(dataIndex).node();

            // Level Logic
            var selectedLevel = $('#levelFilter').val();
            var rowLevel = $(row).attr('data-level');
            var levelMatch = (selectedLevel === 'all' || !selectedLevel) ? true :
                (selectedLevel === '4' ? (rowLevel === '1' || rowLevel === '2' || rowLevel === '4') : rowLevel === selectedLevel);

            // Month Logic
            var selectedMonth = $('#searchInput').val();
            var rowMonth = $(row).attr('data-pubmonth');
            var monthMatch = (!selectedMonth || rowMonth === selectedMonth);

            return levelMatch && monthMatch;
        }
    );

    // 3. Event Listeners
    $('#searchInput, #levelFilter').on('change', function() {
        table.draw();
        updateStats();
    });

    function updateStats() {
        var info = table.page.info();
        var selectedMonth = $('#searchInput').val();
        var statsText = '';

        if (selectedMonth) {
            var dateObj = new Date(selectedMonth + '-01');
            var formattedMonth = dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
            statsText = `Showing ${info.recordsDisplay} of ${info.recordsTotal} entries for ${formattedMonth}`;
        } else {
            statsText = `Showing ${info.recordsDisplay} entries`;
        }
        $('#statsText').text(statsText);
    }

    // Initial calls
    updateStats();
    setTimeout(() => $('#loadingOverlay').fadeOut(300), 500);

    table.on('draw', function() {
        updateStats();
    });

    // Button loading state (Delegated)
    $(document).on('click', '.action-btn', function() {
        var btn = $(this);
        var originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        setTimeout(() => btn.html(originalText), 500);
    });

    // Row Animations
    $('#careersTable tbody').on('mouseenter', 'tr', function() {
        $(this).addClass('animate__animated animate__fadeIn');
    });

    // Search focus UI
    $('#searchInput').on('focus', function() {
        $(this).parent().addClass('search-focused');
    }).on('blur', function() {
        $(this).parent().removeClass('search-focused');
    });

    // Modal Cleanup
    const previewModalEl = document.getElementById('previewModal');
    if (previewModalEl) {
        previewModalEl.addEventListener('hidden.bs.modal', function () {
            document.getElementById('previewIframe').src = '';
            document.getElementById('unsupportedMsg').style.display = 'none';
        });
    }
});