<script>
    function formatDetails(details, action) {
        if (!details) return '<span class="text-muted">No details provided</span>';

        var str = details.trim();
        var actionLower = action ? action.toLowerCase() : '';

        // Parse common IDs using regex
        var idMatch = str.match(/(?:BRGY|DEPT|NEWS|ANNOUNCEMENT|ANNNOUNCEMENT|MAYOR|FULLDISC|CAREER|INVEST|SERVICE|CONTACT|HOTLINE|ABOUT|PROFILE|PROFILE_PASSWORD|PROFILE_IMAGE|PROFILE_DEPT|ACCOUNT|CITYOFFICIAL|MAP|JOB)_ID:\s*(\d+)/i);
        var id = idMatch ? idMatch[1] : null;

        // Check status
        var status = null;
        if (str.toUpperCase().indexOf('INACTIVE') !== -1) {
            status = '<strong style="color: #858796;">Inactive</strong>';
        } else if (str.toUpperCase().indexOf('ACTIVE') !== -1) {
            status = '<strong style="color: #858796;">Active</strong>';
        } else if (str.toUpperCase().indexOf('ARCHIVED') !== -1) {
            status = '<strong style="color: #858796;">Archived</strong>';
        } else if (str.toUpperCase().indexOf('DELETED') !== -1) {
            status = '<strong style="color: #858796;">Deleted</strong>';
        }

        // Title matching
        var titleMatch = str.match(/TITLE:\s*(.+)$/i) || str.match(/TITLE\s*:\s*(.+)$/i);
        var title = titleMatch ? titleMatch[1].trim() : null;

        // Year and Qtr matching
        var yearMatch = str.match(/(?:YEAR\/QTR:\s*)?(\d{4})\s*\/\s*([a-zA-Z0-9]+)/i) || str.match(/(?:FULLDISC_ID:\s*\d+\s+)?(\d{4})\s*-\s*([a-zA-Z0-9]+)/i);
        var year = yearMatch ? yearMatch[1] : null;
        var qtr = yearMatch ? yearMatch[2] : null;

        // Helper to format ID
        var formattedId = id ? ' <span class="text-secondary small">(ID: ' + id + ')</span>' : '';

        try {
            if (actionLower.startsWith('create_')) {
                var module = actionLower.replace('create_', '');
                if (module === 'postcontent') {
                    return 'Created new post content' + (title ? ': "<strong>' + title + '</strong>"' : '') + formattedId;
                }
                if (module === 'fulldiscpol') {
                    var categoryMatch = str.match(/FULLDISC_ID:\s*\d+\s+(.+?)\s+\d{4}\s*-\s*/i);
                    var category = categoryMatch ? categoryMatch[1].trim() : '';
                    var period = (year && qtr) ? ' for ' + year + ' (' + qtr + ' Quarter)' : '';
                    return 'Created new Full Disclosure Policy record' + (category ? ': "<strong>' + category + '</strong>"' : '') + period + formattedId;
                }
                if (module === 'cityoff') {
                    var posMatch = str.match(/CITYOFFICIAL_ID:\s*\d+\s+(.+)$/i);
                    var position = posMatch ? posMatch[1].trim() : '';
                    return 'Created city official record' + (position ? ' for <strong>' + position + '</strong>' : '') + formattedId;
                }
                if (module === 'barangay') {
                    var brgyMatch = str.match(/BRGY_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/BRGY_ID:\s*\d+\s+(.+)$/i);
                    var brgy = brgyMatch ? brgyMatch[1].trim() : '';
                    return 'Created new barangay record' + (brgy ? ': "<strong>' + brgy + '</strong>"' : '') + formattedId;
                }
                if (module === 'dept') {
                    var deptMatch = str.match(/DEPT_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/DEPT_ID:\s*\d+\s*(.+)$/i);
                    var dept = deptMatch ? deptMatch[1].trim() : '';
                    return 'Created new department record' + (dept ? ': "<strong>' + dept + '</strong>"' : '') + formattedId;
                }
                if (module === 'mayor') {
                    var mNameMatch = str.match(/MAYOR_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/MAYOR_ID:\s*\d+\s*(.+)$/i);
                    var mName = mNameMatch ? mNameMatch[1].trim() : '';
                    return 'Created new Mayor\'s content' + (mName ? ': "<strong>' + mName + '</strong>"' : '') + formattedId;
                }
                if (module === 'news' || module === 'anns') {
                    var displayMod = (module === 'news') ? 'news article' : 'announcement';
                    return 'Created new ' + displayMod + (title ? ': "<strong>' + title + '</strong>"' : '') + formattedId;
                }
                if (module === 'job') {
                    return 'Created new job posting' + (title ? ': "<strong>' + title + '</strong>"' : '') + formattedId;
                }
                if (module === 'contact') {
                    var contMatch = str.match(/(?:CONTACT|HOTLINE)_ID:\s*\d+\s+([^\-\[]+)/i);
                    var contName = contMatch ? contMatch[1].trim() : '';
                    return 'Created contact/hotline entry' + (contName ? ' for <strong>' + contName + '</strong>' : '') + formattedId;
                }
                if (module === 'invest') {
                    var categoryMatch = str.match(/INVEST_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/INVEST_ID:\s*\d+\s*(.+)$/i);
                    var category = categoryMatch ? categoryMatch[1].trim() : '';
                    return 'Created new investment content' + (category ? ': "<strong>' + category + '</strong>"' : '') + formattedId;
                }
                return 'Created new ' + module.replace('_', ' ') + ' record' + formattedId;
            }

            if (actionLower.startsWith('update_')) {
                var module = actionLower.replace('update_', '');
                if (module === 'postcontent') {
                    return 'Updated post content' + (title ? ': "<strong>' + title + '</strong>"' : '') + formattedId;
                }
                if (module === 'fulldiscpol') {
                    var categoryMatch = str.match(/FULLDISC_ID:\s*\d+\s+(.+?)\s+\d{4}\s*-\s*/i);
                    var category = categoryMatch ? categoryMatch[1].trim() : '';
                    var period = (year && qtr) ? ' for ' + year + ' (' + qtr + ' Quarter)' : '';
                    return 'Updated Full Disclosure Policy record' + (category ? ': "<strong>' + category + '</strong>"' : '') + period + formattedId;
                }
                if (module === 'cityoff') {
                    var nameMatch = str.match(/CITYOFFICIAL_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/CITYOFFICIAL_ID:\s*\d+\s*(.+)$/i);
                    var name = nameMatch ? nameMatch[1].trim() : '';
                    return 'Updated city official' + (name ? ' details for <strong>' + name + '</strong>' : '') + formattedId;
                }
                if (module === 'barangay') {
                    var brgyMatch = str.match(/BRGY_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/BRGY_ID:\s*\d+\s*(.+)$/i);
                    var brgy = brgyMatch ? brgyMatch[1].trim() : '';
                    return 'Updated barangay record' + (brgy ? ' "<strong>' + brgy + '</strong>"' : '') + formattedId;
                }
                if (module === 'dept') {
                    var deptMatch = str.match(/DEPT_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/DEPT_ID:\s*\d+\s*(.+)$/i);
                    var dept = deptMatch ? deptMatch[1].trim() : '';
                    return 'Updated department details' + (dept ? ' for "<strong>' + dept + '</strong>"' : '') + formattedId;
                }
                if (module === 'mayor') {
                    var mNameMatch = str.match(/MAYOR_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/MAYOR_ID:\s*\d+\s*(.+)$/i);
                    var mName = mNameMatch ? mNameMatch[1].trim() : '';
                    return 'Updated Mayor\'s content' + (mName ? ' for "<strong>' + mName + '</strong>"' : '') + formattedId;
                }
                if (module === 'news') {
                    return 'Updated news article' + (title ? ': "<strong>' + title + '</strong>"' : '') + formattedId;
                }
                if (module === 'policy') {
                    var period = (year && qtr) ? ' to ' + year + ' (' + qtr + ' Quarter)' : '';
                    return 'Updated system policy period' + period;
                }
                if (module === 'job') {
                    return 'Updated job posting' + (title ? ': "<strong>' + title + '</strong>"' : '') + formattedId;
                }
                if (module === 'profile') {
                    var nameMatch = str.match(/PROFILE_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/PROFILE_ID:\s*\d+\s*(.+)$/i);
                    var name = nameMatch ? nameMatch[1].trim() : '';
                    return 'Updated profile account details' + (name ? ' for "<strong>' + name + '</strong>"' : '') + formattedId;
                }
                if (module === 'user') {
                    var nameMatch = str.match(/ACCOUNT_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/ACCOUNT_ID:\s*\d+\s*(.+)$/i);
                    var name = nameMatch ? nameMatch[1].trim() : '';
                    return 'Updated user account' + (name ? ' details for "<strong>' + name + '</strong>"' : '') + formattedId;
                }
                if (module === 'profile_department') {
                    return 'Updated linked department details' + formattedId;
                }
                if (module === 'contact') {
                    var contMatch = str.match(/(?:CONTACT|HOTLINE)_ID:\s*\d+\s+([^\-\[]+)/i);
                    var contName = contMatch ? contMatch[1].trim() : '';
                    return 'Updated contact/hotline entry' + (contName ? ' for <strong>' + contName + '</strong>' : '') + formattedId;
                }
                if (module === 'invest') {
                    var categoryMatch = str.match(/INVEST_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/INVEST_ID:\s*\d+\s*(.+)$/i);
                    var category = categoryMatch ? categoryMatch[1].trim() : '';
                    return 'Updated investment content' + (category ? ' "<strong>' + category + '</strong>"' : '') + formattedId;
                }
                return 'Updated ' + module.replace('_', ' ') + ' record' + formattedId;
            }

            if (actionLower.startsWith('set_status_')) {
                var module = actionLower.replace('set_status_', '');
                var label = module.replace('_', ' ');
                if (module === 'anns') label = 'announcement';
                if (module === 'fulldiscpol') label = 'Full Disclosure Policy';

                var entityName = '';
                var regex = new RegExp('(?:' + module.toUpperCase() + '|ACCOUNT|PROFILE|BRGY|DEPT|JOB|NEWS|ANNOUNCEMENT|ANNNOUNCEMENT|CITYOFFICIAL|SERVICE|CONTACT|HOTLINE|INVEST|FULLDISC)_ID:\\s*\\d+\\s+([^\\-\\[]+)', 'i');
                var match = str.match(regex);
                if (match && match[1]) {
                    entityName = match[1].trim();
                } else if (title) {
                    entityName = title;
                }

                var nameDisplay = entityName ? ': "<strong>' + entityName + '</strong>"' : '';
                return 'Changed ' + label + nameDisplay + ' status to ' + (status || 'updated status') + formattedId;
            }

            if (actionLower.startsWith('delete_')) {
                var module = actionLower.replace('delete_', '');
                var label = module.replace('_', ' ');
                if (module === 'anns') label = 'announcement';
                if (module === 'fulldiscpol') label = 'Full Disclosure Policy';

                var entityName = '';
                var regex = new RegExp('(?:' + module.toUpperCase() + '|ACCOUNT|PROFILE|BRGY|DEPT|JOB|NEWS|ANNOUNCEMENT|ANNNOUNCEMENT|CITYOFFICIAL|SERVICE|CONTACT|HOTLINE|INVEST|FULLDISC)_ID:\\s*\\d+\\s+([^\\-\\[]+)', 'i');
                var match = str.match(regex);
                if (match && match[1]) {
                    entityName = match[1].trim();
                } else if (title) {
                    entityName = title;
                }

                var nameDisplay = entityName ? ': "<strong>' + entityName + '</strong>"' : '';
                return 'Deleted ' + label + nameDisplay + ' record' + formattedId;
            }

            if (actionLower === 'change_profile_password') {
                var nameMatch = str.match(/PROFILE_PASSWORD_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/PROFILE_PASSWORD_ID:\s*\d+\s*(.+)$/i);
                var name = nameMatch ? nameMatch[1].trim() : '';
                return 'Changed profile login password' + (name ? ' for "<strong>' + name + '</strong>"' : '') + formattedId;
            }
            if (actionLower === 'update_profile_picture') {
                var nameMatch = str.match(/PROFILE_IMAGE_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/PROFILE_IMAGE_ID:\s*\d+\s*(.+)$/i);
                var name = nameMatch ? nameMatch[1].trim() : '';
                return 'Uploaded a new profile picture' + (name ? ' for "<strong>' + name + '</strong>"' : '') + formattedId;
            }
            if (actionLower === 'reset_pass_account' || actionLower === 'reset_password') {
                var nameMatch = str.match(/ACCOUNT_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/ACCOUNT_ID:\s*\d+\s*(.+)$/i);
                var name = nameMatch ? nameMatch[1].trim() : '';
                return 'Triggered password reset and notification email' + (name ? ' for "<strong>' + name + '</strong>"' : '') + formattedId;
            }

        } catch (e) {
            console.error('Error formatting log details:', e);
        }

        // Generic patterns fallbacks
        if (str.match(/^BRGY_ID:/i)) {
            var rest = str.replace(/^BRGY_ID:\s*\d*\s*/i, '');
            return 'Barangay' + formattedId + (rest ? ' - <strong>' + rest + '</strong>' : '') + (status ? ' (' + status + ')' : '');
        }
        if (str.match(/^DEPT_ID:/i)) {
            var rest = str.replace(/^DEPT_ID:\s*\d*\s*/i, '');
            return 'Department' + formattedId + (rest ? ' - <strong>' + rest + '</strong>' : '') + (status ? ' (' + status + ')' : '');
        }
        if (str.match(/^NEWS_ID:/i)) {
            return 'News Article' + formattedId + (title ? ': "<strong>' + title + '</strong>"' : '') + (status ? ' (' + status + ')' : '');
        }
        if (str.match(/^ANNOUNCEMENT_ID:/i) || str.match(/^ANNNOUNCEMENT_ID:/i)) {
            return 'Announcement' + formattedId + (title ? ': "<strong>' + title + '</strong>"' : '') + (status ? ' (' + status + ')' : '');
        }
        if (str.match(/^ACCOUNT_ID:/i)) {
            var nameMatch = str.match(/ACCOUNT_ID:\s*\d+\s+([^\s\[]+(?:\s+[^\s\[]+)*)/i);
            var typeMatch = str.match(/\[([A-Z_]+)\]/i);
            var name = nameMatch ? nameMatch[1].trim() : '';
            var type = typeMatch ? typeMatch[1] : '';
            return 'User Account' + formattedId + (name ? ': "<strong>' + name + '</strong>"' : '') + (type ? ' <span class="badge bg-light text-dark">' + type + '</span>' : '') + (status ? ' (' + status + ')' : '');
        }
        if (str.match(/^INVEST_ID:/i)) {
            var categoryMatch = str.match(/INVEST_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/INVEST_ID:\s*\d+\s*(.+)$/i);
            var category = categoryMatch ? categoryMatch[1].trim() : '';
            return 'Investment Content' + formattedId + (category ? ': "<strong>' + category + '</strong>"' : '') + (status ? ' (' + status + ')' : '');
        }
        if (str.match(/^FULLDISC_ID:/i)) {
            var categoryMatch = str.match(/FULLDISC_ID:\s*\d+\s+(.+?)\s+\d{4}\s*-\s*/i) || str.match(/FULLDISC_ID:\s*\d+\s+([^\-\[#]+)/i) || str.match(/FULLDISC_ID:\s*\d+\s*(.+)$/i);
            var category = categoryMatch ? categoryMatch[1].trim() : '';
            var period = (year && qtr) ? ' for ' + year + ' (' + qtr + ' Quarter)' : '';
            return 'Full Disclosure Policy' + (category ? ': "<strong>' + category + '</strong>"' : '') + period + formattedId + (status ? ' (' + status + ')' : '');
        }

        var cleanStr = str.replace(/_/g, ' ');
        return cleanStr;
    }

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
                d.searchAction = $('#searchAction').val();
                d.searchDateFrom = $('#searchDateFrom').val();
                d.searchDateTo = $('#searchDateTo').val();
            },
            "dataSrc": function (json) {
                return json.data || [];
            }
        },
        columns: [
            { "title": "ID", "data": "ID", "visible": false },
            {
                "title": "Time and Date",
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
            { "title": "Action", "data": "action", "className": "dt-center" },
            {
                "title": "Details",
                "data": "processDetails",
                "className": "dt-center align-middle",
                "render": function (data, type, row) {
                    var humanDetails = formatDetails(data, row.action);
                    var escapedData = data ? data.replace(/"/g, '&quot;') : '';
                    return '<span title="Raw Log Data: ' + escapedData + '" style="cursor: help; color: #858796;">' + humanDetails + '</span>';
                }
            },
            {
                "title": "Device",
                "data": "device",
                "className": "dt-center",
                "render": function (data, type, row) {
                    if (!data || data === 'Unknown') return '<span class="text-muted">Unknown</span>';
                    var icon = '<i class="bi bi-laptop"></i>';
                    var badgeClass = 'bg-secondary text-white';
                    if (data.toLowerCase().indexOf('mobile') !== -1) {
                        icon = '<i class="bi bi-phone"></i>';
                        badgeClass = 'bg-info text-dark';
                    } else if (data.toLowerCase().indexOf('bot') !== -1 || data.toLowerCase().indexOf('robot') !== -1) {
                        icon = '<i class="bi bi-robot"></i>';
                        badgeClass = 'bg-warning text-dark';
                    }
                    return '<span class="badge ' + badgeClass + ' d-inline-flex align-items-center gap-1">' + icon + ' ' + data + '</span>';
                }
            },
            {
                "title": "Browser",
                "data": "browser",
                "className": "dt-center",
                "render": function (data, type, row) {
                    if (!data || data === 'Unknown') return '<span class="text-muted">Unknown</span>';
                    return '<span class="d-inline-flex align-items-center gap-1"><i class="bi bi-globe text-success"></i> ' + data + '</span>';
                }
            },
            { "title": "IP Address", "data": "ipaddress", "className": "dt-center" },
            {
                "title": "Username", "data": "userID", "className": "dt-center"
            },
        ],
        initComplete: function () {
            var api = this.api();
            api.on('draw', function () {
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

    function pad2(n) {
        return String(n).padStart(2, '0');
    }

    function monthLabel(monthNumber) {
        var monthNames = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        var index = parseInt(monthNumber, 10) - 1;
        return monthNames[index] || '';
    }

    var currentAuditYear = <?= (int) date('Y'); ?>;
    var defaultAuditYearStart = getAuditYearWindowStart(currentAuditYear);

    var auditPickerState = {
        from: { yearStart: defaultAuditYearStart, selectedYear: '', selectedMonth: '' },
        to: { yearStart: defaultAuditYearStart, selectedYear: '', selectedMonth: '' }
    };

    function getAuditYearWindowStart(year) {
        var baseYear = 2011;
        var numericYear = parseInt(year, 10);

        if (isNaN(numericYear) || numericYear < baseYear) {
            return baseYear;
        }

        return baseYear + (Math.floor((numericYear - baseYear) / 12) * 12);
    }

    function getYearWindow(state) {
        var start = state.yearStart;
        return [start, start + 11];
    }

    function getDefaultYearForTarget(target) {
        var yearWindow = getYearWindow(auditPickerState[target]);

        if (currentAuditYear >= yearWindow[0] && currentAuditYear <= yearWindow[1]) {
            return String(currentAuditYear);
        }

        return String(yearWindow[0]);
    }

    function renderAuditMonthGrid(target) {
        var html = '';
        var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        for (var i = 1; i <= 12; i++) {
            var active = auditPickerState[target].selectedMonth === pad2(i) ? ' active' : '';
            html += '<button type="button" class="audit-range-month' + active + '" data-target="' + target + '" data-month="' + pad2(i) + '">' + monthNames[i - 1] + '</button>';
        }

        $('#monthGrid' + (target === 'from' ? 'From' : 'To')).html(html);
    }

    function renderAuditYearGrid(target) {
        var state = auditPickerState[target];
        var yearWindow = getYearWindow(state);
        var html = '';

        $('#rangeHeader' + (target === 'from' ? 'From' : 'To')).text(yearWindow[0] + '-' + yearWindow[1]);

        for (var year = yearWindow[0]; year <= yearWindow[1]; year++) {
            var active = String(year) === String(state.selectedYear) ? ' active' : '';
            html += '<button type="button" class="audit-range-picker-cell' + active + '" data-target="' + target + '" data-year="' + year + '">' + year + '</button>';
        }

        $('#yearGrid' + (target === 'from' ? 'From' : 'To')).html(html);
        renderAuditMonthGrid(target);
    }

    function renderAuditPickerDisplays() {
        var fromLabel = auditPickerState.from.selectedMonth && auditPickerState.from.selectedYear
            ? monthLabel(auditPickerState.from.selectedMonth) + ' ' + auditPickerState.from.selectedYear
            : 'Month Year';
        var toLabel = auditPickerState.to.selectedMonth && auditPickerState.to.selectedYear
            ? monthLabel(auditPickerState.to.selectedMonth) + ' ' + auditPickerState.to.selectedYear
            : 'Month Year';

        $('#rangeDisplayFrom span').text(fromLabel === 'Custom Range' ? 'Month Year' : fromLabel);
        $('#rangeDisplayTo span').text(toLabel === 'Custom Range' ? 'Month Year' : toLabel);
    }

    function syncAuditRange() {
        var fromMonth = auditPickerState.from.selectedMonth;
        var fromYear = auditPickerState.from.selectedYear;
        var toMonth = auditPickerState.to.selectedMonth;
        var toYear = auditPickerState.to.selectedYear;

        if (fromMonth && fromYear) {
            $('#searchDateFrom').val(startOfMonthIso(fromYear, fromMonth));
        } else {
            $('#searchDateFrom').val('');
        }

        if (toMonth && toYear) {
            $('#searchDateTo').val(endOfMonthIso(toYear, toMonth));
        } else {
            $('#searchDateTo').val('');
        }

        if (fromMonth && fromYear && toMonth && toYear) {
            $('#auditRangeLabel').text(monthLabel(fromMonth) + ' ' + fromYear + ' - ' + monthLabel(toMonth) + ' ' + toYear);
        } else if (fromMonth && fromYear) {
            $('#auditRangeLabel').text(monthLabel(fromMonth) + ' ' + fromYear);
        } else if (toMonth && toYear) {
            $('#auditRangeLabel').text(monthLabel(toMonth) + ' ' + toYear);
        } else {
            $('#auditRangeLabel').text('Select Range');
        }
    }

    function startOfMonthIso(year, month) {
        return year + '-' + pad2(month) + '-01';
    }

    function endOfMonthIso(year, month) {
        var lastDay = new Date(year, month, 0).getDate();
        return year + '-' + pad2(month) + '-' + pad2(lastDay);
    }

    function closeAuditRangePopover() {
        $('#auditRangePopover').removeClass('is-open');
        $('#auditRangeToggle').attr('aria-expanded', 'false');
    }

    renderAuditYearGrid('from');
    renderAuditYearGrid('to');
    renderAuditPickerDisplays();

    $('#auditRangeToggle').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#auditRangePopover').toggleClass('is-open');
        $('#auditRangeToggle').attr('aria-expanded', $('#auditRangePopover').hasClass('is-open') ? 'true' : 'false');
    });

    $('#auditRangePopover').on('click', function (e) {
        e.stopPropagation();
    });

    $('#auditRangeCancel').on('click', function (e) {
        e.stopPropagation();
        closeAuditRangePopover();
    });

    $('#auditRangeApply').on('click', function (e) {
        e.stopPropagation();
        syncAuditRange();
        closeAuditRangePopover();
        tbl.ajax.reload();
    });

    $('#auditRangePopover').on('click', '.audit-range-picker-nav', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var target = $(this).data('target');
        var nav = $(this).data('nav');
        var state = auditPickerState[target];
        state.yearStart += nav === 'prev' ? -12 : 12;
        if (state.yearStart < 1900) {
            state.yearStart = 1900;
        }
        renderAuditYearGrid(target);
    });

    $('#auditRangePopover').on('click', '.audit-range-picker-cell', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var target = $(this).data('target');
        auditPickerState[target].selectedYear = String($(this).data('year'));
        renderAuditYearGrid(target);
        renderAuditPickerDisplays();
        syncAuditRange();
    });

    $('#auditRangePopover').on('click', '.audit-range-month', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var target = $(this).data('target');
        if (!auditPickerState[target].selectedYear) {
            auditPickerState[target].selectedYear = getDefaultYearForTarget(target);
        }
        auditPickerState[target].selectedMonth = String($(this).data('month'));
        renderAuditYearGrid(target);
        renderAuditMonthGrid(target);
        renderAuditPickerDisplays();
        syncAuditRange();
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.audit-range-wrap').length) {
            closeAuditRangePopover();
        }
    });

    // Prevent page reload on form submit (e.g. Enter key) and reload DataTable instead
    $('#auditLogSearchForm').submit(function (e) {
        e.preventDefault();
        tbl.ajax.reload();
    });

    // Search button click handler
    $('#searchBtn').click(function () {
        syncAuditRange();
        closeAuditRangePopover();
        tbl.ajax.reload();
    });

    // Clear filters handler using form reset
    $('#auditLogSearchForm').on('reset', function () {
        setTimeout(function () {
            $('#searchAction').val('');
            $('#searchDateFrom').val('');
            $('#searchDateTo').val('');
            auditPickerState.from.yearStart = defaultAuditYearStart;
            auditPickerState.from.selectedYear = '';
            auditPickerState.from.selectedMonth = '';
            auditPickerState.to.yearStart = defaultAuditYearStart;
            auditPickerState.to.selectedYear = '';
            auditPickerState.to.selectedMonth = '';
            renderAuditYearGrid('from');
            renderAuditYearGrid('to');
            renderAuditPickerDisplays();
            $('#auditRangeLabel').text('Select Range');
            tbl.ajax.reload();
        }, 0);
    });
</script>
