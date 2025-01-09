document.addEventListener("DOMContentLoaded", function () {
    // Get section data from the hidden input
    const sectionDataElement = document.getElementById("section-data");
    if (!sectionDataElement) {
        console.warn("Section data element not found");
        return;
    }

    const sectionData = JSON.parse(sectionDataElement.value || "{}");
    console.log("Loaded section data:", sectionData); // Debug log

    // Handle repeater fields
    const repeaterContainers = document.querySelectorAll(".repeater-container");

    if (repeaterContainers.length === 0) {
        console.warn("No repeater containers found on the page");
        return;
    }

    repeaterContainers.forEach((container) => {
        const addButton = container.querySelector(".add-repeater-item");
        const itemsContainer = container.querySelector(".repeater-items");
        const fieldName = container.dataset.field;

        console.log("Processing repeater:", fieldName); // Debug log

        // Add null checks to prevent errors
        if (!addButton || !itemsContainer) {
            console.warn("Required repeater elements not found in container:", {
                containerHTML: container.innerHTML,
                addButton: !!addButton,
                itemsContainer: !!itemsContainer,
                fieldName: fieldName,
            });
            return;
        }

        // Verify field configuration exists
        if (!fieldName || !sectionData.fields?.[fieldName]) {
            console.warn("Field configuration not found:", {
                fieldName: fieldName,
                availableFields: Object.keys(sectionData.fields || {}),
            });
            return;
        }

        const minItems = parseInt(container.dataset.min) || 0;
        const maxItems = parseInt(container.dataset.max) || null;

        // Function to create new repeater item
        function createRepeaterItem(values = {}) {
            const itemDiv = document.createElement("div");
            itemDiv.className =
                "repeater-item border rounded p-3 mb-3 position-relative";

            // Get the field structure from sectionData
            const repeaterFields = sectionData.fields[fieldName].fields;

            // Create fields based on the structure
            Object.entries(repeaterFields).forEach(([key, field]) => {
                const fieldDiv = document.createElement("div");
                fieldDiv.className = "mb-3";

                const label = document.createElement("label");
                label.className = "form-label";
                label.textContent = field.label;

                let input;

                switch (field.type) {
                    case "image":
                        input = document.createElement("input");
                        input.type = "file";
                        input.accept = "image/*";
                        break;
                    case "textarea":
                        input = document.createElement("textarea");
                        break;
                    default:
                        input = document.createElement("input");
                        input.type = field.type || "text";
                }

                input.className = "form-control";
                input.name = `fields[${fieldName}][items][]`;
                input.required = field.required || false;

                if (values[key]) {
                    input.value = values[key];
                }

                fieldDiv.appendChild(label);
                fieldDiv.appendChild(input);
                itemDiv.appendChild(fieldDiv);
            });

            // Add remove button
            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.className =
                "btn btn-danger btn-sm position-absolute top-0 end-0 m-2";
            removeButton.innerHTML = "&times;";
            removeButton.onclick = () => {
                if (itemsContainer.children.length > minItems) {
                    itemDiv.remove();
                    updateAddButtonState();
                }
            };

            itemDiv.appendChild(removeButton);
            return itemDiv;
        }

        // Function to update add button state
        function updateAddButtonState() {
            if (maxItems) {
                addButton.disabled = itemsContainer.children.length >= maxItems;
            }
        }

        // Add initial items if min_items is set
        for (let i = 0; i < minItems; i++) {
            itemsContainer.appendChild(createRepeaterItem());
        }

        // Add button click handler
        addButton.addEventListener("click", () => {
            if (!maxItems || itemsContainer.children.length < maxItems) {
                itemsContainer.appendChild(createRepeaterItem());
                updateAddButtonState();
            }
        });

        // Initial button state
        updateAddButtonState();
    });

    // Initialize Bootstrap tabs
    const tabElements = document.querySelectorAll('[data-bs-toggle="tab"]');
    if (tabElements && tabElements.length > 0) {
        tabElements.forEach((tab) => {
            new bootstrap.Tab(tab);
        });
    }
});
