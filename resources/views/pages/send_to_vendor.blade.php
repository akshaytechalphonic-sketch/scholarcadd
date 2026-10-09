@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    @php echo breadcrums('tender.send-to-vendor',['']) @endphp
@section('back_button')
    @php echo back_button('back') @endphp
    <button type="button" data-target="send-vendor-invite" data-position="right"
        class="open-drawer-btn  inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class="ri-send-plane-line text-black font-semibold pt-1"></i>
        {{ __('common.invite') }}
    </button>
    <button type="button" data-target="send-vendor-filter" data-position="right"
        class="open-drawer-btn  inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class="ri-filter-2-line text-blue-500 font-semibold pt-1"></i>
        {{ __('tender.add-filter') }}
    </button>
    <button type="button" onclick="$.send_email_check();"
        class="inline-flex hover-scale gap-2 border-l-[1px] items-center px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2">
        <i class="ri-send-plane-2-line text-blue-500 font-semibold pt-1"></i>
        {{ __('tender.send') }}

    </button>
@endsection
<div>
    <header
        class="bg-white dark:bg-black dark:text-white rounded-sm border-t-2 border-primary flex justify-between p-3">
        <div class="flex justify-between flex-wrap items-center ">
            <div class="flex items-center gap-4">
                <h5 class="text-sm font-bold text-black">
                    @php $all_data = App\Helpers\ModelHelper::status_id_in_send_blade($model,$enc_id); @endphp
                    {{ $all_data[0] }} : @php echo App\Helpers\MasterHelper::status_check($all_data[1]); @endphp
                </h5>
            </div>
        </div>
    </header>
    <div class="p-3   bg-white  rounded-md mt-3">
        <hr class="my-4 border-primary border-[1px]">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-900 max-h-[300px] overflow-y-auto">
            <div class="w-full relative">
                <label for="" class="text-sm font-semibold pb-3">{{ __('common.LineOfBusiness') }}</label>
                <select style="width:100%" name="LineOfBusiness" id="LineOfBusiness" style="width: 100%"
                    class="select-single form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block  cursor-pointer p-2.5">
                    <option value="">{{ __('common.chooseoption') }}</option>
                    @foreach ($lineofbusiness as $options)
                        <option value="{{ $options->id }}">{{ $options->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full relative append-list">
                <label for="" class="text-sm font-semibold pb-3">{{ __('common.SupplierType') }}</label>
                <select style="width:100%" name="SupplierType" id="SupplierType" style="width: 100%"
                    class="select-single form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block  cursor-pointer p-2.5">
                    <option value="">{{ __('common.chooseoption') }}</option>
                    @foreach ($suppliertype as $options)
                        <option value="{{ $options->id }}">{{ $options->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="flex justify-end  py-2">
            <button type="button" class="btn-primary-sm" onclick="$.apply_filter()"><i class="ri-checkbox-circle-line"></i> {{ __('tender.apply') }}</button>
        </div>
        <hr class="my-4 border-primary border-[1px]">
        <div class="overflow-x-auto max-h-[300px] overflow-y-auto">
            <table class="w-full border-collapse border border-gray-300 text-xs">
                <thead class="bg-primary text-white sticky top-0 z-50">
                    <tr>
                        <th class="border p-2 text-start">{{ __('tender.vendor-name') }}</th>
                        <th class="border p-2 text-start">{{ __('common.email') }}</th>
                        <th class="border p-2 text-start">{{ __('quotation.vendor-account') }}</th>
                        <th> <input type="checkbox" id="checkall"> </th>
                    </tr>
                </thead>
                <tbody id="vendor_list" class="z-40"> </tbody>
 
            </table>
        </div>
    </div>

</div>

{{-- <div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
    <div id="send-vendor-invite"
    class="drawer fixed z-50 bg-white dark:bg-gray-800 transition-transform hidden w-96 h-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-lg shadow-lg flex flex-col"
    tabindex="-1">
    <div class="flex justify-between items-center px-2 py-4 border-b">
        <h5 class="text-base font-semibold text-gray-500 dark:text-gray-400">
            {{ __('common.Invite New Vendor') }} </h5>
        <button type="button" class="close-drawer-btn close-btn"> <i class="ri-close-line"></i> </button>
    </div>

    <form id="add_modal">
        @csrf
        <div class="p-3 max-h-[85vh] overflow-y-auto">
            <div class="">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold">Contact Person</label>
                    <input type="text"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 ">
                    </input>
                </div>
                <div class="w-full mt-5">
                    <label for="" class="text-sm font-semibold">Email</label>
                    <input type="email"
                        class="form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block w-full  p-2.5 ">
                    </input>
                </div>
                <div class="w-full mt-5">
                    <label for="" class="text-sm font-semibold">Company</label>
                    <select style="width: 100%" multiple="multiple"
                        class="select-multi border border-gray-300 text-gray-900 text-sm rounded-sm cursor-pointer focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled>Please Select</option>
                        <option value="US">United States</option>
                        <option value="CA">Canada</option>
                        <option value="FR">France</option>
                        <option value="DE">Germany</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <button type="button" class="btn-primary-sm close-drawer-btn"><i class="ri-close-line"></i>
                {{ __('common.close') }}</button>
            <button type="submit" class="btn-success-sm close-drawer-btn"><i class="ri-file-line"></i>
                Submit</button>
        </div>
    </form>
</div> --}}
 
<div id="email-notification" class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white w-80"
    tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 id="" class="inline-flex items-center text-base font-semibold ">
            {{ __('tender.enter-email-to-get-notified') }}
        </h5>
        <button type="button" onclick="sendvendorModal()"
            class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>
    <div class="p-4">
        <div class="flex gap-2 item-centre my-2">
            <div class="flex gap-2 item-centre">                
                <input type="radio" name="cc_bcc" id="formRadios1" checked="" value="cc">
                <label for="">{{ __('tender.cc') }}</label>
            </div>
            <div class="flex gap-2 item-centre">
                <input type="radio" name="cc_bcc" id="formRadios2" value="bcc">
                <label for="" class="">{{ __('tender.bcc') }}</label>
            </div>
        </div>

        <div class="w-full my-3"> 
            <input type="email" name="email_to_notify" id="email_to_notify" placeholder="{{ __('tender.enter-email-to-get-notified') }}" class="email form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5">
        </div>

        <div class="flex gap-2 item-centre my-2">
            <div class="flex gap-2 item-centre">
                <input type="radio" name="sendding_type" id="formRadios3" checked="" value="individual" onclick="$.show_hide_to_mail()">
                <label for="">{{ __('tender.individual-mail') }}</label>
            </div>
            <div class="flex gap-2 item-centre">
                <input type="radio" name="sendding_type" id="formRadios4" value="all_on_one" onclick="$.show_hide_to_mail()">
                <label for="" class=""> {{ __('tender.all-on-one-mail') }}</label>
            </div>
        </div>

        <input style="display:none;" class="email form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md block w-full p-2.5 all_one_div" type="email" name="email_from_notify" id="email_from_notify" placeholder="{{ __('tender.enter-email-to-get-notified') }}">
 
        <div class="mt-[50vh]">
            <div class="flex justify-end gap-2">
                <button type="reset" onclick="sendvendorModal()"
                    class="px-3 py-2 text-xs font-medium text-center text-white bg-gray-400 rounded-sm hover:bg-gray-600">
                    {{ __('common.close') }}
                </button>
                <button type="button" onclick="$.send(); sendvendorModal()"
                    class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800">
                    {{ __('tender.send') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Add Filter vendor --}}
<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="send-vendor-filter"
    class="drawer fixed z-50 bg-white dark:bg-gray-800 transition-transform hidden w-96 h-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-lg shadow-lg flex flex-col"
    tabindex="-1">
    <div class="flex justify-between items-center px-2 py-4 border-b">
        <h5 class="text-base font-semibold text-gray-500 dark:text-gray-400">{{ __('tender.add-filter') }} </h5>
        <button type="button" class="close-drawer-btn close-btn"> <i class="ri-close-line"></i> </button>
    </div>

    <form id="add_filter" action="#">
        @csrf
        <div class="p-3 max-h-[85vh] overflow-y-auto">
            <div class="">
                <div class="w-full mt-5">
                    <label for="" class="text-sm font-semibold">Company</label>
                    <select style="width: 100%" id="filter_type"
                        class="select-single border border-gray-300 text-gray-900 text-sm rounded-sm cursor-pointer focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">{{ __('common.chooseoption') }}</option>
                        <option value="Chain">{{ __('common.Chain') }}</option>
                        <option value="BusinessClassification">{{ __('common.BusinessClassification') }}</option>
                        <option value="SalesTaxGroup">{{ __('common.SalesTaxGroup') }}</option>
                        <option value="Segment">{{ __('common.Segment') }}</option>
                        <option value="SubSegment">{{ __('common.SubSegment') }}</option>
                        <option value="ModeOfPayment">{{ __('common.ModeOfPayment') }}</option>
                        <option value="TermsOfPayment">{{ __('common.TermsOfPayment') }}</option>
                        <option value="TermsOfDelivery">{{ __('common.TermsOfDelivery') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
            <button type="reset" class="btn-primary-sm close-drawer-btn"><i class="ri-close-line"></i>
                {{ __('tender.cancle') }}</button>
            <button type="reset" onclick="$.add_filter()" class="btn-success-sm close-drawer-btn"><i
                    class="ri-file-line"></i>
                {{ __('common.submit') }}</button></button>
        </div>
    </form>
</div>

@endsection
@section('scripts')
@include('layouts.select2js')
@include('layouts.drawerjs')

<script>
    function sendvendorModal() {
        const sidebar = document.getElementById('email-notification');
        sidebar.classList.toggle('translate-x-full');
    }

    $(document).ready(function() {

        $.add_filter = function() {
            var filter_type = $('#filter_type').val();
            var labels = '';

            if (filter_type == 'Chain')
                labels = "{{ __('common.Chain') }}";
            if (filter_type == 'BusinessClassification')
                labels = "{{ __('common.BusinessClassification') }}";
            if (filter_type == 'SalesTaxGroup')
                labels = "{{ __('common.SalesTaxGroup') }}";
            if (filter_type == 'Segment')
                labels = "{{ __('common.Segment') }}";
            if (filter_type == 'SubSegment')
                labels = "{{ __('common.SubSegment') }}";
            if (filter_type == 'ModeOfPayment')
                labels = "{{ __('common.ModeOfPayment') }}";
            if (filter_type == 'TermsOfPayment')
                labels = "{{ __('common.TermsOfPayment') }}";
            if (filter_type == 'TermsOfDelivery')
                labels = "{{ __('common.TermsOfDelivery') }}";


            var filter = new Array();
            $("input[name='filter[]']").each(function() {
                filter.push(this.value);
            });

            if (filter.includes(filter_type)) {
                error('error', filter_type + " Filter is already available");
            } else {
                if (filter_type) {
                    showLoader();
                    $.ajax({
                        url: "{{ route('filter-add') }}",
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            filter_type: filter_type,
                        },
                        cache: false,
                        success: function(dataResult) {
                            hideLoader();
                            var data = JSON.parse(dataResult);

                            $('.append-list').after('<div class="w-full relative">' +
                                '<label for="" class="text-sm font-semibold pb-3">' +
                                labels + '</label>' +
                                '<input type="hidden" name="filter[]" value="' +
                                filter_type + '"><select style="width:100%" name="' +
                                filter_type + '" id="' + filter_type +
                                '" style="width: 100%" class="select-single form-input border my-1 border-gray-300 text-gray-900 text-sm rounded-md  block  cursor-pointer p-2.5">' +
                                data.options + '</select>' +
                                '</div>');
                        }
                    });
                } else {
                    toastr.warning('Please select Filter Type');
                }
            }
            $("#offcanvasheader").offcanvas("hide");
        }

        $.apply_filter = function() {
            var LineOfBusiness = $('#LineOfBusiness').val();
            var SupplierType = $('#SupplierType').val();
            var Chain = $('#Chain').val();
            var BusinessClassification = $('#BusinessClassification').val();
            var SalesTaxGroup = $('#SalesTaxGroup').val();
            var Segment = $('#Segment').val();
            var SubSegment = $('#SubSegment').val();
            var ModeOfPayment = $('#ModeOfPayment').val();
            var TermsOfPayment = $('#TermsOfPayment').val();
            var TermsOfDelivery = $('#TermsOfDelivery').val();

            $('#vendor_list').html('');
            showLoader();
            $.ajax({
                url: "{{ route('apply-filter') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    LineOfBusiness: LineOfBusiness,
                    SupplierType: SupplierType,
                    Chain: Chain,
                    BusinessClassification: BusinessClassification,
                    SalesTaxGroup: SalesTaxGroup,
                    Segment: Segment,
                    SubSegment: SubSegment,
                    ModeOfPayment: ModeOfPayment,
                    TermsOfPayment: TermsOfPayment,
                    TermsOfDelivery: TermsOfDelivery,
                },
                cache: false,
                //   async: false,
                success: function(response) {
                    hideLoader();
                    $.each(response, function(index, data) {
                        $('#vendor_list').append('<tr class="">' +
                            '<td class="border p-2">' + data['name'] + '</td>' +
                            '<td class="border p-2">' + data['email'] + '</td>' +
                            '<td class="border p-2">' + data['vendor_account_id'] +
                            '</td>' +
                            '<td class="border p-2 flex justify-center items-center">' +
                            '<input type="checkbox" class="check_one" id="' + data[
                                'id'] + '">' +
                            '</td>' +
                            '</tr>');
                    });
                }
            });
        }

        $.apply_filter();

        $("#checkall").click(function() {
            $(".check_one").prop('checked', $(this).prop('checked'));
        });

        $.show_hide_to_mail = function(){
            var sendding_type = $('input[name="sendding_type"]:checked').val();
            if(sendding_type=="all_on_one"){
                $("#email_from_notify").show();
            }
            else{
                $('#email_from_notify').val('');
                $("#email_from_notify").hide();
            }
        }

        $.send_email_check = function() {
            const checkboxes = document.querySelectorAll('#vendor_list input[type=checkbox]');
            const checkedIds = [];
            const uncheckedIds = [];
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    checkedIds.push(checkbox.id);
                } else {
                    uncheckedIds.push(checkbox.id);
                }
            });
            if (checkedIds.length < 1) {
                toastr.error('Please Select at lease one Vendor');
            } else {
                var enc_id = "<?php echo $enc_id; ?>";
                var model = "<?php echo $model; ?>";
                var cc_bcc = "";
                var sendding_type = "";
                var email_to_notify = "";
                var email_from_notify = "";
                if (model == "TenderPreSend") {
                    showLoader();
                    $.ajax({
                        url: "{{ route('send-to-vendor-after-filter') }}",
                        method: 'get',
                        data: {
                            checkedIds: checkedIds,
                            enc_id: enc_id,
                            model: model,
                            cc_bcc: cc_bcc,
                            sendding_type: sendding_type,
                            email_to_notify: email_to_notify,
                            email_from_notify: email_from_notify,
                            // _token: '{{ csrf_token() }}'
                        },
                        cache: false,
                        //  async: false,
                        success: function(dataResult) {
                            hideLoader();
                            var dataResult = JSON.parse(dataResult);
                            if (dataResult.statusCode == 200) {
                                toastr.success(model + " is successfuly sent !");
                              //  sendvendorModal();
                            } else if (dataResult.statusCode == 205) {
                                toastr.error(model +
                                    " is already sent to some of the Vendors. Kindly recheck the vendor names before sending !"
                                );
                              //  sendvendorModal();
                            } else {
                                toastr.error('Something went wrong');

                            }
                        }
                    });
                } else { 
                    sendvendorModal();
                }
            }
        }



        $.send = function() {
            var enc_id = "<?php echo $enc_id; ?>";
            var model = "<?php echo $model; ?>";
            const checkboxes = document.querySelectorAll('#vendor_list input[type=checkbox]');
            const checkedIds = [];
            const uncheckedIds = [];
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    checkedIds.push(checkbox.id);
                } else {
                    uncheckedIds.push(checkbox.id);
                }
            });

            if (checkedIds.length < 1 && enc_id) {
                toastr.error('Please Select at least one Vendor');
                button.disabled = false;
                button.innerHTML = "{{ __('tender.send') }}";
                return false;
            }

            var email_to_notify = $('#email_to_notify').val();
            var email_from_notify = $('#email_from_notify').val();
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if(email_to_notify){
                if(!emailPattern.test(email_to_notify)){
                    toastr.error('Please Enter valid email');
                    button.disabled = false;
                    button.innerHTML = "{{ __('tender.send') }}";
                    return false;
                }
            }

            if(email_from_notify){
                if(!emailPattern.test(email_from_notify)){
                    toastr.error('Please Enter valid email');
                    button.disabled = false;
                    button.innerHTML = "{{ __('tender.send') }}";
                    return false;
                }
            }

            var cc_bcc = $('input[name="cc_bcc"]:checked').val();
            var sendding_type = $('input[name="sendding_type"]:checked').val();
            showLoader();
            $.ajax({
                url: "{{ route('send-to-vendor-after-filter') }}",
                method: 'get',
                data: {
                    checkedIds: checkedIds,
                    enc_id: enc_id,
                    model: model,
                    cc_bcc: cc_bcc,
                    sendding_type: sendding_type,
                    email_to_notify: email_to_notify,
                    email_from_notify: email_from_notify,
                // _token: '{{ csrf_token() }}'
                },
                success: function(dataResult) {
                    hideLoader();
                    var dataResult = JSON.parse(dataResult);                 
                    if (dataResult.statusCode == 200) {                        
                        toastr.success(model + " is successfully sent !");
                    } else if (dataResult.statusCode == 205) {                        
                        toastr.error(model + " is already sent to some of the Vendors. Kindly recheck the vendor names before sending !");
                    } else {
                        toastr.error('Something went wrong');
                    }
 
                    button.disabled = false;
                    button.innerHTML = "{{ __('tender.send') }}";
                },
                error: function() {
                    toastr.error('Something went wrong'); 
                    hideLoader(); 
                    button.disabled = false;
                    button.innerHTML = "{{ __('tender.send') }}";
                }
            });
        }


    });
</script>
@endsection
