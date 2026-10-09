<script>
    $(document).ready(function() {
    // Toggle password visibility
        $('#togglePassword').on('click', function() {
            const passwordField = $('#passwordField');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
        });

        var myInput = document.getElementById("passwordField");

        var letter = document.getElementById("letter");
        var capital = document.getElementById("capital");
        var number = document.getElementById("number");
        var specialcharacter = document.getElementById("specialcharacter");
        var length = document.getElementById("length");
        var passwordMismatchMessage = document.getElementById("passwordMismatchMessage");
        var confirmPasswordField = document.getElementById("confirmPasswordField");

        // Track the number of special characters
        var specialCharCount = 0;

        // Show the message box when the user clicks on the password field
        myInput.onfocus = function() {
            document.getElementById("message").style.display = "block";
        }

        // Hide the message box when the user clicks outside the password field
        myInput.onblur = function() {
            document.getElementById("message").style.display = "none";
        }

        // Handle input events for password validation
        myInput.onkeyup = function() {
            var password = myInput.value;

            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if (password.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if (password.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Validate numbers
            var numbers = /[0-9]/g;
            if (password.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Special character validation: Allow up to 3 special characters
            var specialCharacter = /[@#$!%*?&]/g;

            // Count special characters in the current password
            var specialCharMatches = password.match(specialCharacter);
            specialCharCount = specialCharMatches ? specialCharMatches.length : 0;

            // Allow only 3 special characters
            if (specialCharCount <= 3) {
                specialcharacter.classList.remove("invalid");
                specialcharacter.classList.add("valid");
                specialcharacter.style.color = specialCharCount === 3 ? 'green' : 'red'; // Turn green if exactly 3, else red
            } else {
                specialcharacter.classList.remove("valid");
                specialcharacter.classList.add("invalid");
                specialcharacter.style.color = 'red'; // More than 3 special characters = red
            }

            // Prevent entering more than 3 special characters: Remove only the extra special characters
            if (specialCharCount > 3) {
                var specialCharsRemoved = 0;
                myInput.value = password.replace(/[@#$!%*?&]/g, function(match) {
                    // If there are already 3 special characters, remove any extra ones
                    if (specialCharsRemoved < 3) {
                        specialCharsRemoved++;
                        return match; // Keep the first 3 special characters
                    }
                    return ''; // Remove extra special characters
                });
            }

            // Validate length (minimum 12 characters)
            if (password.length >= 12) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }

            // Validate confirm password
            if (password === confirmPasswordField.value && confirmPasswordField.value !== "") {
                passwordMismatchMessage.style.display = "block";
                passwordMismatchMessage.style.color = "green";
                passwordMismatchMessage.textContent = "Passwords match successfully!";
                confirmPasswordField.style.borderColor = "#00A9E2";
                submitButton.disabled = false;
            } else if (confirmPasswordField.value !== "") {
                passwordMismatchMessage.style.display = "block";
                passwordMismatchMessage.style.color = "red";
                passwordMismatchMessage.textContent = "Passwords do not match";
                confirmPasswordField.style.borderColor = "red";
                submitButton.disabled = true;
                // Optional: change border color to red
            } else {
                passwordMismatchMessage.style.display = "none";
                submitButton.disabled = true;
            }
        }

        // Bind input events to validate passwords
        $('#passwordField, #confirmPasswordField').on('input', function() {
            // Trigger the password validation function when the user types in the confirm password field
            myInput.onkeyup();
        });

        $.get_emp_data = function(e){
            var doc_id = e.value;
            var model = 'Company_employee';
            var full_url = model+"-edit/";
            var url = "{{ url('master',['urls'],'') }}";
            url = url.replace('urls',full_url);
            $('#name').val('');
            $('#email').val('');
            showLoader();
            $.ajax({
                url: url,
                method: 'get',
                data: {
                    // _token: '{{ csrf_token() }}',
                    doc_id:doc_id
                },
                success: function(data) {
                    hideLoader();
                    $('#name').val(data['name']);
                    $('#email').val(data['email']);
                }
            });
        }


    });

function togglview_modelSidebar(userId){
    const userViewSidebar = document.getElementById('user-view-sidebar');
    const overlay = document.querySelector(".drawer-overlay");
    const isOpen = !userViewSidebar.classList.contains('translate-x-full');

    if (isOpen) {
        userViewSidebar.classList.add('translate-x-full');
    } else {
        userViewSidebar.classList.remove('translate-x-full');
    }

    toggleOverlay();
    if (userId) {
        showLoader();
        $.ajax({
            url: "{{route('user-view')}}",
            method: 'get',
            data: {
                userId: userId
            },
            success: function(response) {
                if(response.status){
                    hideLoader();
                    const editUserLink = document.getElementById('editUserLink');
                    const baseUrl = "{{ route('user-edit', ['id' => '']) }}";
                    editUserLink.href = baseUrl + response.id;
                    $('#id').val(response.user.id);
                    $('#nameView').val(response.user.name);
                    $('#emailView').val(response.user.email);
                    const $select = $('#companyView');
                    $select.empty();
                    response.companiesInfo.forEach(company => {
                                                    $select.append(
                                                        $('<option>', {value: company.id,text: company.name,selected: true})
                                                    );
                    });
                    $select.trigger('change');

                }
            }
        });
    }
}


    function toggleOverlay() {
        const userViewSidebar = document.getElementById('user-view-sidebar');
        const overlay = document.querySelector(".drawer-overlay");
        if (
            !userViewSidebar.classList.contains('translate-x-full')
        ) {
            overlay.classList.remove('hidden');
        } else {
            overlay.classList.add('hidden');
        }
    }
    document.querySelector(".drawer-overlay").addEventListener('click', closeAllSidebars);
    function closeAllSidebars() {
        const userViewSidebar = document.getElementById('user-view-sidebar');
        const overlay = document.querySelector(".drawer-overlay");
        userViewSidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
    }
</script>


