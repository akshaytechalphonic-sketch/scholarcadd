<script>
    function sanitizeAndUpdate(input, counter, maxLength) {
        const filtered = input.value.replace(/[^a-zA-Z\s]/g, '');
        if (input.value !== filtered) {
            input.value = filtered;
        }
        if (input.value.length > maxLength) {
            input.value = input.value.substring(0, maxLength);
        }
        counter.textContent = `${input.value.length}/${maxLength}`;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const fields = document.querySelectorAll('.limited-input');
        fields.forEach(field => {
            const maxLength = parseInt(field.dataset.maxlength || 500, 10);
            const counter = field.nextElementSibling;
            if (!counter || !counter.classList.contains('char-count')) return;

            field.addEventListener('input', () => sanitizeAndUpdate(field, counter, maxLength));
            sanitizeAndUpdate(field, counter, maxLength);
        });
        // Allow form reset to finish, then update counters
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('reset', function() {
                setTimeout(() => {
                    const resetFields = form.querySelectorAll('.limited-input');
                    resetFields.forEach(field => {
                        const maxLength = parseInt(field.dataset.maxlength ||
                            500, 10);
                        const counter = field.nextElementSibling;
                        if (counter && counter.classList.contains(
                                'char-count')) {
                            sanitizeAndUpdate(field, counter, maxLength);
                        }
                    });
                }, 0);
            });
        });
    });

    function getPlainTextFromHTML(html) {
        const div = document.createElement('div');
        div.innerHTML = html;
        return div.textContent || div.innerText || '';
    }
    // Validation for summernot
    function descriptionValidationUpdateSummernote($summernote, $counter, maxLength) {
        let html = $summernote.summernote('code');
        const text = getPlainTextFromHTML(html);
        $counter.text(`${text.length}/${maxLength}`);
        if (text.length > maxLength) {
            const trimmed = text.substring(0, maxLength);
            $summernote.summernote('code', trimmed);
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        const $editors = $('.description-validation-summernote');
        $editors.each(function() {
            const $editor = $(this);
            const maxLength = parseInt($editor.data('maxlength') || 500, 10);
            const $counter = $editor.next('.char-count');
            if (!$counter.length) return;
            $editor.summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['font', ['fontname']]
                ],
                callbacks: {
                    onKeydown: function(e) {
                        const html = $editor.summernote('code');
                        const text = getPlainTextFromHTML(html);
                        if (text.length >= maxLength && ![8, 37, 38, 39, 40, 46].includes(e
                                .keyCode)) {
                            e.preventDefault();
                        }
                    },
                    onPaste: function(e) {
                        e.preventDefault();
                        const clipboardText = (e.originalEvent || e).clipboardData.getData(
                            'text/plain');
                        const html = $editor.summernote('code');
                        const currentText = getPlainTextFromHTML(html);
                        const allowedText = clipboardText.substring(0, maxLength -
                            currentText.length);
                        $editor.summernote('pasteHTML', allowedText);
                    },
                    onChange: function() {
                        descriptionValidationUpdateSummernote($editor, $counter, maxLength);
                    }
                }
            });
            descriptionValidationUpdateSummernote($editor, $counter, maxLength);
        });
        $('form').on('reset', function() {
            const form = this;
            setTimeout(() => {
                $(form).find('.description-validation-summernote').each(function() {
                    const $editor = $(this);
                    const $counter = $editor.next('.char-count');
                    const maxLength = parseInt($editor.data('maxlength') || 500, 10);
                    $editor.summernote('code', '');
                    if ($counter.length) {
                        $counter.text(`0/${maxLength}`);
                    }
                });
            }, 0);
        });
    });


    // Global validation for description inputs
    function descriptionvalidationUpdate(input, counter, maxLength) {
        const filtered = input.value.replace(/[^a-zA-Z0-9\s.,!?;:'"()\[\]{}&/\\\-]/g, '');
        if (input.value !== filtered) {
            input.value = filtered;
        }
        if (input.value.length > maxLength) {
            input.value = input.value.substring(0, maxLength);
        }
        counter.textContent = `${input.value.length}/${maxLength}`;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const descriptionFields = document.querySelectorAll('.description-validation');
        descriptionFields.forEach(field => {
            const maxLength = parseInt(field.dataset.maxlength || 500, 10);
            const counter = field.nextElementSibling;
            if (!counter || !counter.classList.contains('char-count')) return;
            field.addEventListener('input', () => descriptionvalidationUpdate(field, counter,
                maxLength));
            descriptionvalidationUpdate(field, counter, maxLength);
        });
        // Handle reset on all forms for description-validation fields
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('reset', function() {
                setTimeout(() => {
                    const resetFields = form.querySelectorAll(
                        '.description-validation');
                    resetFields.forEach(field => {
                        const maxLength = parseInt(field.dataset.maxlength ||
                            500, 10);
                        const counter = field.nextElementSibling;
                        if (counter && counter.classList.contains(
                                'char-count')) {
                            descriptionvalidationUpdate(field, counter,
                                maxLength);
                        }
                    });
                }, 0);
            });
        });
    });


    // Global validation for counting only (no restrictions on input)
