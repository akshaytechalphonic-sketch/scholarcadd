@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.Course Youtube Video View'),[__('common.home'), __('Course.Course YouTube Vedios List'), __('Course.Course Youtube Video View')],['dashboard','course-you-tube-list']) @endphp

    @section('back_button')
        <a href="{{ route('course-you-tube-list') }}">
            <button type="button"
                class="flex gap-1 items-center px-4 py-2
                   text-sm font-medium text-gray-900 hover:bg-gray-100">
                <i class="ri-arrow-left-line"></i> Back
            </button>
        </a>
    @endsection
    @section('extra_buttons')
        <a href="{{ route('course-you-tube-edit', ['id' => encrypt($data->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
    @endsection
    <hr class="md-6" />
    <div class="grid grid-cols-2 md:grid-cols-1 gap-5 w-full my-2">

        <div class="w-full">
            <p class="md:text-1xm font-semibold text-gray-800">
                {{ __('Course.Course Category') }} : <span class="md:text-1xl font-semibold text-gray-800">
                    {{ $data?->Coursedate->course_title }} </span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @if ($multidat && $multidat->isNotEmpty())
            @foreach ($multidat as $coursyoutubeDat)
                <div class="bg-white border rounded-md shadow-md hover:shadow-lg overflow-hidden p-2">
                    <div class="bg-black aspect-video">
                        <iframe class="w-full h-full" src="{{ $coursyoutubeDat?->video_url }}" title="Video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </div>
                    <div class="p-6 space-y-4">
                        <p class="md:text-1xm font-semibold text-gray-800">
                            {{ $coursyoutubeDat?->video_description }}
                        </p>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white border rounded-lg shadow-sm overflow-hidden p-6 space-y-4">
                <label class="block form-label">
                    {{ __('course.Video Link (Please insert src of a embedded video link only, without any quotation.)') }}
                </label>
                <iframe width="360" height="320" src="{{ $coursyoutubeDat?->video_url }}" frameborder="0"
                    allowfullscreen></iframe>

                <label class="block form-label">{{ __('course.Video Description') }}</label>
                <textarea type="text" class="form-input mt-1 description-validation" name="video_description[]" data-maxlength="200"
                    readonly>{{ $data?->video_description }}</textarea>
            </div>
        @endif
    </div>

</div>

@endsection
