@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Slider',['common.home','Slider'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('slider-list') @endphp
    @endsection
</div>
<div class="mt-4">
    <details
        class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-md mb-2"
        open>
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Add Slider</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
      <form action="{{ $data ? route('slider-update', ['id' => encrypt($data->id)]) : route('slider-store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($data)
                <input type="hidden" name="id" value="{{ encrypt($data->id) }}">
            @endif
            @csrf
            <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Sub Heading</label>
                        <input type="text" class="input-box form-input mt-1 form-control-file" name="sub_heading"
                            value="{{ old('sub_heading', $data?->sub_heading) }}">
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Heading</label>
                        <input type="text" class="input-box form-input mt-1 form-control-file" name="heading"
                            value="{{ old('heading', $data?->heading) }}">
                    </div>
                    <div class="w-full relative z-10">
                        <label class="text-sm font-semibold text-black">Main Content</label>
                        <input type="text" class="input-box form-input mt-1" name="mainContent"
                            value="{{ old('mainContent', $data?->main_content) }}">
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Section Image</label>
                        <input type="file" class="input-box form-input mt-1 form-control-file" name="sectionImage"
                            accept="image/*" onchange="previewImage(event);">

                        {{-- Hidden input to send existing image path on edit --}}
                        @if ($data?->section_image)
                            <input type="hidden" name="existing_section_image" value="{{ $data->section_image }}">
                        @endif

                        {{-- Image preview --}}
                        @if ($data?->section_image)
                            <img id="image-preview" src="{{ asset($data->section_image) }}" alt="Section Image" class="mt-2"
                                style="max-height: 100px; display: block;">
                        @else
                            <img id="image-preview" style="display: none; max-height: 100px;" alt="Section Image" class="mt-2">
                        @endif
                    </div>




                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Card One Label </label>
                        <input class="w-full form-input description-validation mt-1" name="label_one"
                            value="{{ old('label_one', $data?->label_one) }}" data-maxlength="100">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/100</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Card One Counter </label>
                        <input class="w-full form-input description-validation mt-1" name="label_one_counter"
                            value="{{ old('label_one_counter', $data?->label_one_counter) }}" data-maxlength="100">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/100</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Card Two Label </label>
                        <input class="w-full form-input description-validation mt-1" name="label_two"
                            value="{{ old('label_two', $data?->label_two) }}" data-maxlength="100">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/100</div>
                    </div>
                    <div class="w-full">
                        <label class="text-sm font-semibold text-black">Card Two Counter </label>
                        <input class="w-full form-input description-validation mt-1" name="label_two_counter"
                            value="{{ old('label_two_counter', $data?->label_two_counter) }}" data-maxlength="100">
                        <div class="char-count text-xs text-gray-500 text-right mt-1 hidden">0/100</div>
                    </div>
                </div>

               <div class="mt-4">
                    <label class="text-sm font-semibold text-black">Select Slider Image</label>
                    <input type="file" id="singleImageInput" class="input-box form-input mt-1" accept="image/*" onchange="addImageToSlider()" />
                </div>

                <input type="file" id="sliderImagesInput" name="slider_images[]" multiple class="hidden" />

                @if($existingImages && count($existingImages) > 0)
                    @foreach($existingImages as $image)
                        <input type="hidden" name="existing_images[]" value="{{ asset($image) }}">
                    @endforeach
                @endif


                <div id="previewContainer" class="mt-4 flex gap-4 flex-wrap">
                    <!-- Existing and new images will be rendered here -->
                </div>

                <div class="flex justify-end mt-4">
                    <div class="flex gap-2">
                        <button type="reset" class="btn-primary-sm flex items-center gap-1">
                            <i class="ri-refresh-line"></i>
                            Reset
                        </button>
                        <button type="submit" class="btn-success-sm flex items-center gap-1">
                            <i class="ri-check-line"></i>
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </details>
</div>
{{-- <div class="mt-4">
    <details
        class="accordion group border border-gray-200 rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-md mb-2">
        <summary
            class="flex items-center justify-between cursor-pointer select-none bg-white px-5 py-2 text-sm font-medium text-gray-800 group-open:bg-gray-200 transition-colors duration-300">
            <span class="text-sm font-semibold">Image Slider</span>
            <i class="ri-arrow-down-s-line text-xl"></i>
        </summary>
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-5 py-4 text-sm text-gray-700 bg-white border-t border-gray-200">
                <div class="w-full">
                    <label class="text-sm font-semibold text-black">
                        Select Slider Images
                    </label>
                    <input type="file" id="imageInput" class="input-box form-input mt-1" name="slider_images[]"
                        accept="image/*" multiple onchange="previewImages(this)" />
                </div>
                <div id="previewContainer" class="mt-4 flex gap-4 flex-wrap"></div>
                <div class="flex justify-end mt-4">
                    <div class="flex gap-2">
                        <button type="button" class="btn-success-sm flex items-center gap-1">
                            <i class="ri-check-line"></i>
                            Add Image
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </details>
</div> --}}
@endsection
@section('scripts')
@include('layouts.summer_note_js')
{{-- <script>
    let selectedFiles = [];

    function previewImages(input) {
        selectedFiles = Array.from(input.files);
        renderPreviews();
    }

    function renderPreviews() {
        const previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();

            reader.onload = function(e) {
                const card = document.createElement('div');
                card.className =
                    'relative w-[150px] h-[80px] border rounded-md bg-white flex items-center justify-center';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'max-w-full max-h-full object-contain';

                const removeBtn = document.createElement('button');
                removeBtn.innerHTML = '&times;';
                removeBtn.className =
                    'absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-sm flex items-center justify-center hover:bg-red-600';

                removeBtn.onclick = () => removeImage(index);

                card.appendChild(img);
                card.appendChild(removeBtn);
                previewContainer.appendChild(card);
            };

            reader.readAsDataURL(file);
        });
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);

        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        document.getElementById('imageInput').files = dataTransfer.files;

        renderPreviews();
    }
