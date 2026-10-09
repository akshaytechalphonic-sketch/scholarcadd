<div id="add-curriculam"
    class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 z-50 flex justify-center items-start w-full h-full bg-black bg-opacity-50">
    <div
        class="new-modal-content relative top-3  w-full max-w-2xl opacity-0 translate-y-20 transition-all duration-300 ease-out">
        <div class="bg-white rounded-md shadow-lg dark:bg-gray-700">
            <div
                class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-md font-semibold text-gray-900 dark:text-white">
                    Add Curriculam
                </h3>
                <button onclick="closeNewModal('add-curriculam')"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-target="add-curriculam">
                    <i class="ri-close-line text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 md:p-3 space-y-4 max-h-[80vh] overflow-y-scroll text-sm">
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Title
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text"
                        class="limited-input form-input mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2"
                        data-maxlength="200" name="point_six">
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200
                    </div>
                </div>
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">Description
                        <span class="text-red-500 font-bold">*</span></label>
                    <textarea type="text" class="form-input mt-1 description-validation-summernote summernote" name="description"></textarea>
                </div>

            </div>
            <div
                class="flex justify-end items-center p-4 md:p-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <div class="flex gap-2">
                    <button type="button" onclick="closeNewModal('add-curriculam')" class="btn-primary-sm"
                        data-target="add-curriculam">
                        Close
                    </button>
                    <button type="submit" class="btn-success-sm">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="publish-course"
    class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 z-50 flex justify-center items-start w-full h-full bg-black bg-opacity-50">
    <div
        class="new-modal-content relative top-3  w-full max-w-4xl opacity-0 translate-y-20 transition-all duration-300 ease-out">
        <div class="bg-white rounded-md shadow-lg dark:bg-gray-700">
            <div
                class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-md font-semibold text-gray-900 dark:text-white">
                    Publish to Country and City
                </h3>
                <button onclick="closeNewModal('publish-course')"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-target="add-curriculam">
                    <i class="ri-close-line text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 md:p-3 space-y-4 max-h-[80vh] overflow-y-scroll text-sm">

                <!-- ROW -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Country -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">
                            Country <span class="text-red-500 font-bold">*</span>
                        </label>

                        {{--  <select id="country_id"
        name="country_id[]"
        class="select-country w-full" style="width: 100%"
        multiple>
        @foreach ($countries as $country)
            <option value="{{ $country->id }}">{{ $country->name }}</option>
        @endforeach
    </select>  --}}


                    </div>

                    <!-- City -->
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">
                            City <span class="text-red-500 font-bold">*</span>
                        </label>

                        <select class="select-single" style="width:100%" id="stateName">
                            <option value="" selected>Select State</option>
                        </select>
                    </div>





                </div>

            </div>

            <div
                class="flex justify-end items-center p-4 md:p-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                <div class="flex gap-2">
                    <button type="button" onclick="closeNewModal('publish-course')" class="btn-primary-sm"
                        data-target="publish-course">
                        Close
                    </button>
                    <button type="submit" class="btn-success-sm">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('Course::Dynamic_pages.extra_js')

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const tableBody = document.querySelector("#data-table-list tbody");
        const submitBtn = document.querySelector("#add-curriculam .btn-success-sm");
        const modal = document.getElementById("add-curriculam");

        submitBtn.addEventListener("click", (e) => {
            e.preventDefault();

            // Get input values
            const titleInput = modal.querySelector('input[name="point_six"]');
            const descInput = modal.querySelector('textarea[name="description"]');

            const title = titleInput.value.trim();
            const description = descInput.value.trim();

            // Validate
            if (!title || !description) {
                alert("Please fill in all required fields.");
                return;
            }

            // Create a new row
            const newRow = document.createElement("tr");
            newRow.innerHTML = `
            <td class="px-4 text-center py-2">${title}</td>
            <td class="px-4 text-center py-2">${description}</td>
            <td class="px-4 text-center py-2">
                <button class="text-blue-500 hover:underline edit-btn"><i class="ri-edit-2-fill"></i></button> |
                <button class="text-red-500 hover:underline delete-btn"><i class="ri-delete-bin-5-fill"></i></button>
            </td>
        `;

            // Append new row to the table
            tableBody.appendChild(newRow);

            // Reset form inputs
            titleInput.value = "";
            descInput.value = "";

            // Close modal
            closeNewModal('add-curriculam');
        });

        // Handle delete button dynamically
        document.addEventListener("click", (e) => {
            if (e.target.classList.contains("delete-btn")) {
                e.target.closest("tr").remove();
            }
        });
    });
</script>
