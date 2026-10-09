@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Company Alumni Add',['common.home','Course.Company Alumni List','Course.Company Alumni Add'],['dashboard','course-company-alumni-list']) @endphp
        @section('back_button')
            @php echo back_button('back') @endphp
        @endsection
        <div class="text-xs w-full bg-white border rounded-md p-4">
            @if ($data)
                <form action="{{ route('course-company-alumni-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('course-company-alumni-store') }}" method="post" enctype="multipart/form-data">
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
                <div class="flex justify-between items-center my-2">
                    <h4 class="text-lg font-semibold"> Company Name</h4>
                </div>
                <div id="Company-Name-container" class="space-y-2">
                    <div class="flex items-center space-x-2 border p-3 bg-white rounded-md shadow">
                        <input name="Company_name" placeholder="Enter Company Name" class="form-input" value="{{ old('Company_name', $data?->company_name) }}" required>
                    </div>
                </div>
            </div>

                                    <div class="my-2">
                <label class="flex justify-between items-center my-2">
                    <h4 class="text-lg font-semibold"> Company Link</h4>
                    <span class="text-red-500 font-bold">*</span>
                </label>
                <div id="Company-Link-container" class="space-y-2">
                    <div class="flex items-center space-x-2 border p-3 bg-white rounded-md shadow">
                       <input name="Company_link" placeholder="Enter Company Link" class="form-input" value="{{ old('Company_link', $data?->company_link) }}" required>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
                <div class="w-full">
                    <label for=""
                           class="text-sm font-semibold text-black">{{ __('Course.Country Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
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
                    <label for=""
                           class="text-sm font-semibold text-black">{{ __('Course.State Name') }}
                        <span class="text-red-500 font-bold">*</span></label>
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
            </div>


            <hr class="my-6">

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
        // function addCertificateText() {
        //     const container = document.getElementById('certificate-text-container');
        //     const div = document.createElement('div');
        //     div.classList.add('flex', 'items-center', 'space-x-2', 'border', 'p-3', 'bg-white', 'rounded-lg', 'shadow');
        //     div.innerHTML = `
        //         <input type="text" name="certificate_text[]" placeholder="Enter certificate text"
        //                class="form-input" />
        //         <button type="button" onclick="removeItem(this)"
        //                 class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600">
        //             <i class="ri-delete-bin-line"></i> Delete
        //         </button>
        //     `;
        //     container.appendChild(div);
        // }

        function removeItem(button) {
            button.parentElement.remove();
        }

        $(document).ready(function () {
            $('#country_id').change(function () {
                var countryId = $(this).val();
                if (countryId) {
                    $.ajax({
                        url: '{{ route('get-states') }}',
                        type: 'GET',
                        data: {country_id: countryId},
                        success: function (data) {
                            $('#state_id').empty();
                            $('#state_id').append('<option value="" disabled>Select State..</option>');
                            $.each(data, function (key, value) {
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