</script> --}}

<script>
    let sliderFiles = [];

    function renderPreview() {
        const preview = document.getElementById('previewContainer');
        preview.innerHTML = '';

        // Render existing images
        const existingImages = document.querySelectorAll('input[name="existing_images[]"]');
        existingImages.forEach(input => {
            const wrapper = document.createElement('div');
            wrapper.className = 'w-24 h-24 relative';

            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover rounded';
            img.src = input.value;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '×';
            removeBtn.className = 'absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs';
            removeBtn.onclick = () => {
                // Add to deleted_images input
                const deletedInput = document.querySelector('input[name="deleted_images"]');
                if (!deletedInput) {
                    const newInput = document.createElement('input');
                    newInput.type = 'hidden';
                    newInput.name = 'deleted_images';
                    newInput.value = '[]';
                    document.querySelector('form').appendChild(newInput);
                }
                let deleted = JSON.parse(deletedInput.value);
                deleted.push(input.value);
                deletedInput.value = JSON.stringify(deleted);

                input.remove();
                wrapper.remove();
            };

            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            preview.appendChild(wrapper);
        });

        // Render new files (same as before)
        sliderFiles.forEach((file, index) => {
            const wrapper = document.createElement('div');
            wrapper.className = 'w-24 h-24 relative';

            const img = document.createElement('img');
            img.className = 'w-full h-full object-cover rounded';
            img.src = URL.createObjectURL(file);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '×';
            removeBtn.className = 'absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs';
            removeBtn.onclick = () => {
                sliderFiles.splice(index, 1);
                syncHiddenInput();
                renderPreview();
            };

            wrapper.appendChild(img);
            wrapper.appendChild(removeBtn);
            preview.appendChild(wrapper);
        });
    }


    function syncHiddenInput() {
        const hiddenInput = document.getElementById('sliderImagesInput');
        const dt = new DataTransfer();
        sliderFiles.forEach(file => dt.items.add(file));
        hiddenInput.files = dt.files;
    }

    function addImageToSlider() {
        const singleInput = document.getElementById('singleImageInput');
        if (!singleInput.files || singleInput.files.length === 0) return;

        Array.from(singleInput.files).forEach(file => {
            sliderFiles.push(file);
        });
        singleInput.value = '';
        syncHiddenInput();
        renderPreview();
    }

    // Call renderPreview on page load to show existing images
    document.addEventListener('DOMContentLoaded', renderPreview);
</script>


<script>
  function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('image-preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
    }

    function resetImagePreview() {
        const fileInput = document.querySelector('input[name="sectionImage"]');
        const preview = document.getElementById('image-preview');
        fileInput.value = '';
        if (preview) {
            preview.style.display = 'none';
            preview.src = '';
        }
    }


</script>
@endsection
