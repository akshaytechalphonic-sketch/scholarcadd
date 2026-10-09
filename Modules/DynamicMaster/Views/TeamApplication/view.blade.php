@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Team Application view'),[__('common.home'), __('website.Team Application List'), __('website.Team Application view')],['dashboard','application-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    @section('extra_buttons')
        {{-- <a href="{{ route('course-edit', ['id' => encrypt($data->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a> --}}
    @endsection
    <div class="text-xs w-full bg-white rounded-md p-4 border mb-3 space-y-6">
        <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Full Name
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->full_name }}" readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">E-Mail
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->email }}" readonly>

            </div>

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Contact Number
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->phone }}"
                        readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Enquiry
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->enquiry_for }}"
                        readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Training Expertise
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->training_expertise }}"
                        readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Company Name
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->company_name }}"
                        readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Job Title
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->job_title }}"
                        readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Course Name
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->Coursedate?->course_title }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Country Name
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->CourseCountryName?->name }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Group Size
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->group_size }}"
                        readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Delivery Mode
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->training_delivery_mode }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">ready to travel
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->ready_to_travel }}" readonly>
            </div>


            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Preferred Option
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->preferred_option }}" readonly>
            </div>

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Linkedin Profile
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->linkedin_profile }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Submission Date
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->created_at }}" readonly>
            </div>

<div class="w-full">
    <label class="text-sm font-semibold text-black">CV</label>

    @php
        // uploaded_files is a JSON string
        $files = json_decode($data->uploaded_files, true);
    @endphp

    @if(is_array($files) && count($files) > 0)
        @php
            $filePath = 'teamApplication/' . $files[0];
        @endphp

        <a href="{{ asset($filePath) }}" target="_blank"
           class="mt-2 inline-flex items-center gap-2 text-blue-600 hover:underline">
            📄 View CV
        </a>
    @else
        <p class="text-sm text-gray-500 mt-2">No CV uploaded</p>
    @endif
</div>




        </div>


    </div>
</div>
@endsection
@section('scripts')
@endsection
