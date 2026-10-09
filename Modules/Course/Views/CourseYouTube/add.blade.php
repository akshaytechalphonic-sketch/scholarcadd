@extends('layouts.app')

@section('content')
    <div>
        @php echo breadcrums('Course.Course YouTube Video Add',['common.home','Course.Course YouTube Vedios List','Course.Course YouTube Video Add'],['dashboard','course-you-tube-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('course-you-tube-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('course-you-tube-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        @if ($data)
            <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                data-maxlength="100" name="course_youtubeId" required>
        @endif
        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">

            <div class="w-full">
                <label class="text-sm font-semibold text-black">
                    {{ __('Course.Course Name') }}
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <select id="course_id" class="select-single mt-1" style="width:100%" name="course_id" required>
                    <option value="" disabled>Select Course..</option>
                    @foreach ($course as $courses)
                        <option value="{{ $courses->id }}" @if (old('course_id', $data?->course_id) == $courses->id) selected @endif>
                            {{ $courses->course_title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-2">
            <!-- Class Details Section -->
            <div class="flex justify-between items-center my-2">
                <h4 class="text-lg font-semibold text-gray-600  ">Video Details</h4>

                <button type="button" onclick="addClass()" class="btn-primary-sm">
                    <i class="ri-add-line"></i> Add Video
                </button>
            </div>



            <div id="class-container" class="space-y-4">
                <div class="border p-4 bg-white rounded-md  space-y-3 shadow">
                    <div id="class-container" class="space-y-4">
                        @if ($multidat && $multidat->isNotEmpty())
                            @foreach ($multidat as $coursyoutubeDat)
                                <div class="border p-4 bg-white rounded-lg shadow space-y-3">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block form-label">
                                                {{ __('Course.video-link') }}
                                            </label>
                                            <textarea class="form-input mt-1 description-validation w-full border rounded p-2" name="video_link[]"
                                                data-maxlength="200">{{ $coursyoutubeDat?->video_url }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                                        </div>
                                        <div>
                                            <label class="block form-label">{{ __('Course.Video Description') }}</label>
                                            <textarea class="form-input mt-1 description-validation w-full border rounded p-2" name="video_description[]"
                                                data-maxlength="200">{{ $coursyoutubeDat?->video_description }}</textarea>
                                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                                        </div>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" onclick="removeItem(this)"
                                            class="bg-red-500 text-white flex items-center gap-1 px-3 py-2 rounded-md hover:bg-red-600">
                                            <i class="ri-delete-bin-line"></i> Delete item
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="border p-4 bg-white rounded-lg shadow space-y-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block form-label">
                                            {{ __('Course.video-link') }}
                                        </label>
                                        <textarea class="form-input mt-1 description-validation w-full border rounded p-2" name="video_link[]"
                                            data-maxlength="200">{{ $data?->video_url }}</textarea>
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                                    </div>
                                    <div>
                                        <label class="block form-label">{{ __('Course.Video Description') }}</label>
                                        <textarea class="form-input mt-1 description-validation w-full border rounded p-2" name="video_description[]"
                                            data-maxlength="200">{{ $data?->video_description }}</textarea>
                                        <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" onclick="removeItem(this)"
                                        class="bg-red-500 text-white flex items-center gap-1 px-3 py-2 rounded-md hover:bg-red-600">
                                        <i class="ri-delete-bin-line"></i> Delete item
                                    </button>
                                </div>
                            </div>
                        @endif
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
<script>
    function addClass() {
        const container = document.getElementById('class-container');
        const div = document.createElement('div');
        div.classList.add('border', 'p-4', 'bg-white', 'rounded-lg', 'shadow', 'space-y-3');
        div.innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block form-label">
                        {{ __('Course.video-link') }}
                    </label>
                    <textarea class="form-input mt-1 description-validation w-full border rounded p-2"
                              name="video_link[]" data-maxlength="200"></textarea>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                </div>
                <div>
                    <label class="block form-label">{{ __('Course.Video Description') }}</label>
                    <textarea class="form-input mt-1 description-validation w-full border rounded p-2"
                              name="video_description[]" data-maxlength="200"></textarea>
                    <div class="char-count text-xs text-gray-500 text-right mt-1">0/200</div>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="removeItem(this)"
                        class="bg-red-500 text-white flex items-center gap-1 px-3 py-2 rounded-md hover:bg-red-600">
                    <i class="ri-delete-bin-line"></i> Delete item
                </button>
            </div>
        `;
        container.appendChild(div);
    }

    function removeItem(button) {
        button.closest('.border').remove();
    }
</script>
@endsection
