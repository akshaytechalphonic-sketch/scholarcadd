<div id="Topbar"
    class="bg-white z-40  border-b  flex justify-between p-[2px] transition-all duration-500 ease-in-out transform opacity-100 translate-y-0">
    <div class="flex justify-between md:flex-row  w-full">
        {{-- Common Header Menu --}}
        <div class="md:w-[70vw] overflow-x-auto overflow-y-hidden">
            <div class="inline-flex w-full" role="group">
                @yield('back_button')
                @yield('extra_buttons')
            </div>

        </div>
        {{-- Right Side: Refresh, Toggle, and Footer Toggle --}}
        <div class="flex items-center ">
            @yield('extra_buttons_right')
            <div class="inline-flex" role="group">
                <button title="Refresh" type="button" onClick="refreshPage()"
                    class="inline-flex hover-scale items-center px-2 py-2 transition duration-300 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2 border-l-[1px]">
                    <i class="ri-refresh-line text-blue-500 font-semibold"></i>
                </button>
                <button title="Toggle Topbar" type="button" id="toggleButton"
                    class="inline-flex hover-scale items-center px-2 py-2 transition duration-300 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary focus:z-10 focus:ring-2 border-l-[1px]">
                    <i id="toggleIcon" class="ri-arrow-up-s-line"></i>
                </button>
                <button title="Toggle Footer" type="button" id="toggleButtonfooter"
                    class="inline-flex hover-scale gap-2 items-center px-2 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100 border-l-[1px] hover:text-primary focus:z-10 focus:ring-2 transition duration-300">
                    <i id="toggleIconfooter" class="ri-arrow-down-s-line"></i>
                </button>
            </div>
        </div>
    </div>
    {{-- All Drawer Menu List Render Here... --}}
    @include('Sidebar-Drawer-Lists.render-menu')
</div>

<script>
    // Page Refresh
    function refreshPage() {
        window.location.reload();
    }
    // Header Dropwown Menu
    // document.addEventListener("DOMContentLoaded", function() {
    //     const dropdownButton = document.getElementById('dropdownDividerButton');
    //     const dropdownCloseButton = document.getElementById('dropdownCloseButton');
    //     const dropdownMenu = document.getElementById('dropdownDivider');
    //     if (dropdownButton && dropdownCloseButton && dropdownMenu) {
    //         const showDropdown = () => {
    //             dropdownMenu.classList.remove('hidden', 'opacity-0', 'scale-95');
    //             dropdownMenu.classList.add('opacity-100', 'scale-100');
    //         };
    //         const hideDropdown = () => {
    //             dropdownMenu.classList.add('opacity-0', 'scale-95');
    //             dropdownMenu.classList.remove('opacity-100', 'scale-100');
    //             setTimeout(() => {
    //                 if (dropdownMenu.classList.contains('opacity-0')) {
    //                     dropdownMenu.classList.add('hidden');
    //                 }
    //             }, 500);
    //         };
    //         dropdownButton.addEventListener('click', () => {
    //             if (
    //                 dropdownMenu.classList.contains('hidden') ||
    //                 dropdownMenu.classList.contains('opacity-0')
    //             ) {
    //                 showDropdown();
    //             } else {
    //                 hideDropdown();
    //             }
    //         });
    //         dropdownCloseButton.addEventListener('click', hideDropdown);
    //     } else {
    //         console.warn("Dropdown elements not found. Check your element IDs in HTML.");
    //     }
    // });
</script>
