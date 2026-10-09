@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>         
       
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4 border">
        <form action="{{ route('sql-store') }}" method="post" enctype="multipart/form-data">
            @csrf
             
            <div class="w-full"> 
                <textarea class="input-box form-input mt-1 description-validation" name="sql_command" required cols="30" rows="12" maxlength="300"></textarea>
            </div>
      
            <div class="flex justify-end py-2">
                <div class="flex gap-2">                  
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
