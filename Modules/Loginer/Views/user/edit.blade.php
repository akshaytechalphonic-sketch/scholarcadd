@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    <style>
        .toggle-checkbox:checked {
            left: 1.5rem;
        }

        .toggle-checkbox:checked+.toggle-label {
            background-color: #3b82f6;
        }
    </style>
@endsection
@section('content')
    <div>
        @php echo breadcrums('company.edit-user',['common.home','company.edit-user'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('users-list') @endphp
    @endsection
    <div class="text-xs w-full border  bg-white rounded-md p-4">
        <form method="POST" action="{{ route('users-update') }}">
            @csrf
            <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="w-full">
                    <input value="{{ encrypt($data['user']?->id) }}" type="hidden" class="form-input mt-1 " name="id">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.name') }} <span
                            class="text-danger">*</span> </label>
                    <input value="{{ $data['user']->name }}" type="text" readonly class="input-box form-input mt-1 ">
                    @include('components.error_code', ['keys' => 'name'])
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.email') }} <span
                            class="text-danger">*</span></label>
                    <input value="{{ $data['user']->email }}" type="text" readonly
                        class="input-box form-input mt-1 ">
                    @include('components.error_code', ['keys' => 'email'])
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.company-name') }}
                        <span class="text-danger">*</span> </label>
                    <select style="width: 100%" class="select-multi mt-1 " id="company_id"
                        data-placeholder="Select Company" name="company_id[]" multiple="multiple" required>
                        @foreach ($data['company'] as $companies)
                            <option value="{{ $companies->id }}" @if (in_array($companies->id, $data['selectedcompany'])) selected @endif>
                                {{ $companies?->name }}</option>
                        @endforeach
                    </select>
                    @include('components.error_code', ['keys' => 'company_id'])
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.role') }} <span
                            class="text-danger">*</span></label>
                    <select multiple="multiple" class="select-multi mt-1 " id="select2" data-placeholder="Select Role"
                        name="roles[]" required style="width: 100%">
                        <option disabled>{{ __('company.select-role') }}</option>
                        @foreach ($data['roles'] as $role)
                            <option value="{{ $role->name }}"
                                {{ in_array($role->name, $data['userRole']) ? 'selected' : '' }}>
                                {{ $role->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('role'))
                        <span class="text-danger text-left">{{ $errors->first('role') }}</span>
                    @endif
                </div>
                <div class="w-full">
                    @if ($data['emp_access'])
                        <label for=""
                            class="text-sm font-semibold text-black">{{ __('company.employee-name') }}</label>
                        <select style="width: 100%" class="select-multi mt-1" name="employee_id">
                            <option value="">{{ __('common.chooseoption') }}</option>
                            @foreach ($data['emps'] as $emp)
                                <option value="{{ $emp->id }}" @if ($data['user']?->emp_id == $emp?->id) selected @endif>
                                    {{ $emp->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="w-full">
                    {{-- @if (!empty(array_intersect(['Employee', 'Admin'], $data['userRole'])))
                        <div class="flex gap-2 items-center">
                            <label for="enable_2fa">{{ __('company.enable-2fa') }}</label>
                            <input type="checkbox" class="rounded-sm cursor-pointer  " id="enable_2fa" name="enable_2fa"
                                {{ $data['user']->google2fa_secret ? 'checked' : '' }}>
                        </div>
                    @endif --}}
                    @if (!empty(array_intersect(['Employee', 'Admin'], $data['userRole'])))
                        <div class="flex items-center gap-4">
                            <label for="enable_2fa"
                                class="text-sm font-medium text-gray-800">{{ __('company.enable-2fa') }}</label>
                            <div
                                class="relative inline-block w-12 h-6 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="enable_2fa" id="enable_2fa"
                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer top-0 left-0 transition-all duration-200 ease-in-out
                {{ $data['user']->google2fa_secret ? 'checked:bg-primary checked:left-6' : '' }}"
                                    {{ $data['user']->google2fa_secret ? 'checked' : '' }}>
                                <label for="enable_2fa"
                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer transition duration-200 ease-in-out"></label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="flex gap-2 mt-2 justify-end">
                <button type="reset" class="btn-primary-sm">
                    {{ __('common.cancel') }}
                </button>
                <button type="submit" class="btn-success-sm">{{ __('common.update') }}</button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
@include('loginer::user.extra_js')
@include('layouts.select2js')
@include('components.formvalidationjs')
@endsection
