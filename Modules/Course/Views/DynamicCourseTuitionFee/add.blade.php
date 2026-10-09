@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Tuition Fee Add',['common.home','Course.Tuition Fee List','Course.Tuition Add'],['dashboard','tuition-fee-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    <div class="text-xs w-full bg-white border rounded-md p-4">
        @if ($data)
            <form action="{{ route('tuition-fee-update') }}" method="post" enctype="multipart/form-data">
            @else
                <form action="{{ route('tuition-fee-store') }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        @if ($data)
            <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
                data-maxlength="100" name="id" required>
        @endif

        <div class="my-2">
            <label for="" class="text-sm font-semibold text-black">{{ __('Course.Course Name') }}
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

        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.Country Name') }}
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
                <label for="" class="text-sm font-semibold text-black">{{ __('Course.State') }}
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

        <div class="my-2">
            <div class="flex justify-between items-center my-2">
                <label class="text-sm font-semibold text-black">ClassRoom Pointers</label>
                <button type="button" onclick="addInclude('classroom-container')"
                    class="btn-primary-sm flex items-center">
                    <i class="ri-add-line"></i> Add item
                </button>
            </div>
            <div id="classroom-container" class="space-y-2"></div>
        </div>
         <div class="my-2">
            <div class="flex justify-between items-center my-2">
                <label class="text-sm font-semibold text-black">Basic Pointers</label>
                <button type="button" onclick="addInclude('basic-container')"
                    class="btn-primary-sm flex items-center">
                    <i class="ri-add-line"></i> Add item
                </button>
            </div>
            <div id="basic-container" class="space-y-2"></div>
        </div>


        <div class="my-2">
            <div class="flex justify-between items-center my-2">
                <label class="text-sm font-semibold text-black">Superior Pointers</label>
                <button type="button" onclick="addInclude('superior-container')"
                    class="btn-primary-sm flex items-center">
                    <i class="ri-add-line"></i> Add item
                </button>
            </div>
            <div id="superior-container" class="space-y-2"></div>
        </div>

        <div class="my-2">
            <div class="flex justify-between items-center my-2">
                <label class="text-sm font-semibold text-black">premium Pointers</label>
                <button type="button" onclick="addInclude('premium-container')"
                    class="btn-primary-sm flex items-center">
                    <i class="ri-add-line"></i> Add item
                </button>
            </div>
            <div id="premium-container" class="space-y-2"></div>
        </div>

        <div class="my-2">
            <div class="flex justify-between items-center my-2">
                <h4 class="text-lg font-semibold text-gray-600 ">Read More</h4>
            </div>
            <div id="readMore-container" class="space-y-2">
                <div class="flex items-center space-x-2 border p-3 bg-white rounded-md shadow">
                    <textarea name="readMore" placeholder="Enter readmore" class="form-input">{{ old('readMore', $data?->read_more) }}</textarea>
                </div>
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

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
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

{{-- <script>
    const existingOtherPointer = @json(json_decode($data?->other_pointer ?? '[]', true));
    function addInclude(containerId) {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'space-x-2', 'border', 'p-3', 'bg-white', 'rounded-lg', 'shadow');
        div.innerHTML = `
            <input type="text" name="${containerId.replace('-container', '')}_pointer[]" placeholder="Enter item"
                   class="form-input" />
            <button type="button" onclick="removeItem(this)"
                    class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600">
                <i class="ri-delete-bin-line"></i> Delete
            </button>
        `;
        container.appendChild(div);
    }

    function removeItem(button) {
        button.parentElement.remove();
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (existingOtherPointer.length > 0) {
            existingOtherPointer.forEach(item => {
                addInclude(item);
            });
        } else {
            // Add one empty input for new entry
            addInclude();
        }
    });

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
</script> --}}

<script>
     const existingClassroom = @json(json_decode($data?->classroom_pointers ?? '[]', true));
    const existingPremium = @json(json_decode($data?->premium_pointers ?? '[]', true));
    const existingSuperior = @json(json_decode($data?->superior_pointers ?? '[]', true));
    const existingBasic = @json(json_decode($data?->basic_pointers ?? '[]', true));

    function addInclude(containerId, value = '') {
        const container = document.getElementById(containerId);
        const div = document.createElement('div');
        div.classList.add('flex', 'items-center', 'space-x-2', 'border', 'p-3', 'bg-white', 'rounded-lg', 'shadow');
        div.innerHTML = `
        <input type="text" name="${containerId.replace('-container', '')}[]" value="${value}" placeholder="Enter item" class="form-input" />
        <button type="button" onclick="removeItem(this)"
                class="bg-red-500 text-white flex items-center gap-1 px-2 py-2 rounded-md hover:bg-red-600">
            <i class="ri-delete-bin-line"></i> Delete
        </button>
    `;
        container.appendChild(div);
    }

    function removeItem(button) {
        button.parentElement.remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Populate other-container
        if (existingClassroom.length > 0) {
            existingClassroom.forEach(item => {
                addInclude('classroom-container', item);
            });
        } else {
            addInclude('classroom-container');
        }
        if (existingBasic.length > 0) {
            existingBasic.forEach(item => {
                addInclude('basic-container', item);
            });
        } else {
            addInclude('basic-container');
        }

         if (existingSuperior.length > 0) {
            existingSuperior.forEach(item => {
                addInclude('superior-container', item);
            });
        } else {
            addInclude('superior-container');
        }

        if (existingPremium.length > 0) {
            existingPremium.forEach(item => {
                addInclude('premium-container', item);
            });
        } else {
            addInclude('premium-container');
        }
    });

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
