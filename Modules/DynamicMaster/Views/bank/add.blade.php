@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('common.bank-add',['common.home','common.bank-list','common.bank-add'],['dashboard','bank-list']) @endphp
    @section('back_button')
        @php echo back_button('bank-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="{{ route('bank-store') }}" method="post">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.Name') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input type="hidden" name="bank_id"
                        @if ($data) value="{{ encrypt($data?->id) }}" @endif></input>
                    <input class="input-box form-input mt-1" name="name"  maxlength="100" value="{{ $data?->name }}"
                        @if ($data) readonly @endif required> </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black"> {{ __('common.Account No') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="account_no"  maxlength="16" value="{{ $data?->account_no }}"
                        @if ($data) readonly @endif required> </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.Contact Name') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" value="{{ $data?->contact_name }}"  maxlength="100" name="contact_name"
                        required> </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.email') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input type="email" class="input-box form-input mt-1" value="{{ $data?->email }}" maxlength="100" name="email" required>
                    </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.phone-number') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input type="text" class="input-box form-input mt-1 number-only" maxlength="12" value="{{ $data?->phone_no }}" name="phone_no" required>
                    </input>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.Bank Group') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="bank_group_id" required>
                        <option value=""> </option>
                        @foreach ($bank_groups as $ctry)
                            <option value="{{ $ctry?->id }}" @if ($ctry?->id == $data?->bank_group_id) selected @endif>
                                {{ $ctry?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.Country') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="country_id" required>
                        <option value=""> </option>
                        @foreach ($countries as $ctry)
                            <option value="{{ $ctry?->id }}" @if ($ctry?->id == $data?->country_id) selected @endif>
                                {{ $ctry?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.City') }}</label>
                    <select class="select-single mt-1" style="width:100%" name="city_id">
                        <option value=""> </option>
                        @foreach ($cities as $ctry)
                            <option value="{{ $ctry?->id }}" @if ($ctry?->id == $data?->city_id) selected @endif>
                                {{ $ctry?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.Bank Main Account') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="gl_account_id" required>
                        <option value=""> </option>
                        @foreach ($gl_accounts as $ctry)
                            <option value="{{ $ctry?->id }}" @if ($ctry?->id == $data?->gl_account_id) selected @endif>
                                {{ $ctry?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.Currency') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="currency_id" required>
                        <option value=""> </option>
                        @foreach ($currencies as $ctry)
                            <option value="{{ $ctry?->id }}" @if ($ctry?->id == $data?->currency_id) selected @endif>
                                {{ $ctry?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="block form-label mb-2">{{ __('common.Reconciliation') }}</label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <!-- Hidden input to ensure "No" is sent when unchecked -->
                        <input type="hidden" name="enable_reconciliation" value="No">

                        <!-- Checkbox overrides value to "Yes" when checked -->
                        <input type="checkbox" value="Yes" name="enable_reconciliation"
                            @if (!isset($data) || $data?->enable_reconciliation == 'Yes') checked @endif class="sr-only peer">

                        <div
                            class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-primary transition duration-300">
                        </div>
                        <div
                            class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5">
                        </div>
                    </label>
                </div>
            </div>
            <div class="flex justify-end py-2">
                <div class="flex gap-2">
                    <button type="reset" class="btn-primary-sm">{{ __('common.reset') }}</button>
                    <button type="submit" class="btn-success-sm">{{ __('common.submit') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.image_validation_js')
@endsection
