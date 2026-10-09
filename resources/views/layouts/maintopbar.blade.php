@php
    // $notifications = App\Models\Notifications::get();
    // $test = App\Http\Controllers\NotificationController::index();
    $cmps = user_company_permission();

    $user = App\Helpers\CommonHelper::leftbar_detaild();
    $type = $user[0];
    $name = $user[1];
    $img = $user[2];
    $company_name = $user[3];
    $short_name = $user[4];
    $email = $user[5];

    $secondLastLogin = Modules\Loginer\Models\LoginHistory::where('user_id', Auth::user()?->id)
        ->orderByDesc('id')
        ->offset(1)
        ->first();
@endphp

<style>
    :disabled {
        background-color: transparent;
        color: #6b7280;
        cursor: not-allowed;
    }

    :is([dir=rtl]) select:not([size]) {
        background-position: left 0.75rem center;
        padding-right: 1.75rem;
        padding-left: 37px;
    }
</style>
<div id="Topbar"
    class="bg-primary  z-50 border-b   flex justify-between  p-2 transition-all duration-500 ease-in-out transform opacity-100 translate-y-0">

    <!-- Sidebar toggler button for desktop -->
    <button title="Toggle Sidebar" id="sidebar-toggle-desktop" class="text-black  hidden lg:block">
        <div class=" text-white text-2xl  p-1 rounded-full ">
            <i class="ri-bar-chart-horizontal-line"></i>
        </div>
    </button>
    <!-- Sidebar toggler button for mobile -->
    <button title="Toggle Sidebar" id="sidebar-toggle-mobile" class="text-black  block lg:hidden">
        <div class="text-white text-2xl ">
            <i class="ri-bar-chart-horizontal-line"></i>
        </div>
    </button>
    <div class="flex justify-end items-center gap-2  w-full text-sm">
        <div class="flex justify-center gap-2  custom-topbar-content">
            <div class="flex items-center space-x-2">
                <span class="text-orange font-bold">{{ __('common.Current Time') }} :</span>
                <span id="current-time" class="text-white"></span>
            </div>
            <div class="border-[1px]"></div>
            <div class="text-sm">
                <span class="text-orange font-bold">{{ __('common.Last Login') }} :</span> <span class="text-white">
                    {{ $secondLastLogin?->login_time ? \Carbon\Carbon::parse($secondLastLogin->login_time)->format('M d, Y, h:i:s A') : 'No previous login' }}</span>
            </div>
        </div>


        <!-- Multi Option dropdown -->
        <div class="relative" style="display:none">
            <button title="More Options" data-dropdown-button
                class="text-black bg-green-100 hover:bg-green-300 h-8 w-8 rounded-md flex items-center justify-center gap-2">
                <i class="ri-apps-2-line"></i>
            </button>
            <div data-dropdown-content
                class=" absolute hidden mt-5 w-auto min-w-96 bg-white border rounded-md  z-50 animate-fade-in">
                <div
                    class="max-h-[300px] overflow-y-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3  py-1 px-1 gap-2">
                    <a href="/settings/general-settings">
                        <div title="Settings"
                            class="flex flex-col items-center text-center hover:bg-gray-50 cursor-pointer rounded-md p-1">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex justify-center items-center">
                                <i class="ri-settings-3-fill text-xl"></i>
                            </div>
                            <span class="mt-2">Settings</span>
                            <span></span>
                        </div>
                    </a>
                    <div title="Appearence Settings" data-drawer="Appearence_Settings"
                        class="open-drawer-btn flex flex-col items-center text-center hover:bg-gray-50 cursor-pointer rounded-md p-1">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex justify-center items-center">
                            <i class=" ri-palette-fill text-xl"></i>
                        </div>
                        <span class="mt-2">Appearence Settings</span>
                    </div>

                    <div title="Full Screen" id="fullscreen-toggle"
                        class="flex flex-col items-center text-center hover:bg-gray-50 cursor-pointer rounded-md p-1">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex justify-center items-center">
                            <i class="ri-fullscreen-fill text-xl"></i>
                        </div>
                        <span class="mt-2">Full Screen</span>
                    </div>
                    <a href="{{ url('/settings/profile') }}">
                        <div title="Profile"
                            class="flex flex-col items-center text-center hover:bg-gray-50 cursor-pointer rounded-md p-1">
                            <div class="w-10 h-10 rounded-full bg-red-100 flex justify-center items-center">
                                <i class="ri-user-3-line text-xl"></i>
                            </div>
                            <span class="mt-2">Profile</span>
                        </div>
                    </a>
                    <div title="Log Out"
                        class="flex flex-col items-center text-center hover:bg-gray-50 cursor-pointer rounded-md p-1">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"> {{-- {{ route('logout') }} --}}
                            @csrf
                            <a href="javascript:void();" class=""
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex justify-center items-center">
                                    <i class="ri-logout-box-line text-xl"></i>
                                </div>
                                <span class="mt-2">{{ __('common.logout') }}
                                </span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative inline-block" style="display:none">
            <button data-dropdown-button title="Notification"
                class=" bg-purple-100 hover:bg-purple-300 h-8 w-8 rounded-md text-black  font-medium flex justify-center items-center "
                type="button">
                <i class="ri-notification-2-line relative"></i>
                <span class="w-[6px] h-[6px] rounded-full bg-orange absolute top-0 right-0"></span>
            </button>
            <div data-dropdown-content
                class="absolute hidden mt-5 w-auto min-w-[300px] bg-white border rounded-md  z-50 animate-fade-in">
                <div class="bg-primary p-2 flex items-center justify-between rounded-sm ">
                    <p class="text-white font-bold flex items-center gap-2"><i class="ri-notification-3-line"></i>
                        Notifications</p>
                    <span class="bg-secondary  p-1 rounded-full text-xs">2+</span>
                </div>
                <div class="divide-y divide-gray-100 max-h-[200px] overflow-y-auto">
                    {{-- <p class="text-center p-3 text-red-500 font-semibold"> No Notifications </p> --}}
                    <a href="#" class="flex px-4 py-3 hover:bg-gray-100">
                        <div class="flex-shrink-0">
                            <div class="rounded-full w-8 h-8 bg-red-100"></div>
                        </div>
                        <div class="w-full ps-3">
                            <p class="text-gray-500 text-sm mb-1.5">
                                Notification 1
                            </p>
                            <div class="text-xs text-primary">44 minutes ago</div>
                        </div>
                    </a>
                    <a title="View" href="#" class="flex px-4 py-3 hover:bg-gray-100">
                        <div class="flex-shrink-0">
                            <div class="rounded-full w-8 h-8 bg-green-100"></div>
                        </div>
                        <div class="w-full ps-3">
                            <p class="text-gray-500 text-sm mb-1.5">
                                Notification 2
                            </p>
                            <div class="text-xs text-primary">44 minutes ago</div>
                        </div>
                    </a>
                    <a href="#" class="flex px-4 py-3 hover:bg-gray-100">
                        <div class="flex-shrink-0">
                            <div class="rounded-full w-8 h-8 bg-green-100"></div>
                        </div>
                        <div class="w-full ps-3">
                            <p class="text-gray-500 text-sm mb-1.5">
                                Notification 2
                            </p>
                            <div class="text-xs text-primary">44 minutes ago</div>
                        </div>
                    </a>
                    <a href="#" class="flex px-4 py-3 hover:bg-gray-100">
                        <div class="flex-shrink-0">
                            <div class="rounded-full w-8 h-8 bg-green-100"></div>
                        </div>
                        <div class="w-full ps-3">
                            <p class="text-gray-500 text-sm mb-1.5">
                                Notification 2
                            </p>
                            <div class="text-xs text-primary">44 minutes ago</div>
                        </div>
                    </a>
                    <a href="#" class="flex px-4 py-3 hover:bg-gray-100">
                        <div class="flex-shrink-0">
                            <div class="rounded-full w-8 h-8 bg-green-100"></div>
                        </div>
                        <div class="w-full ps-3">
                            <p class="text-gray-500 text-sm mb-1.5">
                                Notification 2
                            </p>
                            <div class="text-xs text-primary">44 minutes ago</div>
                        </div>
                    </a>
                </div>
                <a href="#"
                    class="flex items-center justify-center  rounded-sm py-2 text-sm font-medium text-center text-white rounded-b-sm bg-primary ">
                    <div class="flex items-center gap-1 text-white hover:text-secondary hover:underline">
                        View all <i class="ri-eye-line mt-1"></i>
                    </div>
                </a>
            </div>
        </div>
        <div class="relative inline-block">
            <button data-dropdown-button
                class="flex bg-blue-100 hover:bg-blue-300 h-8 w-8 rounded-md items-center   justify-center text-sm  font-medium text-gray-900  hover:text-primary md:me-0  @if (!$img) first-letter: p-2 @endif"
                type="button">
                @if ($img)
                    <img src="{{ route('secure-documents', ['path' => $img]) }}"alt="a"
                        class="bg-blue-200 h-8 w-8 rounded-md border">
                @else
                    <span class="text-md font-semibold text-primary">
                        {{ $short_name }}
                    </span>
                @endif
            </button>
            <!-- Dropdown menu -->
            <div data-dropdown-content
                class="absolute hidden mt-3 w-auto min-w-[200px] bg-white border rounded-md  z-50 animate-fade-in">
                <div class="px-4 py-3 text-sm text-gray-900">
                    <div class="font-medium">{{ $name }}</div>
                    <div class="truncate ">{{ $email }}</div>
                </div>
                <hr>
                <ul class=" text-sm text-gray-700">
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <a href="javascript:void();" class="flex gap-2 px-4 py-2 hover:bg-gray-100"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <div
                                    class="h-6 w-6 rounded-full bg-[#edecf3]  text-primary    flex justify-center items-center">
                                    <i class="ri-logout-box-line"></i>
                                </div>
                                {{ __('common.logout') }}
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    function saveDirection(direction) {
        localStorage.setItem("direction", direction);
        location.reload();
    }


    // route 'language-switch'


    function language_switch() {
        var language = document.getElementById("language").value;
        var langs = "";
        if (language === "EN") {
            saveDirection("ltr");
            langs = "en";
        } else if (language === "AR") {
            saveDirection("rtl");
            langs = "ar";
        }
        document.documentElement.setAttribute("lang", langs);
        var url = "{{ route('language-switch') }}";
        window.location.href = url + "?lang=" + langs;
    }

    const laravelTimezone = @json(config('app.timezone'));

    function change_company() {
        var company_id = document.getElementById("company_id").value;
        showLoader();
        $.ajax({
            type: "POST",
            url: "{{ route('switch-company') }}",
            data: {
                _token: '{{ csrf_token() }}',
                company_id: company_id
            },
            cache: false,
            success: function(response) {
                console.log(response);
                if (response.status) {
                    window.location.href = "{{ route('login') }}";
                } else {
                    toastr.error(response.error);
                    // location.reload();
                }
            }
        });
    }


    function updateMuscatTime() {
        const now = new Date();
        const options = {
            timeZone: laravelTimezone,
            hour12: true,
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric',
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        };

        const formatter = new Intl.DateTimeFormat([], options);
        document.getElementById('current-time').textContent = formatter.format(now);
    }
    setInterval(updateMuscatTime, 1000);
    updateMuscatTime();
</script>
