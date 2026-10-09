// ------Login Js--------
document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const emailInput = document.getElementById("input-email");
    const passwordInput = document.getElementById("input-password");
    const loginButton = document.getElementById("login-button");
    const togglePassword = document.getElementById("toggle-password");
    const checkFields = () => {
        const emailValue = emailInput.value.trim();
        const passwordValue = passwordInput.value.trim();
        if (emailValue && passwordValue) {
            loginButton.disabled = false;
            loginButton.classList.remove("opacity-50", "cursor-not-allowed");
        } else {
            loginButton.disabled = true;
            loginButton.classList.add("opacity-50", "cursor-not-allowed");
        }
    };
    togglePassword.addEventListener("click", () => {
        const isPasswordVisible = passwordInput.type === "text";
        passwordInput.type = isPasswordVisible ? "password" : "text";
        togglePassword.innerHTML = isPasswordVisible
            ? '<i class="ri-eye-line"></i>'
            : '<i class="ri-eye-off-line"></i>';
    });
    emailInput.addEventListener("input", checkFields);
    passwordInput.addEventListener("input", checkFields);
    // loginForm.addEventListener("submit", (e) => {
    //     e.preventDefault();
    //     alert("Form submitted successfully!");
    // });
}); 




// -------Register Js------
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("signupForm");
    const inputs = form.querySelectorAll(".form-input");
    const submitButton = document.getElementById("submitButton");
    const validateForm = () => {
        const allFilled = Array.from(inputs).every(input => input.value.trim() !== "");
        submitButton.disabled = !allFilled;
        if (allFilled) {
            submitButton.classList.remove("opacity-50", "cursor-not-allowed");
        } else {
            submitButton.classList.add("opacity-50", "cursor-not-allowed");
        }
    };
    inputs.forEach(input => {
        input.addEventListener("input", validateForm);
    });
    // form.addEventListener("submit", e => {
    //     e.preventDefault();
    //     alert("Form submitted successfully!");
    // });
});


// ----forget passowrd------
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("loginForm");
    const emailInput = document.getElementById("input-email");
    const submitButton = document.getElementById("login-button");
    const validateForm = () => {
        const emailFilled = emailInput.value.trim() !== "";
        if (emailFilled) {
            submitButton.disabled = false;
            submitButton.classList.remove("opacity-50", "cursor-not-allowed");
        } else {
            submitButton.disabled = true;
            submitButton.classList.add("opacity-50", "cursor-not-allowed");
        }
    };
    emailInput.addEventListener("input", validateForm);
    // form.addEventListener("submit", (e) => {
    //     e.preventDefault();
    //     alert("Password reset link has been sent to your email.");
    // });
    validateForm();
});

// ---------Reset Paaword Js--------
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("resetPasswordForm");
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const togglePassword = document.getElementById("toggle-password");
    const toggleConfirmPassword = document.getElementById("toggle-confirm-password");
    const passwordError = document.getElementById("password-error");
    const confirmPasswordError = document.getElementById("confirm-password-error");
    const submitButton = document.getElementById("submit-button");

    // Function to toggle password visibility
    const togglePasswordVisibility = (input, toggleButton) => {
        toggleButton.addEventListener("click", () => {
            const type = input.type === "password" ? "text" : "password";
            input.type = type;
            toggleButton.innerHTML = input.type === "password"
                ? '<i class="ri-eye-line"></i>'
                : '<i class="ri-eye-off-line"></i>';
        });
    };

    // Function to validate password strength
    const validatePasswordStrength = (password) => {
        // Regex for strong password: at least 8 characters, 1 special char, 1 number, and 1 uppercase letter
        const strongPasswordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return strongPasswordRegex.test(password);
    };

    // Function to validate matching passwords
    const validatePasswords = () => {
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();
        const isPasswordStrong = validatePasswordStrength(password);

        // Handle password strength error
        if (!isPasswordStrong) {
            passwordError.classList.remove("hidden");
        } else {
            passwordError.classList.add("hidden");
        }

        // Handle password match error
        if (password && confirmPassword) {
            if (password === confirmPassword && isPasswordStrong) {
                confirmPasswordError.classList.add("invisible");
                submitButton.disabled = false;
                submitButton.classList.remove("opacity-50", "cursor-not-allowed");
            } else {
                confirmPasswordError.classList.remove("invisible");
                submitButton.disabled = true;
                submitButton.classList.add("opacity-50", "cursor-not-allowed");
            }
        } else {
            confirmPasswordError.classList.add("invisible");
            submitButton.disabled = true;
            submitButton.classList.add("opacity-50", "cursor-not-allowed");
        }
    };

    // Add event listeners for validation
    passwordInput.addEventListener("input", validatePasswords);
    confirmPasswordInput.addEventListener("input", validatePasswords);

    // Toggle password visibility
    togglePasswordVisibility(passwordInput, togglePassword);
    togglePasswordVisibility(confirmPasswordInput, toggleConfirmPassword);

    // Form submission handler
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        alert("Password has been reset successfully!");
    });
});