function countValidationUpdate(input, counter, maxLength) {
    if (input.value.length > maxLength) {
        input.value = input.value.substring(0, maxLength);
    }
    counter.textContent = `${input.value.length}/${maxLength}`;
}

document.addEventListener('DOMContentLoaded', function() {
    const countFields = document.querySelectorAll('.count-validation');
    countFields.forEach(field => {
        const maxLength = parseInt(field.dataset.maxlength || 500, 10);
        const counter = field.nextElementSibling;
        if (!counter || !counter.classList.contains('char-count')) return;
        field.addEventListener('input', () => countValidationUpdate(field, counter, maxLength));
        countValidationUpdate(field, counter, maxLength);
    });

    // Handle reset on all forms for count-validation fields
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('reset', function() {
            setTimeout(() => {
                const resetFields = form.querySelectorAll('.count-validation');
                resetFields.forEach(field => {
                    const maxLength = parseInt(field.dataset.maxlength || 500, 10);
                    const counter = field.nextElementSibling;
                    if (counter && counter.classList.contains('char-count')) {
                        countValidationUpdate(field, counter, maxLength);
                    }
                });
            }, 0);
        });
    });
});

    // Global validation for Email inputs
    function updateEmailValidation(input, counter, maxLength) {
        const email = input.value.trim();
        const isValid = /^[a-zA-Z0-9._%+-]+@([a-zA-Z0-9-]{2,})\.[a-zA-Z]{2,}$/.test(email) && !/\s/.test(email);
        if (email.length > maxLength) {
            input.value = email.substring(0, maxLength);
        }
        counter.textContent = `${input.value.length}/${maxLength}`;
        const error = input.nextElementSibling?.nextElementSibling;
        if (error && error.classList.contains('email-error')) {
            error.textContent = (!isValid && email !== "") ?
                "Please enter a valid email address." :
                "";
        }
        input.setCustomValidity(!isValid && email !== "" ? "Invalid email address." : "");
    }
    document.addEventListener('DOMContentLoaded', function() {
        const emailFields = document.querySelectorAll('.email-validation');
        emailFields.forEach(field => {
            const maxLength = parseInt(field.dataset.maxlength || 100, 10);
            const counter = field.nextElementSibling;
            if (!counter || !counter.classList.contains('char-count')) return;

            field.addEventListener('input', () => updateEmailValidation(field, counter, maxLength));
            updateEmailValidation(field, counter, maxLength);
        });
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('reset', function() {
                setTimeout(() => {
                    const resetFields = form.querySelectorAll('.email-validation');
                    resetFields.forEach(field => {
                        const maxLength = parseInt(field.dataset.maxlength ||
                            100, 10);
                        const counter = field.nextElementSibling;
                        if (counter && counter.classList.contains(
                                'char-count')) {
                            updateEmailValidation(field, counter, maxLength);
                        }
                        field.setCustomValidity("");
                        const error = field.nextElementSibling
                            ?.nextElementSibling;
                        if (error && error.classList.contains('email-error')) {
                            error.textContent = "";
                        }
                    });
                }, 0);
            });
        });
    });



    // Global Validation for integer and max value (only digits and spaces, no special chars or negatives)
    function updateNumberOnlyInput(input, counter, maxLength) {
        const filtered = input.value.replace(/[^0-9 ]/g, '');
        const trimmed = filtered.substring(0, maxLength);
        if (input.value !== trimmed) {
            input.value = trimmed;
        }
        counter.textContent = `${input.value.length}/${maxLength}`;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const numberFields = document.querySelectorAll('.number-only');
        numberFields.forEach(field => {
            const maxLength = parseInt(field.dataset.maxlength || 10, 10);
            const counter = field.nextElementSibling;
            if (!counter || !counter.classList.contains('char-count')) return;

            field.addEventListener('input', () => updateNumberOnlyInput(field, counter, maxLength));
            updateNumberOnlyInput(field, counter, maxLength);
        });
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('reset', function() {
                setTimeout(() => {
                    const resetFields = form.querySelectorAll('.number-only');
                    resetFields.forEach(field => {
                        const maxLength = parseInt(field.dataset.maxlength ||
                            10, 10);
                        const counter = field.nextElementSibling;
                        if (counter && counter.classList.contains(
                                'char-count')) {
                            updateNumberOnlyInput(field, counter, maxLength);
                        }
                    });
                }, 0);
            });
        });
    });


    // Global Validation for decimal
    function updateDecimalOnlyInput(input, counter, maxLength) {
        let value = input.value;
        value = value.replace(/[^0-9.]/g, '')
            .replace(/(\..*)\./g, '$1');
        const parts = value.split('.');
        if (parts[1]) {
            parts[1] = parts[1].substring(0, 3);
        }
        value = parts.join('.');
        value = value.substring(0, maxLength);
        if (input.value !== value) {
            input.value = value;
        }
        if (counter) {
            counter.textContent = `${input.value.length}/${maxLength}`;
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        const decimalFields = document.querySelectorAll('.decimal-only');
        decimalFields.forEach(field => {
            const maxLength = parseInt(field.dataset.maxlength || 10, 10);
            const counter = field.nextElementSibling;
            if (!counter || !counter.classList.contains('char-count')) return;
            field.addEventListener('input', () =>
                updateDecimalOnlyInput(field, counter, maxLength)
            );
            updateDecimalOnlyInput(field, counter, maxLength);
        });
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('reset', function() {
                setTimeout(() => {
                    const resetFields = form.querySelectorAll('.decimal-only');
                    resetFields.forEach(field => {
                        const maxLength = parseInt(field.dataset.maxlength ||
                            10, 10);
                        const counter = field.nextElementSibling;
                        if (counter && counter.classList.contains(
                                'char-count')) {
                            updateDecimalOnlyInput(field, counter, maxLength);
                        }
                    });
                }, 0);
            });
        });
    });

    function updatePercentageOnlyInput(input, counter, maxLength) {
        let value = input.value;

        // Allow only digits and a single dot
        value = value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');

        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts[1];
        }

        // Limit decimal places to 2
        if (parts[1]) {
            parts[1] = parts[1].substring(0, 2);
        }

        value = parts.join('.');

        // Allow typing partial decimal values like "99." or "99.9"
        let numericValue = parseFloat(value);
        if (!isNaN(numericValue)) {
            if (numericValue > 100) {
                value = '100';
            } else {
                // Keep partial decimal like "99." or "99.9"
                value = value;
            }
        } else {
            // If value is just ".", reset to empty
            if (value === ".") {
                value = "";
            }
        }

        // Limit length
        value = value.substring(0, maxLength);

        // Update input and counter
        if (input.value !== value) {
            input.value = value;
        }

        if (counter) {
            counter.textContent = `${input.value.length}/${maxLength}`;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const percentageFields = document.querySelectorAll('.percentage-only');
        percentageFields.forEach(field => {
            const maxLength = parseInt(field.dataset.maxlength || 6, 10);
            const counter = field.nextElementSibling;
            if (!counter || !counter.classList.contains('char-count')) return;

            field.addEventListener('input', () =>
                updatePercentageOnlyInput(field, counter, maxLength)
            );
            updatePercentageOnlyInput(field, counter, maxLength);
        });

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('reset', function() {
                setTimeout(() => {
                    const resetFields = form.querySelectorAll('.percentage-only');
                    resetFields.forEach(field => {
                        const maxLength = parseInt(field.dataset.maxlength || 6,
                            10);
                        const counter = field.nextElementSibling;
                        if (counter && counter.classList.contains(
                                'char-count')) {
                            updatePercentageOnlyInput(field, counter,
                                maxLength);
                        }
                    });
                }, 0);
            });
        });
    });



    // Specific validation for 'wind' input: only digits, no spaces or special characters, max 3 digits
    document.querySelector('input[name="wind"]').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
    });


    // global validation for 3digit integer and 2 digit decimal
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-validate]').forEach(function(input) {
            input.addEventListener('input', function() {
                const type = input.dataset.validate;
                let val = input.value;
                if (type === 'decimal') {
                    const match = val.match(/^(\d{0,3})(\.\d{0,2})?/);
                    if (match) {
                        input.value = match[0];
                    } else {
                        input.value = '';
                    }
                } else if (type === 'integer') {
                    input.value = val.replace(/\D/g, '').slice(0, 3);
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentYear = new Date().getFullYear();

        function validateYearInputs(className = 'year-input') {
            document.querySelectorAll(`.${className}`).forEach((input) => {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/\D/g, '');
                    if (this.value.length > 4) {
                        this.value = this.value.slice(0, 4);
                    }
                    if (parseInt(this.value) > currentYear) {
                        this.value = currentYear;
                    }
                });
            });
        }
        validateYearInputs();
    });
</script>
<script>
    // {{-- latitude and longitude validations js --}}
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.coord-input').forEach(input => {
            input.addEventListener('input', () => {
                let val = input.value;
                val = val.replace(/[^\d\.\-]/g, '');
                if (val.includes('-')) {
                    val = '-' + val.replace(/-/g, '').replace(/^-/, '');
                }
                const parts = val.split('.');
                parts[0] = parts[0].slice(0, 5);
                if (parts.length > 1) {
                    parts[1] = parts[1].slice(0, 9);
                    val = parts[0] + '.' + parts[1];
                } else {
                    val = parts[0];
                }
                input.value = val;
            });
        });
    });
</script>
