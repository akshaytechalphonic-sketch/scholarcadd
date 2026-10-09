@php
    $user = '';
    $type = '';
    $name = '';
    $img = '';
@endphp

<div id="sidebar" data-theme="purple" style="background-color: var(--sidebar-bg);"
    class="app w-[260px] sidebar   z-50  bg-white text-black border-r  flex-none transition-all duration-300 relative">
        <a href="{{ url('/') }}" class="block expanded-logo text-center">
        <img src="{{ asset('assets/image/logo/scholaracad_logo.svg') }}" class="w-[220px]  mt-3 mb-2 mx-auto"
            alt="Expanded Logo" />
        {{--  @if ($system?->light_logo)
            <img src="{{ route('secure-documents', ['path' => $system?->light_logo]) }}" class="w-[220px] mx-auto m-3">
        @else
            <img src="{{ asset('assets/image/logo/scholaracad_logo.svg') }}" class="w-[120px] mb-2 mx-auto"
                alt="Expanded Logo" />
        @endif  --}}
    </a>

    <a href="{{ url('/') }}" class="collapsed-logo hidden text-orange text-xl font-bold">
        @if ($system?->small_logo)
            <img src="{{ route('secure-documents', ['path' => $system?->small_logo]) }}"
                class="bg-blue-200 h-8 w-8 rounded-md border">
        @else
            AMY
        @endif
    </a>
    <ul class="  px-4 overflow-y-auto h-[calc(100vh-100px)]">
        <li title="Dashboard" class="group relative">
            <a href="{{ url('/dashboard') }}"
                class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all font-semibold"
                style="
            background-color: {{ Request::is('dashboard*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
            color: {{ Request::is('dashboard*') ? 'var(--sidebar-text)' : '#374151' }};
        "
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='{{ Request::is('dashboard*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ Request::is('dashboard*') ? 'var(--sidebar-text)' : '#374151' }}';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="
                background-color: {{ Request::is('dashboard*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                color: {{ Request::is('dashboard*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
            "
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='{{ Request::is('dashboard*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ Request::is('dashboard*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                    <i class="ri-function-line font-normal"></i>
                </div>
                <span class="flex-1 text-left whitespace-nowrap"
                    style="color: var(--sidebar-text) !important;">{{ __('loginer::messages.dashboard') }}</span>
            </a>
        </li>
        <li class="group relative my-[6px]">
            <button type="button" data-drawer="course-management-menu" title="Course Management"
                class="open-drawer-btn flex items-center gap-3 px-[6px] py-[6px] rounded-md w-full transition-all  font-semibold"
                style="background-color: transparent; color: var(--sidebar-text);"
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--sidebar-text)';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="background-color: var(--sidebar-icon-hover-bg); color: var(--sidebar-text);"
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='var(--sidebar-icon-hover-bg)'; this.style.color='var(--sidebar-text)';">
                    <i class="ri-store-2-line font-normal"></i>
                </div>
                <span class="flex-1 text-left rtl:text-right whitespace-nowrap">
                    Course Management
                </span>
                <i class="ri-arrow-right-s-line text-sm transition-all" style="color: var(--sidebar-text);"
                    onmouseover="this.style.color='var(--sidebar-text)';"
                    onmouseout="this.style.color='var(--sidebar-text)';"></i>
            </button>
        </li>

        <li class="group relative my-[6px]">
            <button type="button" data-drawer="website-menu" title="Website"
                class="open-drawer-btn flex items-center gap-3 px-[6px] py-[6px] rounded-md w-full transition-all  font-semibold"
                style="background-color: transparent; color: var(--sidebar-text);"
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--sidebar-text)';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="background-color: var(--sidebar-icon-hover-bg); color: var(--sidebar-text);"
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='var(--sidebar-icon-hover-bg)'; this.style.color='var(--sidebar-text)';">
                    <i class="ri-store-2-line font-normal"></i>
                </div>
                <span class="flex-1 text-left rtl:text-right whitespace-nowrap">
                    Website
                </span>
                <i class="ri-arrow-right-s-line text-sm transition-all" style="color: var(--sidebar-text);"
                    onmouseover="this.style.color='var(--sidebar-text)';"
                    onmouseout="this.style.color='var(--sidebar-text)';"></i>
            </button>
        </li>
        <li x-data="{ open: {{ request()->is('seller-company*') ? 'true' : 'false' }} }" class="relative">
            <button type="button" @click="open = !open"
                class="flex items-center gap-3 px-[6px] py-[6px] rounded-md w-full transition-all group my-[6px] font-semibold"
                style="
            background-color: {{ request()->is('seller-company*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
            color: {{ request()->is('seller-company*') ? 'var(--sidebar-text)' : '#374151' }};"
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='{{ request()->is('seller/list') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('Seller/list') ? 'var(--sidebar-text)' : '#374151' }}';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="
                background-color: {{ request()->is('seller-company*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                color: {{ request()->is('seller-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};"
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('seller/list') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('incident_list') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                    <i class="ri-building-line font-normal"></i>
                </div>
                <span class="flex-1 text-left whitespace-nowrap" style="color: var(--sidebar-text) !important;">
                    System Control
                </span>
                <i class="ri-arrow-down-s-line text-sm transition-transform duration-300"
                    style="color: var(--sidebar-text);" :class="{ 'rotate-180': open }"></i>
            </button>
            <ul x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2 scale-y-90"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-y-100"
                class="space-y-1 mt-2 origin-top overflow-hidden ml-4" style="display: none;">
                <li>
                    <a href="{{ route('system-control-sidebar') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('seller-company*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('seller-company*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('seller-company*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('seller-company*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('seller-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('seller-company*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">System Control Sidebar</span>
                    </a>
                </li>
            </ul>
        </li>

        <li x-data="{ open: {{ request()->is('faqs-list*') ? 'true' : 'false' }} }" class="relative">
            <button type="button" @click="open = !open"
                class="flex items-center gap-3 px-[6px] py-[6px] rounded-md w-full transition-all group my-[6px] font-semibold"
                style="
            background-color: {{ request()->is('faqs-list*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
            color: {{ request()->is('faqs-list*') ? 'var(--sidebar-text)' : '#374151' }};"
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='{{ request()->is('faqs-list/list') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('Seller/list') ? 'var(--sidebar-text)' : '#374151' }}';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="
                background-color: {{ request()->is('faqs-list*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                color: {{ request()->is('faqs-list*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};"
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('faqs-list/list') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('incident_list') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                    <i class="ri-building-line font-normal"></i>
                </div>
                <span class="flex-1 text-left whitespace-nowrap" style="color: var(--sidebar-text) !important;">
                    FAQs
                </span>
                <i class="ri-arrow-down-s-line text-sm transition-transform duration-300"
                    style="color: var(--sidebar-text);" :class="{ 'rotate-180': open }"></i>
            </button>
            <ul x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2 scale-y-90"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-y-100"
                class="space-y-1 mt-2 origin-top overflow-hidden ml-4" style="display: none;">
                <li>
                    <a href="{{ route('faqs-list-index') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('course-faqs-list*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('course-faqs-list*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('course-faqs-list*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('course-faqs-list*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('course-faqs-list*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('course-faqs-list*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('course-faqs-list*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('course-faqs-list*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Course FAQs</span>


                    </a>
                </li>
                <li>
                    <a href="{{ route('faqs-list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('faqs-list*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('faqs-list*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('faqs-list*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('faqs-list*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('faqs-list*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('faqs-list*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">General FAQs</span>


                    </a>
                </li>
                <li>
                    <a href="{{ route('faqs.category.list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('faqs-category*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('faqs-category*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('faqs-category*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('faqs-category*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('faqs-category*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('faqs-category*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">FAQs Category</span>


                    </a>
                </li>
            </ul>
        </li>

        <li x-data="{ open: {{ request()->is('enrolled*') ? 'true' : 'false' }} }" class="relative">
            <button type="button" @click="open = !open"
                class="flex items-center gap-3 px-[6px] py-[6px] rounded-md w-full transition-all group my-[6px] font-semibold"
                style="
            background-color: {{ request()->is('enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
            color: {{ request()->is('enrolled*') ? 'var(--sidebar-text)' : '#374151' }};"
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled/list') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('Seller/list') ? 'var(--sidebar-text)' : '#374151' }}';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="
                background-color: {{ request()->is('enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                color: {{ request()->is('enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};"
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled/list') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('incident_list') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                    <i class="ri-building-line font-normal"></i>
                </div>
                <span class="flex-1 text-left whitespace-nowrap" style="color: var(--sidebar-text) !important;">
                    Enrollments
                </span>
                <i class="ri-arrow-down-s-line text-sm transition-transform duration-300"
                    style="color: var(--sidebar-text);" :class="{ 'rotate-180': open }"></i>
            </button>
            <ul x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2 scale-y-90"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-y-100"
                class="space-y-1 mt-2 origin-top overflow-hidden ml-4" style="display: none;">

                <li>
                    <a href="{{ route('complete-enrollment-list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Enrollment List</span>
                    </a>
                </li>


                <li>
                    <a href="{{ route('enroll-course-corporate-list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Corporate</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('enroll-course-individual-list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Individual</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('enrolled-request-individual.index') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                     "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Enrolled old Individual</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('enrolled-request-corporate.index') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                      "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Enrolled old Corporate</span>
                    </a>
                </li> --}}


            </ul>
        </li>

        {{-- ================== contact Management start ============== --}}


        <li x-data="{ open: {{ request()->is('Enquiries-consultation*') ? 'true' : 'false' }} }" class="relative">
            <button type="button" @click="open = !open"
                class="flex items-center gap-3 px-[6px] py-[6px] rounded-md w-full transition-all group my-[6px] font-semibold"
                style="
            background-color: {{ request()->is('Enquiries-consultation*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
            color: {{ request()->is('Enquiries-consultation*') ? 'var(--sidebar-text)' : '#374151' }};"
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='{{ request()->is('Enquiries-consultation/list') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('Seller/list') ? 'var(--sidebar-text)' : '#374151' }}';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="
                background-color: {{ request()->is('Enquiries-consultation*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                color: {{ request()->is('Enquiries-consultation*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};"
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('Enquiries-consultation/list') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('incident_list') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                    <i class="ri-building-line font-normal"></i>
                </div>
                <span class="flex-1 text-left whitespace-nowrap" style="color: var(--sidebar-text) !important;">
                    Contact Management
                </span>
                <i class="ri-arrow-down-s-line text-sm transition-transform duration-300"
                    style="color: var(--sidebar-text);" :class="{ 'rotate-180': open }"></i>
            </button>
            <ul x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2 scale-y-90"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-y-100"
                class="space-y-1 mt-2 origin-top overflow-hidden ml-4" style="display: none;">
                <li>
                    <a href="{{ route('submission-list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Contact Submission</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('appointment-list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Schedule Appointment</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('enquiries-list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Consultation Enquiries</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('application-list') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('Enrolled*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('Enrolled*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('Enrolled*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('vendor-company*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Team Applicaton</span>
                    </a>
                </li>


            </ul>
        </li>
        <li x-data="{ open: {{ request()->is('faqs-list*') ? 'true' : 'false' }} }" class="relative">
            <button type="button" @click="open = !open"
                class="flex items-center gap-3 px-[6px] py-[6px] rounded-md w-full transition-all group my-[6px] font-semibold"
                style="background-color: {{ request()->is('faqs-list*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
            color: {{ request()->is('faqs-list*') ? 'var(--sidebar-text)' : '#374151' }};"
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='{{ request()->is('faqs-list/list') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('Seller/list') ? 'var(--sidebar-text)' : '#374151' }}';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="
                background-color: {{ request()->is('faqs-list*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                color: {{ request()->is('faqs-list*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};"
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='{{ request()->is('faqs-list/list') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('incident_list') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                    <i class="ri-building-line font-normal"></i>
                </div>
                <span class="flex-1 text-left whitespace-nowrap" style="color: var(--sidebar-text) !important;">
                    Export Excel
                </span>
                <i class="ri-arrow-down-s-line text-sm transition-transform duration-300"
                    style="color: var(--sidebar-text);" :class="{ 'rotate-180': open }"></i>
            </button>
            <ul x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2 scale-y-90"
                x-transition:enter-end="opacity-100 transform translate-y-0 scale-y-100"
                class="space-y-1 mt-2 origin-top overflow-hidden ml-4" style="display: none;">
                <li>
                    <a href="{{ route('export-excel.index') }}"
                        class="flex items-center gap-3 px-[6px] py-[6px] rounded-md transition-all text-sm group font-medium"
                        style="
                    background-color: {{ request()->is('course-faqs-list*') ? 'var(--sidebar-active-bg)' : 'transparent' }};
                    color: {{ request()->is('course-faqs-list*') ? 'var(--sidebar-text)' : '#374151' }};
                "
                        onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                        onmouseout="this.style.backgroundColor='{{ request()->is('course-faqs-list*') ? 'var(--sidebar-active-bg)' : 'transparent' }}'; this.style.color='{{ request()->is('course-faqs-list*') ? 'var(--sidebar-text)' : '#374151' }}';">
                        <div class="flex items-center justify-center w-6 h-6 rounded-md text-xs transition-all"
                            style="
                        background-color: {{ request()->is('course-faqs-list*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }};
                        color: {{ request()->is('course-faqs-list*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }};
                    "
                            onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                            onmouseout="this.style.backgroundColor='{{ request()->is('course-faqs-list*') ? 'var(--sidebar-icon-bg)' : 'var(--sidebar-icon-hover-bg)' }}'; this.style.color='{{ request()->is('course-faqs-list*') ? 'var(--sidebar-icon-color)' : 'var(--sidebar-text)' }}';">
                            <i class="ri-building-line"></i>
                        </div>
                        <span class="flex-1 font-semibold text-left whitespace-nowrap"
                            style="color: var(--sidebar-text) !important;">Export Excel</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- ============================== contact management end ============================ --}}

        <li class="group relative my-[6px]">
            <button type="button" data-drawer="Master-menu" title="Master Settings"
                class="open-drawer-btn flex items-center gap-3 px-[6px] py-[6px] rounded-md w-full transition-all  font-semibold"
                style="background-color: transparent; color: var(--sidebar-text);"
                onmouseover="this.style.backgroundColor='var(--sidebar-hover-bg)'; this.style.color='var(--sidebar-text)';"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--sidebar-text)';">
                <div class="flex items-center justify-center w-7 h-7 rounded-md text-sm transition-all"
                    style="background-color: var(--sidebar-icon-hover-bg); color: var(--sidebar-text);"
                    onmouseover="this.style.backgroundColor='var(--sidebar-icon-bg)'; this.style.color='var(--sidebar-icon-color)';"
                    onmouseout="this.style.backgroundColor='var(--sidebar-icon-hover-bg)'; this.style.color='var(--sidebar-text)';">
                    <i class="ri-list-settings-fill font-normal"></i>
                </div>
                <span class="flex-1 text-left rtl:text-right whitespace-nowrap">
                    {{ __('common.master-settings') }}
                </span>
                <i class="ri-arrow-right-s-line text-sm transition-all " style="color: var(--sidebar-text);"
                    onmouseover="this.style.color='var(--sidebar-text)';"
                    onmouseout="this.style.color='var(--sidebar-text)';"></i>
            </button>
        </li>
    </ul>

</div>

<div id="sidebar-overlay" class="hidden fixed inset-0 h-full w-full bg-[#FAFBFE] bg-opacity-100 z-40 lg:hidden"></div>
