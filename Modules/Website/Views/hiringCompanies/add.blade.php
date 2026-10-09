@extends('layouts.app')

@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection

@section('back_button')
    @php echo back_button('back') @endphp
@endsection

@section('content')
    <div>
        @php echo breadcrums('website.Hiring Companies Add',['common.home','website.Hiring Companies List','website.Hiring Companies Add'],['dashboard','course-city-list']) @endphp
    </div>

    <div class="text-xs w-full bg-white border rounded-md p-4">
        @if($data)
            <form action="{{ route('hiring-companies-update') }}" method="post" enctype="multipart/form-data">
        @else
            <form action="{{ route('hiring-companies-store') }}" method="post" enctype="multipart/form-data">
        @endif
            @csrf
            @if ($data)
                <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}" data-maxlength="100" name="id" required>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full my-2">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Course Name') }} <span class="text-red-500 font-bold">*</span></label>
                    <select id="course_id" class="select-single mt-1" style="width:100%" name="course_id" required>
                        <option value="" disabled>Select Course..</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id', $data?->course_id) == $course->id) selected @endif>
                                {{ $course->course_title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Country Name') }} <span class="text-red-500 font-bold">*</span></label>
                    <select id="country_id" class="select-single mt-1" style="width:100%" name="country_id" required>
                        <option value="" disabled>Select Country..</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @if (old('country_id', $data?->country_id) == $country->id) selected @endif>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.State Name') }} <span class="text-red-500 font-bold">*</span></label>
                    <select id="state_id" class="select-single mt-1" style="width:100%" name="state_id" required>
                        <option value="" disabled>Select State..</option>
                        @if ($data)
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}" @if (old('state_id', $data?->state_id) == $state->id) selected @endif>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Company Name') }} <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="form-input mt-1" value="{{ $data?->company_name }}" data-maxlength="100" name="company_name" required>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Job Role') }} <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="form-input mt-1" value="{{ $data?->job_role }}" data-maxlength="100" name="job_role" required>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('Course.Job link') }} <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="form-input mt-1" value="{{ $data?->job_link }}" data-maxlength="100" name="job_link" required>
                </div>
            </div>

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Job Description') }} <span class="text-red-500 font-bold">*</span></label>
                <textarea class="form-input mt-1" name="job_description" required>{{ old('job_description', $data?->job_description) }}</textarea>
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

    <script>
        $(document).ready(function() {
            $('#country_id').change(function() {
                var countryId = $(this).val();
                if (countryId) {
                    $.ajax({
                        url: '{{ route('get-states') }}',
                        type: 'GET',
                        data: { country_id: countryId },
                        success: function(data) {
                            $('#state_id').empty();
                            $('#state_id').append('<option value="">Select State..</option>');
                            $.each(data, function(key, value) {
                                $('#state_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#state_id').empty();
                    $('#state_id').append('<option value="" disabled>Select State..</option>');
                }
            });
        });
    </script>
@endsection

@section('scripts')
    @include('layouts.select2js')
    @include('layouts.summer_note_js')
    @include('layouts.image_validation_js')
@endsection
