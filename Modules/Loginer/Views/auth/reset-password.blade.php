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
                @if (Session::has('error'))
                    <script type="text/javascript">
                        Swal.fire('{{ Session::get('error') }}');
                    </script>
                @endif
                <div class="card">
                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <h5>Reset Account Password !</h5>
                            <p class="text-muted">Enter a new password to continue with Scholaracad .</p>
                        </div>
                        <div class="p-2 mt-4">
                            <form method="POST" action="{{ route('password.store') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ request()->route('token') }}">
                                <div class="mb-3">
                                    <input type="hidden" name="email"
                                        class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        value="{{ old('email', request('email')) }}" readonly>
                                    <div class="position-relative input-custom-icon" hidden>
                                        <label for="input-email"
                                            class="block text-sm mb-2 font-medium text-gray-700 dark:text-gray-300">
                                            {{ __('common.email') }} <span class="text-red-500">*</span>
                                        </label>

                                        {{--  <input id="email"
                                            class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                            type="hidden" name="email" :value="{{ old('email', request('email')) }}"
                                            placeholder="Enter email" autofocus autocomplete="username" readonly />  --}}

                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="position-relative input-custom-icon">
                                        <label for="password" :value="__('Password')">
                                            <input id="password"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                                type="password" name="password" required autocomplete="new-password"
                                                placeholder="Enter Password" />

                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="position-relative input-custom-icon">
                                        <label for="password_confirmation" :value="__('Confirm Password')">
                                            <input id="password_confirmation"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                                type="password" name="password_confirmation" required
                                                autocomplete="new-password" placeholder="Confirm Password" />
                                            <span class="bx bx-lock mt-3"></span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-primary w-100 myButton" type="submit">Reset
                                        Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    @yield('scripts')
@endsection
