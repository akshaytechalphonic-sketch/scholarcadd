@extends('layouts.app')
<style>
    main {
        height: 82vh;
        overflow-y: auto;
    }

    #sidebar {
        transform: translateX(0);
    }

    #sidebar.hidden {
        transform: translateX(-100%);
    }

    #mainContent {
        transition: margin-left 0.3s ease, width 0.3s ease;
    }

    #mainContent.full-width {
        margin-left: 0;
        width: 100%;
    }
</style>
@section('content')
    <div class="flex justify-between">
        <div>
            <h1 class="font-semibold text-lg text-black">Settings</h1>
        </div>
        <nav class="flex " aria-label="Breadcrumb">
            <ol class="inline-flex items-center  space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center  text-sm  text-primary font-bold hover:text-blue-600">
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center justify-center">
                        <i class=" ri-speed-mini-line text-gray-400 p-1"></i>
                        <a href="#"
                            class="ms-1 text-sm font-bold text-gray-700 hover:text-blue-600 md:ms-2">Setting</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center justify-center">
                        <i class=" ri-speed-mini-line text-gray-400"></i>
                        <span class="ms-1 text-sm font-medium text-gray-500 cursor-not-allowed md:ms-2">General
                            Settings</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>


    <div class="">
        <div class="flex h-full  fixed w-[95vw]  border  rounded-md md:max-w-[83vw]  mt-3">
            <!-- Sidebar -->
            <aside id="settingsidebar"
                class="w-[220px]  rounded-md bg-white  text-black h-[73vh] fixed  overflow-y-auto transition-transform duration-300">
                <nav class="space-y-2 p-1">
                    <button
                        class="sidebar-link flex justify-between  hover:bg-secondary text-black w-full text-start rounded-sm hover:text-black hover-scale hover:dark:text-black    px-4 py-2"
                        data-target="theme-settings">
                        <span> Theme Settings</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button>
                    <button
                        class="sidebar-link  flex justify-between hover:bg-secondary text-black rounded-sm text-start w-full hover:text-black hover-scale hover:dark:text-black px-4 py-2"
                        data-target="system-settings">
                        <span> System Settings</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button>
                    <button
                        class="sidebar-link  flex justify-between hover:bg-secondary text-black rounded-sm text-start w-full  hover:text-black hover-scale hover:dark:text-black px-4 py-2"
                        data-target="company-settings">
                        <span>Company Setting</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button>

                    <button
                        class="sidebar-link  flex justify-between hover:bg-secondary text-black rounded-sm text-start w-full hover:text-black hover-scale hover:dark:text-black px-4 py-2"
                        data-target="currency-settings">
                        <span> Currency Settings</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button>
                    <button
                        class="sidebar-link  flex justify-between hover:bg-secondary text-black rounded-sm text-start w-full hover:text-black hover-scale hover:dark:text-black px-4 py-2"
                        data-target="notification-settings">
                        <span>Notification Settings</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button>
                    <button
                        class="sidebar-link  flex justify-between hover:bg-secondary text-black rounded-sm text-start w-full hover:text-black hover-scale hover:dark:text-black px-4 py-2"
                        data-target="storage-settings">
                        <span>Storage Settings</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button>
                    <button
                        class="sidebar-link  flex justify-between hover:bg-secondary hover:dark:text-black text-black rounded-sm text-start w-full hover:text-black hover-scale px-4 py-2"
                        data-target="file-settings">
                        <span>File Settings</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button>
                    <button
                        class="sidebar-link  flex justify-between hover:bg-secondary hover:dark:text-black text-black rounded-sm text-start w-full hover:text-black hover-scale px-4 py-2"
                        data-target="security-settings">
                        <span>Security Settings</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button>
                </nav>
            </aside>

            <!-- Main Content -->
            <main id="mainContent"
                class="ml-[220px] rtl:mr-[220px] flex-1 overflow-y-auto p-3  transition-all duration-300">
                <button id="Settingsidebartoggler"
                    class="ml-[-12px] h-6 w-6  rounded-full fixed opacity-35 top-[50%] text-white bg-primary z-50">
                    <i id="sidebar-toggler-icon" class="ri-arrow-left-s-line text-md"></i>
                </button>
                <section id="theme-settings" class="scroll-section    mb-16  border rounded-sm bg-neutral">
                    <div class="bg-white  border-l-2 border-primary  p-2 flex  items-center">
                        <span class="text-md font-semibold text-black ">Theme Settings</span>
                    </div>
                    <div class="p-2">
                        <div class="flex  justify-between gap-2 md:flex-row flex-col p-2">
                            <!-- Logo Light -->
                            <div class="bg-white   md:basis-1/3 w-full p-2">
                                <div class="bg-white  border-l-2 flex justify-between border-primary p-2 items-center">
                                    <span class="text-md font-semibold text-black">Logo Light</span>
                                    <button type="button"
                                        class="w-8 h-8 rounded-full text-xs font-medium text-center text-white bg-primary hover:bg-blue-800"
                                        onclick="document.getElementById('lightlogo-input').click()">
                                        <i class="ri-upload-2-line"></i>
                                    </button>
                                    <input type="file" id="lightlogo-input" class="hidden" accept="image/*"
                                        onchange="previewLogo('lightlogo-input', 'lightlogo-preview', 'Logo Light')">
                                </div>
                                <div class="p-2 bg-neutral my-3 flex justify-center items-center">
                                    <img id="lightlogo-preview" src="{{ asset('assets/image/logo/logo-black.png') }}"
                                        class="w-[120px] py-3" alt="Logo Light Preview" />
                                </div>
                            </div>

                            <!-- Logo Dark -->
                            <div class="bg-white  md:basis-1/3 w-full p-2">
                                <div class="bg-white border-l-2   flex justify-between border-primary p-2 items-center">
                                    <span class="text-md font-semibold text-black ">Logo Dark</span>
                                    <button type="button"
                                        class="w-8 h-8 rounded-full text-xs font-medium text-center text-white bg-primary hover:bg-blue-800"
                                        onclick="document.getElementById('darklogo-input').click()">
                                        <i class="ri-upload-2-line"></i>
                                    </button>
                                    <input type="file" id="darklogo-input" class="hidden" accept="image/*"
                                        onchange="previewLogo('darklogo-input', 'darklogo-preview', 'Logo Dark')">
                                </div>
                                <div class="p-2 bg-primary my-3 flex justify-center items-center">
                                    <img id="darklogo-preview" src="{{ asset('assets/image/logo/logo-black.png') }}"
                                        class="w-[120px] py-3" alt="Logo Dark Preview" />
                                </div>
                            </div>

                            <!-- Sidebar Collapsed Icon -->
                            <div class="bg-white  md:basis-1/3 w-full p-2">
                                <div class="bg-white  border-l-2 flex justify-between border-primary p-2 items-center">
                                    <span class="text-md font-semibold text-black ">Sidebar Collapsed
                                        Icon</span>
                                    <button type="button"
                                        class="w-8 h-8 rounded-full text-xs font-medium text-center text-white bg-primary hover:bg-blue-800"
                                        onclick="document.getElementById('collapsedlogo-input').click()">
                                        <i class="ri-upload-2-line"></i>
                                    </button>
                                    <input type="file" id="collapsedlogo-input" class="hidden" accept="image/*"
                                        onchange="previewLogo('collapsedlogo-input', 'collapsedlogo-preview', 'Sidebar Collapsed Icon')">
                                </div>
                                <div class="p-2 bg-neutral my-3 flex justify-center items-center">
                                    <h1 class="text-3xl text-orange font-bold py-3" id="collapsedlogo-preview">AMY</h1>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <!-- Primary Colors -->
                            <div
                                class="bg-white   p-4 rounded-sm mb-2 flex justify-between items-center md:flex-row flex-col">
                                <div>
                                    <h5 class="font-semibold text-black mb-2">Primary Color</h5>
                                    <p class="text-gray-500 mb-4">Choose a primary color for your
                                        application</p>
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
                            <div class="bg-white  p-4 mb-2 flex justify-between items-center md:flex-row flex-col">
                                <div>
                                    <h5 class="font-semibold text-black mb-2">Secondary Color</h5>
                                    <p class="text-gray-500 mb-4">Choose a secondary color for your
                                        application</p>
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
                            <div class="bg-white  p-4 rounded-sm  flex justify-between items-center md:flex-row flex-col">
                                <div>
                                    <h5 class="font-semibold text-black mb-2">Neutral Color</h5>
                                    <p class="text-gray-500 mb-4">Choose a neutral color for your
                                        application</p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-6 h-6  bg-neutral rounded-full cursor-pointer neutral-color-option"
                                        data-color="#F9FAFB"></div>
                                    <div class="w-6 h-6  bg-gray-200 rounded-full cursor-pointer neutral-color-option"
                                        data-color="#E5E7EB"></div>
                                    <div class="w-6 h-6  bg-gray-300 rounded-full cursor-pointer neutral-color-option"
                                        data-color="#D1D5DB"></div>
                                    <div class="w-6 h-6  bg-gray-400 rounded-full cursor-pointer neutral-color-option"
                                        data-color="#9CA3AF"></div>
                                    <div class="w-6 h-6  bg-gray-50 rounded-full cursor-pointer neutral-color-option"
                                        data-color="#F3F4F6"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between gap-2 md:flex-row flex-col p-2">
                            <div class="bg-white md:basis-1/4 w-full p-2 ">
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Sidebar Layout
                                    </span>
                                    <p class="text-gray-500  text-xs">Collapsed Sidebar</p>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white md:basis-1/4 w-full p-2 ">
                                <div class="bg-white    p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Dark Layout
                                    </span>
                                    <p class="text-gray-500 text-xs ">Dark Layout</p>
                                    <div class="py-4">
                                        <label
                                            class="relative inline-block h-7 w-[48px] cursor-pointer rounded-full bg-gray-200 transition [-webkit-tap-highlight-color:_transparent] has-[:checked]:bg-primary">
                                            <input type="checkbox" class="peer sr-only" />
                                            <span
                                                class="absolute inset-y-0 start-0 m-1 size-5 rounded-full ring-[5px] ring-inset ring-white transition-all peer-checked:start-7 bg-gray-200 peer-checked:w-2 peer-checked:bg-white peer-checked:ring-transparent"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white md:basis-1/4 w-full p-2 ">
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black ">RTL Layout
                                    </span>
                                    <p class="text-gray-500  text-xs">Enable RTL</p>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white md:basis-1/4 w-full p-2 ">
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Topbar Layout
                                    </span>
                                    <p class="text-gray-500  text-xs">Hide Topbar</p>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">
                            <div class="bg-white  md:basis-1/3 w-full p-2 ">
                                <div class="bg-white border-l-2  flex justify-between border-primary p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Font Family</span>
                                </div>
                                <div class="p-2 flex md:flex-row flex-col md:gap-5">
                                    <div class="md:basis-1/2 w-full py-2">
                                        <label for="" class="">Font Family</label>
                                        <select
                                            class=" my-2 border border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                            <option class="" selected>Choose font family</option>
                                            <option value="US">Nunito</option>
                                            <option value="CA">Sans Serif</option>
                                            <option value="FR">Popins</option>
                                        </select>
                                    </div>
                                    <div class="md:basis-1/2 w-full py-2">
                                        <label for="" class="">Font Size</label>
                                        <select
                                            class="  my-2 border border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                            <option class="" selected>Choose font Size</option>
                                            <option value="US">Small</option>
                                            <option value="CA">Medium</option>
                                            <option value="FR">Large</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end p-2">
                            <div class="flex gap-2">
                                <button type="button" id="reset-theme"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                    <i class="ri-refresh-line"></i>
                                    Reset
                                    Default
                                </button>
                                <button type="button" id="set-theme"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                    <i class="ri-save-2-line"></i>
                                    Save
                                    Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="system-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                    <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                        <span class="text-md font-semibold text-black ">System Settings</span>
                    </div>
                    <div class="p-3  ">
                        <form class=" w-full bg-white  p-3 ">
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5">
                                <div class="w-full">
                                    <label for="" class="">Default Language</label>
                                    <select
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                        <option class="" selected>Choose Date Format</option>
                                        <option value="US">English</option>
                                        <option value="CA">Arabic</option>
                                    </select>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Date Format</label>
                                    <select
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                        <option class="" selected>Choose Date Format</option>
                                        <option value="US">DD-MM-YYYY</option>
                                        <option value="CA">MM-DD-YYYY</option>
                                        <option value="FR">YYYY-MM-DD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-full">
                                    <label for="" class="">Time Format</label>
                                    <select
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                        <option class="" selected>Choose Time Format</option>
                                        <option value="US">12 Hours</option>
                                        <option value="CA">24 Hours</option>
                                    </select>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Default Timezone
                                    </label>
                                    <select
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                        <option class="" selected>Choose Timezone</option>
                                        <option value="US">MM-DD-YY</option>
                                        <option value="CA">Sans Serif</option>
                                        <option value="FR">Popins</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-full">
                                    <label for="" class="">Country Restrictions</label>
                                    <select
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                        <option class="" selected>Choose Country Restrictions </option>
                                        <option value="US">Allow All Country</option>
                                        <option value="CA">Denied All Country</option>
                                    </select>
                                </div>
                                <div class="w-full">
                                    <div class="w-full rounded-sm flex flex-col gap-3">
                                        <label for="" class="">Language Switcher
                                        </label>
                                        <div class="">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end p-2">
                                <div class="flex gap-2">
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                        <i class="ri-refresh-line"></i>
                                        Reset
                                        Default
                                    </button>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                        <i class="ri-save-2-line"></i>
                                        Save
                                        Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section id="company-settings" class="scroll-section  mb-16 border rounded-sm bg-neutral">
                    <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                        <span class="text-md font-semibold text-black ">Company Settings</span>
                    </div>
                    <div class="p-3 ">
                        <form class=" w-full bg-white p-3 ">
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5">
                                <div class="w-full">
                                    <label for="" class="">Company Name</label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Company Registration Number
                                    </label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Company Logo
                                    </label>
                                    <input type="file"
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full   ">
                                    </input>
                                </div>
                            </div>
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-full">
                                    <label for="" class="">Address</label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Country
                                    </label>
                                    <select
                                        class=" my-2 border border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                        <option class="" selected>Select Country</option>
                                        <option value="India">India</option>
                                        <option value="Pakistan">Pakistan</option>
                                        <option value="America">America</option>
                                    </select>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">City</label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                            </div>
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-full">
                                    <label for="" class="">State
                                    </label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Zip/Post Code</label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Contact Number
                                    </label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                            </div>
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-full">
                                    <label for="" class="">Company Email</label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Employee Email</label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Company Url</label>
                                    <input type="url"
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                            </div>
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-full">
                                    <label for="" class="">Origin Year</label>
                                    <input type="date"
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label class="">About Company</label>
                                    <input
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full   ">
                                    </input>
                                </div>
                            </div>

                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-1/2 bg-white border p-3 rounded-sm flex justify-between items-center">
                                    <label for="" class="">Tax Number</label>
                                    <div class="">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer" id="toggleSwitch"
                                                onchange="toggleVisibility()">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Section to Show/Hide -->
                                <div id="toggleSection" class="w-1/2 hidden mt-4">
                                    <div class="flex gap-5 items-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <input type="radio" class="cursor-pointer">
                                            <p>VAT Number</p>
                                        </div>
                                        <div class="flex justify-center items-center gap-3">
                                            <input type="radio" class="cursor-pointer">
                                            <p>GST Number</p>
                                        </div>
                                    </div>
                                    <div>
                                        <input
                                            class="border my-2 border-gray-300 text-gray-900 text-sm rounded-sm block w-full p-2.5">
                                    </div>
                                </div>
                            </div>
                            <br />
                            <hr />
                            <br />
                            <label>Contact Person Name</label>
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-full">
                                    <label for="" class="">Name</label>
                                    <input type="text"
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Contact Number</label>
                                    <input type="number"
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Email</label>
                                    <input type="email"
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full  p-2.5 ">
                                    </input>
                                </div>
                            </div>
                            <div class="flex justify-end p-2">
                                <div class="flex gap-2">
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                        <i class="ri-refresh-line"></i>
                                        Reset
                                        Default
                                    </button>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                        <i class="ri-save-2-line"></i>
                                        Save
                                        Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section id="currency-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                    <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                        <span class="text-md font-semibold text-black ">Currency Settings</span>
                    </div>
                    <div class="p-3 ">
                        <form class=" w-full bg-white p-3 ">
                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5">
                                <div class="w-full">
                                    <label for="" class="">Decimal Format</label>
                                    <select
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                        <option class="" selected>Choose Decimal Format</option>
                                        <option value="US">1</option>
                                        <option value="CA">1.0</option>
                                        <option value="CA">1.00</option>
                                        <option value="CA">1.000</option>
                                        <option value="CA">1.0000</option>
                                        <option value="CA">1.00000</option>
                                    </select>
                                </div>
                                <div class="w-full">
                                    <label for="" class="">Default Currency</label>
                                    <select
                                        class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                        <option class="" selected>Choose Default Currency</option>
                                        <option value="US">$USD</option>
                                        <option value="CA">UFN</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                <div class="w-full border rounded-sm p-2">
                                    <label for="" class="">Currency Symbol Space</label>
                                    <div class=" py-2 ">
                                        <div class="flex  items-center gap-3">
                                            <input type="radio" class="cursor-pointer">
                                            <p>With space</p>
                                        </div>
                                        <div class="flex  items-center gap-3">
                                            <input type="radio" class="cursor-pointer">
                                            <p>Without space</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full border rounded-sm p-2">
                                    <label for="" class="">Currency Symbol Position</label>
                                    <div class=" py-2 ">
                                        <div class="flex  items-center gap-3">
                                            <input type="radio" class="cursor-pointer">
                                            <p>Pre</p>
                                        </div>
                                        <div class="flex  items-center gap-3">
                                            <input type="radio" class="cursor-pointer">
                                            <p>Post</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full border rounded-sm p-2">
                                    <label for="" class="">Currency Symbol & Name </label>
                                    <div class=" py-2 ">
                                        <div class="flex  items-center gap-3">
                                            <input type="radio" class="cursor-pointer">
                                            <p>With Currency Symbol</p>
                                        </div>
                                        <div class="flex  items-center gap-3">
                                            <input type="radio" class="cursor-pointer">
                                            <p>With Currency Name</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end p-2">
                                <div class="flex gap-2">
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                        <i class="ri-refresh-line"></i>
                                        Reset
                                        Default
                                    </button>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                        <i class="ri-save-2-line"></i>
                                        Save
                                        Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section id="notification-settings" class="scroll-section mb-16  border rounded-sm bg-neutral">
                    <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                        <span class="text-md font-semibold text-black ">Notification Settings</span>
                    </div>
                    <div class="p-3 ">
                        <form class=" w-full bg-white p-3  ">
                            <div>
                                <div class="flex justify-between items-center my-2">
                                    <div class="w-full bg-white flex justify-between items-center">
                                        <label for="" class="">Mobile Push Notifications
                                        </label>
                                        <div class="">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center my-2">
                                    <div class="w-full bg-white flex justify-between items-center">
                                        <label for="" class="">Desktop Notifications
                                        </label>
                                        <div class="">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center my-2">
                                    <div class="w-full bg-white flex justify-between items-center">
                                        <label for="" class="">Email Notifications
                                        </label>
                                        <div class="">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center my-2">
                                    <div class="w-full bg-white flex justify-between items-center">
                                        <label for="" class="">Mobile Push Notifications
                                        </label>
                                        <div class="">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="">
                                <div class="overflow-x-auto  py-3">
                                    <table class="table-auto border-collapse w-full text-left">
                                        <thead>
                                            <tr>
                                                <th class="border-b p-4 font-medium text-black ">General
                                                    Notification</th>
                                                <th class="border-b p-4 font-medium text-black ">Push</th>
                                                <th class="border-b p-4 font-medium text-black ">SMS</th>
                                                <th class="border-b p-4 font-medium text-black ">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td class="border-b p-4 text-gray-800 ">Transaction</td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                            </tr>
                                            <tr>
                                                <td class="border-b p-4 text-gray-800 ">Email Verification
                                                </td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                            </tr>
                                            <tr>
                                                <td class="border-b p-4 text-gray-800 ">OTP</td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                            </tr>
                                            <tr>
                                                <td class="border-b p-4 text-gray-800 ">Activity</td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                            </tr>
                                            <tr>
                                                <td class="border-b p-4 text-gray-800 ">Account</td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                                <td class="border-b p-4"><input type="checkbox"
                                                        class="toggle-checkbox cursor-pointer"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="flex justify-end p-2">
                                <div class="flex gap-2">
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                        <i class="ri-refresh-line"></i>
                                        Reset
                                        Default
                                    </button>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                        <i class="ri-save-2-line"></i>
                                        Save
                                        Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section id="storage-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                    <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                        <span class="text-md font-semibold text-black ">Storage Settings</span>
                    </div>
                    <div class="p-2">
                        <div class="flex justify-between gap-2 md:flex-row flex-col p-2">
                            <div class="bg-white md:basis-1/2 w-full p-2 flex gap-5 ">
                                <div class="bg-surface rounded-md p-2 flex justify-center items-center">
                                    <img class="h-[40px] w-[80px] rounded-sm " src="{{ url('assets/profile-12.png') }}"
                                        alt="">
                                </div>
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black">Local Storage
                                    </span>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                        </label>
                                    </div>

                                </div>

                            </div>
                            <div class="bg-white md:basis-1/2 w-full p-2 flex gap-5">
                                <div class="bg-surface rounded-md p-2 flex justify-center items-center">
                                    <img class="h-[40px] w-[80px] rounded-sm " src="{{ url('assets/profile-12.png') }}"
                                        alt="">
                                </div>
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Session Storage
                                    </span>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" value="" class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex justify-end p-2">
                            <div class="flex gap-2">
                                <button type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                    <i class="ri-refresh-line"></i>
                                    Reset
                                    Default
                                </button>
                                <button type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                    <i class="ri-save-2-line"></i>
                                    Save
                                    Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="file-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                    <div class="bg-white  border-l-2 border-primary p-2 flex  items-center">
                        <span class="text-md font-semibold text-black ">File Settings</span>
                    </div>

                    <div class="p-2 ">
                        <form class=" w-full bg-white p-3 md:flex-row flex-col ">
                            <div class="flex gap-2">
                                <div class="w-full">
                                    <div class="overflow-x-auto  py-3">
                                        <table class="table-auto border-collapse w-full text-left">
                                            <thead>
                                                <tr>
                                                    <th class="border-b p-3 font-medium text-black ">File
                                                        Types</th>
                                                    <th class="border-b p-3 font-medium text-black">Allowed
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th class="p-2 font-medium text-gray-500 ">
                                                        JPEG <small>(.jpeg)</small>, JPG <small>(.jpg)</small>, PNG
                                                        <small>(.png)</small>
                                                    </th>
                                                    <td class="p-2 space-x-4">
                                                        <input type="checkbox" class="toggle-checkbox cursor-pointer">
                                                        <input type="checkbox" class="toggle-checkbox cursor-pointer">
                                                        <input type="checkbox" class="toggle-checkbox cursor-pointer">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="p-2 font-medium text-gray-500 ">
                                                        PDF <small>(.pdf)</small>, DOCS <small>(.docs)</small>, EXCEL
                                                        <small>(.excel)</small>
                                                    </th>
                                                    <td class="p-2 space-x-4">
                                                        <input type="checkbox" class="toggle-checkbox cursor-pointer">
                                                        <input type="checkbox" class="toggle-checkbox cursor-pointer">
                                                        <input type="checkbox" class="toggle-checkbox cursor-pointer">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="p-2 font-medium text-gray-500 ">
                                                        TXT <small>(.txt)</small>
                                                    </th>
                                                    <td class="p-2">
                                                        <input type="checkbox" class="toggle-checkbox cursor-pointer">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <div class="w-1/2">
                                <label for="" class="">Max File Size
                                </label>
                                <select
                                    class=" border my-2 border-gray-300 text-gray-900 text-sm rounded-sm  block w-full cursor-pointer p-2.5 ">
                                    <option class="" selected>Choose Max File Size
                                    </option>
                                    <option value="US">500 KB</option>
                                    <option value="CA">1 MB</option>
                                    <option value="CA">2 MB</option>
                                    <option value="CA">5 MB</option>
                                </select>
                            </div>
                        </form>
                        <div class="flex justify-end p-2">
                            <div class="flex gap-2">
                                <button type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                    <i class="ri-refresh-line"></i>
                                    Reset
                                    Default
                                </button>
                                <button type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                    <i class="ri-save-2-line"></i>
                                    Save
                                    Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="security-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                    <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                        <span class="text-md font-semibold text-black ">Security Settings</span>
                    </div>
                    <div class="p-3 ">
                        <form class=" w-full ">
                            <div class=" bg-white p-3 space-y-6">
                                <!-- Password Section -->
                                <div class="flex md:items-center justify-between max-sm:flex-col  border-b pb-4">
                                    <div class="flex items-center  space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="ri-lock-line"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Password</h3>
                                            <p class="text-sm text-gray-500">Last Changed 22 July 2023, 10:30 AM</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-green-500 rounded-sm hover:bg-green-800 ">
                                        <i class=" ri-lock-unlock-line"></i>
                                        Change Password
                                    </button>

                                </div>

                                <!-- Two Factor Section -->
                                <div class="flex md:items-center max-sm:flex-col justify-between border-b pb-4">
                                    <div class="flex items-center space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="ri-key-line"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Two Factor</h3>
                                            <p class="text-sm text-gray-500">Receive codes via SMS or email every time you
                                                login</p>
                                        </div>
                                    </div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <span class="mr-3 text-sm font-medium text-red-500">Disable</span>

                                        <div class="py-4">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                </div>
                                            </label>
                                        </div>
                                    </label>
                                </div>

                                <!-- Google Authentication Section -->
                                <div class="flex md:items-center max-sm:flex-col justify-between border-b pb-4">
                                    <div class="flex items-center space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="ri-google-line"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Google Authentication
                                            </h3>
                                            <p class="text-sm text-gray-500">Connect to Google</p>
                                        </div>
                                    </div>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <span class="mr-3 text-sm font-medium text-green-500">Connected</span>

                                        <div class="py-4">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer">
                                                <div
                                                    class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                </div>
                                            </label>
                                        </div>
                                    </label>
                                </div>
                                {{-- ------disable copy paste --}}
                                <div class="flex md:items-center max-sm:flex-col justify-between border-b pb-4">
                                    <div class="flex items-center space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class=" ri-bookmark-2-fill"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Copy/Paste</h3>
                                            <p class="text-sm text-gray-500">Disable Copy/Paste in your application</p>
                                        </div>
                                    </div>
                                    <div class="py-4 inline-flex items-center cursor-pointer">
                                        <span class="mr-3 text-sm font-medium text-red-500">Disable</span>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input id="toggle-copy-paste" type="checkbox" value=""
                                                class="sr-only peer">
                                            <div
                                                class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <!-- Phone Number Verification -->
                                <div class="flex md:items-center max-sm:flex-col  justify-between border-b pb-4">
                                    <div class="flex items-center space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="ri-phone-line"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Phone Number
                                                Verification</h3>
                                            <p class="text-sm text-gray-500">Verified Mobile Number: +81699799974</p>
                                        </div>
                                    </div>
                                    <div class="space-x-3 max-sm:my-3">
                                        <button type="button"
                                            class="px-3 py-2 text-xs font-medium text-center text-white bg-green-500 rounded-sm hover:bg-green-800  ">
                                            Verified <i class="  ri-checkbox-circle-line"></i>
                                        </button>
                                        <button type="button"
                                            class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                            <i class=" ri-exchange-line"></i>
                                            Change
                                        </button>
                                        <button class="text-red-500 font-medium">Remove</button>
                                    </div>
                                </div>

                                <!-- Email Verification -->
                                <div class="flex md:items-center max-sm:flex-col justify-between border-b pb-4">
                                    <div class="flex items-center space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Email Verification</h3>
                                            <p class="text-sm text-gray-500">Verified Email: info@example.com</p>
                                        </div>
                                    </div>
                                    <div class="space-x-3 max-sm:my-3">
                                        <button type="button"
                                            class="px-3 py-2 text-xs font-medium text-center text-white bg-green-500 rounded-sm hover:bg-green-800  ">
                                            Verified <i class="  ri-checkbox-circle-line"></i>
                                        </button>
                                        <button type="button"
                                            class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                            <i class=" ri-exchange-line"></i>
                                            Change
                                        </button>
                                        <button class="text-red-500 font-medium">Remove</button>
                                    </div>
                                </div>

                                <!-- Device Management -->
                                {{-- <div class="flex md:items-center max-sm:flex-col justify-between border-b pb-4">
                                    <div class="flex items-center space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="fas fa-cogs"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Device Management</h3>
                                            <p class="text-sm text-gray-500">Last Changed 22 July 2023, 10:30 AM</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                        <i class="ri-refresh-line"></i>
                                        Manage
                                    </button>
                                </div> --}}

                                <!-- Account Activity -->
                                {{-- <div class="flex md:items-center max-sm:flex-col justify-between border-b pb-4">
                                    <div class="flex items-center space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="fas fa-box"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Account Activity</h3>
                                            <p class="text-sm text-gray-500">Last Checked 25 July 2023, 11:00 AM</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                        <i class="ri-eye-line"></i>
                                        View
                                    </button>
                                </div> --}}

                                <!-- Deactivate Account -->
                                {{-- <div class="flex md:items-center max-sm:flex-col justify-between border-b pb-4">
                                    <div class="flex items-center space-x-3 max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="fas fa-user-slash"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Deactivate Account</h3>
                                            <p class="text-sm text-gray-500">Last Changed 21 July 2023, 09:37 AM</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                        <i class="fas fa-user-slash"></i>
                                        Deactivate
                                    </button>
                                </div> --}}

                                <!-- Delete Account -->
                                {{-- <div class="flex md:items-center max-sm:flex-col justify-between">
                                    <div class="flex items-center space-x-3  max-sm:my-3">
                                        <span class="text-primary bg-surface p-5 rounded-sm">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-medium text-gray-900 ">Delete Account</h3>
                                            <p class="text-sm text-gray-500">Last Changed 26 July 2023, 11:40 AM</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                        <i class=" ri-delete-bin-line"></i>
                                        Delete
                                    </button>
                                </div> --}}

                            </div>


                            <div class="flex justify-end p-2 my-2">
                                <div class="flex gap-2">
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-red-500 rounded-sm hover:bg-red-800 ">
                                        <i class="ri-refresh-line"></i>
                                        Reset
                                        Default
                                    </button>
                                    <button type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-primary rounded-sm hover:bg-blue-800 ">
                                        <i class="ri-save-2-line"></i>
                                        Save
                                        Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </div>
@endsection
<script>
    function toggleVisibility() {
        const toggleSwitch = document.getElementById('toggleSwitch');
        const toggleSection = document.getElementById('toggleSection');
        if (toggleSwitch.checked) {
            toggleSection.classList.remove('hidden');
        } else {
            toggleSection.classList.add('hidden');
        }
    }
</script>
