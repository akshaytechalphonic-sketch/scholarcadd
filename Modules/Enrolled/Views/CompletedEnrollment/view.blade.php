@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div>
        @php echo breadcrums(__('website.Enrollment view'),[__('common.home'), __('website.Enrollment List'), __('website.Enrollment view')],['dashboard','complete-enrollment-list']) @endphp
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
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->first_name . ' ' . $data->last_name}}" readonly>

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
                <label for="" class="text-sm font-semibold text-black">Transaction Id
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->transaction_id }}"
                        readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Paid Amount
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data->paid_amount }}"
                        readonly>

            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Payment_Date
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->payment_date }}"
                        readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Payment Status
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->payment_status }}"
                        readonly>
            </div>
          <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Course Name
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->coursedate->course_title ?? " " }}"
                        readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Contact Type
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->Coursedate?->contact_type }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Enroll Type
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->enroll_type }}" readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Alternat Contact
                    <input type="text" class="limited-input form-input mt-1" value="{{ $data?->alternate_contact }}"
                        readonly>
            </div>
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">Alternate Email
                    <input type="text" class="limited-input form-input mt-1"
                        value="{{ $data?->alternate_email }}" readonly>
            </div>
        </div>


    </div>
</div>
@endsection
@section('scripts')
@endsection
