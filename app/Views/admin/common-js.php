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

    function renderStatusToggleAction(level, row, toggleFnName) {
        const status = (row.status || '').toUpperCase();
        if (status === 'ARCHIVED') {
            return '';
        }
        return `<li><a class="dropdown-item" href="#" onclick="${toggleFnName}(${row.ID}, '${row.status}')"><i class="bi bi-toggle-${status === 'ACTIVE' ? 'on' : 'off'} me-1"></i>${status === 'ACTIVE' ? 'Deactivate' : 'Activate'}</a></li>`;
    }

    function nextRecordStatus(currentStatus, forcedStatus) {
        if (forcedStatus) {
            return forcedStatus;
        }
        return currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
    }

    function statusActionText(newStatus) {
        return newStatus === 'ACTIVE' ? 'activate' : 'deactivate';
    }

    function statusActionTitle(newStatus, noun) {
        return (newStatus === 'ACTIVE' ? 'Activate ' : 'Deactivate ') + noun;
    }

    function statusSuccessText(noun, actionText) {
        return noun + ' ' + (actionText + 'd') + ' successfully';
    }

</script>
