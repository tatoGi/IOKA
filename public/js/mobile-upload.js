// Mobile image upload and compression (single image version, with sliders)

// Store the original file and preview data
let originalFile = {};
let previewData = {};

// Handle file selection
function handleMobileImageSelect(input) {
    if (!input || !input.files || input.files.length === 0) return;
    const file = input.files[0];
    const fieldIdentifier = input.getAttribute('data-field');
    if (!fieldIdentifier) return;
    originalFile[fieldIdentifier] = file;
    showPreview(file, fieldIdentifier);
}

// Get slider value or fallback to default
function getSliderValue(id, fallback) {
    const el = document.getElementById(id);
    if (el && el.value) {
        return parseFloat(el.value);
    }
    return fallback;
}

// Show preview and prepare compression
function showPreview(file, fieldIdentifier) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            // Get slider values or defaults
            const maxWidth = getSliderValue('max-width-slider-' + fieldIdentifier, 800);
            const quality = getSliderValue('quality-slider-' + fieldIdentifier, 0.65);
            let width = img.width;
            let height = img.height;
            if (width > maxWidth) {
                height = Math.round((height * maxWidth) / width);
                width = maxWidth;
            }
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);
            const dataUrl = canvas.toDataURL('image/jpeg', quality);
            // Estimate compressed size
            const compressedSizeKB = Math.round((dataUrl.length * 3 / 4) / 1024);
            previewData[fieldIdentifier] = { dataUrl, width, height, quality, file, compressedSizeKB };
            // Show preview
            const previewImg = document.getElementById('preview-image-' + fieldIdentifier);
            if (previewImg) {
                previewImg.src = dataUrl;
                previewImg.parentElement.classList.remove('d-none');
            }
            // Show action buttons
            const actionButtons = document.getElementById('action-buttons-' + fieldIdentifier);
            if (actionButtons) actionButtons.classList.remove('d-none');
            // Show info (quality, width, size)
            const info = document.getElementById('file-info-' + fieldIdentifier);
            if (info) {
                info.innerHTML =
                    'Quality: ' + Math.round(quality * 100) + '% | ' +
                    'Width: ' + width + 'px | ' +
                    'Est. size: ' + compressedSizeKB + ' KB';
            }
            // Update slider labels if present
            const qualityLabel = document.getElementById('quality-value-' + fieldIdentifier);
            if (qualityLabel) qualityLabel.textContent = Math.round(quality * 100);
            const widthLabel = document.getElementById('max-width-value-' + fieldIdentifier);
            if (widthLabel) widthLabel.textContent = width;
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
    // Attach slider listeners for live preview update
    attachSliderListeners(fieldIdentifier);
}

function attachSliderListeners(fieldIdentifier) {
    const qualitySlider = document.getElementById('quality-slider-' + fieldIdentifier);
    const widthSlider = document.getElementById('max-width-slider-' + fieldIdentifier);
    if (qualitySlider) {
        qualitySlider.oninput = function() {
            showPreview(originalFile[fieldIdentifier], fieldIdentifier);
        };
    }
    if (widthSlider) {
        widthSlider.oninput = function() {
            showPreview(originalFile[fieldIdentifier], fieldIdentifier);
        };
    }
}

