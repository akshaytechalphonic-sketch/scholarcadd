@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @if ($data['data'])
            @php echo breadcrums('company.emp-edit-employee',['common.home','company.emp-head','company.emp-edit-employee'],['dashboard','employee-list']) @endphp
        @else
            @php echo breadcrums('company.emp-create-employee',['common.home','company.emp-head','company.emp-create-employee'],['dashboard','employee-list']) @endphp
        @endif
    @section('back_button')
        @php echo back_button('employee-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4 border">
        <form action="{{ route('employee-store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.employee-code') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input type="hidden" name="cmp_emp_id"
                        @if ($data['data']) value="{{ encrypt($data['data']?->id) }}" @endif>
                    <input class="input-box form-input mt-1" name="empCode"
                        @if ($data['data']?->empCode) value="{{ $data['data']?->empCode }}" readonly @else maxlength="30" placeholder="eg:- E-10100" required @endif>
                    </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black"> {{ __('company.employee-name') }}
                        <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="name"
                        @if ($data['data']?->name) value="{{ $data['data']?->name }}" readonly @else maxlength="50" @endif
                        required> </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.email') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input type="email" class="email form-input mt-1" name="email"
                        @if ($data['data']?->email) value="{{ $data['data']?->email }}" readonly @else maxlength="30" @endif
                        required> </input>
                </div>
                <div class="w-full">
                    <label for=""
                        class=" text-sm font-semibold text-black">{{ __('company.contact-number') }}<span
                            class="text-red-500 font-bold">*</span></label>
                    <input class="number form-input mt-1" value="{{ $data['data']?->contact_no }}" name="contact_no"
                        maxlength="15" minlength="7" required></input>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.Department') }}<span
                            class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1 " style="width:100%" name="department_id" required>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($data['department'] as $departmentData)
                            <option value="{{ $departmentData->id }}"
                                @if ($data['data']?->department_id == $departmentData?->id) selected @endif> {{ $departmentData?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.Position') }}</label>
                    <select class="select-single mt-1" style="width:100%" name="position_id">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($data['position'] as $position_data)
                            <option value="{{ $position_data?->id }}"
                                @if ($data['data']?->position_id == $position_data?->id) selected @endif> {{ $position_data?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for=""
                        class=" text-sm font-semibold text-black">{{ __('company.reporting-to') }}</label>
                    <select class="select-single mt-1" style="width:100%" name="reportto_id">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($data['employee'] as $companyemployees)
                            <option value="{{ $companyemployees?->id }}"
                                @if ($data['data']?->reportto_id == $companyemployees?->id) selected @endif> {{ $companyemployees?->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.status') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="status" required>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        <option value="Active" @if ($data['data']?->status == 'Active') selected @endif>
                            {{ __('common.active') }} </option>
                        <option value="Inactive" @if ($data['data']?->status == 'Inactive') selected @endif>
                            {{ __('common.inactive') }} </option>
                        <option value="Blocked" @if ($data['data']?->status == 'Blocked') selected @endif>
                            {{ __('common.blocked') }} </option>
                    </select>
                </div>

                <div class="w-full">
                    <label for=""
                        class=" text-sm font-semibold text-black">{{ __('common.business-unit') }}<span
                            class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="business_unit" required>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($data['business_unit'] as $items)
                            <option value="{{ $items?->id }}" @if ($data['data']?->business_unit == $items?->id) selected @endif>
                                {{ $items?->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.CostCenter') }}<span
                            class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="cost_center" required>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($data['cost_center'] as $items)
                            <option value="{{ $items?->id }}" @if ($data['data']?->cost_center == $items?->id) selected @endif>
                                {{ $items?->name }} </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black"> {{ __('common.City') }} </label>
                    <input class="input-box form-input mt-1" value="{{ $data['data']?->city }}" name="city"
                        maxlength="30"> </input>
                </div>
                <div class="w-full">
                    <label for=""
                        class=" text-sm font-semibold text-black">{{ __('company.zip-code') }}</label>
                    <input class="number form-input mt-1" name="zip_code" value="{{ $data['data']?->zip_code }}"
                        maxlength="6"></input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.state') }} </label>
                    <input type="text" class="input-box form-input mt-1" name="state"
                        value="{{ $data['data']?->state }}" maxlength="30"> </input>
                </div>

                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.Country') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%" name="country_id" required>
                        <option value="">{{ __('common.chooseoption') }}</option>
                        @foreach ($data['country'] as $countryname)
                            <option value="{{ $countryname?->id }}"
                                @if ($data['data']?->country_id == $countryname?->id) selected @endif> {{ $countryname?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.logo') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input type="file" class="form-input mt-1" @if (!$data['data']?->profile_picture) required @endif
                        name="profile_pic" onChange="validateFile(this,'image',2);" accept="image/*"></input>
                </div>

                @if ($data['data']?->profile_picture)
                    <div class="col-md-2">
                        <label for="" class="text-sm font-semibold text-black">Uploded Logo</label>
                        <div class="border p-1 rounded-md">
                            <a target="_blank"
                                href="{{ route('secure-documents', ['path' => $data['data']?->profile_picture]) }}"><img
                                    class=" "
                                    src="{{ route('secure-documents', ['path' => $data['data']?->profile_picture]) }}"
                                class="img-fluid" style="width: 85px;" alt=""></a>
                        </div>
                    </div>
                @endif
                <div class="w-full">
                    <label for=""
                        class="text-sm font-semibold text-black">{{ __('common.current-address') }}</label>
                    <textarea class="input-box form-input mt-1 description-validation" name="current_address" cols="30" rows="2" maxlength="300">{{ $data['data']?->current_address }}</textarea>

                </div>
                <div class="w-full">
                    <label for=""
                        class="text-sm font-semibold text-black">{{ __('common.permanent-address') }}</label>
                    <textarea class="input-box form-input mt-1 description-validation" name="permanent_address"  cols="30" rows="2" maxlength="300">{{ $data['data']?->permanent_address }}</textarea>
                </div>



            </div>
            <div class="flex justify-end py-2">
                <div class="flex gap-2">
                    <button type="reset" class="btn-primary-sm flex items-center gap-1">
                        <i class="ri-refresh-line"></i>
                        {{ __('common.reset') }}
                    </button>
                    <button type="submit" class="btn-success-sm flex items-center gap-1">
                        <i class="ri-file-line"></i>
                        {{ __('common.submit') }}
                    </button>
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
