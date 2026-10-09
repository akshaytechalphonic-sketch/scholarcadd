@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Course Schedule Add',['common.home','Course.Course Schedule List','Course.Course Schedule Add'],['dashboard','bank-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('course-schedule-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('course-schedule-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        @if ($data)
            <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                data-maxlength="100" name="Course_id" required>
        @endif
        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
            <div class="w-full">
                <label for=""
                    class="text-sm font-semibold text-black">{{ __('Course.Course Name(Short Form)') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <input type="text" class="limited-input form-input mt-1" value="{{ $data?->course_short_name }}"
                    data-maxlength="100" name="course_short_name" required>
                </input>
                <div class="char-count text-xs text-gray-500 text-right mt-1 ">0/100</div>
            </div>

            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.Category') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="category_id" class="select-single mt-1" style="width:100%" name="course_category_id"
                    required>
                    <option value="" disabled>Select Category..</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if (old('category_id', $data?->course_category_id) == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>


        </div>

        <div class="my-2">
            <!-- It Includes Section -->
            <div class="flex justify-between items-center my-2">
                <h4 class="text-lg font-semibold text-gray-600 ">It Includes</h4>
                <button type="button" onclick="addInclude()" class=" btn-primary-sm flex items-center">
                    <i class="ri-add-line"></i> Add item
                </button>
            </div>
            <div id="includes-container" class="space-y-2 ">
                <div class="flex items-center space-x-2 border p-3 bg-white rounded-md shadow">
                    <input type="text" name="includes[]" placeholder="Enter item" class="form-input" />
                    <button type="button" onclick="removeItem(this)"
                        class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600">
                        <i class="ri-delete-bin-line "></i> Delete
                    </button>
                </div>
            </div>

        </div>

        <hr class="my-6">

        <div class="mt-2">
            <!-- Class Details Section -->
            <div class="flex justify-between items-center my-2">
                <h4 class="text-lg font-semibold text-gray-600  ">Class Details</h4>
                <button type="button" onclick="addClass()" class="btn-primary-sm">
                    <i class="ri-add-line"></i> Add item
                </button>
            </div>
            <div id="class-container" class="space-y-4">
                <div class="border p-4 bg-white rounded-md  space-y-3 shadow">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block form-label">Class Start Date</label>
                            <input type="date" name="class_start[]" class="form-input">
                        </div>
                        <div>
                            <label class="block form-label">Class End Date</label>
                            <input type="date" name="class_end[]" class="form-input">
                        </div>
                        <div>
                            <label class="block form-label">Discount (Amt or %)</label>
                            <input type="text" name="discount[]" class="form-input">
                        </div>
                        <div>
                            <label class="block form-label">Discount End Date</label>
                            <input type="date" name="discount_end[]" class="form-input">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="removeItem(this)"
                            class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600 ">
                            <i class="ri-delete-bin-line "></i> Delete item
                        </button>
                    </div>
                </div>
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
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.tab2_js')
@include('layouts.summer_note_js')
@include('layouts.image_validation_js')
<script>
    function addInclude() {
        const container = document.getElementById('includes-container');
        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'space-x-2', 'border', 'p-3', 'bg-white', 'rounded-lg', 'shadow');
        div.innerHTML = `
        <input type="text" name="includes[]" placeholder="Enter item"
          class="form-input" />
        <button type="button" onclick="removeItem(this)"
          class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600 ">
          <i class="ri-delete-bin-line "></i>  Delete
        </button>
      `;
        container.appendChild(div);
    }

    function addClass() {
        const container = document.getElementById('class-container');
        const div = document.createElement('div');
        div.classList.add('border', 'p-4', 'bg-white', 'rounded-lg', 'shadow', 'space-y-3');
        div.innerHTML = `
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block form-label">Class Start Date</label>
            <input type="date" name="class_start[]"
              class="form-input">
          </div>
          <div>
            <label class="block form-label">Class End Date</label>
            <input type="date" name="class_end[]"
              class="form-input">
          </div>
          <div>
            <label class="block form-label">Discount (Amt or %)</label>
            <input type="text" name="discount[]"
              class="form-input">
          </div>
          <div>
            <label class="block form-label">Discount End Date</label>
            <input type="date" name="discount_end[]"
              class="form-input">
          </div>
        </div>
     <div class="flex justify-end">
           <button type="button" onclick="removeItem(this)"
          class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600 ">
          <i class="ri-delete-bin-line "></i>  Delete item
        </button>
        </div>
      `;
        container.appendChild(div);
    }

    function removeItem(button) {
        button.parentElement.remove();
    }
</script>

<script>
    function validateFileInput(inputId, errorId, allowedTypes, maxSizeMB) {
        const input = document.getElementById(inputId);
        const error = document.getElementById(errorId);
        const maxSize = maxSizeMB * 1024 * 1024;

        input.addEventListener('change', function() {
            error.textContent = '';
            error.classList.add('hidden');

            const file = input.files[0];
            if (!file) return;

            if (!allowedTypes.includes(file.type)) {
                error.textContent =
                    `Invalid file type. Allowed: ${allowedTypes.map(t => t.split('/')[1]).join(', ').toUpperCase()}`;
                error.classList.remove('hidden');
                input.value = '';
                return;
            }

            if (file.size > maxSize) {
                error.textContent = `File "${file.name}" exceeds the ${maxSizeMB}MB limit.`;
                error.classList.remove('hidden');
                input.value = '';
                return;
            }
        });
    }

    // Apply validation
    validateFileInput('feature_image', 'feature-image-error', ['image/jpeg', 'image/png'], 2);
    validateFileInput('brochure_file', 'brochure-error', ['application/pdf'], 2);
</script>
@endsection
