@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Cosultation Enquiries view'),[__('common.home'), __('website.Consultation Enquiries List'), __('website.Cosultation Enquiries view')],['dashboard','enquiries-list']) @endphp
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
                <label for="" class="text-sm font-semibold text-black">Enquiry Type
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->enquiry_type }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Job title
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->job_title }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Group Size
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->group_size }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Created On
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->created_at }}" readonly>
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
