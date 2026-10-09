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
                    {{-- <button
                        class="sidebar-link  flex justify-between hover:bg-secondary hover:dark:text-black text-black rounded-sm text-start w-full hover:text-black hover-scale px-4 py-2"
                        data-target="mail-settings">
                        <span>Mail Settings</span>
                        <i class=" ri-arrow-drop-right-line"></i>
                    </button> --}}

                </nav>
            </aside>

            <form class=" w-full bg-white  p-3" action="{{ route('system-control-sidebar-store') }}" method="post" enctype="multipart/form-data">
            @csrf
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

                        </div>
                    </section>

                    <section id="system-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                        <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                            <span class="text-md font-semibold text-black ">System Settings</span>
                        </div>
                        <div class="p-3  ">

                        <div class="flex justify-between gap-2 md:flex-row flex-col p-2">

                            <div class="bg-white md:basis-1/4 w-full p-2 ">
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Enable Dark Mode Switch</span>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="dark_mode" @if($data?->dark_mode) checked @endif class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"> </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white md:basis-1/4 w-full p-2 ">
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Enable RTL</span>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="rtl" @if($data?->rtl) checked @endif class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"> </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white md:basis-1/4 w-full p-2 ">
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Allow Delivery Note Cancellation</span>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="grn_cancle_allow" @if($data?->grn_cancle_allow == "Yes") checked @endif class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"> </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white md:basis-1/4 w-full p-2 ">
                                <div class="bg-white   p-2   items-center">
                                    <span class="text-md font-semibold text-black ">Allow Invoice Cancellation</span>
                                    <div class="py-4">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="invoice_cancle_allow" @if($data?->invoice_cancle_allow == "Yes") checked @endif class="sr-only peer">
                                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"> </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="flex  justify-between gap-2 md:flex-row flex-col p-2">
                                <!-- Logo Light -->
                                <div class="bg-white   md:basis-1/3 w-full p-2">
                                    <div class="bg-white  border-l-2 flex justify-between border-primary p-2 items-center">
                                        <span class="text-md font-semibold text-black">Logo Light</span>
                                        <button type="button" class="w-8 h-8 rounded-full text-xs font-medium text-center text-white bg-primary hover:bg-blue-800"
                                            onclick="document.getElementById('lightlogo-input').click()">
                                            <i class="ri-upload-2-line"></i>
                                        </button>
                                        <input type="file" name="light_logo" id="lightlogo-input" class="hidden" accept="image/*" >
                                    </div>
                                    @if($data?->light_logo)
                                        <div class="p-2 bg-neutral my-3 flex justify-center items-center">
                                            <img id="lightlogo-preview" src="{{route('secure-documents', ['path' => $data?->light_logo])}}" class="w-[120px] py-3">
                                        </div>
                                    @endif
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
                                        <input type="file" name="small_logo" id="collapsedlogo-input" class="hidden" accept="image/*" >
                                    </div>
                                    @if($data?->small_logo)
                                        <div class="p-2 bg-neutral my-3 flex justify-center items-center">
                                            <img id="lightlogo-preview" src="{{route('secure-documents', ['path' => $data?->small_logo])}}" class="w-[120px] py-3">
                                        </div>
                                    @endif
                                </div>

                                <!-- Logo Dark -->
                                <div class="bg-white  md:basis-1/3 w-full p-2">
                                    <div class="bg-white border-l-2   flex justify-between border-primary p-2 items-center">
                                        <span class="text-md font-semibold text-black ">Brand Name</span>
                                    </div>
                                    <div class="p-2 bg-primary my-3 flex justify-center items-center">
                                        <input type="text" class="form-input flex-1" name="brand_name" value="{{ $data?->brand_name }}">
                                    </div>
                                </div>
                            </div>


                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5">
                                    <div class="w-full">
                                        <label for="" class="">Default Language</label>
                                        <select class="form-input" name="def_language">
                                            <option value="EN" @if($data?->def_language == "EN") selected @endif>English</option>
                                            <option value="AR" @if($data?->def_language == "AR") selected @endif>Arabic</option>
                                        </select>
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">Date Format</label>
                                        <select class="form-input" name="date_format">
                                            <option class="" value="">Choose Date Format</option>
                                            @foreach($all_date_formates as $frmt)
                                                <option value="{{$frmt['format']}}" @if($frmt['format'] == $data?->date_format) selected @endif>{{$frmt['format_view']}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                    <div class="w-full">
                                        <label for="" class="">Time Format</label>
                                        <select class="form-input" name="time_format">
                                            <option value="12" @if($data?->time_format == "12") selected @endif>12 Hours</option>
                                            <option value="24" @if($data?->time_format == "24") selected @endif>24 Hours</option>
                                        </select>
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">Default Timezone </label>
                                        <select class="form-input" name="time_zone">
                                            <option class="" value="">Choose Timezone</option>
                                            @foreach($time_zone as $zns)
                                                <option value="{{$zns['value']}}" @if($zns['value'] == $data?->time_zone) selected @endif>{{$zns['value']}} {{$zns['zone']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                    <div class="w-full">
                                        <label for="" class="">Workflow Type</label>
                                        <select class="form-input" name="workflow_type">
                                            <option value="Consolidated" @if($data?->workflow_type == "Consolidated") selected @endif>Consolidated</option>
                                            <option value="Company Wise" @if($data?->workflow_type == "Company Wise") selected @endif>Company Wise</option>
                                        </select>
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">Number Sequence Type</label>
                                        <select class="form-input" name="numbersequence_type">
                                            <option value="Consolidated" @if($data?->numbersequence_type == "Consolidated") selected @endif>Consolidated</option>
                                            <option value="Company Wise" @if($data?->numbersequence_type == "Company Wise") selected @endif>Company Wise</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                    <div class="w-full">
                                        <label for="" class="">Default Company</label>
                                        <select class="form-input" name="default_company">
                                            <option class="" value="">Select Company</option>
                                            @foreach($companies as $zns)
                                                <option value="{{$zns->id}}" @if($zns?->id == $data?->default_company) selected @endif>{{$zns?->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                    <div class="w-full">
                                        <label for="" class="">Blocked User Auto Release Time (in Minutes)</label>
                                         <input type="number" class="form-input flex-1" name="auto_release_time" value="{{ $data?->auto_release_time }}">
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">Auto Password Expiry (In Days)</label>
                                         <input type="number" class="form-input flex-1" name="password_expiry_days" value="{{ $data?->password_expiry_days }}">
                                    </div>
                                </div>


                                <div class="flex justify-end p-2">
                                    <div class="flex gap-2">
                                        <button type="submit" class="btn-primary-sm "> <i class="ri-save-2-line"></i> Save Changes </button>
                                    </div>
                                </div>

                        </div>
                    </section>

                    <section id="currency-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                        <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                            <span class="text-md font-semibold text-black ">Currency Settings</span>
                        </div>
                        <div class="p-3 ">
                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5">
                                    <div class="w-full">
                                        <label for="" class="">Default Currency</label>
                                        <select class="form-input" name="default_currency_id">
                                            <option class="" value="">Choose Default Currency</option>
                                            @foreach($currencies as $curr)
                                                <option value="{{$curr?->id}}" @if($curr?->id == $data?->default_currency_id) selected @endif>{{$curr?->code}} - {{$curr?->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">Currency Symbol</label>
                                        <select class="form-input" name="currency_symbol">
                                            <option class="code" @if($data?->currency_symbol == "code") selected @endif>Code</option>
                                            <option class="name" @if($data?->currency_symbol == "name") selected @endif>Name</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="flex justify-end p-2">
                                    <div class="flex gap-2">
                                        <button type="submit" class="btn-primary-sm "> <i class="ri-save-2-line"></i> Save Changes </button>
                                    </div>
                                </div>

                        </div>
                    </section>

                    <section id="notification-settings" class="scroll-section mb-16  border rounded-sm bg-neutral">
                        <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                            <span class="text-md font-semibold text-black ">Notification Settings</span>
                        </div>
                        <div class="p-3 ">
                                <div>

                                    <div class="flex justify-between items-center my-2">
                                        <div class="w-full bg-white flex justify-between items-center">
                                            <label for="" class="">System Notifications </label>
                                            <div class="">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="enable_system_notification" @if($data?->enable_system_notification) checked @endif class="sr-only peer">
                                                    <div
                                                        class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center my-2">
                                        <div class="w-full bg-white flex justify-between items-center">
                                            <label for="" class="">Email Notifications </label>
                                            <div class="">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="enable_email_notification" @if($data?->enable_email_notification) checked @endif class="sr-only peer">
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
                                        <button type="submit" class="btn-primary-sm "> <i class="ri-save-2-line"></i> Save Changes </button>
                                    </div>
                                </div>

                        </div>
                    </section>

                    <section id="file-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                        <div class="bg-white  border-l-2 border-primary p-2 flex  items-center">
                            <span class="text-md font-semibold text-black ">File Settings</span>
                        </div>

                        <div class="p-2 ">
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
                                                            JPEG <small>(.jpeg)</small>, JPG <small>(.jpg)</small>, PNG <small>(.png)</small>
                                                        </th>
                                                        <td class="p-2 space-x-4">
                                                            <input type="checkbox" name="doc_format[]" @if(in_array('jpeg',$doc_formate)) checked @endif value="jpeg" class="toggle-checkbox cursor-pointer rounded-sm">
                                                            <input type="checkbox" name="doc_format[]" @if(in_array('jpg',$doc_formate)) checked @endif value="jpg" class="toggle-checkbox cursor-pointer rounded-sm">
                                                            <input type="checkbox" name="doc_format[]" @if(in_array('png',$doc_formate)) checked @endif value="png" class="toggle-checkbox cursor-pointer rounded-sm">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="p-2 font-medium text-gray-500 ">
                                                            PDF <small>(.pdf)</small>, DOCS <small>(.docx , .doc)</small>, EXCEL <small>(.xlsx , .xls)</small>
                                                        </th>
                                                        <td class="p-2 space-x-4">
                                                            <input type="checkbox" name="doc_format[]" @if(in_array('pdf',$doc_formate)) checked @endif value="pdf" class="toggle-checkbox cursor-pointer rounded-sm">
                                                            <input type="checkbox" name="doc_format[]" @if(in_array('doc',$doc_formate)) checked @endif value="docx,doc" class="toggle-checkbox cursor-pointer rounded-sm">
                                                            <input type="checkbox" name="doc_format[]" @if(in_array('xls',$doc_formate)) checked @endif value="xlsx,xls" class="toggle-checkbox cursor-pointer rounded-sm">
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                                <div class="w-1/2">
                                    <label for="" class="">Max File Size </label>
                                    <select class="form-input" name="doc_max_size">
                                        <option class="" value="">Choose Max File Size </option>
                                        <option value="0.5" @if($data?->doc_max_size == "0.5") selected @endif>500 KB</option>
                                        <option value="1" @if($data?->doc_max_size == "1") selected @endif>1 MB</option>
                                        <option value="2" @if($data?->doc_max_size == "2") selected @endif>2 MB</option>
                                        <option value="3" @if($data?->doc_max_size == "3") selected @endif>3 MB</option>
                                        <option value="4" @if($data?->doc_max_size == "4") selected @endif>4 MB</option>
                                        <option value="5" @if($data?->doc_max_size == "5") selected @endif>5 MB</option>
                                    </select>
                                </div>

                            <div class="flex justify-end p-2">
                                <div class="flex gap-2">
                                    <button type="submit" class="btn-primary-sm "> <i class="ri-save-2-line"></i> Save Changes </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="security-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                        <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                            <span class="text-md font-semibold text-black ">Security Settings</span>
                        </div>
                        <div class="p-3 ">
                                <div class=" bg-white p-3 space-y-6">


                                    <!-- Two Factor Section -->
                                    <div class="flex md:items-center max-sm:flex-col justify-between border-b pb-4">
                                        <div class="flex items-center space-x-3 max-sm:my-3">
                                            <span class="text-primary bg-surface p-5 rounded-sm">
                                                <i class="ri-key-line"></i>
                                            </span>
                                            <div>
                                                <h3 class="font-medium text-gray-900 ">Two Factor</h3>
                                                <p class="text-sm text-gray-500">Receive codes via SMS or email every time you login</p>
                                            </div>
                                        </div>
                                        <label class="inline-flex items-center cursor-pointer">
                                            <div class="py-4">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" value="Yes" name="two_fa_enable_employee" @if($data?->two_fa_enable_employee == "Yes") checked @endif class="sr-only peer">
                                                    <div class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"> </div>
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
                                                <h3 class="font-medium text-gray-900 ">Google Authentication </h3>
                                                <p class="text-sm text-gray-500">Connect to Google</p>
                                            </div>
                                        </div>
                                        <label class="inline-flex items-center cursor-pointer">

                                            <div class="py-4">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="enable_google_auth" value="Yes" @if($data?->enable_google_auth == "Yes") checked @endif class="sr-only peer">
                                                    <div class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"> </div>
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
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="enable_copy_paste" value="Yes" @if($data?->enable_copy_paste == "Yes") checked @endif class="sr-only peer">
                                                <div class="relative w-11 h-6 bg-gray-200  rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"> </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end p-2 my-2">
                                    <div class="flex gap-2">
                                        <button type="submit" class="btn-primary-sm "> <i class="ri-save-2-line"></i> Save Changes </button>
                                    </div>
                                </div>

                        </div>
                    </section>

                    <section id="mail-settings" class="scroll-section  mb-16  border rounded-sm bg-neutral">
                        <div class="bg-white border-l-2 border-primary p-2 flex  items-center">
                            <span class="text-md font-semibold text-black ">Mail Settings</span>
                        </div>
                        <div class="p-3  ">

                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                    <div class="w-full">
                                        <label for="" class="">Mailer</label>
                                         <input type="text" class="form-input flex-1" name="mail_mailer" value="{{ $data?->mail_mailer }}">
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">Host</label>
                                         <input type="text" class="form-input flex-1" name="mail_host" value="{{ $data?->mail_host }}">
                                    </div>
                                </div>

                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                    <div class="w-full">
                                        <label for="" class="">Port</label>
                                         <input type="text" class="form-input flex-1" name="mail_port" value="{{ $data?->mail_port }}">
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">User Name</label>
                                         <input type="text" class="form-input flex-1" name="mail_user_name" value="{{ $data?->mail_user_name }}">
                                    </div>
                                </div>

                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                    <div class="w-full">
                                        <label for="" class="">Password</label>
                                         <input type="text" class="form-input flex-1" name="mail_password" value="{{ $data?->mail_password }}">
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">Encryption</label>
                                         <input type="text" class="form-input flex-1" name="mail_encryption" value="{{ $data?->mail_encryption }}">
                                    </div>
                                </div>

                                <div class="flex  justify-between md:flex-row flex-col w-full gap-5 mt-5">
                                    <div class="w-full">
                                        <label for="" class="">From Name</label>
                                         <input type="text" class="form-input flex-1" name="mail_from_name" value="{{ $data?->mail_from_name }}">
                                    </div>
                                    <div class="w-full">
                                        <label for="" class="">From Address</label>
                                         <input type="text" class="form-input flex-1" name="mail_from_address" value="{{ $data?->mail_from_address }}">
                                    </div>
                                </div>


                                <div class="flex justify-end p-2">
                                    <div class="flex gap-2">
                                        <button type="submit" class="btn-primary-sm "> <i class="ri-save-2-line"></i> Save Changes </button>
                                    </div>
                                </div>

                        </div>
                    </section>

                </main>
            </form>
        </div>
    </div>
    @include('pages.settings.interfaceMaster.modal')
@endsection
@section('scripts')
    @include('layouts.select2js')
    @include('layouts.modal_js')


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sidebarLinks = document.querySelectorAll(".sidebar-link");
            const sections = document.querySelectorAll(".scroll-section");
            const mainContent = document.querySelector("main");
            sidebarLinks[0].classList.add(
                "bg-secondary",
                "border-l-2",
                "border-primary",
                "dark:bg-red-500"
            );
            sidebarLinks.forEach((link) => {
                link.addEventListener("click", (e) => {
                    e.preventDefault();
                    const targetId = link.getAttribute("data-target");
                    const targetSection = document.getElementById(targetId);
                    targetSection.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                    sidebarLinks.forEach((link) =>
                        link.classList.remove(
                            "bg-secondary",
                            "border-l-2",
                            "border-primary",
                            "dark:bg-red-500"
                        )
                    );
                    link.classList.add(
                        "bg-secondary",
                        "dark:bg-red-500",
                        "border-l-2",
                        "border-primary"
                    );
                });
            });

            mainContent.addEventListener("scroll", () => {
                let currentSectionId = "";
                let maxVisibility = 0;
                sections.forEach((section) => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    const sectionVisible = Math.max(
                        0,
                        Math.min(
                            sectionTop + sectionHeight,
                            mainContent.scrollTop + mainContent.clientHeight
                        ) - Math.max(sectionTop, mainContent.scrollTop)
                    );
                    const visibilityPercentage = (sectionVisible / sectionHeight) * 100;
                    if (visibilityPercentage > maxVisibility) {
                        maxVisibility = visibilityPercentage;
                        currentSectionId = section.getAttribute("id");
                    }
                });
                if (maxVisibility > 50) {
                    sidebarLinks.forEach((link) => {
                        link.classList.remove(
                            "bg-secondary",
                            "border-l-2",
                            "border-primary",
                            "dark:bg-red-500"
                        );
                        if (link.getAttribute("data-target") === currentSectionId) {
                            link.classList.add(
                                "bg-secondary",
                                "border-l-2",
                                "border-primary",
                                "dark:bg-red-500"
                            );
                        }
                    });
                }
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const settingsidebar = document.getElementById("settingsidebar");
            const mainContent = document.getElementById("mainContent");
            const Settingsidebartoggler = document.getElementById(
                "Settingsidebartoggler"
            );
            const sidebarTogglerIcon = document.getElementById("sidebar-toggler-icon");
            let sidebarVisible = window.innerWidth >= 1024;
            const handleResize = () => {
                if (window.innerWidth >= 1024) {
                    sidebarVisible = true;
                    settingsidebar.classList.remove("hidden");
                    mainContent.classList.remove("full-width");
                    sidebarTogglerIcon.classList.remove("ri-arrow-right-s-line");
                    sidebarTogglerIcon.classList.add("ri-arrow-left-s-line");
                } else {
                    sidebarVisible = false;
                    settingsidebar.classList.add("hidden");
                    mainContent.classList.add("full-width");
                    sidebarTogglerIcon.classList.remove("ri-arrow-left-s-line");
                    sidebarTogglerIcon.classList.add("ri-arrow-right-s-line");
                }
            };
            handleResize();
            window.addEventListener("resize", handleResize);
            Settingsidebartoggler.addEventListener("click", () => {
                sidebarVisible = !sidebarVisible;
                if (sidebarVisible) {
                    settingsidebar.classList.remove("hidden");
                    mainContent.classList.remove("full-width");
                    sidebarTogglerIcon.classList.remove("ri-arrow-right-s-line");
                    sidebarTogglerIcon.classList.add("ri-arrow-left-s-line");
                } else {
                    settingsidebar.classList.add("hidden");
                    mainContent.classList.add("full-width");
                    sidebarTogglerIcon.classList.remove("ri-arrow-left-s-line");
                    sidebarTogglerIcon.classList.add("ri-arrow-right-s-line");
                }
            });
        });

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
@endsection
