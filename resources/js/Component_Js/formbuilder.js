document.addEventListener("DOMContentLoaded", function () {
    const fieldsContainer = document.getElementById("fieldsContainer");
    const previewContainer = document.getElementById("dynamicFields");
    const addFieldBtn = document.getElementById("addFieldBtn");
    const inputTypeSelect = document.getElementById("inputType");
    const inputLabelInput = document.getElementById("inputLabel");
    const isRequiredCheckbox = document.getElementById("isRequired");
    const addFieldToFormButton = document.querySelector(".modal-add-fields");
    // Predefined Fields List
    const predefinedFields = [
        {
            id: "1",
            name: "text",
            value: "text",
            label: "Text",
        },
        {
            id: "2",
            name: "email",
            value: "email",
            label: "Email",
        },

        {
            id: "3",
            name: "number",
            value: "number",
            label: "Number",
        },
        {
            id: "4",
            name: "date",
            value: "date",
            label: "Date",
        },
        {
            id: "5",
            name: "textarea",
            value: "textarea",
            label: "Textarea",
        },
        {
            id: "6",
            name: "checkbox",
            value: "checkbox",
            label: "Checkbox",
        },
        {
            id: "7",
            name: "radio",
            value: "radio",
            label: "Radio",
        },
    ];
    // Populate Predefined Input Types
    function populatePredefinedInputs() {
        inputTypeSelect.innerHTML = "";
        predefinedFields.forEach((field) => {
            const option = document.createElement("option");
            option.value = field.value;
            option.textContent = field.label;
            inputTypeSelect.appendChild(option);
        });
    }

    // Add Field Dynamically by Label
    addFieldBtn.addEventListener("click", () => {
        const fieldLabel = prompt("Enter the label for the new field:");
        if (!fieldLabel) return;
        const isRequired = confirm("Should this field be required?");
        createField(fieldLabel, "text", isRequired);
    });

    // Add Field from Modal
    addFieldToFormButton.addEventListener("click", () => {
        const selectedType = inputTypeSelect.value;
        const label = inputLabelInput.value.trim();
        const isRequired = isRequiredCheckbox.checked;

        if (!label) {
            alert("Please provide a label for the field.");
            return;
        }
        createField(label, selectedType, isRequired);
        // Reset Modal Inputs
        inputLabelInput.value = "";
        isRequiredCheckbox.checked = false;
        document.querySelector("[data-modal-hide='static-modal']").click();
    });

    // Create a Field in Builder and Preview
    function createField(label, type, isRequired) {
        // Add Field to Builder
        const newField = document.createElement("div");
        newField.className =
            "field bg-gray-100 rounded-sm p-2 flex items-center justify-between mt-2";
        newField.innerHTML = `
    <span>${label} (${type}) ${isRequired ? "*" : ""}</span>
    <button type="button" class="text-red-500 hover:underline removeField">
        <i class="ri-delete-bin-6-line"></i>
        </button>
`;
        fieldsContainer.appendChild(newField);

        // Add Field to Preview
        const newPreviewField = document.createElement("div");
        newPreviewField.className = "field";
        let fieldHtml = `
    <label>${label}${isRequired ? "*" : ""}</label>
`;

        if (type === "textarea") {
            fieldHtml += `
        <textarea placeholder="Enter your ${label.toLowerCase()}" 
            class="border rounded-sm w-full px-4 py-2 mt-1"></textarea>
    `;
        } else if (type === "checkbox" || type === "radio") {
            fieldHtml += `
        <div>
            <input type="${type}" id="${label.toLowerCase()}" class="mr-2">
            <label for="${label.toLowerCase()}">${label}</label>
        </div>
    `;
        } else {
            fieldHtml += `
        <input type="${type}" placeholder="Enter your ${label.toLowerCase()}" 
            class="border rounded-sm w-full px-4 py-2 mt-1" />
    `;
        }
        newPreviewField.innerHTML = fieldHtml;
        previewContainer.appendChild(newPreviewField);
        // Remove Field Event
        const removeBtn = newField.querySelector(".removeField");
        removeBtn.addEventListener("click", () => {
            newField.remove();
            newPreviewField.remove();
        });
    }
    populatePredefinedInputs();
});

document.addEventListener("DOMContentLoaded", function () {
    const logoCheckbox = document.getElementById("showLogo");
    const companyNameCheckbox = document.getElementById("showCompanyName");
    const logoPreview = document.getElementById("logoPreview");
    const companyNamePreview = document.getElementById("companyNamePreview");
    const logoUrlInput = document.getElementById("logoUrlInput");
    const companyNameInput = document.getElementById("companyNameInput");
    const editLogoBtn = document.getElementById("editLogoBtn");
    const editCompanyNameBtn = document.getElementById("editCompanyNameBtn");
    // Toggle visibility of logo preview
    logoCheckbox.addEventListener("change", function () {
        if (this.checked) {
            logoPreview.classList.remove("hidden");
        } else {
            logoPreview.classList.add("hidden");
        }
    });
    // Toggle visibility of company name preview
    companyNameCheckbox.addEventListener("change", function () {
        if (this.checked) {
            companyNamePreview.classList.remove("hidden");
        } else {
            companyNamePreview.classList.add("hidden");
        }
    });
    // Edit Logo URL
    editLogoBtn.addEventListener("click", function () {
        if (logoUrlInput.classList.contains("hidden")) {
            logoUrlInput.classList.remove("hidden");
            logoUrlInput.focus();
            this.textContent = "Save Logo";
        } else {
            const newLogoUrl = logoUrlInput.value.trim();
            if (newLogoUrl) {
                const logoImg = logoPreview.querySelector("img");
                logoImg.src = newLogoUrl;
            }
            logoUrlInput.classList.add("hidden");
            this.textContent = "Edit Logo";
        }
    });
    // Edit Company Name
    editCompanyNameBtn.addEventListener("click", function () {
        if (companyNameInput.classList.contains("hidden")) {
            companyNameInput.classList.remove("hidden");
            companyNameInput.focus();
            this.textContent = "Save Name";
        } else {
            const newCompanyName = companyNameInput.value.trim();
            if (newCompanyName) {
                const companyNameText = companyNamePreview.querySelector("h3");
                companyNameText.textContent = newCompanyName;
            }
            companyNameInput.classList.add("hidden");
            this.textContent = "Edit Name";
        }
    });
    // Show logo and company name by default
    logoCheckbox.checked = true;
    companyNameCheckbox.checked = true;
    logoPreview.classList.remove("hidden");
    companyNamePreview.classList.remove("hidden");
});
