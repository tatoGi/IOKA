// Fix for select dropdown options display
document.addEventListener('DOMContentLoaded', function() {
    // Find all select elements with the form-select class
    const selects = document.querySelectorAll('.form-select');
    
    selects.forEach(select => {
        // Remove any event listeners that might be interfering
        const newSelect = select.cloneNode(true);
        select.parentNode.replaceChild(newSelect, select);
        
        // Add a click event to ensure options are displayed
        newSelect.addEventListener('click', function(e) {
            // Log for debugging
            console.log('Select clicked:', this.id);
            
            // Force the select to show its options
            if (this.size <= 1) {
                // Temporarily expand the select to show options
                this.size = 4;
                
                // Reset after selection
                document.addEventListener('click', function resetSize(event) {
                    if (!newSelect.contains(event.target)) {
                        newSelect.size = 1;
                        document.removeEventListener('click', resetSize);
                    }
                });
            }
        });
        
        // Log the options for debugging
        console.log('Select options for ' + newSelect.id + ':', 
            Array.from(newSelect.options).map(opt => opt.text));
    });
});
