{{-- js for comment section --}}
<script>
    const maxFiles = 3;
    const maxSize = 2 * 1024 * 1024;
    const allowedTypes = [
        "application/pdf", "image/jpeg",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
    ];

    let files = [];

    function handleFileSelect(event) {
        const newFiles = Array.from(event.target.files);
        for (const file of newFiles) {
            if (!allowedTypes.includes(file.type)) {
                alert(`${file.name} is not allowed.`);
                continue;
            }
            if (file.size > maxSize) {
                alert(`${file.name} exceeds 2MB.`);
                continue;
            }
            if (files.length >= maxFiles) {
                alert("Max 3 files allowed.");
                break;
            }
            files.push(file);
        }
        // event.target.value = '';
        renderFilePreview();
    }

    function renderFilePreview() {
        const preview = document.getElementById('filePreview');
        preview.innerHTML = '';
        files.forEach((file, index) => {
            const isImage = file.type.startsWith('image/');
            const div = document.createElement('div');
            div.className = "flex items-center justify-between border p-3 rounded hover:bg-gray-50";
            div.innerHTML = `
          <div class="flex items-center gap-2">
            ${isImage ? `<img src="${URL.createObjectURL(file)}" class="w-8 h-8 object-cover rounded" alt="">` : ''}
            <span class="text-sm truncate max-w-[200px]">${file.name}</span>
          </div>
          <div class=" text-md space-x-2">
            <button title="View" onclick="viewFile(${index})" class="text-blue-600 ">
                <i class="ri-eye-line"></i></button>
            <button title="Delete " onclick="removeFile(${index})" class="text-red-600 ">
                <i class="ri-delete-bin-line"></i></button>
          </div>
        `;
            preview.appendChild(div);
        });
    }

    function viewFile(index) {
        const url = URL.createObjectURL(files[index]);
        window.open(url, '_blank');
    }

    function removeFile(index) {
        files.splice(index, 1);
        renderFilePreview();
    }

    function submitComment() {
        const input = document.getElementById('commentInput');
        const text = input.value.trim();
        const container = document.getElementById('commentList');
        const commentDiv = document.createElement('div');
        commentDiv.className = "p-4 bg-gray-50 rounded-lg shadow-sm";
        let fileList = '';
        if (files.length > 0) {
            fileList += '<ul class="mt-2 ml-5 list-disc text-sm text-gray-600">';
            for (let f of files) {
                fileList += `<li>${f.name}</li>`;
            }
            fileList += '</ul>';
        }
        commentDiv.innerHTML = `
        <p class="text-gray-800">${text || "(No message text)"}</p>
        ${fileList}
        <button onclick="toggleReply(this)" class="text-sm text-blue-600 hover:underline mt-2">Reply</button>
      `;
        container.prepend(commentDiv);
        input.value = '';
        files = [];
        document.getElementById('filePreview').innerHTML = '';
    }

    function toggleReply(button) {
        const parent = button.parentElement;
        if (parent.querySelector('.reply-box')) return;
        const replyBox = document.createElement('div');
        replyBox.className = "reply-box mt-3 space-y-2";

        replyBox.innerHTML = `
        <textarea rows="2" class="w-full border rounded p-2 text-sm" placeholder="Write a reply..."></textarea>
        <button class="bg-blue-500 text-white text-sm px-3 py-1 rounded hover:bg-blue-600" onclick="submitReply(this)">Reply</button>
      `;
        parent.appendChild(replyBox);
    }

    function submitReply(button) {
        const textarea = button.previousElementSibling;
        const text = textarea.value.trim();
        if (!text) return;
        const reply = document.createElement('div');
        reply.className = "ml-4 mt-2 p-2 bg-white border rounded text-sm text-gray-700";
        reply.textContent = text;
        const parent = button.closest('.reply-box').parentElement;
        parent.appendChild(reply);
        textarea.value = '';
    }
</script>
<script>
    var source_id;
    var source;
    $(document).ready(function() {
        let button = $('[data-source-id]').first();
        if (button.length > 0) {
            source_id = button.attr('data-source-id');
            source = button.attr('data-source');
            if (source_id && source) {
                $('#comment_source_id').val(source_id);
                $('#comment_source').val(source);
                list_log();
                list_comment();
            }

        }

        function list_log() {

            $.ajax({
                url: "{{ route('activity-log-list') }}",
                type: 'Get',
                dataType: 'json',
                data: {
                    source_id: source_id,
                    source: source
                },
                success: function(response) {
                    if (response.data) {
                        $('.activity_tab').empty();
                        response.data.forEach(function(item, index) {
                            let fullName = item.created_by_name || '';
                            let nameParts = fullName.split(' ');
                            let firstNameInitial = nameParts[0] ? nameParts[0].charAt(0)
                                .toUpperCase() : '';
                            let lastNameInitial = nameParts[1] ? nameParts[1].charAt(0)
                                .toUpperCase() : '';
                            let initials = `${firstNameInitial}${lastNameInitial}`;
                            // let baseUrl = "{{ asset('') }}";
                            // let file = item.profile_img ? item.profile_img : '';
                            // let fileUrl = `${baseUrl}${item.profile_img}`;
                            let createdAt = new Date(item.created_at);
                            let options = {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: 'numeric',
                                hour12: true
                            };
                            let formattedDate = createdAt.toLocaleString('en-GB', options);
                            let listItem = `
                                 <div class="mb-10 ml-6 relative">
                                 <div class="absolute -left-11 ">
                                    <div  class="flex items-center justify-center w-10 h-10 bg-gray-400 rounded-full text-white">
                                        ${initials}                                     
                                    </div>
                                 </div>
                                 <p class="text-sm ml-4 font-semibold text-gray-900">${item.message}</p>
                                 <time class="block text-sm text-gray-500 ml-4">${formattedDate}</time>
                             </div>
                            `;
                            $('.activity_tab').append(listItem);
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

    })
</script>
