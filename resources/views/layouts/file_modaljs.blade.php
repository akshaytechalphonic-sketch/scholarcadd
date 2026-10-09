 <script>
     var sourceId;
     var source;
     var module_name;

     function closeNewModalfile(modalId) {
         const modal = document.getElementById(modalId);
         if (modal) {
             modal.querySelector('.new-modal-content').classList.remove("opacity-100", "translate-y-0");
             modal.querySelector('.new-modal-content').classList.add("opacity-0", "translate-y-20");
             setTimeout(() => {
                 modal.classList.add("hidden");
             }, 300);
         }
     }

     $(document).ready(function() {
         let button = $('[data-source-id]').first();

         if (button.length > 0) {
             sourceId = button.attr('data-source-id');
             source = button.attr('data-source');
             module_name = button.attr('data-module');
             $('#source_id_upload').val(sourceId);
             $('#source_upload').val(source);
             $('#module_upload').val(module_name);
             $('#comment_source_id').val(source_id);
             $('#comment_source').val(source);
             $('#comment_module').val(module_name);
             submitlist();
           // doc_workflow_list();
         }

         window.openNewModalfile = function() {
             const modalEl = document.getElementById('upload-document-modal');

             if (modalEl) {
                 modalEl.classList.remove("hidden");
                 modalEl.querySelector('.new-modal-content').classList.remove("opacity-0",
                     "translate-y-20");
                 modalEl.querySelector('.new-modal-content').classList.add("opacity-100", "translate-y-0");
                 list();
             }

             modalEl.addEventListener("click", (event) => {
                 if (!modalEl.querySelector('.new-modal-content').contains(event.target)) {
                     closeNewModalfile(modalId);
                 }
             });
         };

         window.openNewModalfileList = function(button) {
             sourceId = button.getAttribute('data-source-id');
             source = button.getAttribute('data-source');
             module_name = button.getAttribute('data-module');

             console.log('Clicked from:', {
                 sourceId,
                 source,
                 module_name
             });

             $('#source_id_upload').val(sourceId);
             $('#source_upload').val(source);
             $('#module_upload').val(module_name);

             const modalEl = document.getElementById('upload-document-modal');
             if (modalEl) {
                 modalEl.classList.remove("hidden");
                 modalEl.querySelector('.new-modal-content').classList.remove("opacity-0",
                     "translate-y-[-50px]");
                 modalEl.querySelector('.new-modal-content').classList.add("opacity-100", "translate-y-0");
                 list();
             }

             modalEl.onclick = function(event) {
                 if (!modalEl.querySelector('.new-modal-content').contains(event.target)) {
                     closeNewModalfile('upload-document-modal');
                 }
             };
         };
         $('#uploadForm').on('submit', function(e) {
             e.preventDefault();
             var formData = new FormData(this);
             showLoader();

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
                     hideLoader();
                 },
                 error: function(xhr, status, error) {
                     if (xhr.status === 422) {
                         let errors = xhr.responseJSON.errors;
                         let errorMessages = '';
                         for (const [key, messages] of Object.entries(errors)) {
                             errorMessages += messages.join('<br>');
                         }
                         toastr.error('Validation Error!')
                     } else {
                         toastr.error('Oops..,Something went wrong: ' + error)
                     }
                     hideLoader();
                 }
             });
         });

         //  $('#uploadForm').on('submit', async function(e) {
         //      e.preventDefault();

         //      const form = this;
         //      showLoader();

         //      // Build form data
         //      const originalFormData = new FormData(form);
         //      const payload = {};

         //      // Extract and prepare only non-file fields for encryption
         //      for (const [key, value] of originalFormData.entries()) {
         //          const input = form.querySelector(`[name="${key}"]`);
         //          if (input && input.type !== 'file') {
         //              if (key.endsWith('[]')) {
         //                  const cleanKey = key.slice(0, -2);
         //                  if (!Array.isArray(payload[cleanKey])) {
         //                      payload[cleanKey] = [];
         //                  }
         //                  payload[cleanKey].push(value);
         //              } else {
         //                  payload[key] = value;
         //              }
         //          }
         //      }

         //      // Encrypt payload (implement your own encryptPayload function)
         //      const encrypted = await encryptPayload(payload);

         //      // Create final FormData to send
         //      const finalFormData = new FormData();
         //      finalFormData.append('_token', '{{ csrf_token() }}'); // Laravel CSRF

         //      // Append encrypted payload
         //      finalFormData.append('payload', encrypted);

         //      // Append file(s)
         //      for (const input of form.querySelectorAll('input[type="file"]')) {
         //          for (const file of input.files) {
         //              finalFormData.append(input.name, file);
         //          }
         //      }

         //      // AJAX request
         //      $.ajax({
         //          url: "{{ route('attachement-upload') }}",
         //          method: 'POST',
         //          data: finalFormData,
         //          processData: false,
         //          contentType: false,
         //          success: function(response) {
         //              if (response.success) {
         //                  toastr.success(response.message);
         //                  $('#description_upload').val('');
         //                  $('#uploadFile').val('');
         //                  list();
         //              } else {
         //                  toastr.error(response.message);
         //              }
         //              hideLoader();
         //          },
         //          error: function(xhr) {
         //              if (xhr.status === 422) {
         //                  const errors = xhr.responseJSON.errors;
         //                  let errorMessages = '';
         //                  for (const [key, messages] of Object.entries(errors)) {
         //                      errorMessages += messages.join('<br>');
         //                  }
         //                  toastr.error(errorMessages);
         //              } else {
         //                  toastr.error('Oops.. Something went wrong.');
         //              }
         //              hideLoader();
         //          }
         //      });
         //  });




     })

     function list() {
         showLoader();
         $.ajax({
             url: "{{ route('attachement-list') }}",
             type: 'Get',
             dataType: 'json',
             data: {
                 source_id: sourceId,
                 source: source
             },
             success: function(response) {
                 hideLoader();
                 if (response.data) {
                     $('.document-table tbody').empty();
                     response.data.forEach(function(item, index) {
                         let fileName = item.attachment_name.split('/').pop();
                         let path = item.attachment_name;
                         let fileUrl = `{{ route('secure-documents', ['path' => '__path__']) }}`
                             .replace('__path__', encodeURIComponent(path));
                         let row = `
                                <tr class="text-xs hover:bg-gray-50">
                                    <td class="p-3 border-b">${index + 1}</td>
                                    <td class="p-3 border-b">${fileName}</td>
                                    <td class="p-3 border-b">${item.attachment_type}</td>
                                    <td class="p-3 border-b">
                                      <div class="relative group max-w-full">
                                       <p class="text-gray-800 text-sm leading-relaxed cursor-help">
                                        ${item.description
                                            ? item.description.split(" ").slice(0, 2).join(" ") + (item.description.split(" ").length > 2 ? "..." : "")
                                            : 'N/A'}
                                        </p>
                                         <div class="absolute z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200
                                                bg-gray-800 text-white text-sm rounded-md px-3 py-2 w-64 max-w-xs shadow-lg
                                                top-full mt-2 left-1/2 right-1/2  transform -translate-x-1/2 whitespace-normal">
                                         ${item.description ? item.description : 'N/A'}
                                    </div>
                                    </div>
                                    </td>
                                    <td class="p-3 border-b">
                                        <span class="${
                                            item.status === 'Submitted'
                                                ? 'status-badge-success'
                                                : item.status === 'Draft'
                                                    ? 'status-badge-pending'
                                                    : ''
                                        }">
                                            ${item.status}
                                        </span>
                                    </td>

                                    <td class="p-3 border-b">${new Date(item.created_at).toLocaleDateString()}</td>
                                    <td class="p-3 border-b">

                                        <div class="dropdown relative inline-block">
                                            <button onClick="toggleDropdown(this)"
                                                class="inline-flex items-center text-sm font-medium text-gray-900"
                                                type="button">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <div
                                                class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[220px]">
                                                <ul class="text-sm text-black">
                                                    <li>
                                                        <a href="${fileUrl}" target="_blank" title="View"
                                                            class="flex items-center gap-2 px-6 py-2 hover:bg-gray-100 cursor-pointer">
                                                            <i class="ri-eye-line"></i>
                                                            View
                                                        </a>
                                                    </li>
                                                     ${item.status === 'Draft' ? `
                                                               <li>
                                                                   <a data-document_id="${item.id}" title="Submit"
                                                                       class="submit_document cursor-pointer flex items-center gap-2 px-6 py-2 hover:bg-gray-100">
                                                                       <i class="ri-file-line"></i>
                                                                       Submit
                                                                   </a>
                                                               </li>
                                                               <li>
                                                                   <a data-document_id="${item.id}" title="Delete"
                                                                       class="delete_document flex cursor-pointer items-center gap-2 px-6 py-2 hover:bg-gray-100 text-red-600">
                                                                       <i class="ri-delete-bin-line"></i>
                                                                       Delete
                                                                   </a>
                                                               </li>
                                                                `  : ''}
                                                </ul>

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
         showLoader();
         $.ajax({
             url: "{{ route('attachement-submit') }}",
             type: 'Get',
             data: {
                 document_id: documentId
             },
             success: function(response) {
                 hideLoader();
                 toastr.success(response.message)
                 closeNewModalfile('upload-document-modal')
                 list();
                 submitlist();

             },
             error: function(xhr) {
                 hideLoader();
                 toastr.error('Something went wrong')
             }
         });

     })
     $(document).on('click', '.delete_document', function() {
         let documentId = $(this).data('document_id');
         Swal.fire({
             title: 'Are you sure?',
             text: "You won't be able to revert this!",
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#d33',
             cancelButtonColor: '#3085d6',
             confirmButtonText: 'Yes, delete it!'
         }).then((result) => {
             if (result.isConfirmed) {
                 showLoader();
                 $.ajax({
                     url: "{{ route('attachement-delete') }}",
                     type: 'Get',
                     data: {
                         document_id: documentId
                     },
                     success: function(response) {
                         hideLoader();
                         toastr.success(response.message)
                         list();
                     },
                     error: function(xhr) {
                         hideLoader();
                         toastr.error('Something went wrong')
                     }
                 });
             }
         });
     })

     function submitlist() {
         showLoader();
         $.ajax({
             url: "{{ route('attachement-submitlist') }}",
             type: 'Get',
             dataType: 'json',
             data: {
                 source_id: sourceId,
                 source: source
             },
             success: function(response) {
                 console.log(response);
                 hideLoader();
                 $('.attachment-div').empty();
                 if (response.data) {
                     response.data.forEach(function(item, index) {
                         let fileName = item.attachment_name.split('/').pop();
                         let baseUrl = "{{ asset('') }}";
                         let fileType = item.attachment_type;
                         let path = item.attachment_name;
                         let fileUrl = `{{ route('secure-documents', ['path' => '__path__']) }}`
                             .replace('__path__', encodeURIComponent(path));
                         let html = `
                          <div class="flex items-center justify-between border p-4 rounded-lg bg-white hover:shadow-md transition duration-200 overflow-hidden">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <!-- Dynamic icon class based on fileType -->
                                <i class="ri-file-line text-xl text-gray-600 flex-shrink-0"></i>
                                <p class="font-medium text-gray-900 truncate max-w-[240px]">
                                    ${fileName}
                                </p>
                            </div>

                            <div class="flex items-center gap-3 text-gray-500 flex-shrink-0">
                                <a href="${fileUrl}" target="_blank" title="View" class="hover:text-blue-600 transition">
                                    <i class="ri-eye-line text-lg"></i>
                                </a>
                                <a href="${fileUrl}" download title="Download" class="hover:text-green-600 transition">
                                    <i class="ri-download-2-line text-lg"></i>
                                </a>
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
                 hideLoader();
                 toastr.error('An unexpected error occurred. Please try again later.')

             }
         });
     }

     function list_comment() {

         $.ajax({
             url: "{{ route('comments-list') }}",
             type: 'GET',
             dataType: 'json',
             data: {
                 source_id: source_id,
                 source: source
             },
             success: function(response) {
                 if (response.data) {
                     $('.comment_tab').empty();
                     response.data.forEach(function(item, index) {
                        console.log(item.creator,item)
                         let fullName = item.creator.name;
                         let nameParts = fullName.split(' ');
                         let firstNameInitial = nameParts[0] ? nameParts[0].charAt(0)
                             .toUpperCase() : '';
                         let lastNameInitial = nameParts[1] ? nameParts[1].charAt(0)
                             .toUpperCase() : '';
                         let initials = `${firstNameInitial}${lastNameInitial}`;
                         let baseUrl = "{{ asset('') }}";
                         let createdAt = new Date(item.created_at);

                         // Format attachments
                         let attachmentList = '';
                         if (item.comment_image && item.comment_image.length > 0) {
                             item.comment_image.forEach(function(image) {
                                 let attachmentUrl =
                                     `{{ route('secure-documents', ['path' => '__path__']) }}`
                                     .replace('__path__', encodeURIComponent(
                                         image.attachment_name));
                                 attachmentList += `
                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded border border-gray-200 text-sm">
                                <p   class="flex items-center gap-2 ">
                                    <i class="ri-file-line"></i> ${image.attachment_name.split('/').pop()}
                                </p>
                                <a target="_blank" href="${attachmentUrl}" title="View" class="text-gray-500 hover:text-blue-600">
                                    <i class="ri-eye-line "></i>
                                </a>
                            </div>
                        `;
                             });
                         } else {
                             attachmentList =
                                 '<div class="text-gray-500 text-sm">No attachments available</div>';
                         }

                         // Format date
                         let options = {
                             day: '2-digit',
                             month: 'short',
                             year: 'numeric',
                             hour: 'numeric',
                             minute: 'numeric',
                             hour12: true
                         };
                         let formattedDate = createdAt.toLocaleString('en-GB', options);

                         // Final rendered HTML
                         let listItem = `
                    <div class=" border rounded-md p-3 bg-white  hover:shadow-md">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <i class="ri-account-circle-line text-blue-500 text-xl"></i>
                                <h6 class="text-sm font-semibold text-gray-800">${item.creator.name}</h6>
                            </div>
                            <p class="text-xs text-gray-500"> ${formattedDate}</p>
                        </div>
                        <div class="text-sm text-gray-700 mb-2 break-all">
                            ${item.comment}
                        </div>
                        <button title="View Uploaded Attachments" type="button" class="flex items-center text-sm  hover:text-blue-500"
                            onclick="document.getElementById('attachment-${index}').classList.toggle('hidden')">
                            <i class="ri-attachment-line mr-1"></i> View Attachments
                        </button>

                        <div id="attachment-${index}" class="hidden mt-3 space-y-2">
                            ${attachmentList}
                        </div>
                    </div>
                `;

                         $('.comment_tab').append(listItem);
                     });
                 } else {
                     toastr.error(response.message);
                 }
             },
             error: function(xhr, status, error) {
                 toastr.error('An unexpected error occurred. Please try again later.');
             }
         });

     }

     $('#uploadForm_comment').on('submit', function(e) {
         e.preventDefault();
         var formData = new FormData(this);
         showLoader();
         $.ajax({
             url: "{{ route('comment-store') }}",
             type: 'POST',
             data: formData,
             contentType: false,
             processData: false,
             success: function(response) {
                 hideLoader();
                 if (response.data) {
                     toastr.success(response.message)
                     list_comment();
                 } else {
                     toastr.error(response.message)
                 }
             },
             error: function(xhr, status, error) {
                 hideLoader();
                 if (xhr.status === 422) {
                     // Validation error
                     let errors = xhr.responseJSON.errors;
                     let errorMessages = '';
                     for (const [key, messages] of Object.entries(errors)) {
                         errorMessages += messages.join('<br>');
                     }

                     toastr.error('Validation Error !')
                 } else {
                     toastr.error('Something went wrong: ' + error)
                 }
             }
         });
     });


 </script>
