{{-- <script>
     function openLineModal(id) {
        const modal = document.getElementById(id);
        const header_id = "{{ $header_data->id }}";
        if (modal) {
            modal.classList.remove("hidden");
            const content = modal.querySelector('.new-modal-content');
            content.classList.remove("opacity-0", "translate-y-20");
            content.classList.add("opacity-100", "translate-y-0");
            if (id === 'line-modal-add') {
                const form = modal.querySelector('form');
                if (form) {
                    form.querySelectorAll('textarea').forEach(textarea => {
                        textarea.value = '';
                    });
                    form.querySelectorAll('input[type="text"]').forEach(input => {
                        if (!input.hasAttribute('readonly')) {
                            input.value = '';
                        }
                    });
                }
                $('.header_id').val(header_id)
            }
            modal.addEventListener("click", function handler(event) {
                if (!content.contains(event.target)) {
                    closeNewModal(id);
                    modal.removeEventListener("click", handler);
                }
            });
        }
    }

    function closeNewModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const content = modal.querySelector('.new-modal-content');
            content.classList.remove("opacity-100", "translate-y-0");
            content.classList.add("opacity-0", "translate-y-20");
            setTimeout(() => {
                modal.classList.add("hidden");
                const lineIdInput = modal.querySelector(".line_id");
                if (lineIdInput) {
                    lineIdInput.value = "";
                }
            }, 300);
        }
    }
    </script> --}}
    <script>
function openLineModal(modalId, viewType = null) {
    const modal = document.getElementById(modalId);
    const header_id = "{{ $header_data->id }}";
    if (!modal) return;

    modal.classList.remove("hidden");
    const content = modal.querySelector(".new-modal-content");
    content?.classList.remove("opacity-0", "translate-y-20");
    content?.classList.add("opacity-100", "translate-y-0");

    // Optional: Handle viewType-based UI toggling
    if (viewType) {
        const header = modal.querySelector(`#${modalId} h3`);
        if (header) {
            if (viewType === "metadata") {
                header.textContent = "Metadata View";
            } else if (viewType === "tags_analytics") {
                header.textContent = "Tags & Analytics View";
            }
        }

        modal.setAttribute("data-current-view", viewType);
        modal.querySelectorAll(".line-modal-section").forEach(section => {
            section.classList.add("hidden");
        });
        const target = modal.querySelector(`.line-modal-section[data-view="${viewType}"]`);
        if (target) target.classList.remove("hidden");
    }

    // If it's the Add Modal, clear inputs
    if (modalId === "line-modal-add") {
        const form = modal.querySelector("form");
        if (form) {
            form.querySelectorAll("textarea").forEach(textarea => textarea.value = "");
            form.querySelectorAll('input[type="text"]').forEach(input => {
                if (!input.hasAttribute("readonly")) {
                    input.value = "";
                }
            });
            $('.header_id').val(header_id);
        }
    }

    // Click outside to close
    function handler(event) {
        if (!content.contains(event.target)) {
            closeNewModal(modalId);
            modal.removeEventListener("click", handler);
        }
    }

    modal.addEventListener("click", handler);
}

function closeNewModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const content = modal.querySelector(".new-modal-content");
    content?.classList.remove("opacity-100", "translate-y-0");
    content?.classList.add("opacity-0", "translate-y-20");

    setTimeout(() => {
        modal.classList.add("hidden");

        const lineIdInput = modal.querySelector(".line_id");
        if (lineIdInput) {
            lineIdInput.value = "";
        }

        // Reset view sections if any
        modal.querySelectorAll(".line-modal-section").forEach(section => {
            section.classList.add("hidden");
        });
    }, 300);
}
</script>
