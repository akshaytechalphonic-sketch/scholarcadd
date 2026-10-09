@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @if($data)
        @php echo breadcrums('company.budget-header-edit',['common.home','company.budget-header-edit'],['dashboard']) @endphp
        @else
        @php echo breadcrums('company.budget-header-add',['common.home','company.budget-header-add'],['dashboard']) @endphp
        @endif
    @section('back_button')
        @php echo back_button('budget-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form action="{{ route('task-store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{ __('common.code') }} <span class="text-red-500 font-bold">*</span></label>
                    <input type="hidden" name="budget_id" @if ($data) value="{{ base64_encode(convert_uuencode($data?->id)) }}" @endif></input>
                    <input name="dim_no" type="hidden" value="{{$val}}">
                    <input class="input-box form-input mt-1" name="code" value="{{ $data?->code }}" maxlength="20" required> </input>
                    @error('code')<span class="text-red-500 font-bold">{{ $message }}</span>@enderror
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black"> {{ __('budget.budget-date') }} <span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" type="date" name="budget_date"  value="{{ $data?->budget_date }}" required> </input>
                    @error('budget_date')<span class="text-red-500 font-bold">{{ $message }}</span>@enderror

                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black"> {{ __('budget.from-date') }}<span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" type="date" name="from_date"  value="{{ $data?->from_date }}" required> </input>
                    @error('from_date')<span class="text-red-500 font-bold">{{ $message }}</span>@enderror

                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black"> {{ __('budget.to-date') }}<span class="text-red-500 font-bold">*</span></label>
                    <input class="input-box form-input mt-1" type="date" name="to_date"  value="{{ $data?->to_date }}" maxlength="20" required> </input>
                    @error('to_date')<span class="text-red-500 font-bold">{{ $message }}</span>@enderror

                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.BudgetType') }} <span class="text-red-500 font-bold">*</span></label>
                    <select class="select-single mt-1" style="width:100%"  name="budget_type" required>
                        <option value=""> </option>
                        @foreach ($budget_type as $mod)
                            <option value="{{ $mod?->name }}" @if ($data?->budget_type == $mod?->name) selected @endif>{{ $mod?->name }} </option>
                        @endforeach
                    </select>
                    @error('budget_type')<span class="text-red-500 font-bold">{{ $message }}</span>@enderror
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.description') }}  <span class="text-red-500 font-bold">*</span></label>
                    <input class="numeric form-input mt-1" name="description" value="{{ $data?->description }}" maxlength="255"> </input>
                    @error('description')<span class="text-red-500 font-bold">{{ $message }}</span>@enderror
                </div>
            </div>

            </div>
            </div>

            <div id="show_tables">
                <div class="text-xs w-full  bg-white rounded-md p-4 mt-2">
                    <div class="flex justify-between md:items-center md:flex-row flex-col mb-2">
                        <h2 class="text-lg font-semibold">{{__('company.budget-header-line')}}
                        </h2>
                        @if(!$data || $data->Posted =="No")
                            <div class="flex gap-2">
                                <button type="button" id="add-new-btn-decla" class="addline flex items-center gap-1 px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 " type="submit">
                                    <i class="ri-add-circle-line"></i> {{ __('common.save') }}
                                </button>
                                <button type="button" id="add-new-btn-decla" class="addline flex items-center gap-1 px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                    <i class="ri-add-circle-line"></i> {{ __('common.add') }}
                                </button>
                                <button type="button" onClick="$.delete_html_rowbytable('employee-table-decla');" id="remove-all" class="flex items-center gap-1 px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                    <i class="ri-delete-bin-7-line"></i> {{ __('pr.remove-all') }}
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="custome-tables">{{ __('company.line-no') }}</th>
                                    <th class="custome-tables">{{ __('common.code') }} </th>
                                    @for($i=1;$i<=$val;$i++)
                                        <th class="custome-tables">{{ __('budget.dim') }} {{$i}}</th>
                                    @endfor
                                    <th class="custome-tables">{{ __('company.budget-amount') }}</th>
                                    <th class="custome-tables">{{ __('common.action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="add-line-body">
                                @if (sizeof($data_line) > 0)
                                    @foreach ($data_line as $dataln)
                                        <tr>
                                            <td class="py-2"><input type="text" name="line_no[]" value="{{ $dataln?->line_no }}" class="input-box p-1 form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block" />
                                            <td><input type="text" name="codes[]" value="{{ $dataln?->code }}" class="input-box p-1 form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block" /> </td>

                                            @for($nn = 1; $nn <= $val; $nn++)
                                                @php $j = "dim_".$nn; @endphp
                                            <td><select name="dim_{{$nn}}[]" class="select-single mt-1"> <option value="{{ $dataln?->$j }}">{{ $dataln?->$j }}</option> </select> </td>
                                            @endfor

                                            <td><input type="text" name="budget_amount[]" value="{{ $dataln?->budget_amount }}" class="input-box p-1 form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block" /> </td>
                                            @if(!$data || $data->Posted =="No")
                                            <td style="text-align: center;"><span class="del-ico" onClick="$.delete_one_html(this,'')"><i class="ri-delete-bin-line font-size-18 text-danger cursor"></i> </span></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@include('layouts.select2js')
@include('components.formvalidationjs')
@include('pages.company.budget_header.extra_js')

@endsection
