 <form id="editFeaturesForm" method="POST" action="{{ route('add-feature') }}">
    @csrf
    <input type="hidden" name="plan_id" value="" required>
    <input type="hidden" name="course_id" value="" required>

    <div id="edit-features" class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 z-50 flex justify-center items-start w-full h-full bg-black bg-opacity-50">
        <div class="new-modal-content relative top-3 w-full max-w-7xl opacity-0 translate-y-20 transition-all duration-300 ease-out">
            <div class="bg-white rounded-md shadow-lg dark:bg-gray-700">
                <div class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-white">Edit Features</h3>
                    <button type="button" onclick="closeNewModal('edit-features')"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="p-4 md:p-3 space-y-4 max-h-[80vh] overflow-y-scroll text-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Included Features -->
                        <div class="border rounded-md">
                            <div class="flex items-center justify-between p-2 border-b">
                                <h3 class="text-md font-semibold">Included Features</h3>
                                <button type="button" onclick="addFeatureInput('includedWrapper')"
                                        class="flex items-center gap-1 px-3 py-1.5 text-sm font-semibold bg-primary text-white rounded-md">
                                    <i class="ri-add-line"></i> Add
                                </button>
                            </div>
                            <div id="includedWrapper" class="p-3 space-y-2 min-h-[60px]"></div>
                        </div>

                        <!-- Excluded Features -->
                        <div class="border rounded-md">
                            <div class="flex items-center justify-between p-2 border-b">
                                <h3 class="text-md font-semibold">Excluded Features</h3>
                                <button type="button" onclick="addFeatureInput('excludedWrapper')"
                                        class="flex items-center gap-1 px-3 py-1.5 text-sm font-semibold bg-primary text-white rounded-md">
                                    <i class="ri-add-line"></i> Add
                                </button>
                            </div>
                            <div id="excludedWrapper" class="p-3 space-y-2 min-h-[60px]"></div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end items-center p-4 md:p-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <div class="flex gap-2">
                        <button type="button" onclick="closeNewModal('edit-features')" class="btn-primary-sm">Close</button>
                      <button type="button" id="updateFeaturesBtn" class="btn-success-sm">Update Features</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function addFeatureInput(wrapperId, value = '') {
    const wrapper = document.getElementById(wrapperId);

    // ✅ Remove ONLY placeholder message (not real fields)
    const placeholder = wrapper.querySelector('.placeholder-message');
    if (placeholder) {
        placeholder.remove();
    }

    const div = document.createElement('div');
    div.className = "flex items-center gap-2 p-2 border rounded-md bg-gray-50";
    const nameAttr = wrapperId === 'includedWrapper' ? 'included_features[]' : 'excluded_features[]';

    div.innerHTML = `
        <input type="text" name="${nameAttr}" value="${value}"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
               placeholder="Enter ${wrapperId === 'includedWrapper' ? 'included' : 'excluded'} feature">
        <button type="button" onclick="editInput(event, this)"
                class="h-9 w-9 flex items-center justify-center rounded-md bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 transition-colors">
            <i class="ri-edit-2-line text-sm"></i>
        </button>
        <button type="button" onclick="removeInput(event, this, '${wrapperId}')"
                class="h-9 w-9 flex items-center justify-center rounded-md bg-red-100 hover:bg-red-200 text-red-600 hover:text-red-800 transition-colors">
            <i class="ri-delete-bin-line text-sm"></i>
        </button>
    `;
    wrapper.appendChild(div); // ✅ APPENDS - doesn't replace
}



function removeInput(event, btn, wrapperId) {
    event.stopPropagation();

    const wrapper = document.getElementById(wrapperId);
    btn.closest('div').remove();

    // ✅ Only show placeholder if COMPLETELY empty
    if (!wrapper.children.length) {
        wrapper.innerHTML = '<div class="placeholder-message text-gray-500 text-sm italic p-4 text-center col-span-2">No features. Click "Add" to create.</div>';
    }
}





function editInput(btn) {
    const input = btn.closest('div').querySelector('input');
    input.focus();
    input.select();
}

// ✅ Corrected function name and usage
function openEditFeatures(planId, courseId) {

    if (!planId || !courseId) {
        console.warn('Plan ID or Course ID missing');
        return;
    }

    // Set hidden values (make sure these inputs exist in modal)
    const planInput = document.querySelector('input[name="plan_id"]');
    const courseInput = document.querySelector('input[name="course_id"]');

    if (planInput) planInput.value = planId;
    if (courseInput) courseInput.value = courseId;

    // Clear existing inputs
    document.getElementById('includedWrapper').innerHTML = '';
    document.getElementById('excludedWrapper').innerHTML = '';

    // ✅ Send BOTH planId & courseId
    fetch(`/plan-mapping/plan/${planId}/included-plan-features?course_id=${courseId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {

        if (Array.isArray(data.included)) {
            data.included.forEach(name =>
                addFeatureInput('includedWrapper', name)
            );
        }

        if (Array.isArray(data.excluded)) {
            data.excluded.forEach(name =>
                addFeatureInput('excludedWrapper', name)
            );
        }

        showNewModal('edit-features');
    })
    .catch(error => {
        console.error('Error loading features:', error);
        showNewModal('edit-features');
    });
}

document.getElementById('updateFeaturesBtn').addEventListener('click', function(e) {
    e.preventDefault();

    const form = document.getElementById('editFeaturesForm');
    const formData = new FormData(form);
    const btn = this;
    const originalText = btn.innerHTML;

    // Loading state
    btn.innerHTML = '<i class="ri-loader-2-line animate-spin"></i> Saving...';
    btn.disabled = true;

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);

        // Check if response is ok AND content-type is JSON
        const contentType = response.headers.get('content-type');
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
            });
        }
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                throw new Error(`Expected JSON, got ${contentType}: ${text.substring(0, 200)}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Success data:', data);
        if (data.success) {
            // ✅ Close modal
            closeNewModal('edit-features');

            // ✅ Refresh the main page features using your existing function
            const planId = form.querySelector('input[name="plan_id"]').value;
            const courseId = form.querySelector('input[name="course_id"]').value;

            // This will update your frontend display
            loadPlanFeatures(parseInt(planId), parseInt(courseId));

            // Optional success message
            console.log('Features updated successfully!');
        } else {
            throw new Error(data.message || 'Update failed');
        }
    })
    .catch(error => {
        console.error('Detailed error:', error);
        alert('Failed to save: ' + error.message);
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
});




</script>

