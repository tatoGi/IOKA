  // Object to store original files for compression
  const originalFiles = {};
  
  // Initialize existing images on page load
  document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.mobile-image-upload').forEach(container => {
          const fieldIdentifier = container.id.replace('mobile-upload-', '');
          const previewImg = container.querySelector('.preview-image');
          
          // If there's an existing image, show the preview container
          if (previewImg && previewImg.src && !previewImg.src.endsWith('#')) {
              const previewContainer = container.querySelector('.image-preview-container');
              if (previewContainer) {
                  previewContainer.classList.remove('d-none');
              }
          }
      });
  });
  
  // Event delegation for apply and cancel buttons
  document.addEventListener('click', function(event) {
      // Handle apply compression button
      if (event.target.matches('.apply-compression') || event.target.closest('.apply-compression')) {
          const button = event.target.matches('.apply-compression') ? event.target : event.target.closest('.apply-compression');
          const fieldIdentifier = button.getAttribute('data-field');
          if (fieldIdentifier) {
              applyCompression(fieldIdentifier);
          }
      }
      
      // Handle cancel compression button
      if (event.target.matches('.cancel-compression') || event.target.closest('.cancel-compression')) {
          const button = event.target.matches('.cancel-compression') ? event.target : event.target.closest('.cancel-compression');
          const fieldIdentifier = button.getAttribute('data-field');
          if (fieldIdentifier) {
              cancelCompression(fieldIdentifier);
          }
      }
  });
        
  // Handle mobile image file selection
  function handleMobileImageSelect(input) {
      const file = input.files[0];
      if (!file) return;
      
      // Get the field identifier from the data-field attribute
      const fieldIdentifier = input.getAttribute('data-field');
      if (!fieldIdentifier) {
          console.error('Field identifier not found on input');
          return;
      }
      
      console.log('File selected for field:', fieldIdentifier, file);
      
      // Store the original file for compression
      window.originalFiles = window.originalFiles || {};
      window.originalFiles[fieldIdentifier] = file;
      
      // Get the container and show compression options
      const container = input.closest('.mobile-image-upload');
      if (!container) {
          console.error('Container not found for input:', input);
          return;
      }
      
      // Show compression options
      const compressionOptions = container.querySelector('.compression-options');
      if (!compressionOptions) {
          console.error('Compression options not found in container');
          return;
      }
      
      compressionOptions.classList.remove('d-none');
      
      // Set up quality slider
      const qualitySlider = container.querySelector(`#quality-${fieldIdentifier}`);
      const qualityValue = container.querySelector(`#quality-${fieldIdentifier} ~ .d-flex .quality-value`);
      
      console.log('Quality elements:', { qualitySlider, qualityValue });
      
      if (qualitySlider && qualityValue) {
          qualityValue.textContent = qualitySlider.value + '%';
          // Remove any existing event listeners to prevent duplicates
          const newSlider = qualitySlider.cloneNode(true);
          qualitySlider.parentNode.replaceChild(newSlider, qualitySlider);
          
          newSlider.addEventListener('input', function() {
              qualityValue.textContent = this.value + '%';
              previewCompressedImage(fieldIdentifier);
          });
      } else {
          console.error('Quality slider elements not found');
      }
      
      // Set up max width select
      const widthSelect = container.querySelector(`#max-width-${fieldIdentifier}`);
      console.log('Width select:', widthSelect);
      
      if (widthSelect) {
          // Fix for select dropdown display issues
          widthSelect.style.display = 'block';
          widthSelect.style.width = '100%';
          
          widthSelect.addEventListener('click', function(e) {
              e.stopPropagation();
              
              // Force the dropdown to show if it's not showing
              if (this.size <= 1) {
                  this.size = 4; // Show 4 options at once
                  setTimeout(() => {
                      // Reset after selection
                      document.addEventListener('click', function closeDropdown() {
                          widthSelect.size = 1;
                          document.removeEventListener('click', closeDropdown);
                      }, { once: true });
                  }, 0);
              }
          });
          
          // Make sure options are visible
          const options = widthSelect.querySelectorAll('option');
          options.forEach(option => {
              option.style.display = 'block';
          });
          
          widthSelect.addEventListener('change', function() {
              previewCompressedImage(fieldIdentifier);
          });
      } else {
          console.error('Width select not found');
      }
      
      // Show the preview container
      const previewContainer = container.querySelector('.image-preview-container');
      if (previewContainer) {
          previewContainer.classList.remove('d-none');
      } else {
          console.error('Preview container not found');
      }
      
      // Generate initial preview
      previewCompressedImage(fieldIdentifier);
  }
  
  // Preview the compressed image with current settings
  function previewCompressedImage(fieldIdentifier) {
      console.log('Previewing compressed image for field:', fieldIdentifier);
      
      const file = window.originalFiles && window.originalFiles[fieldIdentifier];
      if (!file) {
          console.error('No file found for field:', fieldIdentifier);
          return;
      }
      
      // Find the container using the field identifier
      const input = document.querySelector(`input[data-field="${fieldIdentifier}"]`);
      if (!input) {
          console.error('Input element not found for field:', fieldIdentifier);
          return;
      }
      
      const container = input.closest('.mobile-image-upload');
      if (!container) {
          console.error('Container not found for field:', fieldIdentifier);
          return;
      }
      
      // Get all required elements
      const qualitySlider = container.querySelector(`#quality-${fieldIdentifier}`);
      const widthSelect = container.querySelector(`#max-width-${fieldIdentifier}`);
      const previewContainer = container.querySelector('.image-preview-container');
      const previewImage = container.querySelector('.preview-image');
      const fileInfo = container.querySelector('.file-info');
      
      // Log element status for debugging
      console.log('Preview elements:', {
          qualitySlider: !!qualitySlider,
          widthSelect: !!widthSelect,
          previewContainer: !!previewContainer,
          previewImage: !!previewImage,
          fileInfo: !!fileInfo
      });
      
      if (!qualitySlider || !widthSelect || !previewContainer || !previewImage || !fileInfo) {
          console.error('Required elements not found for field:', fieldIdentifier);
          return;
      }
      
      // Show preview container if hidden
      if (previewContainer.classList.contains('d-none')) {
          previewContainer.classList.remove('d-none');
      }
      
      // Get settings with defaults
      const quality = (qualitySlider && parseInt(qualitySlider.value) / 100) || 0.7;
      const maxWidth = (widthSelect && parseInt(widthSelect.value)) || 800;
      
      // Create a FileReader to read the image
      const reader = new FileReader();
      reader.onload = function(e) {
          // Create an image element to get dimensions
          const img = new Image();
          img.onload = function() {
              // Calculate new dimensions while maintaining aspect ratio
              let newWidth = img.width;
              let newHeight = img.height;
              
              if (maxWidth > 0 && img.width > maxWidth) {
                  newWidth = maxWidth;
                  newHeight = (img.height * maxWidth) / img.width;
              }
              
              // Create canvas for compression
              const canvas = document.createElement('canvas');
              canvas.width = newWidth;
              canvas.height = newHeight;
              
              // Draw and compress image
              const ctx = canvas.getContext('2d');
              ctx.drawImage(img, 0, 0, newWidth, newHeight);
              
              // Get compressed image as data URL
              const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
              
              // Update preview
              previewImage.src = compressedDataUrl;
              
              // Calculate and display file size information
              const originalSizeKB = Math.round(file.size / 1024);
              
              // Estimate compressed size from data URL
              const base64 = compressedDataUrl.split(',')[1];
              const compressedSizeKB = Math.round((base64.length * 3/4) / 1024);
              
              const savedPercent = Math.round((1 - (compressedSizeKB / originalSizeKB)) * 100);
              
              fileInfo.textContent = `Original: ${originalSizeKB}KB → Compressed: ~${compressedSizeKB}KB (${savedPercent}% saved)`;
          };
          img.src = e.target.result;
      };
      reader.readAsDataURL(file);
  }
  
  // Apply compression and store in hidden field for form submission
  function applyCompression(fieldIdentifier) {
      console.log('Applying compression for field:', fieldIdentifier);
      
      if (!fieldIdentifier) {
          console.error('No field identifier provided');
          return;
      }
      
      // Get the file from the original files object
      const file = window.originalFiles && window.originalFiles[fieldIdentifier];
      if (!file) {
          console.error('No file found for field:', fieldIdentifier);
          return;
      }
      
      // Find the container using the field identifier
      const container = document.querySelector(`#mobile-upload-${fieldIdentifier}`);
      if (!container) {
          console.error('Container not found for field:', fieldIdentifier);
          return;
      }
      
      // Get all the necessary elements for this specific field using the container
      const qualitySlider = container.querySelector(`#quality-${fieldIdentifier}`);
      const widthSelect = container.querySelector(`#max-width-${fieldIdentifier}`);
      const hiddenInput = container.querySelector(`#compressed-${fieldIdentifier}`);
      const options = container.querySelector('.compression-options');
      const fileInput = container.querySelector(`#input-${fieldIdentifier}`);
      
      // Log all the elements to make sure they're correctly identified
      console.log('Elements for field', fieldIdentifier, ':', {
          container: container ? 'Found' : 'Missing',
          qualitySlider: qualitySlider ? 'Found' : 'Missing',
          widthSelect: widthSelect ? 'Found' : 'Missing',
          hiddenInput: hiddenInput ? 'Found' : 'Missing',
          fileInput: fileInput ? 'Found' : 'Missing',
          options: options ? 'Found' : 'Missing'
      });
      
      if (!qualitySlider || !widthSelect || !hiddenInput || !fileInput || !options) {
          console.error('Missing required elements for field:', fieldIdentifier);
          console.error('Available elements in container:', {
              qualitySlider: container.querySelector(`#quality-${fieldIdentifier}`),
              widthSelect: container.querySelector(`#max-width-${fieldIdentifier}`),
              hiddenInput: container.querySelector(`#compressed-${fieldIdentifier}`),
              fileInput: container.querySelector(`#input-${fieldIdentifier}`),
              options: container.querySelector('.compression-options')
          });
          return;
      }
      
      // Get settings
      const quality = parseInt(qualitySlider.value) / 100;
      const maxWidth = parseInt(widthSelect.value);
      
      // Show loading state
      const originalContent = options.innerHTML;
      options.innerHTML = '<div class="alert alert-info">Processing image...</div>';
      
      // Create a FileReader to read the image
      const reader = new FileReader();
      reader.onload = function(e) {
          // Create an image element to get dimensions
          const img = new Image();
          img.onload = function() {
              // Calculate new dimensions while maintaining aspect ratio
              let newWidth = img.width;
              let newHeight = img.height;
              
              if (maxWidth > 0 && img.width > maxWidth) {
                  newWidth = maxWidth;
                  newHeight = (img.height * maxWidth) / img.width;
              }
              
              // Create canvas for compression
              const canvas = document.createElement('canvas');
              canvas.width = newWidth;
              canvas.height = newHeight;
              
              // Draw and compress image
              const ctx = canvas.getContext('2d');
              ctx.drawImage(img, 0, 0, newWidth, newHeight);
              
              // Get compressed image as data URL
              const compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
              
              // Upload to server for optimization
              const formData = new FormData();
              formData.append('image', compressedDataUrl);
              formData.append('quality', Math.round(quality * 100));
              formData.append('maxWidth', maxWidth);
              
              // Update loading state
              options.innerHTML = '<div class="alert alert-info">Uploading and optimizing image...</div>';
              
              // Disable file input during upload
              fileInput.disabled = true;
              
              // Get the upload URL from the data attribute
              let uploadUrl = '';
              // Try to find the upload URL in the container or any of its children
              const uploadContainer = container.querySelector('.upload-container') || container;
              uploadUrl = uploadContainer.getAttribute('data-upload-url') || 
                         container.getAttribute('data-upload-url') || 
                         '/mobile-image-upload';
              
              console.log('Upload URL:', uploadUrl);
              
              if (!uploadUrl) {
                  console.error('Upload URL not found in data attributes');
                  options.innerHTML = '<div class="alert alert-danger">Upload URL not configured</div>';
                  fileInput.disabled = false;
                  return;
              }
              
              // Ensure the URL is absolute and doesn't contain unprocessed Blade syntax
              if (uploadUrl.includes('{{')) {
                  console.warn('Upload URL may contain unprocessed Blade syntax, using fallback');
                  uploadUrl = '/mobile-image-upload'; // Fallback to default route
              }
              
              console.log('Upload URL:', uploadUrl);
              
              // Get CSRF token from meta tag
              const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
              if (!csrfToken) {
                  console.error('CSRF token not found');
                  options.innerHTML = '<div class="alert alert-danger">CSRF token not found</div>';
                  fileInput.disabled = false;
                  return;
              }
              
              // Send to server
              fetch(uploadUrl, {
                  method: 'POST',
                  headers: {
                      'X-CSRF-TOKEN': csrfToken,
                      'Accept': 'application/json'
                  },
                  body: formData
              })
              .then(response => response.json())
              .then(data => {
                  console.log('Upload successful, response:', data);
                  
                  // Set the hidden input value to the server response
                  if (hiddenInput) {
                      hiddenInput.value = data.url || data.path || '';
                      console.log('Set hidden input value to:', hiddenInput.value);
                  } else {
                      console.error('Hidden input not found');
                  }
                  
                  // Show success message
                  options.innerHTML = `
                      <div class="alert alert-success">
                          Image uploaded and optimized successfully!
                          <div class="mt-2">
                              <a href="${data.url || data.path}" target="_blank" class="btn btn-sm btn-outline-primary">View Image</a>
                          </div>
                      </div>`;
                  
                  // Hide the preview container
                  const previewContainer = container.querySelector('.image-preview-container');
                  if (previewContainer) {
                      previewContainer.classList.add('d-none');
                  }
                  
                  // Re-enable file input
                  fileInput.disabled = false;
              })
              .catch(error => {
                  console.error('Fetch error:', error);
                  // Show error message
                  options.innerHTML = `<div class="alert alert-danger">Network error: ${error.message}</div>`;
                  // Re-enable file input
                  fileInput.disabled = false;
              });
          };
          img.src = e.target.result;
      };
      reader.readAsDataURL(file);
  }
  
  // Cancel compression and reset the file input
  function cancelCompression(fieldIdentifier) {
      console.log('Cancelling compression for field:', fieldIdentifier);
      
      // Find the container using the field identifier
      const input = document.querySelector(`input[data-field="${fieldIdentifier}"]`);
      if (!input) {
          console.error('Input element not found for field:', fieldIdentifier);
          return;
      }
      
      const container = input.closest('.mobile-image-upload');
      if (!container) {
          console.error('Container not found for field:', fieldIdentifier);
          return;
      }
      
      // Get elements
      const fileInput = container.querySelector('input[type="file"]');
      const options = container.querySelector('.compression-options');
      const hiddenInput = container.querySelector(`#compressed-${fieldIdentifier}`);
      const previewContainer = container.querySelector('.image-preview-container');
      
      console.log('Cancel elements:', {
          fileInput: !!fileInput,
          options: !!options,
          hiddenInput: !!hiddenInput,
          previewContainer: !!previewContainer
      });
      
      // Reset file input
      if (fileInput) {
          fileInput.value = '';
          fileInput.disabled = false;
      }
      
      // Hide options and preview
      if (options) {
          options.classList.add('d-none');
      }
      
      if (previewContainer) {
          previewContainer.classList.add('d-none');
      }
      
      // Clear hidden input and original file
      if (hiddenInput) {
          hiddenInput.value = '';
      }
      
      // Remove from original files
      delete originalFiles[fieldIdentifier];
  }