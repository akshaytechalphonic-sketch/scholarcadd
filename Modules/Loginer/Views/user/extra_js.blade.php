<script>
    $(document).ready(function() {
        $(".drawer-overlay").on('click', function() {
            closeAllSidebars('view-sidebar');
        });
        $.get_emp_data = function(e) {
            var doc_id = e.value;
            $('#name').val('');
            $('#email').val('');
            showLoader();
            $.ajax({
                url: "{{ route('get-employee-by-id') }}",
                method: 'get',
                data: {
                    id: doc_id
                },
                success: function(data) {
                    hideLoader();
                    $('#name').val(data['name']);
                    $('#email').val(data['email']);
                }
            });
        }


    });


    function view_model(modal, userId) {
        toggle_model(modal);
        if (userId) {
            showLoader();
            $.ajax({
                url: "{{ route('users-view') }}",
                method: 'get',
                data: {
                    userId: userId
                },
                success: function(response) {
                    if (response.status) {
                        console.log('data', response);
                        hideLoader();
                        const editUserLink = document.getElementById('editUserLink');

                        $('#id').val(response.user.id);
                        $('#nameView').val(response.user.name);
                        $('#emailView').val(response.user.email);

                    }
                }
            });
        }
    }
</script>

<script>
    const passwordInput = document.getElementById("passwordField");
    const confirmPasswordInput = document.getElementById("confirmPasswordField");
    const togglePassword = document.getElementById("togglePassword");
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
    const letter = document.getElementById("letter");
    const capital = document.getElementById("capital");
    const number = document.getElementById("number");
    const specialcharacter = document.getElementById("specialcharacter");
    const length = document.getElementById("length");
    const passwordMismatchMessage = document.getElementById("passwordMismatchMessage");
    const messageBox = document.getElementById("message");

    // Toggle show/hide password
    togglePassword.addEventListener("click", () => {
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;
        togglePassword.className = type === "text" ?
            "ri-eye-off-line absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-xl text-gray-500" :
            "ri-eye-line absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-xl text-gray-500";
    });

    toggleConfirmPassword.addEventListener("click", () => {
        const type = confirmPasswordInput.type === "password" ? "text" : "password";
        confirmPasswordInput.type = type;
        toggleConfirmPassword.className = type === "text" ?
            "ri-eye-off-line absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-xl text-gray-500" :
            "ri-eye-line absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-xl text-gray-500";
    });

    // Show message box
    passwordInput.addEventListener("focus", () => messageBox.style.display = "block");
    passwordInput.addEventListener("blur", () => messageBox.style.display = "none");

    function validatePassword() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    let isValid = true;

    // Lowercase check
    const hasLower = /[a-z]/.test(password);
    updateValidity(letter, hasLower);
    isValid = isValid && hasLower;

    // Uppercase check
    const hasUpper = /[A-Z]/.test(password);
    updateValidity(capital, hasUpper);
    isValid = isValid && hasUpper;

    // Number check
    const hasNumber = /[0-9]/.test(password);
    updateValidity(number, hasNumber);
    isValid = isValid && hasNumber;

    // Special characters check (exactly 3)
    const specialMatches = password.match(/[@#$!%*?&]/g) || [];
    const hasValidSpecial = specialMatches.length === 3;
    updateValidity(specialcharacter, hasValidSpecial);
    isValid = isValid && hasValidSpecial;

    // Restrict to max 3 special characters
    if (specialMatches.length > 3) {
        passwordInput.value = password.replace(/[@#$!%*?&]/g, (match, offset, str) => {
            return (specialMatches.splice(0, 3).includes(match)) ? match : '';
        });
    }

    // Length check
    const hasLength = password.length >= 12;
    updateValidity(length, hasLength);
    isValid = isValid && hasLength;

    // Confirm password match check
    let passwordsMatch = false;
    if (confirmPassword !== "") {
        if (password === confirmPassword) {
            passwordMismatchMessage.textContent = "Passwords match successfully!";
            passwordMismatchMessage.style.color = "green";
            confirmPasswordInput.classList.add("border-green-500");
            confirmPasswordInput.classList.remove("border-red-500");
            passwordsMatch = true;
        } else {
            passwordMismatchMessage.textContent = "Passwords do not match";
            passwordMismatchMessage.style.color = "red";
            confirmPasswordInput.classList.add("border-red-500");
            confirmPasswordInput.classList.remove("border-green-500");
        }
    } else {
        passwordMismatchMessage.textContent = "";
        confirmPasswordInput.classList.remove("border-green-500", "border-red-500");
    }

    // Enable/Disable submit button
    const submitBtn = document.getElementById("submitButton");
    if (isValid && passwordsMatch) {
        submitBtn.disabled = false;
        submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add("opacity-50", "cursor-not-allowed");
    }
}


    function updateValidity(element, isValid) {
        if (isValid) {
            element.classList.remove("invalid");
            element.classList.add("valid");
        } else {
            element.classList.remove("valid");
            element.classList.add("invalid");
        }
    }

    passwordInput.addEventListener("input", validatePassword);
    confirmPasswordInput.addEventListener("input", validatePassword);
</script>
