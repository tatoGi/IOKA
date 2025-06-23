/**
 * TinyMCE initialization script
 * Self-hosted version (no API key required)
 */
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: 'textarea.editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        height: 500,
        automatic_uploads: true,
        images_upload_url: '/admin/upload-image',
        // This callback is needed to handle TinyMCE's image upload format
        images_upload_handler: function (blobInfo, progress) {
            return new Promise((resolve, reject) => {
                var xhr, formData;
                xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '/admin/upload-image');
                
                xhr.onload = function() {
                    var json;
                    
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }
                    
                    json = JSON.parse(xhr.responseText);
                    
                    if (!json || typeof json.location != 'string') {
                        reject('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    
                    resolve(json.location);
                };
                
                formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                
                // Add CSRF token
                var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                xhr.setRequestHeader('X-CSRF-Token', token);
                
                xhr.send(formData);
            });
        },
        images_upload_credentials: true,
        image_title: true,
        file_picker_types: 'image',
        convert_urls: false,
        relative_urls: false,
        remove_script_host: false,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save(); // This ensures the content is saved to the original textarea
            });
        }
    });
});
