// Ensure jQuery is loaded before executing our code
function initializeFileUploadHandler() {
    // Check if jQuery is available
    if (typeof jQuery === 'undefined') {
        console.warn('jQuery is not loaded. File upload handler requires jQuery.');
        return;
    }
    
    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Function to show error messages using Bootstrap's toast
    // Check if there's a file input on the page
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    // Get the maximum upload size from the server (in bytes)
    const maxUploadSize = () => {
        const postMaxSize = document.head.querySelector('meta[name="post-max-size"]')?.content || '8M';
        const size = parseInt(postMaxSize);
        const unit = postMaxSize.replace(/[^a-zA-Z]/g, '').toUpperCase();
        
        switch(unit) {
            case 'K': return size * 1024;
            case 'M': return size * 1024 * 1024;
            case 'G': return size * 1024 * 1024 * 1024;
            default: return size;
        }
    };

    // Format bytes to a human-readable format
    const formatBytes = (bytes, decimals = 2) => {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    };

    // Check file size before upload
    const checkFileSize = (input) => {
        if (!input.files || input.files.length === 0) return true;
        
        const maxSize = maxUploadSize();
        let totalSize = 0;
        let tooLargeFiles = [];
        
        // Calculate total size of all selected files
        for (let i = 0; i < input.files.length; i++) {
            totalSize += input.files[i].size;
            if (input.files[i].size > maxSize) {
                tooLargeFiles.push({
                    name: input.files[i].name,
                    size: input.files[i].size
                });
            }
        }
        
        // Show error if any file is too large
        if (tooLargeFiles.length > 0) {
            const errorMessage = `The following files exceed the maximum allowed size of ${formatBytes(maxSize)}:\n` +
                tooLargeFiles.map(file => `- ${file.name} (${formatBytes(file.size)})`).join('\n');
            
            // Show error message using Bootstrap's toast or alert
            showError(errorMessage);
            
            // Clear the file input
            input.value = '';
            return false;
        }
        
        return true;
    };
    
    // Function to show error messages using Bootstrap's toast
    const showError = (message) => {
        // Check if toast container exists, if not create one
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.position = 'fixed';
            toastContainer.style.top = '20px';
            toastContainer.style.right = '20px';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast element
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = 'toast show';
        toast.role = 'alert';
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message.replace(/\n/g, '<br>')}
            </div>
        `;
        
        // Add toast to container
        toastContainer.appendChild(toast);
        
        // Initialize Bootstrap toast
        const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: 10000 });
        bsToast.show();
        
        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    };
    
    // Add event listeners to all file inputs
    $(document).on('change', 'input[type="file"]', function() {
        checkFileSize(this);
    });
    
    // Handle AJAX errors for file uploads
    $(document).ajaxError(function(event, xhr) {
        if (xhr.status === 413) {
            try {
                const response = xhr.responseJSON || {};
                if (response.error === 'post_size_exceeded') {
                    showError(`The uploaded file(s) exceed the maximum allowed size of ${response.max_size}. Your upload was ${response.uploaded_size}.`);
                } else {
                    showError(response.message || 'The uploaded file(s) are too large.');
                }
            } catch (e) {
                showError('The uploaded file(s) exceed the server\'s maximum allowed size.');
            }
        }
    });
    
    // Initialize Select2 if it's used in the form
    if ($.fn.select2) {
        $('select.select2').select2({
            theme: 'bootstrap-5'
        });
    }
}

// Initialize when the document is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeFileUploadHandler);
} else {
    initializeFileUploadHandler();
}
