<div id="course-management-menu"
    class="drawer border-r  fixed top-0  left-0 h-[93vh]   overflow-y-auto transition-transform -translate-x-full bg-white ltr:w-[40vw] rtl:w-full "
    tabindex="-1" aria-labelledby="drawer-label">
    <div class="sticky top-0 bg-white z-50 p-4 ">
        <div class="cursor-pointer rtl:p-4 ">
            <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">
                Course Management</h5>
            <button title="Close" type="button"
                class="close-drawer-btn bg-red-300 hover:bg-red-500 h-6 w-6 rounded-md p-3 text-white  absolute top-2.5 end-2.5 flex items-center justify-center">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="mb-2 rtl:hidden">
            <div class="relative w-[250px]">
                <input type="text" placeholder="Search course..."
                    class="pl-2 pr-8 py-1 w-full border-b border-gray-300 border-t-0 border-l-0 border-r-0 rounded-sm focus:outline-none focus:border-gray-300 active:border-gray-300" />
                <i class="ri-search-2-line absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            </div>
            <p id="noResultsMsg" class="text-red-500  text-sm hidden py-2">No Master Found</p>
        </div>
    </div>
    <div class="flex justify-between h-full rtl:p-4 bg-white z-40 p-4">
        <div class="w-full ml-4">
            <div>
                <label class="my-4 text-base font-semibold text-gray-500">{{ __('common.setup') }}</label>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 list-disc pl-5 my-2">
                    <li class="list-view">
                        <a title="{{ __('Course.Course') }}" href="{{ route('course-list') }}">
                            {{ __('Course.Course List') }}
                        </a>
                    </li>
                    <li class="list-view">
                        <a title="{{ __('Course.Course') }}" href="{{ route('course-schedule-list') }}">
                            {{ __('Course.Course Schedule List') }}
                        </a>
                    </li>
                    <li class="list-view">
                        <a title="{{ __('Course.Course') }}" href="{{ route('course-you-tube-list') }}">
                            {{ __('Course.Course YouTube Vedios List') }}
                        </a>
                        {{--  </li>
                    <li class="list-view">
                        <a title="{{ __('Course.Course') }}" href="{{ route('course-will-learn-list') }}">
                            {{ __('Course.What You Learn List') }}
                        </a>
                    </li>  --}}
                        {{--
                    <li class="list-view">
                        <a title="{{ __('Course.Course') }}" href="{{ route('about-geography-list') }}">
                            {{ __('Course.About Geography List') }}
                        </a>
                    </li>  --}}

                        {{--  <li class="list-view">
                        <a title="{{ __('Course.Course Read More List') }}"
                            href="{{ route('course-read-more-list') }}">
                            {{ __('Course.Course Read More List') }}
                        </a>
                    </li>  --}}

                    <li class="list-view">
                        <a title="{{ __('Course.Country') }}" href="{{ route('course-category-list') }}">
                            {{ __('Course.Course Category') }}
                        </a>
                    </li>
                    <li class="list-view">
                        <a title="{{ __('Course.Country') }}" href="{{ route('dynamic-course-pages-list') }}">
                            {{ __('Course.Dynamic Course Pages') }}
                        </a>
                    </li>
                    <li class="list-view">
                        <a title="Course Support Pages" href="{{ route('course-support-page-list') }}">
                            Course Support Pages (Exam/Syllabus)
                        </a>
                    </li>
                    <li class="list-view">
                        <a title="Course Quizzes" href="{{ route('course-quiz-list') }}">
                            Course Quizzes & Mock Exams
                        </a>
                    </li>
                    <li class="list-view"><a
                            href="{{ route('master-parent-list', ['ty' => encrypt('Continent')]) }}">{{ __('common.Continent') }}</a>
                    </li>
                    <li class="list-view">
                        <a title="{{ __('Course.Country') }}" href="{{ route('course-country-list') }}">
                            {{ __('Course.Course-Country') }}
                        </a>
                    </li>

                    <li class="list-view">
                        <a title="{{ __('Course.Country Region') }}" href="{{ route('region-list') }}">
                            {{ __('Course.Country Region') }}
                        </a>
                    </li>

                    <li class="list-view">
                        <a title="{{ __('Course.State') }}" href="{{ route('course-state-list') }}">
                            {{ __('Course.State') }}
                        </a>
                    </li>
                    <li class="list-view">
                        <a title="{{ __('Course.State') }}" href="{{ route('course-city-list') }}">
                            {{ __('Course.City') }}
                        </a>
                    </li>
                    <li class="list-view">
                        <a title="{{ __('Course.Course Trainer List') }}" href="{{ route('course-trainer-list') }}">
                            {{ __('Course.Course Trainer List') }}
                        </a>
                    </li>



                    <li class="list-view">
                        <a href="{{ route('master-parent-list', ['ty' => encrypt('Category')]) }}">
                            {{ __('common.Category') }}
                        </a>
                    </li>

                    <li class="list-view">
                        <a href="{{ route('plan-index', ['ty' => encrypt('Plans')]) }}">
                            {{ __('common.plan') }}
                        </a>
                    </li>
                    <li class="list-view">
                        <a href="{{ route('plan-mapping-index', ['ty' => encrypt('Plans')]) }}">
                            {{ __('common.plan-mapping') }}
                        </a>
                    </li>

                    <li class="list-view">
                        <a title="{{ __('Course.Course Certificate Text List') }}"
                            href="{{ route('course-certificate-text-list') }}">
                            {{ __('Course.Certificate Text') }}
                        </a>
                    </li>

                    <li class="list-view">
                        <a title="{{ __('Course.Course Certificate Text Image') }}"
                            href="{{ route('course-certificate-image-list') }}">
                            {{ __('Course.Certificate Image') }}
                        </a>
                    </li>

                    <li class="list-view">
                        <a title="{{ __('Course.Company Alumni') }}" href="{{ route('course-company-alumni-list') }}">
                            {{ __('Course.Company Alumni') }}
                        </a>
                    </li>

                    <li class="list-view">
                        <a title="{{ __('Course.Course Seo') }}" href="{{ route('course-seo-list') }}">
                            {{ __('Course.Course Seo') }}
                        </a>
                    </li>

                    <li class="list-view">
                        <a title="{{ __('Course.promo-code') }}" href="{{ route('promo-code-list') }}">
                            {{ __('Course.promo-code') }}
                        </a>
                    </li>
                    {{-- <li class="list-view">
                        <a href="{{ route('invoice.create') }}">
                            Invoice
                        </a>
                    </li> --}}


                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[placeholder="Search Inventory..."]');
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
