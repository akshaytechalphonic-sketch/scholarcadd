 <div id="Appearence_Settings"
     class="drawer fixed top-0 border-r  left-0 h-[93vh]  p-4 overflow-y-auto transition-transform -translate-x-full bg-white md:w-[450px] w-80"
     tabindex="-1" aria-labelledby="drawer-label">
     <div class="cursor-pointer">
         <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">
             Appearence Settings
             <button type="button"
                 class="close-drawer-btn bg-red-300 hover:bg-red-500 h-6 w-6 rounded-md p-3 text-white  absolute top-2.5 end-2.5 flex items-center justify-center">
                 <i class="ri-close-line"></i>
             </button>
     </div>
     <div class=" ">
         {{-- -----Rtl Toggler button ------- --}}
         {{-- <div class="">
        <span class="dark:text-white">Directions RTL/LTR</span><br />
        <div class="flex gap-2">
            <div class="flex items-center my-3">
                <input id="ltr-radio" type="radio" value="ltr" name="direction-radio"
                    class="w-4 h-4 text-blue-600 cursor-pointer bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" />
                <label for="ltr-radio" class="ms-2 text-sm font-medium text-gray-900">LTR</label>
            </div>
            <div class="flex items-center ml-4">
                <input id="rtl-radio" type="radio" value="rtl" name="direction-radio"
                    class="w-4 h-4 text-blue-600 cursor-pointer bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" />
                <label for="rtl-radio" class="ms-2 text-sm font-medium text-gray-900">RTL</label>
            </div>
        </div> 
    </div> --}}
         <!-- Theme Toggle -->
         <div class="my-2 ">
             <span class="dark:text-white">Theme</span>
             <br />
             <div class="flex items-center my-3">
                 <div class="flex items-center">
                     <input id="light-radio" type="radio" value="light" name="theme-radio"
                         class="w-4 h-4 text-blue-600 cursor-pointer bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" />
                     <label for="light-radio" class="ms-2 text-sm font-medium text-gray-900">Light</label>
                 </div>
                 <div class="flex items-center ml-4">
                     <input id="dark-radio" type="radio" value="dark" name="theme-radio"
                         class="w-4 h-4 text-blue-600 cursor-pointer bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2" />
                     <label for="dark-radio" class="ms-2 text-sm font-medium text-gray-900">Dark</label>
                 </div>
             </div>
         </div>
         {{-- -----------theme color change------ --}}
         <p class="py-2">Adjust Application Theme color</p>
         <div class="my-2">
             <div class="p-2">
                 <!-- Primary Colors -->
                 <div class="bg-white  rounded-sm mb-2  items-center md:flex-row flex-col">
                     <div>
                         <h5 class="font-semibold text-black ">Primary Color</h5>
                         <p class="text-gray-500 mb-4 ">Choose a primary color for your application</p>
                     </div>
                     <div class="flex gap-4">
                         <div title="Primay"
                             class="w-6 h-6  bg-primary rounded-full cursor-pointer primary-color-option"
                             data-color="#1F3F49"></div>
                         <div title="Mossy Hollow"
                             class="w-6 h-6 border bg-[#3D4127] rounded-full cursor-pointer primary-color-option"
                             data-color="#3D4127"></div>
                         <div title="Blue Eclipse"
                             class="w-6 h-6 border bg-[#0F0E47] rounded-full cursor-pointer primary-color-option"
                             data-color="#0F0E47"></div>
                         <div title="Chili Spice"
                             class="w-6 h-6 border bg-[#38000A] rounded-full cursor-pointer primary-color-option"
                             data-color="#38000A"></div>
                         <div title="Cozy Compfire"
                             class="w-6 h-6 border bg-[#660F09] rounded-full cursor-pointer primary-color-option"
                             data-color="#660F09"></div>
                     </div>
                 </div>

                 <!-- Secondary Colors -->
                 <div class="bg-white  mb-2  items-center md:flex-row flex-col mt-4">
                     <div>
                         <h5 class="font-semibold text-black ">Secondary Color</h5>
                         <p class="text-gray-500 mb-4 ">Choose a secondary color for your application
                         </p>
                     </div>
                     <div class="flex gap-4">
                         <div class="w-6 h-6 border  bg-secondary rounded-full cursor-pointer secondary-color-option"
                             data-color="#d8f6ff"></div>
                         <div class="w-6 h-6 border  bg-[#D4DE95] rounded-full cursor-pointer secondary-color-option"
                             data-color="#D4DE95"></div>
                         <div class="w-6 h-6 border bg-[#BFFFED] rounded-full cursor-pointer secondary-color-option"
                             data-color="#BFFFED"></div>
                         <div class="w-6 h-6 border bg-[#FFA896] rounded-full cursor-pointer secondary-color-option"
                             data-color="#FFA896"></div>
                         <div class="w-6 h-6 border bg-[#DBD4FF] rounded-full cursor-pointer secondary-color-option"
                             data-color="#DBD4FF"></div>
                     </div>
                 </div>

                 <!-- Neutral Colors -->
                 {{-- <div class="bg-white  rounded-sm   items-center md:flex-row flex-col mt-4">
                     <div>
                         <h5 class="font-semibold text-black  ">Neutral Color</h5>
                         <p class="text-gray-500 mb-4 ">Choose a neutral color for your application</p>
                     </div>
                     <div class="flex gap-4">
                         <div class="w-6 h-6 border  bg-neutral rounded-full cursor-pointer neutral-color-option"
                             data-color="#F9FAFB"></div>
                         <div class="w-6 h-6 border bg-gray-200 rounded-full cursor-pointer neutral-color-option"
                             data-color="#E5E7EB"></div>
                         <div class="w-6 h-6 border bg-gray-300 rounded-full cursor-pointer neutral-color-option"
                             data-color="#D1D5DB"></div>
                         <div class="w-6 h-6 border bg-gray-400 rounded-full cursor-pointer neutral-color-option"
                             data-color="#9CA3AF"></div>
                         <div class="w-6 h-6 border bg-[#FFFFFF] rounded-full cursor-pointer neutral-color-option"
                             data-color="#FFFFFF"></div>
                     </div>
                 </div> --}}

                 <!-- Font Size -->
                 <div class="bg-white  rounded-sm   items-center md:flex-row flex-col mt-4">
                     <p class="py-2 ">Adjust Application Font Size</p>
                     <div class="flex gap-4 items-center">
                         <div class="flex gap-2 items-center">
                             <input class="cursor-pointer" type="radio" name="font-size" value="14px">
                             <label> Small</label>
                         </div>
                         <div class="flex gap-2 items-center">
                             <input class="cursor-pointer" type="radio" name="font-size" value="15px" checked>
                             <label> Default</label>
                         </div>
                         <div class="flex gap-2 items-center">
                             <input class="cursor-pointer" type="radio" name="font-size" value="16px">
                             <label> Large</label>
                         </div>
                         <div class="flex gap-2 items-center">
                             <input class="cursor-pointer" type="radio" name="font-size" value="17px">
                             <label>Extra Large</label>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
          <p class="py-2 ">Adjust Sidebar Design</p>
         <div id="theme-selector" class="grid grid-cols-5 gap-2 max-w-sm p-2">
             <div class="theme-option" data-theme="base" style="background-color: #ffffff;" title="Base (Default)">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="purple" style="background-color: #a855f7;" title="Purple">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="blue" style="background-color: #3b82f6;" title="Blue">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="green" style="background-color: #10b981;" title="Green">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="red" style="background-color: #ef4444;" title="Red">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="orange" style="background-color: #f97316;" title="Orange">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="teal" style="background-color: #14b8a6;" title="Teal">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="yellow" style="background-color: #eab308;" title="Yellow">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="pink" style="background-color: #ec4899;" title="Pink">
                 <span class="check-icon hidden">✓</span>
             </div>
             <div class="theme-option" data-theme="indigo" style="background-color: #6366f1;" title="Indigo">
                 <span class="check-icon hidden">✓</span>
             </div>
         </div>
         <div class="absolute bottom-0  left-0 w-full p-3 border-t bg-white dark:bg-gray-900 flex justify-end gap-2">
             <button id="reset-theme"
                 class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                 Reset Default <i class="ri-refresh-line"></i>
             </button>
             <button id="set-theme" type="button"
                 class="px-3 py-2 text-xs hidden font-medium text-center text-white bg-primary rounded-sm hover:bg-primary focus:ring-4 focus:outline-none focus:ring-blue-300">
                 Set Theme
             </button>
         </div>
     </div>
 </div>
 <script>
     document.addEventListener('DOMContentLoaded', function() {
         const resetBtn = document.getElementById('reset-theme');
         const defaultFontSize = '15px';
         const fontSizeRadios = document.querySelectorAll('input[name="font-size"]');
         const fontSizeKey = 'preferredFontSize';
         const savedSize = localStorage.getItem(fontSizeKey);
         if (savedSize) {
             document.documentElement.style.setProperty('--base-font-size', savedSize);
             fontSizeRadios.forEach(radio => {
                 if (radio.value === savedSize) {
                     radio.checked = true;
                 }
             });
         }
         fontSizeRadios.forEach(radio => {
             radio.addEventListener('change', function() {
                 if (this.checked) {
                     document.documentElement.style.setProperty('--base-font-size', this.value);
                     localStorage.setItem(fontSizeKey, this.value);
                 }
             });
         });
         resetBtn.addEventListener('click', function() {
             document.documentElement.style.setProperty('--base-font-size', defaultFontSize);
             fontSizeRadios.forEach(radio => {
                 radio.checked = radio.value === defaultFontSize;
             });
             localStorage.setItem(fontSizeKey, defaultFontSize);
         });
     });
 </script>
