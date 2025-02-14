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
    document.querySelectorAll(".repeater-container").forEach((container) => {
        const addButton = container.querySelector(".add-repeater-item");
        const itemsContainer = container.querySelector(".repeater-items");
        const fieldName = container.dataset.field;
        const repeaterConfig = sectionData.fields[fieldName];

        addButton.addEventListener("click", function () {
            const index = itemsContainer.children.length;
            const template = createRepeaterTemplate(
                fieldName,
                index,
                repeaterConfig.fields
            );
            itemsContainer.insertAdjacentHTML("beforeend", template);

            // Add event listener to new remove button
            const newItem = itemsContainer.lastElementChild;
            newItem
                .querySelector(".remove-repeater-item")
                .addEventListener("click", function () {
                    newItem.remove();
                });
        });

        // Add event listeners to existing remove buttons
        container
            .querySelectorAll(".remove-repeater-item")
            .forEach((button) => {
                button.addEventListener("click", function () {
                    button.closest(".repeater-item").remove();
                });
            });
    });

    // Initialize Bootstrap tabs
    const tabElements = document.querySelectorAll('[data-bs-toggle="tab"]');
    if (tabElements && tabElements.length > 0) {
        tabElements.forEach((tab) => {
            new bootstrap.Tab(tab);
        });
    }
});

function createRepeaterTemplate(fieldName, index, fields) {
    let fieldsHtml = "";

    for (const [key, field] of Object.entries(fields)) {
        // Determine input type based on field key and type
        let inputType = field.type;
        if (key === "phone") {
            inputType = "tel";
        } else if (key === "email") {
            inputType = "email";
        }

        fieldsHtml += `
            <div class="mb-3">
                <label class="form-label">${field.label}</label>
                <input type="${inputType}"
                    class="form-control"
                    name="fields[${fieldName}][${index}][${key}]"
                    ${field.required ? "required" : ""}>
            </div>
        `;
    }

    return `
        <div class="repeater-item card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn btn-danger btn-sm remove-repeater-item">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                ${fieldsHtml}
            </div>
        </div>
    `;
}
