<div id="verify-otp"
    class="hidden fixed top-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-50">

    <div class="new-modal-content transition-all duration-300 transform opacity-0 translate-y-20 w-full max-w-xl">

        <div class="bg-white rounded-md shadow-lg dark:bg-gray-700">
            <div class="p-10 md:p-5 min-h-[20vh] max-h-[80vh] overflow-y-auto flex items-center justify-center">
                <p class="text-center font-semibold">
                    An OTP sent to your Email. Please enter a valid OTP
                </p>
            </div>

            <div class="flex justify-center items-center  md:p-2 border-gray-200 rounded-b dark:border-gray-600">
                <button onclick="openNewModal('enter-otp')"
                    class="px-3 py-1 text-lg font-medium text-white bg-green-600 rounded-md hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 "
                    data-target="verify-otp">
                    ok
                </button>

            </div>
        </div>
    </div>
</div>
<div id="enter-otp"
    class="hidden fixed top-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-50">

    <div class="new-modal-content transition-all duration-300 transform opacity-0 translate-y-20 w-full max-w-xl">


        <div class="bg-white rounded-md shadow-lg dark:bg-gray-700">
            <div class="p-10 md:p-5 min-h-[20vh] max-h-[80vh] overflow-y-auto flex items-center justify-center">
                <div class="w-full max-w-md mx-auto p-6  rounded-md text-center">
                    <p id="timer" class="mb-4 text-gray-700">Resend OTP in 60 seconds</p>
                    <div class="flex justify-center space-x-3">
                        <input type="text" maxlength="1" name="otp1"
                            class="w-12 h-12 border border-orange-500 rounded-md text-center text-xl focus:outline-none focus:ring-2 focus:ring-orange-400"
                            oninput="moveToNext(this, 'otp2')" id="otp1" />
                        <input type="text" maxlength="1" name="otp2"
                            class="w-12 h-12 border border-orange-500 rounded-md text-center text-xl focus:outline-none focus:ring-2 focus:ring-orange-400"
                            oninput="moveToNext(this, 'otp3')" id="otp2" />
                        <input type="text" maxlength="1" name="otp3"
                            class="w-12 h-12 border border-orange-500 rounded-md text-center text-xl focus:outline-none focus:ring-2 focus:ring-orange-400"
                            oninput="moveToNext(this, 'otp4')" id="otp3" />
                        <input type="text" maxlength="1" name="otp4"
                            class="w-12 h-12 border border-orange-500 rounded-md text-center text-xl focus:outline-none focus:ring-2 focus:ring-orange-400"
                            oninput="moveToNext(this, 'otp5')" id="otp4" />
                        <input type="text" maxlength="1" name="otp5"
                            class="w-12 h-12 border border-orange-500 rounded-md text-center text-xl focus:outline-none focus:ring-2 focus:ring-orange-400"
                            oninput="moveToNext(this, 'otp6')" id="otp5" />
                        <input type="text" maxlength="1" name="otp6"
                            class="w-12 h-12 border border-orange-500 rounded-md text-center text-xl focus:outline-none focus:ring-2 focus:ring-orange-400"
                            id="otp6" />
                    </div>
                    <button id="resendBtn" class="mt-4 text-orange-500 font-semibold" disabled>Resend OTP</button>
                </div>
            </div>

            <div class="flex justify-center items-center  md:p-2 border-gray-200 rounded-b dark:border-gray-600">
                <button onclick="closeNewModal('enter-otp')"
                    class="px-3 py-1 text-lg font-medium text-white mb-4 bg-green-600 rounded-md hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 "
                    data-target="verify-otp">
                    Cancel
                </button>
                <button id="supplier-otp" type="submit"
                    class="px-3 py-1 text-lg font-medium text-white mb-4 ml-2 bg-primary rounded-md "
                   >
                   Submit
                </button>

            </div>
        </div>
         
    </div>
</div>
<script>
    function moveToNext(current, nextId) {
        if (current.value.length === 1) {
            document.getElementById(nextId)?.focus();
        }
    }

    let timeLeft = 60;
    const timerDisplay = document.getElementById("timer");
    const resendBtn = document.getElementById("resendBtn");

    const countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerDisplay.textContent = "You can now resend OTP";
            resendBtn.disabled = false;
        } else {
            timerDisplay.textContent = `Resend OTP in ${timeLeft} seconds`;
            timeLeft--;
        }
    }, 1000);

    resendBtn.addEventListener("click", () => {
        timeLeft = 60;
        resendBtn.disabled = true;
        timerDisplay.textContent = `Resend OTP in ${timeLeft} seconds`;

        const countdownRestart = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdownRestart);
                timerDisplay.textContent = "You can now resend OTP";
                resendBtn.disabled = false;
            } else {
                timerDisplay.textContent = `Resend OTP in ${timeLeft} seconds`;
                timeLeft--;
            }
        }, 1000);
    });
</script>
