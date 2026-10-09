@extends('layouts.master')
@section('Page_content')
    <div class="flex min-h-screen items-center justify-center bg-gray-100 dark:bg-gray-800 p-2">

        <div
            class="relative flex flex-col md:flex-row bg-white dark:bg-gray-700 shadow-sm rounded-lg overflow-hidden max-w-5xl w-full">

            <div class="relative hidden md:flex md:w-1/2 items-center justify-center p-4 bg-[#E6F0FF] dark:bg-gray-800">
                {{-- <img src="{{ overall_logo() }}" class="w-32 absolute top-4 left-4" /> --}}

                <img class="max-w-full h-auto" src="{{ asset('assets/image/logo/Scholaracad.svg') }}" alt="Login Image Here..">

            </div>
            <div class="w-full md:w-1/2 p-8 relative my-5">

                <img class="max-w-full h-auto text-center" src="{{ asset('assets/image/logo/scholaracad_logo.svg') }}"
                    alt="Login Image Here.." style="width:320px;margin: auto;">
                <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center">{{ __('common.Sign In') }}</h1>
                <p class="text-gray-500 dark:text-gray-300 text-center mb-4">{{ __('common.Access message') }}</p>

                <form id="loginForm" method="POST" class="" action="{{ route('login-check') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="input-email" class="block text-sm mb-2 font-medium text-gray-700 dark:text-gray-300">
                            {{ __('common.email') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="ri-mail-line dark:text-white"></i>
                            </div>
                            <input type="email" name="email" id="input-email"
                                class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="{{ __('common.email') }}" required />
                        </div>
                        <span id="email-error"
                            class="text-red-500 text-sm mt-1 hidden">Invalid Email Id</span>
                    </div>

                    <div class="mb-4">
                        <label for="input-password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('common.password') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="ri-lock-line dark:text-white"></i>
                            </div>
                            <input type="password" name="password" id="input-password" maxlength="15"
                                class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="{{ __('common.password') }}" required />
                            <button type="button" id="toggle-password"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 dark:text-white">
                                <i class="ri-eye-line"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-4">
                        <div class="text-end">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="forget-position text-sm text-blue-600 hover:underline">
                                    Forgot Password?
                                </a>
                            @endif

                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <button type="submit"
                        class="w-full bg-primary text-white py-2 rounded-md hover:bg-blue-700 transition">
                        {{ __('common.Sign In') }}
                    </button>

                </form>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('input-email').addEventListener('blur', function() {
            const email = this.value.trim();
            const errorSpan = document.getElementById('email-error');
            const emailPattern = /^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailPattern.test(email)) {
                errorSpan.classList.remove('hidden');
            } else {
                errorSpan.classList.add('hidden');
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const togglePassword = document.getElementById("toggle-password");
            const passwordInput = document.getElementById("input-password");

            togglePassword.addEventListener("click", () => {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    togglePassword.innerHTML = '<i class="ri-eye-off-line"></i>';
                } else {
                    passwordInput.type = "password";
                    togglePassword.innerHTML = '<i class="ri-eye-line"></i>';
                }
            });
        });
    </script>
    @yield('scripts')
    <script>
        // function saveDirection(direction) {
        //     localStorage.setItem("direction", direction);
        //     location.reload();
        // }


        // route 'language-switch'


        function language_switch() {
            var language = document.getElementById("language").value;
            var langs = "";
            if (language === "EN") {
                //saveDirection("ltr");
                langs = "en";
            } else if (language === "AR") {
                //saveDirection("rtl");
                langs = "ar";
            }
            document.documentElement.setAttribute("lang", langs);
            var url = "{{ route('language-switch') }}";
            window.location.href = url + "?lang=" + langs;
        }

        const laravelTimezone = @json(config('app.timezone'));
    </script>
@endsection
