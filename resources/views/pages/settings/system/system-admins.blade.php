@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('common.system-setup',['common.home','common.system-setup'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('employee-dashboard') @endphp
    @endsection
    <div class="text-xs ">
        <header class="bg-white rounded-sm border-t-2 border-primary p-2 mb-2">
            <div class="flex justify-between items-center">
                <div>
                    <h5 class="font-bold text-primary text-sm">{{ __('admin.system-administration') }}</h5>
                </div>

            </div>

            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab"
                    data-tabs-toggle="#default-styled-tab-content"
                    data-tabs-active-classes="text-primary dark:text-primary border-primary dark:border-primary"
                    data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300"
                    role="tablist">
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab"
                            data-tabs-target="#general-tab" type="button" role="tab" aria-controls="profile"
                            aria-selected="false">{{ __('admin.general') }}</button>
                    </li>
                    <li class="me-2" role="presentation">
                        <button
                            class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                            id="dashboard-styled-tab" data-tabs-target="#email-tab" type="button" role="tab"
                            aria-controls="dashboard" aria-selected="false">{{ __('admin.email-setting') }}</button>
                    </li>

                </ul>
            </div>
        </header>
    <form  method="POST" action="{{ route('systemadminStore') }}">
    @csrf
        <div id="default-styled-tab-content">
                <div class="hidden " id="general-tab" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="flex justify-between md:flex-row flex-col gap-2 ">
                        <div class="text-sm w-full  bg-white rounded-md p-4">
                            <h5 class="font-bold text-black">{{ __('admin.otp-setting') }}</h5>
                            <input type="hidden" name="id" @if (!@empty($systemadmin)) value="{{ $systemadmin->id }}" @endif />
                            <hr class="my-2 ">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('admin.otp-timeout') }}
                                        (Seconds)</label>
                                    <input class="form-input mt-1 " name="otp_time" @if (!@empty($systemadmin)) value="{{ $systemadmin->otptime }}" @endif required  maxlength="3"></input>
                                    @include('components.error_code', ['keys' => 'otp_time',])
                                </div>
                                <div class=" px-2">
                                    <label for="" class="text-sm font-semibold text-black">{{ __('admin.enable') }}</label>
                                    <br />
                                    <label class="inline-flex items-center mb-5 cursor-pointer mt-2">
                                        <input type="checkbox" name="otp_time_enable" value="1" class="sr-only peer"  @if (isset($systemadmin) && $systemadmin->otp_enable == 1) checked @endif>
                                        <div class="toggle"></div>
                                    </label>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('admin.number-of-attempts') }}
                                    </label>
                                    <input class="form-input mt-1 " name="number_attempts" @if (!@empty($systemadmin)) value="{{ $systemadmin->numbertime }}" @endif  maxlength="1" id="numberAttemptsInput"></input>
                                </div>
                                <div class=" px-2">
                                    <label for="" class="text-sm font-semibold text-black">{{ __('admin.enable') }}</label>
                                    <br />
                                    <label class="inline-flex items-center mb-5 cursor-pointer mt-2">
                                        <input type="checkbox" value="1" class="sr-only peer" name="number_attempts_enable" id="numbercheck" @if (isset($systemadmin) && $systemadmin->number_enable == 1) checked @endif>
                                        <div class="toggle"></div>
                                    </label>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('admin.time-of-auto-release') }}(In Hour)</label>
                                    <input class="form-input mt-1 " name="auto_release_time" @if (!@empty($systemadmin)) value="{{ $systemadmin->auto_release_time }}" @endif  maxlength="2" placeholder="In Hour" id="auto_release_time"></input>
                                </div>
                                <div class=" px-2">
                                    <label for="" class="text-sm font-semibold text-black">{{ __('admin.enable') }}</label>
                                    <br />
                                    <label class="inline-flex items-center mb-5 cursor-pointer mt-2">
                                        <input type="checkbox" value="1" class="sr-only peer" name="auto_release_enable" id="auto_release_enable" @if (isset($systemadmin) && $systemadmin->auto_release_enable == 1) checked @endif>
                                        <div class="toggle"></div>
                                    </label>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('admin.password-expiry-duration') }}(In Days)</label>
                                    <input class="form-input mt-1 " name="password_expiry" @if (!@empty($systemadmin)) value="{{ $systemadmin->password_expiry }}" @endif maxlength="3" placeholder="In Day's" id="password_expiry"></input>
                                </div>
                                <div class=" px-2">
                                    <label for="" class="text-sm font-semibold text-black">{{ __('admin.enable') }}</label>
                                    <br />
                                    <label class="inline-flex items-center mb-5 cursor-pointer mt-2">
                                        <input type="checkbox" class="sr-only peer" value="1" name="password_expiry_enable" id="password_expiry_enable" @if (isset($systemadmin) && $systemadmin->password_expiry_enable == 1) checked @endif>
                                        <div class="toggle"></div>
                                    </label>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.workflow-type') }} </label>
                                    <select class="select-single mt-1 " style="width: 100%" name="workflow_type" required>
                                        <option value="">{{ __('common.chooseoption') }}</option>
                                        <option value="Consolidated"@if ($systemadmin?->workflow_type == 'Consolidated') selected @endif>Consolidated</option>
                                        <option value="Company Wise" @if ($systemadmin?->workflow_type == 'Company Wise') selected @endif>Company Wise</option>
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black"> {{ __('common.no-seq-type') }}</label>
                                    <select class="select-single mt-1 " style="width: 100%" name="numbersequence_type" required>
                                        <option value="">{{ __('common.chooseoption') }}</option>
                                        <option value="Consolidated" @if ($systemadmin?->numbersequence_type == 'Consolidated') selected @endif>Consolidated </option>
                                        <option value="Company Wise" @if ($systemadmin?->numbersequence_type == 'Company Wise') selected @endif>Company Wise</option>
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.quotation-evaluation') }}</label>
                                    <select class="select-single mt-1 " style="width: 100%" name="quotation_evaluation_type" required>
                                        <option value="">{{ __('common.chooseoption') }}</option>
                                        <option value="Complete" @if ($systemadmin?->quotation_evaluation_type == 'Complete') selected @endif>Complete</option>
                                        <option value="Line Wise" @if ($systemadmin?->quotation_evaluation_type == 'Line Wise') selected @endif>Line Wise</option>
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.tender-evaluation') }} </label>
                                    <select class="select-single mt-1 " style="width: 100%" name="tender_evaluation_type" required>
                                        <option value="">{{ __('common.chooseoption') }}</option>
                                        <option value="Complete" @if ($systemadmin?->tender_evaluation_type == 'Complete') selected @endif>Complete</option>
                                        <option value="Line Wise" @if ($systemadmin?->tender_evaluation_type == 'Line Wise') selected @endif>Line Wise</option>
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('common.rfi-evaluation') }} </label>
                                    <select class="select-single mt-1 " style="width: 100%" name="rfi_evaluation_type" required>
                                        <option value="">{{ __('common.chooseoption') }}</option>
                                        <option value="Complete" @if ($systemadmin?->rfi_evaluation_type == 'Complete') selected @endif>Complete</option>
                                        <option value="Line Wise" @if ($systemadmin?->rfi_evaluation_type == 'Line Wise') selected @endif>Line Wise</option>
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{ __('quotation.currency') }} </label>
                                    <select class="select-single mt-1 " style="width: 100%" name="currency_id" required>
                                        <option value="">{{ __('common.chooseoption') }}</option>
                                        @foreach ($currency as $currencypr)
                                            <option value="{{ $currencypr->id }}" @if ($systemadmin?->default_currency_id == $currencypr->id) selected @endif> {{ $currencypr->name }} ({{ $currencypr->code }}) </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{__('common.bid-opening-allow')}} </label>
                                    <select class="select-single mt-1 " style="width: 100%" name="bid_opening_allow" required>
                                        <option value="">{{ __('common.chooseoption') }}</option>
                                        <option value="Today Only" @if($systemadmin?->bid_opening_allow == "Today Only") selected @endif>Today Only </option>
                                        <option value="All" @if($systemadmin?->bid_opening_allow == "All") selected @endif>All </option>
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{__('common.tender-ranking-criteria')}} </label>
                                    <select class="select-single mt-1 " style="width: 100%"  name="tender_ranking" required>
                                        <option value="">{{ __('common.chooseoption') }}</option>
                                        <option value="Range" @if($systemadmin?->tender_ranking == "Range") selected @endif>Range </option>
                                        <option value="Rank" @if($systemadmin?->tender_ranking == "Rank") selected @endif>Rank </option>
                                    </select>
                                </div>

                            </div>

                            <div class=" px-2 mt-2">
                                <label for="" class=" text-sm font-semibold text-black">{{__('common.user-can-apply-checklist')}}</label>
                                <select multiple="multiple" class="select-multi mt-1" style="width: 100%" name="user_apply_checklist[]">
                                    <option value="">{{ __('common.chooseoption') }}</option>
                                    @foreach($emps as $emply)
                                        <option value="{{$emply?->id}}" @if(in_array($emply?->id,$checklist_emp)) selected @endif>{{$emply?->name}} / {{$emply?->company?->name}}</option>
                                    @endforeach
                                </select>
                                <label for="" class=" text-sm">{{__('common.default-supplier-request-approval-workflow')}} </label>
                                <select class="select-single mt-1 " style="width: 100%" name="supplier_workflow_id">
                                    <option value="">----No need----</option>
                                    @foreach($wf as $wf_name)
                                        <option value="{{$wf_name?->id}}" @if($systemadmin?->supplier_workflow_id == $wf_name?->id) selected @endif>{{$wf_name?->workflow_name}} / {{$wf_name?->company?->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{__('common.default-vendor-approval-workflow')}} </label>
                                    <select class="select-single mt-1 " style="width: 100%" name="vendor_workflow_id">
                                        <option value="">----No need----</option>
                                        @foreach($wf as $wf_name)
                                            <option value="{{$wf_name?->id}}" @if($systemadmin?->vendor_workflow_id == $wf_name?->id) selected @endif>{{$wf_name?->workflow_name}} / {{$wf_name?->company?->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{__('common.default-checklist')}} </label>
                                    <select class="select-single mt-1 " style="width: 100%" name="checklist_id">
                                        <option value="">----No need----</option>
                                        @foreach($checklist as $chk)
                                            <option value="{{$chk?->id}}" @if($systemadmin?->checklist_id == $chk?->id) selected @endif>{{$chk?->name}} / {{$chk?->company?->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{__('common.tender-ranking-for-vendor')}} </label>
                                    <select class="select-single mt-1 " style="width: 100%" name="tender_technical_commercial_ranking" required>
                                        <option value="">Select an option</option>
                                        <option value="Auto" @if($systemadmin?->tender_technical_commercial_ranking == "Auto") selected @endif>Auto</option>
                                        <option value="Manual" @if($systemadmin?->tender_technical_commercial_ranking == "Manual") selected @endif>Manual</option>
                                    </select>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{__('common.resource-path-for-payment')}}</label>
                                    <input class="form-input mt-1 " type="text" name="resource_path" value="{{$systemadmin?->resource_path}}"></input>
                                </div>
                                <div class=" px-2">
                                    <label for="" class=" text-sm font-semibold text-black">{{__('common.base-url-for-payment')}} </label>
                                    <input class="form-input mt-1 " type="text" name="base_url" value="{{$systemadmin?->base_url}}"></input>
                                </div>


                            </div>
                        </div>
                        <div class="text-sm w-full  bg-white rounded-md p-4 md:h-[200px] sm:h-[300px]">
                            <h5 class="font-bold text-black">{{ __('admin.registration-setting') }}</h5>
                            <hr class="my-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-4 gap-2">
                                <div class=" p-4">
                                    <label for="" class="text-sm font-semibold text-black">{{ __('admin.aprroval-required') }} </label>
                                    <br />
                                    <label class="inline-flex items-center mb-5 cursor-pointer mt-2">
                                        <input type="checkbox" name="registration_setting" id="otpcheck" class="sr-only peer" value="1" @if(isset($systemadmin) && $systemadmin->registration == 1) checked @endif>
                                        <div class="toggle"></div>
                                    </label>
                                    <br />

                                    <label for="" class="text-sm font-semibold text-black">{{__('admin.default-company')}} </label>
                                    <br />
                                    <label class="inline-flex items-center mb-5 cursor-pointer mt-2">
                                        <input type="checkbox" name="default_company_enable" class="sr-only peer" value="1" @if(isset($systemadmin) && $systemadmin->default_company_enable == 1) checked @endif>
                                        <div class="toggle"></div>
                                    </label>
                                </div>
                                <div class=" p-4">
                                    <label for="" class="text-sm font-semibold text-black">{{ __('common.pre-screening') }}</label>
                                    <br />
                                    <label class="inline-flex items-center mb-5 cursor-pointer mt-2">
                                        <input type="checkbox" class="sr-only peer" name="pre_screening" value="1" @if(!empty($systemadmin->pre_screening) && $systemadmin->pre_screening == 1) checked @endif>
                                        <div class="toggle"></div>
                                    </label>
                                    <br />
                                    <label for="" class=" text-sm font-semibold text-black">{{__('admin.select-default-company')}} </label>
                                    <select class="select-single mt-1 " name="default_company" style="width: 100%">
                                        @foreach($companies as $company)
                                            <option value="{{$company?->id}}" @if($systemadmin?->default_company == $company?->id) selected @endif>{{$company?->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden text-sm w-full  bg-white rounded-md p-4" id="email-tab" role="tabpanel"
                    aria-labelledby="dashboard-tab">
                    <h5 class="font-bold text-black">{{ __('admin.email-setting') }}</h5>
                    <hr class="my-2 ">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <div class=" px-2">
                            <label for="" class="text-sm font-semibold text-black">{{ __('admin.enable') }}</label>
                            <br />
                            <label class="inline-flex items-center mb-5 cursor-pointer mt-2">
                                <input type="checkbox" value="1" class="sr-only peer" name="email_setting" id="emailcheck" checked="">
                                <div class="toggle"></div>
                            </label>
                        </div>
                        <div class=" px-2"> </div>
                        <div class=" px-2">
                            <label for="" class="text-sm font-semibold text-black">{{ __('admin.host') }}</label>
                            <input class="form-input mt-1 "  name="host" value="{{ $systemadmin?->host }}"></input>
                        </div>
                        <div class=" px-2">
                            <label for="" class="text-sm font-semibold text-black">{{ __('admin.port') }}</label>
                            <input class="form-input mt-1 " name="port" value="{{ $systemadmin?->port }}"></input>
                        </div>
                        <div class=" px-2">
                            <label for="" class="text-sm font-semibold text-black">{{ __('admin.username') }}</label>
                            <input class="form-input mt-1 " name="username" value="{{ $systemadmin?->username }}"></input>
                        </div>
                        <div class=" px-2">
                            <label for="" class="text-sm font-semibold text-black">{{ __('admin.password') }}</label>
                            <input class="form-input mt-1 " name="password" value="{{ $systemadmin?->password }}"></input>
                        </div>
                        <div class=" px-2">
                            <label for="" class="text-sm font-semibold text-black">{{ __('admin.encryption') }}</label>
                            <input class="form-input mt-1 " name="encryption" @if (!@empty($systemadmin)) value="{{ $systemadmin->encryption }}" @endif></input>
                        </div>
                        <div class=" px-2">
                            <label for="" class="text-sm font-semibold text-black">{{ __('admin.from-address') }}</label>
                            <input class="form-input mt-1 " name="address" @if (!@empty($systemadmin)) value="{{ $systemadmin->address }}" @endif></input>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <div class="flex gap-2 items-center">
                        <button type="reset" class="btn-primary-sm flex gap-1 items-center">
                            <i class="ri-refresh-line"></i> {{__('common.cancel')}}
                        </button>
                        <button type="submit" class="btn-success-sm flex gap-1 ">
                            <i class="ri-save-2-line"></i> {{__('common.save')}}
                        </button>
                    </div>
                </div>
        </div>
    </form>
    </div>
</div>

@endsection
@section('scripts')
@include('layouts.select2js')
<script>
    $(document).ready(function() {
        function toggleRequiredAttribute() {
            if ($('#numbercheck').prop('checked')) {
                $('#numberAttemptsInput').attr('required', true);
            } else {
                $('#numberAttemptsInput').removeAttr('required');
            }
        }
        toggleRequiredAttribute();
        $('#numbercheck').change(function() {
            toggleRequiredAttribute();
        });

        // for auto release time
        function toggleRequiredAttribute_auto() {
            if ($('#auto_release_enable').prop('checked')) {
                $('#auto_release_time').attr('required', true);
            } else {
                $('#auto_release_time').removeAttr('required');
            }
        }
        toggleRequiredAttribute_auto();
        $('#auto_release_enable').change(function() {
            toggleRequiredAttribute_auto();
        });
        //End auto release time

         // for password expiry
         function toggleRequiredAttribute_pass() {
            if ($('#password_expiry_enable').prop('checked')) {
                $('#password_expiry').attr('required', true);
            } else {
                $('#password_expiry').removeAttr('required');
            }
        }
        toggleRequiredAttribute_pass();
        $('#password_expiry_enable').change(function() {
            toggleRequiredAttribute_pass();
        });
        //End password expiry
        $('#otpcheck').click(function() {
            $('.otp-time-enable').prop("disabled", !$("#otpcheck").prop("checked"));
            $('.otp-time-enable').val('');
        })

        $('#emailcheck').click(function() {
            $('.email-enable').prop("disabled", !$("#emailcheck").prop("checked"));
            $('.email-enable').val('');
        })
    });
</script>
@endsection
