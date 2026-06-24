document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.preview-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var fileUrl = this.getAttribute('data-fileurl');
            var extension = fileUrl.split('.').pop().toLowerCase();
            
            // Set download button hrefs
            document.getElementById('fileDownloadBtn').href = fileUrl;
            document.getElementById('placeholderDownloadBtn').href = fileUrl;
            
            // Set modal title filename
            var filename = fileUrl.substring(fileUrl.lastIndexOf('/') + 1);
            // Remove timestamp prefix if exists (e.g. 1718534839_file.xlsx)
            var cleanFilename = decodeURIComponent(filename).replace(/^\d+_/, '');
            document.getElementById('filePreviewModalLabel').textContent = 'Preview: ' + cleanFilename;

            var previewable = ['pdf', 'png', 'jpg', 'jpeg', 'gif'].includes(extension);
            
            var iframe = document.getElementById('filePreviewFrame');
            var placeholder = document.getElementById('filePreviewPlaceholder');
            var icon = document.getElementById('fileTypeIcon');
            var message = document.getElementById('placeholderMessage');
            
            if (previewable) {
                placeholder.style.display = 'none';
                iframe.style.display = 'block';
                iframe.src = fileUrl;
            } else {
                iframe.style.display = 'none';
                iframe.src = '';
                placeholder.style.display = 'flex';
                
                // Adjust icon and message based on extension
                var iconClass = 'fa-file-alt';
                var color = '#6c757d';
                var bgColor = '#e9ecef';
                
                if (['xls', 'xlsx'].includes(extension)) {
                    iconClass = 'fa-file-excel';
                    color = '#2e7d32';
                    bgColor = '#e8f5e9';
                } else if (['doc', 'docx'].includes(extension)) {
                    iconClass = 'fa-file-word';
                    color = '#1565c0';
                    bgColor = '#e3f2fd';
                } else if (['ppt', 'pptx'].includes(extension)) {
                    iconClass = 'fa-file-powerpoint';
                    color = '#c62828';
                    bgColor = '#ffebee';
                } else if (['zip', 'rar'].includes(extension)) {
                    iconClass = 'fa-file-archive';
                    color = '#f57f17';
                    bgColor = '#fffde7';
                }
                
                icon.className = 'fas ' + iconClass;
                icon.parentNode.style.color = color;
                icon.parentNode.style.backgroundColor = bgColor;
                
                message.innerHTML = 'This file format (<strong>.' + extension + '</strong>) cannot be previewed directly in the browser.<br>Please click the download button below to save and open it on your device.';
            }
            
            var modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
            modal.show();
        });
    });
    
    document.getElementById('filePreviewModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('filePreviewFrame').src = '';
    });
}); 