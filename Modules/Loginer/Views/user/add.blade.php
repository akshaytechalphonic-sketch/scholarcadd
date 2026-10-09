@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('company.create-user',['common.home','company.create-user'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('users-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white border rounded-md p-4">
        <form method="POST" action="{{ route('users-store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full mt-4">
                <div class="w-full">
                    <label for=""
                        class="text-sm font-semibold text-black">{{ __('company.employee-name') }}</label>
                    <select style="width: 100%" class="select-multi mt-1" id="emp_id" name="emp_id"
                        onchange="$.get_emp_data(this)">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($employee as $emp)
                            <option value="{{ encrypt($emp->id) }}">{{ $emp?->name }} - {{ $emp?->empCode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.name') }}<span
                            class="text-red-500 font-bold">*</span></label>
                    <input value="{{ old('name') }}" type="text" class="input-box form-input mt-1 " name="name"
                        id="name" required>
                    @include('components.error_code', ['keys' => 'name'])
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.email') }}<span
                            class="text-red-500 font-bold">*</span></label>
                    <input value="{{ old('email') }}" type="email" class="form-input mt-1 " name="email"
                        id="email" required>
                    @include('components.error_code', ['keys' => 'email'])
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.company-name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-multi mt-1 " id="company_id"
                        data-placeholder="{{ __('company.select-company') }}" name="company_id[]" multiple="multiple"
                        style="width: 100%" required>
                        @foreach ($company as $companies)
                            <option value="{{ $companies->id }}"> {{ $companies?->name }}</option>
                        @endforeach
                    </select>
                    @include('components.error_code', ['keys' => 'company_id'])
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.role') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <select multiple="multiple" class="select-multi mt-1 " id="roles"
                        data-placeholder="{{ __('company.select-role') }}" name="roles[]" required style="width: 100%">
                        <option disabled>{{ __('company.select-role') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role?->name }}"> {{ $role?->name }}</option>
                        @endforeach
                    </select>
                    @include('components.error_code', ['keys' => 'roles'])
                </div>

            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                <!-- New Password Field -->
                <div class="mb-4 relative">
                    <label for="passwordField" class="block text-sm font-medium text-gray-700 mb-1">New
                        Password</label>
                    <div class="relative">
                        <input type="password" id="passwordField" name="password" minlength="6" maxlength="50"
                            placeholder="Enter Your New Password"
                            class="block w-full pr-10 py-2 px-4 border border-gray-300 rounded-md  text-sm form-input">
                        <i id="togglePassword"
                            class="ri-eye-line absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-xl text-gray-500"></i>
                    </div>
                    <div id="message" class="mt-3 text-sm hidden">
                        <h3 class="text-blue-600 font-semibold mb-2">Password must contain the following:</h3>
                        <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
                        <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
                        <p id="number" class="invalid">A <b>number</b></p>
                        <p id="specialcharacter" class="invalid">Exactly <b>3 special characters</b></p>
                        <p id="length" class="invalid">Minimum <b>12 characters</b></p>
                    </div>
                </div>
                <!-- Confirm Password Field -->
                <div class="mb-4 relative">
                    <label for="confirmPasswordField" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                        Password</label>
                    <div class="relative">
                        <input type="password" id="confirmPasswordField" name="confirm_password" minlength="6"
                            maxlength="50" placeholder="Enter Your Confirm Password"
                            class="block w-full pr-10 py-2 px-4 border border-gray-300 rounded-md  text-sm form-input">
                        <i id="toggleConfirmPassword"
                            class="ri-eye-line absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-xl text-gray-500"></i>
                    </div>
                    <span id="passwordMismatchMessage" class="text-sm mt-1 block"></span>
                </div>
            </div>

            <div class="flex gap-2 mt-2 justify-end">
                <button type="reset" class="btn-primary-sm flex items-center gap-1">
                    <i class="ri-refresh-line"></i>
                    {{ __('common.reset') }}
                </button>
                <button type="submit" id="submitButton" class="btn-success-sm flex items-center gap-1">
                    <i class="ri-file-line"></i>
                    {{ __('common.submit') }}
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
@include('loginer::user.extra_js')
@include('layouts.select2js')
@endsection
