<div id="website-menu"
    class="drawer border-r  fixed top-0  left-0 h-[93vh]   overflow-y-auto transition-transform -translate-x-full bg-white ltr:w-[40vw] rtl:w-full "
    tabindex="-1" aria-labelledby="drawer-label">
    <div class="sticky top-0 bg-white z-50 p-4 ">
        <div class="cursor-pointer rtl:p-4 ">
            <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">
                website Menu</h5>
            <button title="Close" type="button"
                class="close-drawer-btn bg-red-300 hover:bg-red-500 h-6 w-6 rounded-md p-3 text-white  absolute top-2.5 end-2.5 flex items-center justify-center">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="mb-2 rtl:hidden">
            <div class="relative w-[250px]">
                <input type="text" placeholder="Search..."
                    class="pl-2 pr-8 py-1 w-full border-b border-gray-300 border-t-0 border-l-0 border-r-0 rounded-sm focus:outline-none focus:border-gray-300 active:border-gray-300" />
                <i class="ri-search-2-line absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            </div>
            <p id="noResultsMsg" class="text-red-500  text-sm hidden py-2">No Master Found</p>
        </div>
    </div>
    <div class="flex justify-between h-full rtl:p-4 bg-white z-40 p-4">
        <div class="md:w-[600px] ml-4">
            <div>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 list-disc pl-5 my-2">
                    <li class="list-view"><a
                            href="{{ route('knowledge-center-list') }}">{{ __('common.Knowledge Center') }}</a></li>
                    <li class="list-view"><a href="{{ route('page-list') }}">Pages</a></li>
                    <li class="list-view"><a href="{{ route('consulting-list') }}">Consulting</a></li>
                    {{--  <li class="list-view"><a href="{{ route('businessgroup-index') }}">Business Group</a></li>  --}}
                    <li class="list-view"><a href="{{ route('landingpage.index') }}">Landing Page Sections</a></li>
                    <li class="list-view"><a href="{{ route('tags-list') }}">{{ __('website.Tags List') }}</a></li>
                    <li class="list-view"><a href="{{ route('contact-list') }}">Contact</a></li>
                    {{-- <li class="list-view"><a
                            href="{{ route('master-parent-list', ['ty' => encrypt('Menu Bar')]) }}">{{ __('common.Menu Bar') }}</a>
                    </li> --}}
                    <li class="list-view"><a
                            href="{{ route('master-parent-list', ['ty' => encrypt('Knowledge Center Category')]) }}">{{ __('Course.Knowledge Center Category') }}</a>
                    </li>
                    <li class="list-view"><a
                            href="{{ route('testimonial-list') }}">{{ __('Course.testimonial-list') }}</a>
                    </li>
                    {{--  <li class="list-view"><a href="{{ route('slider-list') }}">{{ __('website.Slider List') }}</a>
                    </li>  --}}
                    <li class="list-view"><a
                            href="{{ route('hiring-companies-list') }}">{{ __('website.Hiring Companies List') }}</a>
                    </li>
                    {{-- <li class="list-view"><a
                            href="{{ route('submission-list') }}">{{ __('website.Contact Submission List') }}</a>
                    </li>
                    <li class="list-view"><a
                            href="{{ route('appointment-list') }}">{{ __('website.Schedule Appointments List') }}</a>
                    </li>
                    <li class="list-view"><a
                            href="{{ route('enquiries-list') }}">{{ __('website.Consultation Enquiries List') }}</a>
                    </li>
                    <li class="list-view"><a
                            href="{{ route('application-list') }}">{{ __('website.Team Application List') }}</a>
                    </li> --}}
                    {{-- <li class="list-view"><a href="{{route('gl-period-list')}}">GL Periods</a></li>
                    <li class="list-view"><a href="{{ route('website::slider.index') }}">Slider</a></li>

                    {{-- <li class="list-view"><a href="{{route('gl-period-list')}}">GL Periods</a></li>
                    <li class="list-view"><a href="{{route('ap-invoice-list')}}">AP Invoices</a></li>
                    <li class="list-view"><a href="{{route('ap-payment-list')}}">AP Payments</a></li>
                    <li class="list-view"><a href="{{route('ar-invoice-list')}}">AR Invoice</a></li>
                    <li class="list-view"><a href="{{route('ar-payment-list')}}">AR Payment</a></li> --}}
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[placeholder="Search..."]');
        const listItems = document.querySelectorAll('.list-view');
        const noResultsMsg = document.getElementById('noResultsMsg');

        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            let matchFound = false;

            listItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    item.style.display = "list-item";
                    matchFound = true;
                } else {
                    item.style.display = "none";
                }
            });
            noResultsMsg.style.display = matchFound ? "none" : "block";
        });
    });
</script>
