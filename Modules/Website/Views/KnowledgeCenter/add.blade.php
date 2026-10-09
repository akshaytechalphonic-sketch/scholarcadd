@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('website.Blog Add',['common.home','website.Blog List','website.Blog Add'],['dashboard','knowledge-center-list']) @endphp
    @section('back_button')
        <a href="{{ route('knowledge-center-list') }}">
            <button type="button"
                class="flex gap-1 items-center px-4 py-2
                   text-sm font-medium text-gray-900 hover:bg-gray-100">
                <i class="ri-arrow-left-line"></i> Back
            </button>
        </a>
    @endsection

    @if ($data)
        <form action="{{ route('knowledge-center-update') }}" method="post" enctype="multipart/form-data">
            <input type="hidden" name= "knowledge_id" value = "{{ encrypt($data?->id) }}">
        @else
            <form action="{{ route('knowledge-center-store') }}" method="post" enctype="multipart/form-data">
    @endif
    @csrf
    <div class=" mx-auto p-4 sm:p-3 md:p-3 space-y-4">


        <div class="bg-white p-2 sm:p-4 md:p-4 rounded-xl border space-y-2">
            <h2 class="text-xl sm:text-xl md:text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Course Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.Select Category') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1 form-input" style="width:100%" name="resourceCategory" required>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($blogcategories as $categories)
                            <option value="{{ $categories->id }}" @if ($data?->resource_category == $categories->id) selected @endif>
                                {{ $categories->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('website.Author') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->resource_auther }}"
                        data-maxlength="100" name="resource_auther" required>

                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/100</div>
                </div>
            </div>
            <div>
                <label for="title" class="text-sm font-semibold text-black">
                    {{ __('common.Title') }}
                </label>

                <input type="text" id="resource_title" name="resource_title" required
                    value="{{ $data?->resource_title }}" data-maxlength="500" class="form-input mt-1 w-full" />
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
            </div>
        </div>

        <div class="bg-white p-2 sm:p-4 md:p-4 rounded-xl border space-y-2">
            <h2 class="text-xl sm:text-xl md:text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Course Category
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">
                        {{ __('Course.Course Category') }}

                    </label>
                    <select id="course_id" class="select-single mt-1 mb-8 form-input" style="width:100%"
                        name="course_id">
                        <option value="">Select Category..</option>
                        @foreach ($cousercategories as $category)
                            <option value="{{ $category?->id }}" @if (old('category_id', $data?->course_category_id) == $category?->id) selected @endif>
                                {{ $category?->category_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                </div>


            </div>
            <div>
                <label for="" class="text-sm font-semibold text-black">{{ __('website.Short Description') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <textarea type="text" class="form-input mt-1 description-validation" rows="6" name="short_description"
                    data-maxlength="500">{{ $data?->short_description }}</textarea>
                <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
            </div>
        </div>

        {{-- ///  ------------ this code for images ----------------- --}}
        <div class="bg-white p-2 sm:p-4 md:p-4 rounded-xl border space-y-2">
            <h2 class="text-xl sm:text-xl md:text-xl font-semibold mb-4 border-b pb-2 text-gray-800"> Media</h2>

            <div class="relative">
                <div class="flex gap-2 item-center">
                    <label class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Feature
                        Image </label>
                    <p class="text-gray-400 font-semibold">( Upload a image between 1200×675 and 2400×1350 px (Max size:
                        5MB) )</p>
                </div>

                <div id="feature-image-drop-area"
                    class="flex items-center justify-center w-full h-32 sm:h-36
           border-2 border-dashed border-gray-400 rounded-xl
           bg-gray-50 cursor-pointer
           hover:border-blue-500 hover:bg-gray-100
           transition relative">

                    <input type="file" name="feature_image" id="feature_image" accept="image/*" class="hidden">

                    <!-- 👇 IMPORTANT -->
                    <span id="upload-text" class="text-gray-400 text-sm sm:text-base pointer-events-none">
                        Drag & drop or click to upload br
                    </span>
                </div>
                <p id="feature-image-error" class="text-xs text-red-500 mt-1 hidden"></p>

                <!-- PREVIEW -->
                <div id="feature_image_preview" class="mt-2 hidden">
                    <img id="preview-img" class="h-20 sm:h-24 rounded-md border object-contain" src=""
                        alt="Preview">
                </div>

                <!-- REMOVE BUTTON -->
                <button type="button" id="remove-image"
                    class="hidden mt-2 px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:bg-red-600 transition">
                    Remove
                </button>
            </div>
            @if ($data?->header_image)
                <div>
                    <label class="block text-gray-700 font-medium mb-2 text-sm sm:text-base">Uploaded
                        Image</label>
                    <div class="p-3 border border-gray-200 rounded-lg bg-gray-50 flex items-center justify-center">
                        <a target="_blank" href="{{ route('secure-documents', ['path' => $data?->header_image]) }}">
                            <img src="{{ route('secure-documents', ['path' => $data?->header_image]) }}"
                                class="h-20 sm:h-24 object-contain rounded-md" alt="Uploaded Image">
                        </a>
                    </div>
                </div>
            @endif

        </div>
        {{-- ###--  upload image -----------------  --}}


        <div class="bg-white p-2 sm:p-4 md:p-4 rounded-xl border space-y-2">
            <h2 class="text-xl sm:text-xl md:text-xl font-semibold mb-4 border-b pb-2 text-gray-800">Body & SEO Section
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full my-2">
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('website.Page Title') }}
                    </label>
                    <input type="text" class="char-count form-input text-xs  mt-1"
                        value="{{ $data?->resource_title }}" data-maxlength="500" name="meta_title" required>

                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.status') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1 form-input" style="width:100%" name="status" required>
                        <option value=""></option>
                        <option value="Active" @if ($data?->status == 'Active') selected @endif>Active</option>
                        <option value="In Active" @if ($data?->status == 'In Active') selected @endif>In Active </option>
                    </select>
                </div>


                <div class="w-full">
                    <label for=""
                        class="text-sm font-semibold text-black">{{ __('website.Meta Description') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <textarea type="text" class="form-input mt-1 description-validation" rows="6" name="meta_description"
                        data-maxlength="500">{{ $data?->meta_description }}</textarea>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('website.Meta Keyword') }}
                    </label>
                    <textarea type="text" class="form-input mt-1 description-validation" rows="6" name="meta_keywords"
                        data-maxlength="500">{{ $data?->meta_keywords }}</textarea>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/500</div>
                </div>

                {{--  <div class="w-full">
                    <label class="text-sm font-semibold text-black">
                        {{ __('website.Tags') }}
                        <span class="text-red-500 font-bold">*</span>
                    </label>
                    <select id="category_id" class="select-single mt-1 mb-8" style="width:100%" name="category_id"
                        required>
                        <option value="" disabled>Select Category..</option>

                        @foreach ($cousercategories as $category)
                            <option value="{{ $category?->id }}" @if (old('category_id', $data?->category_id) == $category?->id) selected @endif>
                                {{ $category?->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="char-count text-xs text-gray-500 text-right mt-1"></div>
                </div>  --}}
            </div>
            <div class="w-full mt-4">
                <label for="" class="text-sm font-semibold text-black">{{ __('common.details') }}
                </label>
                <textarea type="text" class="summernote" name="overview">{!! $data?->overview !!}</textarea>
            </div>

            <div class="w-full mt-4">
                <label for="" class="text-sm font-semibold text-black">Table Of Content
                </label>
                <textarea type="text" class="summernote" name="tableof_content">{!! $data?->tableof_content !!}</textarea>
            </div>


        </div>

        <div class="flex justify-end mt-4">
            <div class="flex gap-2">
                <button type="reset" class="btn-primary-sm flex items-center gap-1">
                    <i class="ri-refresh-line"></i>
                    {{ __('common.reset') }}
                </button>
                <button type="submit" class="btn-success-sm flex items-center gap-1">
                    <i class="ri-check-line"></i>
                    {{ __('common.submit') }}
                </button>
            </div>
        </div>

        </form>
    </div>

@endsection
@section('scripts')
    {{--  @include('layouts.select2js')  --}}
    @include('layouts.summer_note_js')
    @include('layouts.image_validation_js')
    @include('layouts.sweetalert_js')

    <script>
        $('#summernote').summernote({
            placeholder: 'Hello stand alone ui',
            tabsize: 3,
            height: 220,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>
    <script>
        $(document).ready(function() {
            let selectedSubcategory = "{{ old('subcategory_id', $data?->subcategory_id) }}";

            function loadSubcategories(categoryId, selectedId = null) {
                if (!categoryId) return;
                $.ajax({
                    url: "{{ route('get-child-Course', '') }}",
                    type: "GET",
                    data: {
                        parent_id: categoryId
                    },
                    success: function(res) {
                        console.log(res);
                        let $subcat = $("#subcategory_id");
                        $subcat.empty().append(
                            '<option value="" disabled>Select Sub Category..</option>');

                        $.each(res, function(key, subcategory) {
                            let selected = (selectedId && selectedId == subcategory.id) ?
                                'selected' : '';
                            $subcat.append(
                                `<option value="${subcategory.id}" ${selected}>${subcategory.category_name}</option>`
                            );
                        });
                    }
                });
            }
            $("#category_id").change(function() {
                loadSubcategories($(this).val());
            });
            let initialCategory = $("#category_id").val();
            if (initialCategory) {
                loadSubcategories(initialCategory, selectedSubcategory);
            }
        });
    </script>

    {{-- <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dropArea = document.getElementById("feature-image-drop-area");
            const input = document.getElementById("feature_image");
            const previewImg = document.getElementById("preview-img");
            const previewBox = document.getElementById("feature_image_preview");
            const uploadText = document.getElementById("upload-text");
            const removeBtn = document.getElementById("remove-image");

            let droppedFile = null; // 🔥 IMPORTANT

            // 🛑 Prevent browser default behavior
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
                dropArea.addEventListener(event, e => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            dropArea.addEventListener("click", () => input.click());

            dropArea.addEventListener("drop", e => {
                const file = e.dataTransfer.files[0];
                if (!file) return;

                droppedFile = file; // store reference
                attachFileToInput(file);
                showPreview(file);
            });

            input.addEventListener("change", () => {
                if (input.files.length) {
                    droppedFile = input.files[0];
                    showPreview(droppedFile);
                }
            });

            function attachFileToInput(file) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
            }

            function showPreview(file) {
                previewImg.src = URL.createObjectURL(file);
                previewBox.classList.remove("hidden");
                uploadText.classList.add("hidden");
                removeBtn.classList.remove("hidden");
            }

            removeBtn.addEventListener("click", () => {
                droppedFile = null;
                input.value = "";
                previewImg.src = "";
                previewBox.classList.add("hidden");
                uploadText.classList.remove("hidden");
                removeBtn.classList.add("hidden");
            });

            // 🔥 FINAL GUARANTEE BEFORE SUBMIT
            const form = input.closest("form");
            if (form) {
                form.addEventListener("submit", () => {
                    if (droppedFile && input.files.length === 0) {
                        attachFileToInput(droppedFile);
                    }
                });
            }
        });
    </script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dropArea = document.getElementById("feature-image-drop-area");
            const input = document.getElementById("feature_image");
            const previewImg = document.getElementById("preview-img");
            const previewBox = document.getElementById("feature_image_preview");
            const uploadText = document.getElementById("upload-text");
            const removeBtn = document.getElementById("remove-image");
            let droppedFile = null;
            const MAX_SIZE = 5 * 1024 * 1024;
            const MIN_WIDTH = 1200;
            const MIN_HEIGHT = 675;
            const MAX_WIDTH = 2400;
            const MAX_HEIGHT = 1350;
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(event => {
                dropArea.addEventListener(event, e => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            dropArea.addEventListener("click", () => input.click());
            dropArea.addEventListener("drop", e => {
                const file = e.dataTransfer.files[0];
                if (!file) return;
                validateAndProcess(file);
            });
            input.addEventListener("change", () => {
                if (input.files.length) {
                    validateAndProcess(input.files[0]);
                }
            });

            function validateAndProcess(file) {
                // File size check
                if (file.size > MAX_SIZE) {

                    Swal.fire(
                        'Image must not exceed 5MB.',
                    );
                    resetInput();
                    return;
                }
                const img = new Image();
                const objectUrl = URL.createObjectURL(file);
                img.onload = function() {
                    const width = img.width;
                    const height = img.height;
                    const ratio = width / height;
                    //  Minimum resolution
                    if (width < MIN_WIDTH || height < MIN_HEIGHT) {
                        Swal.fire(
                            `Minimum resolution of an image must be ${MIN_WIDTH} × ${MIN_HEIGHT}px.`,
                        );
                        cleanup(objectUrl);
                        return;
                    }
                    //  Maximum resolution
                    if (width > MAX_WIDTH || height > MAX_HEIGHT) {
                        Swal.fire(
                            `Maximum resolution of an image must be ${MAX_WIDTH} × ${MAX_HEIGHT}px.`,
                        );
                        cleanup(objectUrl);
                        return;
                    }
                    // If valid
                    droppedFile = file;
                    attachFileToInput(file);
                    showPreview(file);
                    URL.revokeObjectURL(objectUrl);
                };

                img.src = objectUrl;
            }

            function cleanup(objectUrl) {
                URL.revokeObjectURL(objectUrl);
                resetInput();
            }

            function attachFileToInput(file) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
            }

            function showPreview(file) {
                previewImg.src = URL.createObjectURL(file);
                previewBox.classList.remove("hidden");
                uploadText.classList.add("hidden");
                removeBtn.classList.remove("hidden");
            }

            function resetInput() {
                droppedFile = null;
                input.value = "";
                previewImg.src = "";
                previewBox.classList.add("hidden");
                uploadText.classList.remove("hidden");
                removeBtn.classList.add("hidden");
            }

            removeBtn.addEventListener("click", resetInput);

            const form = input.closest("form");
            if (form) {
                form.addEventListener("submit", () => {
                    if (droppedFile && input.files.length === 0) {
                        attachFileToInput(droppedFile);
                    }
                });
            }
        });
    </script>
@endsection
