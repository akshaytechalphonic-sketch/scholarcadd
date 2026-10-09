@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('company.company-add',['common.home','company.company-list','company.company-add'],['dashboard','company-list']) @endphp
    @section('back_button')
        @php echo back_button('company-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="{{ route('company-store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.code') }} <span class="text-red-500 font-bold">*</span></label>
                    <input type="hidden" name="company_id" @if($data) value="{{encrypt($data?->id)}}" @endif></input>
                    <input class="input-box form-input mt-1" name="code" value="{{$data?->code}}" @if($data) readonly @endif required> </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black"> {{ __('company.com-regitration-no') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="registration_no" value="{{$data?->registration_no}}" @if($data) readonly @endif required> </input>                     
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.company-name') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" value="{{$data?->name}}" @if($data) readonly @endif name="name" required> </input>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.company-vat') }}<span class="text-red-500 font-bold">*</span></label>
                    <input class="form-input mt-1" value="{{$data?->vat_no}}" name="vat_no" @if($data) readonly @endif required></input>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.origin-year') }} <span class="text-red-500 font-bold">*</span></label>
                    <input type='date' class="form-input mt-1 " name="origin_date" value="{{$data?->origin_date}}" @if($data) readonly @endif required> </input>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.company-url') }}  </label>
                    <input class="input-box form-input mt-1" value="{{$data?->website}}" name="website"> </input>
                </div>

                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.company-city') }}  <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" value="{{$data?->city}}" name="city" required> </input>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.contact-number') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="number form-input mt-1" value="{{$data?->contact_number}}" name="contact_number" maxlength="15" minlength="7" required></input>
                </div>

                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.company-state') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" value="{{$data?->state}}" name="state" required> </input>

                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.company-pincode') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="number form-input mt-1" value="{{$data?->zip_code}}" name="zip_code" maxlength="8" required></input>                    
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.company-select') }} <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%"  name="country_id" required>
                        <option value=""> </option>
                        @foreach ($country as $ctry)
                            <option value="{{$ctry?->id}}" @if($ctry?->id == $data?->country_id) selected @endif>{{$ctry?->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('company.status') }}<span class="text-red-500 font-bold">*</span></label>
                    <select name="status"  class="form-input" required>
                        <option value=""> </option>
                        <option value="Active" @if($data?->status == 'Active') selected @endif>{{ __('common.active') }} </option>
                        <option value="Inactive" @if($data?->status == 'Inactive') selected @endif>{{ __('common.inactive') }} </option>
                    </select>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.company-address') }} <span class="text-red-500 font-bold">*</span></label>
                    <textarea class="input-box form-input mt-1" name="address" cols="30" rows="2" maxlength="200" required>{{$data?->address}}</textarea>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.about-company') }}</label>
                    <textarea class="input-box form-input mt-1" name="about" cols="30" rows="2" maxlength="200" required>{{$data?->about}}</textarea>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.payment-alias-name') }}</label>
                    <textarea class="input-box form-input mt-1" name="payment_alias_name" cols="30" rows="2" maxlength="200" >{{$data?->payment_alias_name}}</textarea>
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('company.upload-logo') }} <span class="text-red-500 font-bold">*</span></label>
                    <input type="file" class="form-input mt-1" @if (!$data?->company_logo) required @endif name="pic" onChange="validateFile(this,'image',2);" accept="image/*"></input>
                </div>
                
                @if ($data?->company_logo)
                    <div class="col-md-2">                         
                       <div class=" p-2 rounded-md"> 
                            <a target="_blank" href="{{ route('secure-documents', ['path' => $data?->company_logo]) }}"><img class=" " src="{{ route('secure-documents', ['path' => $data?->company_logo]) }}" class="img-fluid" style="width: 108px;" alt=""></a>
                       </div>
                    </div>
                @endif

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
