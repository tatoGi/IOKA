{{-- Include this file at the end of section edit page to handle form and upload errors --}}
@push('scripts')
<script>
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

                    // Check file type
                    const allowedTypes = this.getAttribute('accept')?.split(',') || [];
                    if (allowedTypes.length > 0) {
                        const fileType = this.files[0].type;
                        let isValid = false;

                        for (const type of allowedTypes) {
                            if (type.trim() === '*/*' || 
                                (type.includes('*') && fileType.startsWith(type.replace('*', ''))) ||
                                type.trim() === fileType) {
                                isValid = true;
                                break;
                            }
                        }

                        if (!isValid) {
                            this.classList.add('is-invalid');
                            
                            if (!errorDisplay) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'text-danger mt-1 file-error-message';
                                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> Invalid file type. Allowed types: ${allowedTypes.join(', ')}`;
                                this.parentNode.insertBefore(errorDiv, this.nextSibling);
                            } else {
                                errorDisplay.textContent = `Invalid file type. Allowed types: ${allowedTypes.join(', ')}`;
                                errorDisplay.style.display = 'block';
                            }
                            
                            // Reset the file input
                            this.value = '';
                            return false;
                        }
                    }
                }
                
                // File is valid
                this.classList.remove('is-invalid');
                if (errorDisplay) {
                    errorDisplay.style.display = 'none';
                }
            });
        });
        
        // Show nice error messages for all invalid fields
        const formFields = form.querySelectorAll('input, select, textarea');
        
        formFields.forEach(field => {
            field.addEventListener('invalid', function(e) {
                e.preventDefault();
                this.classList.add('is-invalid');
                
                // Add error message if not exists
                let errorDisplay = this.nextElementSibling;
                if (!errorDisplay || !errorDisplay.classList.contains('field-error-message')) {
                    errorDisplay = document.createElement('div');
                    errorDisplay.className = 'text-danger mt-1 field-error-message';
                    
                    let message = 'This field is required';
                    if (this.type === 'email') {
                        message = 'Please enter a valid email address';
                    } else if (this.type === 'url') {
                        message = 'Please enter a valid URL';
                    } else if (this.type === 'number') {
                        message = 'Please enter a valid number';
                    } else if (this.type === 'tel') {
                        message = 'Please enter a valid phone number';
                    } else if (this.type === 'file') {
                        message = 'Please select a valid file';
                    }
                    
                    errorDisplay.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                    this.parentNode.insertBefore(errorDisplay, this.nextSibling);
                } else {
                    errorDisplay.style.display = 'block';
                }
            });
            
            // Clear error on valid input
            field.addEventListener('input', function() {
                if (this.validity.valid) {
                    this.classList.remove('is-invalid');
                    
                    const errorDisplay = this.nextElementSibling;
                    if (errorDisplay && errorDisplay.classList.contains('field-error-message')) {
                        errorDisplay.style.display = 'none';
                    }
                }
            });
        });
    });
    
    // Highlight file upload fields with errors from server validation
    const invalidFileFields = document.querySelectorAll('input[type="file"].is-invalid');
    invalidFileFields.forEach(field => {
        // Add error message below the field if not present
        let errorDisplay = field.nextElementSibling;
        if (!errorDisplay || !errorDisplay.classList.contains('field-error-message')) {
            const errorMessages = document.querySelectorAll('.invalid-feedback');
            let errorMessage = 'There was an error with this file';
            
            // Try to find a specific error message
            errorMessages.forEach(msg => {
                if (msg.dataset.field === field.name) {
                    errorMessage = msg.textContent;
                }
            });
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-danger mt-1 field-error-message';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${errorMessage}`;
            field.parentNode.insertBefore(errorDiv, field.nextSibling);
        }
    });
});
</script>
@endpush
