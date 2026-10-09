@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.summer_note_css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('email.email-template-add',['common.home','email.email-template-list','email.email-template-add'],['dashboard','email-template-list']) @endphp
    @section('back_button')
        @php echo back_button('email-template-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="{{ route('email-template-store') }}" method="post">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-2  w-full">
                <div class="w-full">
                    <label for="" class="text-sm">{{ __('common.EmailMaster') }}  <span class="text-red-500 font-bold">*</span></label>
                    <input type="hidden" @if($data) value="{{encrypt($data?->id)}}" @endif name="data_id">
                    <select class="select-single mt-1" name="email_master_id" id="email_master_id" style="width:100%" required>
                        <option value=""> </option>
                        @foreach ($email as $emails)
                        <option value="{{ $emails?->id }}" data-email_temp_id="{{encrypt($emails?->id)}}" @if($data?->email_master_id == $emails?->id) selected @endif> {{ $emails?->name }}</option>
                        @endforeach
                    </select>
                    @error('email_master_id')
                        <span class="text-red-500 font-bold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full">
                    <label for="" class="text-sm">{{ __('email.available-variables') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="veriable" id="veriable" type="text" value="{{$data?->veriable}}" required readonly> </input>
                    @error('veriable')
                        <span class="text-red-500 font-bold">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full">
                    <label for="" class="text-sm">{{ __('common.names') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="name" type="text" value="{{$data?->name}}" id="name" alt="blank" maxlength="100" required> </input>
                    @error('name')
                        <span class="text-red-500 font-bold">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="w-full">
                    <label for="" class="text-sm">{{ __('email.subject') }} <span
                            class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" name="subject" type="text" value="{{$data?->subject}}" id="subject" alt="blank" maxlength="255" required> </input>
                    @error('subject')
                        <span class="text-red-500 font-bold">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="w-full mt-2">
                <div class="w-full">
                    <label for="">{{ __('email.content') }} <span class="text-red-500 font-bold">*</span></label>
                    <textarea id="elm1" class="summernote" name="content" required>{{$data?->content}}</textarea>
                    @error('content')
                        <span class="text-red-500 font-bold">{{ $message }}</span>
                    @enderror
                </div>
            </div>


            <div class="flex justify-end py-2">
                <div class="flex gap-2">
                    <button type="submit" class="btn-success-sm">{{ __('common.save') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
@include('layouts.select2js')
@include('dynamicmaster::emailtemplate.extra_js')  
@include('components.formvalidationjs')
@include('layouts.summer_note_js')


@endsection
