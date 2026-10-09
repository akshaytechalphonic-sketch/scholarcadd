@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Contact Submission view'),[__('common.home'), __('website.Contact Submission List'), __('website.Contact Submission view')],['dashboard','submission-list']) @endphp
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
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->fullName }}" readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">E-Mail
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->email }}" readonly>

            </div>

            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Contact Number
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->phone }}" readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Enquiry Type
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->enquiryType }}"
                        readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Contact Mode
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->contactMode }}"
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
                <label for="" class="text-sm font-semibold text-black">Delivery Mode
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->deliveryMode }}"
                        readonly>
            </div>
                 <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Submission Date
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->created_at }}"
                        readonly>
            </div>
        </div>
        <div class="w-full">
            <label for="" class="text-sm font-semibold text-black">Message
                <textarea type="text" class="limited-input form-input mt-1" rows="8" readonly>{!! $data?->message !!}</textarea>

        </div>

    </div>
</div>
@endsection
@section('scripts')
@endsection