// Upload the compressed image
function applyCompression(fieldIdentifier, index = null) {
    const isMultiple = Array.isArray(previewData[fieldIdentifier]);
    const data = isMultiple && index !== null ? previewData[fieldIdentifier][index] : previewData[fieldIdentifier];
    if (!data) return;
    const uploadRoute = document.getElementById('upload-route-' + fieldIdentifier)?.value || '/mobile-image-upload';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.getElementById('csrf-token-' + fieldIdentifier)?.value;

    if (isMultiple && index !== null) {
        // Show loading for this preview
        const previewContainer = document.getElementById('preview-container-' + fieldIdentifier);
        if (previewContainer) {
            const previewDiv = previewContainer.children[index];
            if (previewDiv) previewDiv.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div> Uploading...';
        }
    } else {
        // Show loading for single
        const actionButtons = document.getElementById('action-buttons-' + fieldIdentifier);
        if (actionButtons) actionButtons.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div> Uploading...';
    }

    fetch(data.dataUrl)
        .then(res => res.blob())
        .then(blob => {
            const formData = new FormData();
            if (csrfToken) formData.append('_token', csrfToken);
            formData.append('image', blob, data.file.name);
            formData.append('maxWidth', data.width);
            formData.append('quality', Math.round(data.quality * 100));
            return fetch(uploadRoute, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: formData
            });
        })
        .then(response => {
            if (!response.ok) return response.json().then(err => { throw err; });
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (isMultiple && index !== null) {
                    // Add hidden input for this file
                    const compressedFiles = document.getElementById('compressed-files-' + fieldIdentifier);
                    if (compressedFiles) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = fieldIdentifier + '[]';
                        input.value = data.path.replace(/^\/storage\//, '').replace(/^storage\//, '');
                        compressedFiles.appendChild(input);
                    }
                    // Update preview to show uploaded
                    const previewContainer = document.getElementById('preview-container-' + fieldIdentifier);
                    if (previewContainer) {
                        const previewDiv = previewContainer.children[index];
                        if (previewDiv) previewDiv.innerHTML = '<div class="alert alert-success p-2 mb-0">Upload Successful!</div>';
                    }
                } else {
                    // Single file: update hidden input and preview
                    const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
                    if (hiddenInput) hiddenInput.value = data.path.replace(/^\/storage\//, '').replace(/^storage\//, '');
                    const previewImg = document.getElementById('preview-image-' + fieldIdentifier);
                    if (previewImg) {
                        previewImg.src = data.url;
                        previewImg.parentElement.classList.remove('d-none');
                    }
                    const actionButtons = document.getElementById('action-buttons-' + fieldIdentifier);
                    if (actionButtons) actionButtons.innerHTML = '<div class="alert alert-success p-2 mb-0">Upload Successful!</div>';
                }
            } else {
                throw new Error(data.message || 'Upload failed');
            }
        })
        .catch(error => {
            if (isMultiple && index !== null) {
                const previewContainer = document.getElementById('preview-container-' + fieldIdentifier);
                if (previewContainer) {
                    const previewDiv = previewContainer.children[index];
                    if (previewDiv) previewDiv.innerHTML = '<div class="alert alert-danger p-2 mb-2">' + (error.message || 'Upload failed') + '</div>' +
                        '<button class="btn btn-warning btn-sm w-100" onclick="applyCompression(\'' + fieldIdentifier + '\',' + index + ')">Try Again</button>';
                }
            } else {
                const actionButtons = document.getElementById('action-buttons-' + fieldIdentifier);
                if (actionButtons) actionButtons.innerHTML = '<div class="alert alert-danger p-2 mb-2">' + (error.message || 'Upload failed') + '</div>' +
                    '<button class="btn btn-warning btn-sm w-100" onclick="applyCompression(\'' + fieldIdentifier + '\')">Try Again</button>';
            }
        });
}

// Cancel and reset
function cancelCompression(fieldIdentifier) {
    const fileInput = document.querySelector('input[data-field="' + fieldIdentifier + '"]');
    if (fileInput) fileInput.value = '';
    const previewImg = document.getElementById('preview-image-' + fieldIdentifier);
    if (previewImg) {
        previewImg.src = '';
        previewImg.parentElement.classList.add('d-none');
    }
    const actionButtons = document.getElementById('action-buttons-' + fieldIdentifier);
    if (actionButtons) actionButtons.classList.add('d-none');
    const hiddenInput = document.getElementById('compressed-' + fieldIdentifier);
    if (hiddenInput) hiddenInput.value = '';
    const info = document.getElementById('file-info-' + fieldIdentifier);
    if (info) info.textContent = '';
    delete originalFile[fieldIdentifier];
    delete previewData[fieldIdentifier];
}
