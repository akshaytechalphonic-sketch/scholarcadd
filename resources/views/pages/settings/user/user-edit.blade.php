@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('company.edit-user',['common.home','company.edit-user'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('user-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form method="POST" action="{{ route('user-update') }}">
            @csrf
            <div class="w-full">
                <div class="w-full">
                    <input value="{{ base64_encode(convert_uuencode($user?->id)) }}" type="hidden" class="form-input mt-1 " name="id">
                    <label for="" class="text-sm font-semibold text-black">{{__('company.name')}}</label>
                    <input  value="{{ $user->name }}" type="text" readonly class="input-box form-input mt-1 ">
                    @include('components.error_code', ['keys' => 'name',])
                </div>
                <br>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{__('company.email')}} </label>
                    <input value="{{ $user->email }}" type="text" readonly class="input-box form-input mt-1 " >
                    @include('components.error_code', ['keys' => 'email',])
                </div>

                <br>
                <label for="" class=" text-sm font-semibold text-black">{{__('company.company-name')}} </label>
                <select style="width: 100%" class="select-multi mt-1 " id="company_id" data-placeholder="Select Company" name="company_id[]" multiple="multiple"  required>
                    @foreach ($company as $companies)
                        <option value="{{ $companies->id }}" @if(in_array($companies->id, $selectedcompany)) selected @endif  > {{ $companies?->name }}</option>
                    @endforeach
                </select>
                @include('components.error_code', [ 'keys' => 'company_id', ])
                <br>
                <label for="" class=" text-sm font-semibold text-black">{{__('company.role')}} </label>
                <select multiple="multiple" class="select-multi mt-1 " id="select2" data-placeholder="Select Role" name="roles[]" required style="width: 100%">
                    <option disabled>{{__('company.select-role')}}</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ in_array($role->name, $userRole) ? 'selected' : '' }}>
                            {{ $role->name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('role'))
                    <span class="text-danger text-left">{{ $errors->first('role') }}</span>
                @endif

                <br>
                @if($emp_access)
                    <label for="" class="text-sm font-semibold text-black">{{__('company.employee-name')}}</label>
                    <select style="width: 100%" class="select-multi mt-1" name="employee_id">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($emps as $emp)
                            <option value="{{ $emp->id }}" @if($user?->emp_id == $emp?->id) selected @endif> {{ $emp->name }}</option>
                        @endforeach
                    </select>
                @endif

                <br>
                @if (!empty(array_intersect(['Employee', 'Admin'], $userRole)))
                    <div class="col-md-12 mb-3 form-group">
                        <label for="enable_2fa">{{__('company.enable-2fa')}}</label>
                        <input type="checkbox" id="enable_2fa" name="enable_2fa" {{ $user->google2fa_secret ? 'checked' : '' }}>
                    </div>
                @endif
            </div>


            <div class="flex gap-2 mt-2 justify-end">
                <button type="reset" class="btn-primary-sm">
                    {{__('common.cancel')}}
                </button>
                <button type="submit" class="btn-success-sm">{{__('common.update')}}</button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
@include('pages.settings.user.extra_js')
@include('layouts.select2js')
@include('components.formvalidationjs')

@endsection
