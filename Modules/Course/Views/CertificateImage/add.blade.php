@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Certificate image Add',['common.home','Course.Certificate image List','Course.Certificate image Add'],['dashboard','course-certificate-image-list']) @endphp
        @section('back_button')
            @php echo back_button('back') @endphp
        @endsection
        <div class="text-xs w-full bg-white border rounded-md p-4">
            @if ($data)
                <form action="{{ route('course-certificate-image-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('course-certificate-image-store') }}" method="post" enctype="multipart/form-data">
            @endif
            @csrf
            @if ($data)
                <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                       data-maxlength="100" name="id" required>
            @endif

            <div class="my-2">
                <label for=""
                       class="text-sm font-semibold text-black">{{ __('Course.Course Name') }}
                    <span class="text-red-500 font-bold">*</span></label>
                <select id="course_id" class="select-single mt-1" style="width:100%" name="course_id" required>
                    <option value="" disabled>Select Course..</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @if (old('course_id', $data?->course_id) == $course->id) selected @endif>
                            {{ $course->course_title }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="my-2">
    <label for="image" class="text-sm font-semibold text-black">{{ __('Course.Certificate Image') }} <span class="text-red-500 font-bold">*</span></label>
    <input type="file" id="image" class="form-input mt-1" name="image" accept="image/*" onchange="previewImage(event)">
    <div class="mt-2">
        @if ($data && $data->image_path)
            <img id="old-image" src="{{ asset($data->image_path) }}" alt="Certificate Image" style="max-width: 200px; height: auto;">
        @endif
        <img id="preview" src="" alt="Preview" style="max-width: 200px; height: auto; display: none;">
    </div>
</div>





            <hr class="my-6">

            <div class="flex justify-end mt-4">
                <div class="flex gap-2">
                    <button type="reset" class="btn-primary-sm flex items-center gap-1" onclick="resetImagePreview()">
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

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    @include('layouts.select2js')
    @include('layouts.tab2_js')
    @include('layouts.summer_note_js')
    <script>
        function removeItem(button) {
            button.parentElement.remove();
        }

        function resetImagePreview() {
    const preview = document.getElementById('preview');
    const oldImage = document.getElementById('old-image');
    const fileInput = document.getElementById('image');

    fileInput.value = '';
    preview.style.display = 'none';
    preview.src = '';
    // if (oldImage) {
    //     oldImage.style.display = 'block';
    // }
}


function previewImage(event) {
    const preview = document.getElementById('preview');
    const oldImage = document.getElementById('old-image');
    const file = event.target.files[0];
    if (file) {
        preview.style.display = 'block';
        if (oldImage) {
            oldImage.style.display = 'none';
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}




    </script>
@endsection
