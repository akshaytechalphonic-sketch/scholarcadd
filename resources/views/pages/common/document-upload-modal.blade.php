<div id="document-upload-modal"
    class="hidden fixed top-0 left-1/2 transform -translate-x-1/2 z-50 flex justify-center items-start w-full h-full bg-black bg-opacity-50">
    <div
        class="new-modal-content relative top-3  w-full max-w-3xl opacity-0 translate-y-20 transition-all duration-300 ease-out">
        <div class="bg-white rounded-md shadow-lg dark:bg-gray-700">
            <div
                class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-md font-semibold text-gray-900 dark:text-white">
                    {{ __('common.documents') }}
                </h3>
                <button onclick="closeNewModal('document-upload-modal')"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-target="document-upload-modal">
                    <i class="ri-close-line text-lg"></i>
                    <span class="sr-only">{{ __('common.close') }}</span>
                </button>
            </div>
            <div class="p-2 md:p-2 space-y-4 max-h-[80vh] overflow-y-scroll">
                <form id="uploadForm">
                    @csrf
                    <input type="hidden" class="form-control" id="source_id_upload" name="source_id">
                    <input type="hidden" class="form-control" id="source_upload" name="source">
                    <div class="p-4 md:p-2 space-y-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('common.description') }}</label>
                        <textarea class="w-full form-input" id="description_upload" name="description" rows="2"
                            placeholder="Add a description..."></textarea>

                        <label class="block text-sm font-medium text-gray-700 mt-2">{{ __('tender.choose-file') }} <span
                                class="text-red-500">*</span></label>
                        <input type="file" class="w-full border  rounded form-control-file" id="uploadFile"
                            name="attachment_file[]" multiple required>

                        <p id="errorMessage" class="text-red-500 mt-2 hidden"></p>
                        <div class="flex justify-end">
                            <button id="uploadBtn" type="submit"
                                class=" px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800  dark:bg-blue-600 dark:hover:bg-primary dark:focus:ring-blue-800">{{ __('tender.upload') }}</button>
                        </div>
                    </div>
                </form>
                <div class="p-3 ">
                    <h2 class="text-lg font-semibold">{{ __('tender.uploaded-documents') }}</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full mt-2 border-collapse document-table">
                            <thead>
                                <tr class="bg-primary text-white text-xs">
                                    <th class="custome-tables">{{ __('tender.sr-no') }}</th>
                                    <th class="custome-tables">{{ __('tender.document-name') }}</th>
                                    <th class="custome-tables">{{ __('tender.document-type') }}</th>
                                    <th class="custome-tables">{{ __('common.description') }}</th>
                                    <th class="custome-tables">{{ __('tender.status') }}</th>
                                    <th class="custome-tables">{{ __('tender.created-on') }}</th>
                                    <th class="custome-tables">{{ __('tender.action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="fileTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@section('scripts')
    @include('layouts.modal_js')
    <script>
        function openNewModalforAttechment(modalId, button) {

            const modal = document.getElementById(modalId);
            const sourceId = button.getAttribute('data-source-id');
            const source = button.getAttribute('data-source');

            // Set modal input values
            var auctionId = $('#source_id_upload').val(sourceId);
            var auctionSource = $('#source_upload').val(source);

            if (modal) {
                modal.classList.remove("hidden");
                modal.querySelector('.new-modal-content').classList.remove("opacity-0", "translate-y-20");
                modal.querySelector('.new-modal-content').classList.add("opacity-100", "translate-y-0");
            }
            modal.addEventListener("click", (event) => {
                if (!modal.querySelector('.new-modal-content').contains(event.target)) {
                    closeNewModal(modalId);
                }
            });

            $.ajax({
                url: "{{ route('attachement-list') }}",
                type: 'Get',
                dataType: 'json',
                data: {
                    source_id: sourceId,
                    source: source
                },
                success: function(response) {

                    if (response.data) {
                        $('.document-table tbody').empty();
                        response.data.forEach(function(item, index) {
                            let fileName = item.attachment_name.split('/').pop();
                            let path = item.attachment_name;
                            let fileUrl =
                                `{{ route('secure-documents', ['path' => '__path__']) }}`
                                .replace('__path__', encodeURIComponent(path));
                            let row = `
                                    <tr>
                                        <td class="custome-tables">${index + 1}</td>
                                        <td class="custome-tables">${fileName}</td>
                                        <td class="custome-tables">${item.attachment_type}</td>
                                        <td class="custome-tables">${item.description ? item.description : 'N/A'}</td>
                                        <td class="custome-tables">${item.status}</td>
                                        <td class="custome-tables">${new Date(item.created_at).toLocaleDateString()}</td>
                                        <td>
                                           <div class="dropdown relative inline-block">
                                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]">
                                                <ul class="text-sm text-black">
                                                    <li>
                                                        <a class="block px-4 py-2 hover:bg-gray-200" href="${fileUrl}" target="blank">
                                                            <i class="ri-eye-line"></i> View
                                                        </a>
                                                    </li>
                                                ${item.status === 'Draft' ? `
                                                        <li>
                                                            <a class="block px-4 py-2 hover:bg-gray-200 submit_document" data-document_id="${item.id}" href="#">
                                                                <i class="ri-eye-line"></i> Submit
                                                            </a>
                                                          </li>
                                                        <li>
                                                            <a class="block px-4 py-2 hover:bg-gray-200 delete_document" data-document_id="${item.id}" href="#">
                                                                <i class="ri-eye-line"></i> Delete
                                                            </a>
                                                        </li>`  : ''}
                                                    </div>
                                                 </div>


                                        </td>
                                    </tr>
                                `;
                            $('.document-table tbody').append(row);
                        });
                    } else {
                        toastr.error(response.message)
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An unexpected error occurred. Please try again later.')

                }
            });
        }

        $(document).ready(function() {
            //var auctionId = button.getAttribute('data-auction-id');

            var sourceId;
            var source;
            let button = $('[data-source-id]').first();


            if (button.length > 0) {
                sourceId = button.attr('data-source-id');
                source = button.attr('data-source');
                $('#source_id_upload').val(sourceId);
                $('#source_upload').val(source);
                submitlist();
            }

            $('#uploadForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);


                $.ajax({
                    url: "{{ route('attachement-upload') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message)

                            $('#description_upload').val('');
                            $('#uploadFile').val('');
                            list();
                        } else {

                            toastr.error(response.message)
                        }

                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            // Validation error
                            let errors = xhr.responseJSON.errors;
                            let errorMessages = '';
                            for (const [key, messages] of Object.entries(errors)) {
                                errorMessages += messages.join('<br>');
                            }
                            toastr.error('Validation Error!')
                        } else {
                            toastr.error('Oops..,Something went wrong: ' + error)
                        }

                    }
                });
            });

            $('#uploadFileModal').on('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                sourceId = button.getAttribute('data-source-id');
                source = button.getAttribute('data-source');
                list();

                $('#source_id_upload').val(sourceId);
                $('#source_upload').val(source);
            });

            function list() {

                $.ajax({
                    url: "{{ route('attachement-list') }}",
                    type: 'Get',
                    dataType: 'json',
                    data: {
                        source_id: sourceId,
                        source: source
                    },
                    success: function(response) {

                        if (response.data) {
                            $('.document-table tbody').empty();
                            response.data.forEach(function(item, index) {
                                let fileName = item.attachment_name.split('/').pop();
                                let path = item.attachment_name;
                                let fileUrl =
                                    `{{ route('secure-documents', ['path' => '__path__']) }}`
                                    .replace('__path__', encodeURIComponent(path));
                                let row = `
                                    <tr>
                                        <td class="custome-tables">${index + 1}</td>
                                        <td class="custome-tables">${fileName}</td>
                                        <td class="custome-tables">${item.attachment_type}</td>
                                        <td class="custome-tables">${item.description ? item.description : 'N/A'}</td>
                                        <td class="custome-tables">${item.status}</td>
                                        <td class="custome-tables">${new Date(item.created_at).toLocaleDateString()}</td>
                                        <td>
                                            <div class="dropdown relative inline-block">
                                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]">
                                                <ul class="text-sm text-black">
                                                    <li>
                                                        <a class="block px-4 py-2 hover:bg-gray-200" href="${fileUrl}" target="blank">
                                                            <i class="ri-eye-line"></i> View
                                                        </a>
                                                    </li>
                                                ${item.status === 'Draft' ? `
                                                        <li>
                                                            <a class="block px-4 py-2 hover:bg-gray-200 submit_document" data-document_id="${item.id}" href="#">
                                                                <i class="ri-eye-line"></i> Submit
                                                            </a>
                                                          </li>
                                                        <li>
                                                            <a class="block px-4 py-2 hover:bg-gray-200 delete_document" data-document_id="${item.id}" href="#">
                                                                <i class="ri-eye-line"></i> Delete
                                                            </a>
                                                        </li>`  : ''}
                                                    </div>
                                                 </div>
                                        </td>
                                    </tr>
                                `;
                                $('.document-table tbody').append(row);
                            });
                        } else {
                            toastr.error(response.message)
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An unexpected error occurred. Please try again later.')

                    }
                });
            }
            $(document).on('click', '.submit_document', function() {
                let documentId = $(this).data('document_id');

                $.ajax({
                    url: "{{ route('attachement-submit') }}",
                    type: 'Get',
                    data: {
                        document_id: documentId
                    },
                    success: function(response) {

                        toastr.success(response.message)
                        list();
                        submitlist();

                    },
                    error: function(xhr) {

                        toastr.error('Something went wrong')
                    }
                });

            })
            $(document).on('click', '.delete_document', function() {
                let documentId = $(this).data('document_id');

                if (confirm("Are you sure? You won't be able to revert this!")) {
                    $.ajax({
                        url: "{{ route('attachement-delete') }}",
                        type: 'GET',
                        data: {
                            document_id: documentId
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            list();
                        },
                        error: function(xhr) {
                            toastr.error('Something went wrong');
                        }
                    });
                } else {
                    toastr.info('Deletion cancelled');
                }
            });


            function submitlist() {

                $.ajax({
                    url: "{{ route('attachement-submitlist') }}",
                    type: 'Get',
                    dataType: 'json',
                    data: {
                        source_id: sourceId,
                        source: source
                    },
                    success: function(response) {

                        $('.attachment-div').empty();
                        if (response.data) {
                            response.data.forEach(function(item, index) {
                                let fileName = item.attachment_name.split('/').pop();
                                let baseUrl = "{{ asset('') }}";
                                let path = item.attachment_name;
                                let fileUrl =
                                    `{{ route('secure-documents', ['path' => '__path__']) }}`
                                    .replace('__path__', encodeURIComponent(path));
                                let html = `
                                    <div class="border-bottom px-2 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-sm">
                                                    <div class="avatar-title bg-light text-white rounded font-size-20">
                                                        <i class="ri-folder-zip-line"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-15 mb-1">
                                                    <a href="${fileUrl}" target="blank" class="text-body text-truncate d-block">
                                                        ${fileName}
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <a href="${fileUrl}" class="text-body text-truncate d-block" download><button type="button" class="btn btn-icon text-muted btn-sm font-size-18">
                                                    <i class="ri-download-2-line"></i>
                                                </button></a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('.attachment-div').append(html);
                            });

                        } else {
                            toastr.error(response.message)
                        }
                    },
                    error: function(xhr, status, error) {

                        toastr.error('An unexpected error occurred. Please try again later.')

                    }
                });
            }

        });
    </script>
@endsection
