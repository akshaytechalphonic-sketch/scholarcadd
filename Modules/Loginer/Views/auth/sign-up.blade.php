@extends('layouts.master')
@section('Page_content')
    <div class="flex min-h-screen items-center justify-center bg-gray-100 dark:bg-gray-800 p-2">
        <div
            class="relative flex flex-col md:flex-row bg-white dark:bg-gray-700 shadow-sm rounded-lg overflow-hidden max-w-5xl w-full">
            <div class="relative hidden md:flex md:w-1/2 items-center justify-center p-4 bg-[#E6F0FF] dark:bg-gray-800">
                {{-- <img src="{{ overall_logo() }}" class="w-32 absolute top-4 left-4" /> --}}
                <img class="max-w-full h-auto" src="{{ asset('assets/image/logo/leftside.png') }}" alt="Login Image Here..">
            </div>
            <div class="w-full md:w-1/2 p-8 relative my-5">
                <div class="absolute top-1 right-4 flex gap-2 items-center">
                    <a href="/"
                        class=" w-9 h-9 flex items-center justify-center rounded-full bg-[#E6F0FF] text-primary  hover:text-blue-800">
                        <i class="ri-home-2-line"></i>
                    </a>
                    <div class="text-center  ">
                        <form class="max-w-sm mx-auto ">
                            <div class="flex">
                                <label for="states" class="sr-only">Choose a state</label>
                                <select id="states"
                                    class="  text-sm  rounded-full  border-gray-200 cursor-pointer   block w-full  bg-[#E6F0FF] text-primary  hover:text-blue-800   ">
                                    <option value="AE" class="">AE</option>
                                    <option value="EN" class="">EN</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <br><br>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white text-center">{{ __('common.sign-up') }}</h1>
                <p class="text-gray-500 dark:text-gray-300 text-center mb-4">{{ __('common.signup-message') }}</p>

                <form method="POST" class="" action="{{ route('registration.request.store-final') }}">
                    @csrf
                    <div class="">
                        <label for="input-name" class="block text-sm mb-2 font-medium text-gray-700 dark:text-gray-300">
                            {{ __('common.fullname') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="ri-user-3-line mb-5"></i>
                            </div>
                            <input type="text" name="fullname" id="fullname" data-maxlength="50"
                                class="border limited-input border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="{{ __('common.fullname') }}" required />
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/50</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="input-email" class="block text-sm mb-2 font-medium text-gray-700 dark:text-gray-300">
                            {{ __('common.email') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="ri-mail-line dark:text-white"></i>
                            </div>
                            <input type="email" name="email" id="input-email"
                                class="border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-24 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="{{ __('common.email') }}" required />

                            <a href="#" data-bs-toggle="modal" data-bs-target="#otp-modal" id="verifyOtpBtn"
                                class="absolute right-2 text-blue-600 font-medium text-sm hidden">
                                <strong>{{ __('seller.Verify-Otp') }}</strong>
                            </a>
                        </div>
                        <span id="email-error"
                            class="text-red-500 text-sm mt-1 hidden">{{ __('seller.valid-email-address') }}</span>
                    </div>
                    <div class="">
                        <label for="mobile-number" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('common.type') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="ri-phone-line dark:text-white mt-1"></i>
                            </div>
                            <select
                                class="border number-only mb-3 border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                name="type" id="typy_id" disabled>
                                <option value="">{{ __('common.chooseoption') }}</option>
                                <option value="Seller">Seller </option>
                                <option value="Buyer">Buyer </option>
                            </select>
                        </div>
                    </div>
                    <div class="">
                        <label for="mobile-number" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('common.mobile-number') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="ri-phone-line dark:text-white mb-5"></i>
                            </div>
                            <input type="text" disabled name="mobile_no" id="mobile_no" minlength="8" data-maxlength="10"
                                class="border number-only border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="{{ __('common.mobile-number') }}" required />
                            <div class="char-count text-xs text-gray-500 text-right mt-1">0/10 </div>
                        </div>
                    </div>
                    {{-- <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" class="rounded-sm text-blue-500 focus:ring-blue-500">
                            <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                {{ __('common.Remember Me') }}
                            </label>
                        </div>
                    </div> --}}
                    <button type="submit" disabled id="sumbit_registrationRequest"
                        class="w-full bg-primary text-white py-2 rounded-md hover:bg-blue-700 transition">
                        {{ __('common.sign-up') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    @include('loginer::auth.modal')
@endsection
@section('scripts')
    @include('layouts.modal_js')
    @include('components.formvalidationjs')
    @include('components.form_validationjs')
   <script>
    const emailInput = document.getElementById('input-email');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const emailError = document.getElementById('email-error');

    // ✅ Verify OTP button click
    $("#verifyOtpBtn").click(function () {
        var email = document.getElementById("input-email").value;
        var fullName = document.getElementById("fullname").value;
        if (email && fullName) {
            $.ajax({
                type: "POST",
                url: "{{ route('registration.request.store') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email,
                    fullName: fullName,
                },
                cache: false,
                success: function (data) {
                    if (data == 201) {
                        toastr.error('This Email already exists.');
                    } else if (data == 203) {
                        toastr.error('OTP attempt limit reached! You entered the wrong OTP multiple times.');
                    } else if (data == 202) {
                        toastr.error('Something went wrong. Contact the System Administrator.');
                    } else {
                        openNewModal('enter-otp');
                    }
                }
            });
        } else {
            toastr.error('All fields are required. Failed to resend OTP. Please try again.', 'Error');
            return false;
        }
    });

    // ✅ Supplier OTP Verify
    $("#supplier-otp").click(function () {
        var email = document.getElementById("input-email").value;
        var otpfield1 = document.getElementById("otp1").value;
        var otpfield2 = document.getElementById("otp2").value;
        var otpfield3 = document.getElementById("otp3").value;
        var otpfield4 = document.getElementById("otp4").value;
        var otpfield5 = document.getElementById("otp5").value;
        var otpfield6 = document.getElementById("otp6").value;

        if (otpfield1 && otpfield2 && otpfield3 && otpfield4 && otpfield5 && otpfield6) {
            $.ajax({
                type: "POST",
                url: "{{ route('registration-otp-matched-verify') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    otpfield1: otpfield1,
                    otpfield2: otpfield2,
                    otpfield3: otpfield3,
                    otpfield4: otpfield4,
                    otpfield5: otpfield5,
                    otpfield6: otpfield6,
                    email: email
                },
                cache: false,
                success: function (data) {
                    if (data == 201) {
                        toastr.error('OTP is not verify. Please fill valid OTP.', 'Error');
                        return false;
                    } else if (data == 203) {
                        toastr.error('OTP attempt limit reached..!!. You have entered wrong OTP multiple time.', 'Error');
                        return false;
                    } else if (data == 202) {
                        toastr.error('Something went wrong. Contact to System Administrator.', 'Error');
                        return false;
                    } else {
                        toastr.success('OTP is verified.', 'Success');
                        $('#typy_id').attr("disabled", false);
                        $('#mobile_no').attr("disabled", false);
                        $('#sumbit_registrationRequest').attr("disabled", false);
                        $('#otpsupplier').hide();
                        $("#enter-otp").show(1000);
                    }
                }
            });
        } else {
            toastr.error('All fields are required. Failed to resend OTP. Please try again.', 'Error');
            return false;
        }
    });

    // ✅ Show Verify OTP button only if email is valid
    emailInput.addEventListener('input', () => {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailPattern.test(emailInput.value.trim())) {
            verifyOtpBtn.classList.remove('hidden');
            emailError.classList.add('hidden');
        } else {
            verifyOtpBtn.classList.add('hidden');
            if (emailInput.value.trim() !== "") {
                emailError.classList.remove('hidden');
            } else {
                toastr.error('All fields are required. Failed to resend OTP. Please try again.', 'Error');
                return false;

            }
        }
    });

    const otpInputs = document.querySelectorAll("#otp1, #otp2, #otp3, #otp4, #otp5, #otp6");

    otpInputs.forEach((input, index) => {
      
        input.addEventListener("input", (e) => {
            input.value = input.value.replace(/[^0-9]/g, ""); 

                        } else {
                            toastr.success('OTP is verified .',
                                'Error');
                            $('#typy_id').attr("disabled", false);
                            $('#mobile_no').attr("disabled", false);
                            $('#sumbit_registrationRequest').attr("disabled", false);
                            $('#enter-otp').hide(1000);
                            // $('#otpsupplier').attr("disabled", false);
                        }

                    }
                });
            } else {
                toastr.error('All fields are required. Failed to resend OTP. Please try again.', 'Error');
                return false;
            }

            if (index === 0 && input.value.length > 1) {
                const digits = input.value.split("").slice(0, 6);
                otpInputs.forEach((box, i) => box.value = digits[i] || "");
                otpInputs[Math.min(digits.length, 5)].focus();
            }
        });

        input.addEventListener("keydown", (event) => {
            if (event.key === "Backspace" && input.value === "" && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = "";
            }
        });
    });
</script>

@endsection