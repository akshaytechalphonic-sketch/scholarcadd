@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div>
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')
        <button title="{{ __('Course.Add') }}"  id="printButton" class="topbarbtn">
            <i class="ri-add-line"></i>
           Print
        </button>
        @php echo filter_button(); @endphp
    @endsection
</div>
@include('pages.common.custom_filter_html')
<section>
    <div class="overflow-x-auto bg-white rounded-md border p-4">
        <div
            class="mainContent dark:bg-black dark:text-white rounded-md transition-all duration-500 ease-in-out w-full md:w-[100%]">
            <div id="print-report" class="bg-white  p-4">
                <div class="bg-white p-6 text-sm text-black max-w-6xl mx-auto">
                    <div class="flex justify-between border-b border-gray-800 items-start mb-4">
                        <div class="flex justify-center items-center mb-4">
                            <img src="https://{{url('/assets/image/logo/scholaracad_logo.svg')}}"
                                alt="Logo" class="h-12 w-auto object-contain">
                        </div>
                        <div class="text-right">

                            <p class="font-bold mr-5">Invoice</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-10 mb-6">
                        <div class="space-y-1">
                            <p class="font-bold">Scholar Academy Consulting</p>
                            <p>A-43, Trikuta Hills, Star Sehkari Awas Samiti Ltd.</p>
                            <p>Plot No. C-58/27, Noida Sector-62</p>
                            <p>Gautam Buddha Nagar, Uttar Pradesh, 201309</p>
                            <p class="mt-1"><strong>GSTIN:</strong> 09BLSPP5713P1Z3</p>
                        </div>
                        <div class="space-y-1">
                            <div class="grid grid-cols-2">
                                <p>Invoice No</p>
                                <p></p>
                            </div>
                            <div class="grid grid-cols-2">
                                <p>Date</p>
                                <p></p>
                            </div>
                            <div class="grid grid-cols-2">
                                <p>Customer Name</p>
                                <p></p>
                            </div>
                            <div class="grid grid-cols-2">
                                <p>Contact Number</p>
                                <p></p>
                            </div>
                            <div class="grid grid-cols-2">
                                <p>Email</p>
                                <p></p>
                            </div>
                            <div class="grid grid-cols-2">
                                <p>Currency</p>
                                <p></p>
                            </div>
                            <div class="grid grid-cols-2">
                                <p>Organization Name</p>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <p class="font-semibold mb-1">Description</p>
                    <table class="w-full border border-black text-sm mb-6">
                        <thead>
                            <tr>
                                <th class="border p-1 text-left">Course Name</th>
                                <th class="border p-1 text-center w-20">Qty</th>
                                <th class="border p-1 text-right w-28">Amount</th>
                                <th class="border p-1 text-right w-28">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border p-1">&nbsp;</td>
                                <td class="border p-1">&nbsp;</td>
                                <td class="border p-1">&nbsp;</td>
                                <td class="border p-1">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="grid grid-cols-4 mb-4">
                        <div class="col-span-3 text-right pr-2 font-bold">
                            Total
                        </div>
                        <div class="col-span-3 text-right pr-2 font-semibold">1342</div>
                    </div>
                    <div class="flex items-center mb-6">
                        <div class="w-48 font-bold">
                            Amount in Words:
                        </div>
                        <div class="flex-1 text-right font-semibold">
                            One Thousand Three Hundred Forty Two.
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-y-1 mb-8">
                        <p class="font-semibold">Plan</p>
                        <p class="font-semibold">Plan Name</p>
                        <p class="font-semibold">Payment Status</p>
                        <p class="font-semibold">Paid</p>
                        <p class="font-semibold">Transaction Date</p>
                        <p class="font-semibold">Date Time</p>
                        <p class="font-semibold">Transaction ID</p>
                        <p class="font-semibold">ID</p>
                    </div>
                    <p class="text-center font-semibold mb-2">
                        Thank you for trusting ScholarAcad
                    </p>
                    <p class="text-center text-xs">
                        This is a Computer Generated Document, No signature required
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.sweetalert_js')
@include('Course::Master.CourseCategory.extra_js')
@include('Course::Master.CourseCategory.table_js')


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('printButton').addEventListener('click', function() {
            printSection('print-report');
        });

        function printSection(sectionId) {
            var printContents = document.getElementById(sectionId).innerHTML;
            var iframe = document.createElement('iframe');
            iframe.style.position = 'absolute';
            iframe.style.width = '0px';
            iframe.style.height = '0px';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);
            var iframeDoc = iframe.contentWindow.document;
            iframeDoc.open();
            iframeDoc.write('<html><head><title>OHB LOA</title>');
            var styles = document.querySelectorAll('link[rel="stylesheet"], style');
            styles.forEach(function(style) {
                iframeDoc.write(style.outerHTML);
            });
            iframeDoc.write('</head><body>');
            iframeDoc.write(printContents);
            iframeDoc.write('</body></html>');
            iframeDoc.close();
            iframe.contentWindow.onload = function() {
                iframe.contentWindow.print();
                document.body.removeChild(iframe);
            };
        }
    });
</script>
@endsection
