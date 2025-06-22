/**
 * Form error display enhancement script
 * This script improves error display on file uploads and other form fields
 */
document.addEventListener('DOMContentLoaded', function() {
    // Apply this to any forms on the page
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // For any invalid file inputs, add better error styling
        const fileInputs = form.querySelectorAll('input[type="file"]');
        
        fileInputs.forEach(input => {
            // Add event listener for file validation
            input.addEventListener('change', function() {
                const errorDisplay = this.nextElementSibling?.classList.contains('file-error-message') 
                    ? this.nextElementSibling
                    : null;
                
                // Check file size (max 10MB)
                if (this.files.length > 0) {
                    const fileSize = this.files[0].size / 1024 / 1024; // in MB
                    
                    if (fileSize > 10) {
                        this.classList.add('is-invalid');
                        
                        if (!errorDisplay) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'text-danger mt-1 file-error-message';
                            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> File size exceeds maximum allowed (10MB)';
                            this.parentNode.insertBefore(errorDiv, this.nextSibling);
                        } else {
                            errorDisplay.textContent = 'File size exceeds maximum allowed (10MB)';
                            errorDisplay.style.display = 'block';
                        }
                        
                        // Reset the file input
                        this.value = '';
                        return false;
                    }
                }
                
                // File is valid
                this.classList.remove('is-invalid');
                if (errorDisplay) {
                    errorDisplay.style.display = 'none';
                }
            });
        });
        
        // Handle submit to show all errors
        form.addEventListener('submit', function(event) {
            // Find all required fields
            const requiredFields = form.querySelectorAll('[required]');
            let hasErrors = false;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    
                    // Add error message if not exists
                    let errorDisplay = field.nextElementSibling;
                    if (!errorDisplay || !errorDisplay.classList.contains('field-error-message')) {
                        errorDisplay = document.createElement('div');
                        errorDisplay.className = 'text-danger mt-1 field-error-message';
                        errorDisplay.innerHTML = '<i class="fas fa-exclamation-circle"></i> This field is required';
                        field.parentNode.insertBefore(errorDisplay, field.nextSibling);
                    } else {
                        errorDisplay.style.display = 'block';
                    }
                    
                    hasErrors = true;
                }
            });
            
            if (hasErrors) {
                // Create alert at the top of the form
                const errorAlert = document.createElement('div');
                errorAlert.className = 'alert alert-danger';
                errorAlert.innerHTML = '<strong>Error!</strong> Please fix the highlighted fields before submitting.';
                
                // Add to top of form
                form.prepend(errorAlert);
                
                // Scroll to first error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                event.preventDefault();
                return false;
            }
        });
    });
});
