@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('Course.enroll-course-corporate-view'),[__('common.home'), __('admin.enroll-course-corporate-list'), __('Course.enroll-course-corporate-view')],['dashboard','enroll-course-corporate-list']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    @section('extra_buttons')
        <a href="{{ route('course-edit', ['id' => encrypt($data->id)]) }}" class="topbarbtn">
            <i class="ri-edit-2-line"></i>
            {{ __('common.edit') }}
        </a>
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
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->contact_number }}"
                        readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Enquiry
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->enquiry_for }}"
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
                <label for="" class="text-sm font-semibold text-black">Preferred Contact Mode
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->preferred_contact_mode }}" readonly>
            </div>


            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Message
                    <textarea type="text" class="limited-input form-input mt-1" readonly>{!! $data?->message !!}</textarea>

            </div>

        </div>


    </div>
</div>
@endsection
@section('scripts')
@endsection
