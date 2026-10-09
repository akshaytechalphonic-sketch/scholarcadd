<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<title>
    @yield('page_title')
</title>

<head>
    @php $system = App\Helpers\CommonHelper::system_control(['brand_name', 'light_logo', 'small_logo', 'rtl', 'def_language']); @endphp


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Amysoftech-ERP') }}</title>
    <link rel="stylesheet" href="{{ asset('amybuild/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('amybuild/css/toastr.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_length select {
            padding-inline: 26px !important;
            border-right: none !important;
            border-left: none !important;
            border-top: none !important;
            border-bottom: 2px solid #6B7280 !important;
            border-radius: 0px !important;
        }

        /* table.dataTable.row-border>tbody>tr>th,
        table.dataTable.row-border>tbody>tr>td,
        table.dataTable.display>tbody>tr>th,
        table.dataTable.display>tbody>tr>td {
           padding: 5px !important;
        } */
    </style>
    @yield('css')
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @if (app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        @endphp

        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
        <script src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}" type="module" defer></script>
    @endif
</head>

<body class="font-sans antialiased bg-[#F9FAFB] dark:bg-gray-900 transition-colors duration-200">
    <div>
        <div class="flex h-screen">
            @include('layouts.loader')
            @include('layouts.sidebar')
            <div class="flex-1 flex flex-col overflow-hidden">
                @include('layouts.maintopbar')
                @include('layouts.topbar')
                <div class="flex-1 p-3 dark:bg-slate-700  dark:text-white text-black overflow-auto">
                    <div class="responsive-container">
                        @yield('content')
                    </div>
                </div>
                @include('layouts.footer')
            </div>
        </div>
    </div>
    <div id="status-message" data-status="{{ session('success') }}" style="display: none;"></div>
    <div id="error-message" data-error="{{ session('error') }}" style="display: none;"></div>
</body>
<script src="{{ URL::asset('amybuild/js/layout.js') }}"></script>
<script src="{{ URL::asset('amybuild/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('amybuild/js/toastr.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

@include('components.form_validationjs')
{{-- @include('layouts.global_encrypt') --}}
@yield('scripts')
<script>
    // Js for Show and hide status bar in list section
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-status-section-btn').forEach((btn, index) => {
            const section = document.querySelectorAll('.toggle-status-section')[index];
            if (!section) return;
            const icon = btn.querySelector('i');
            btn.addEventListener('click', () => {
                const isHidden = section.classList.contains('hide');
                if (isHidden) {
                    section.classList.remove('hide');
                    btn.innerHTML =
                        `<i class="ri-eye-off-line mr-1"></i>{{ __('common.Status') }}`;
                } else {
                    section.classList.add('hide');
                    btn.innerHTML =
                        `<i class="ri-eye-line mr-1"></i>{{ __('common.Status') }}`;
                }
            });
        });
    });
</script>
{{-- Js for Sidebar Theme --}}
<script>
    const themeOptions = document.querySelectorAll('.theme-option');
    const app = document.querySelector('.app');
    const setTheme = (theme) => {
        app?.setAttribute('data-theme', theme);
        localStorage.setItem('sidebar-theme', theme);
        themeOptions.forEach(opt => {
            const isActive = opt.getAttribute('data-theme') === theme;
            opt.classList.toggle('selected', isActive);
            opt.querySelector('.check-icon').classList.toggle('hidden', !isActive);
        });
    };
    const savedTheme = localStorage.getItem('sidebar-theme') || 'base';
    setTheme(savedTheme);
    themeOptions.forEach(opt => {
        opt.addEventListener('click', () => {
            const theme = opt.getAttribute('data-theme');
            setTheme(theme);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        document.querySelectorAll('[data-toggle="section"]').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const targetKey = trigger.getAttribute('data-target');
                const targetSection = document.querySelector(`[data-section="${targetKey}"]`);

                // Close others
                document.querySelectorAll('[data-section]').forEach(section => {
                    if (section !== targetSection) {
                        section.classList.add('hidden');
                        section.classList.remove('translate-x-0');
                        section.classList.add('-translate-x-full');
                    }
                });

                if (targetSection) {
                    const isHidden = targetSection.classList.contains('hidden');

                    if (isHidden) {
                        targetSection.classList.remove('hidden');
                        // Trigger animation after forcing reflow
                        requestAnimationFrame(() => {
                            targetSection.classList.remove('-translate-x-full');
                            targetSection.classList.add('translate-x-0');
                        });
                    } else {
                        targetSection.classList.remove('translate-x-0');
                        targetSection.classList.add('-translate-x-full');
                        setTimeout(() => {
                            targetSection.classList.add('hidden');
                        }, 300); // match duration-300
                    }
                }
            });
        });
    });
</script>



{{-- Remove Jqery data table alternate bg color --}}
{{--
<script>
    $.extend(true, $.fn.dataTable.defaults, {
        stripeClasses: []
    });
</script> --}}
<script>
    // Custome Dropdown Js------
    document.addEventListener("DOMContentLoaded", () => {
        const dropdownButtons = document.querySelectorAll("[data-dropdown-button]");

        function closeAllDropdowns() {
            document.querySelectorAll("[data-dropdown-content]").forEach((dropdown) => {
                dropdown.classList.add("hidden");
            });
        }

        function positionDropdown(dropdown, button) {
            dropdown.style.left = "0";
            const dropdownRect = dropdown.getBoundingClientRect();
            const buttonRect = button.getBoundingClientRect();

            if (dropdownRect.right > window.innerWidth) {
                dropdown.style.left = `${window.innerWidth - dropdownRect.right - 20}px`;
            } else if (dropdownRect.left < 0) {
                dropdown.style.left = `${-dropdownRect.left + 20}px`;
            }
        }
        dropdownButtons.forEach((button) => {
            button.addEventListener("click", (event) => {
                event.stopPropagation();
                const dropdown = button.nextElementSibling;
                const isHidden = dropdown.classList.contains("hidden");
                closeAllDropdowns();
                if (isHidden) {
                    dropdown.classList.remove("hidden");
                    positionDropdown(dropdown, button);
                }
            });
        });
        document.addEventListener("click", closeAllDropdowns);
        window.addEventListener("resize", () => {
            document.querySelectorAll("[data-dropdown-content]:not(.hidden)").forEach((dropdown) => {
                const button = dropdown.previousElementSibling;
                positionDropdown(dropdown, button);
            });
        });
    });
</script>
<script>
    // List menu dropdown JS
    function toggleDropdown(button) {
        const dropdownMenu = button.nextElementSibling;
        document.querySelectorAll(".dropdown-menu").forEach(menu => {
            if (menu !== dropdownMenu) {
                menu.classList.add("hidden");
            }
        });
        dropdownMenu.classList.toggle("hidden");
    }
    document.addEventListener("click", function(event) {
        if (!event.target.closest(".dropdown")) {
            document.querySelectorAll(".dropdown-menu").forEach(menu => {
                menu.classList.add("hidden");
            });
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        var statusMessageElement = document.getElementById('status-message');
        if (statusMessageElement) {
            var statusMessage = statusMessageElement.getAttribute('data-status');
            if (statusMessage) {
                toastr.success(statusMessage);
            }
        }
        var errorMessageElement = document.getElementById('error-message');
        if (errorMessageElement) {
            var errorMessage = errorMessageElement.getAttribute('data-error');
            if (errorMessage) {
                toastr.error(errorMessage, 'Error');
            }
        }
    });
    class AlphaSpaceRestrictor {
        static restrictAlphabetsAndSpace(event) {
            return (
                (event.charCode > 64 && event.charCode < 91) ||
                (event.charCode > 96 && event.charCode < 123) ||
                (event.charCode == 32)
            );
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        var elementsWithRestriction = document.querySelectorAll('.alpha-space');

        elementsWithRestriction.forEach(function(element) {
            element.addEventListener('keypress', function(event) {
                if (!AlphaSpaceRestrictor.restrictAlphabetsAndSpace(event)) {
                    event.preventDefault();
                }
            });
        });
    });
    class AlphaRestrictor {
        static restrictAlphabets(event) {
            return (
                (event.charCode > 64 && event.charCode < 91) ||
                (event.charCode > 96 && event.charCode < 123)
            );
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        var elementsWithRestriction = document.querySelectorAll('.alpha');

        elementsWithRestriction.forEach(function(element) {
            element.addEventListener('keypress', function(event) {
                if (!AlphaRestrictor.restrictAlphabets(event)) {
                    event.preventDefault();
                }
            });
        });
    });
    class NumbersRestrictor {
        static restrictNumber(event) {
            return (
                (event.charCode >= 48 && event.charCode <= 57)
            );
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        var elementsWithRestriction = document.querySelectorAll('.number');
        elementsWithRestriction.forEach(function(element) {
            element.addEventListener('keypress', function(event) {
                if (!NumbersRestrictor.restrictNumber(event)) {
                    event.preventDefault();
                }
            });
        });
    });

    class InputRestrictor {
        static restrictAlphaNumericSpace(event) {
            const charCode = event.charCode;
            return (
                (charCode >= 48 && charCode <= 57) ||
                (charCode >= 65 && charCode <= 90) ||
                (charCode >= 97 && charCode <= 122) ||
                charCode === 32
            );
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        var elementsWithRestriction = document.querySelectorAll('.input-box');

        elementsWithRestriction.forEach(function(element) {
            element.addEventListener('keypress', function(event) {
                if (!InputRestrictor.restrictAlphaNumericSpace(event)) {
                    event.preventDefault();
                }
            });
        });
    });
    class NumberAndDotRestrictor {
        static restrictNumbersWithDot(event, input) {
            if (input.includes('.') && event.charCode >= 48 && event.charCode <= 57) {
                return true;
            }
            return (
                (event.charCode >= 48 && event.charCode <= 57) ||
                (event.charCode === 46 && !input.includes('.'))
            );
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        var elementsWithRestriction = document.querySelectorAll('.number-period');
        elementsWithRestriction.forEach(function(element) {
            element.addEventListener('keypress', function(event) {
                var input = event.target.value;
                if (!NumberAndDotRestrictor.restrictNumbersWithDot(event, input)) {
                    event.preventDefault();
                }
            });
        });
    });
    class TextareaRestrictor {
        static textareaWithoutHTML(event) {
            if (event.key === '<' || event.key === '>') {
                return false;
            }
            return true;
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        var elementsWithRestriction = document.querySelectorAll('.textarea');

        elementsWithRestriction.forEach(function(element) {
            element.addEventListener('keypress', function(event) {
                if (!TextareaRestrictor.textareaWithoutHTML(event)) {
                    event.preventDefault();
                }
            });
        });
    });
    class EmailValidator {
        static validateEmail(event) {
            const inputChar = String.fromCharCode(event.charCode);
            const currentValue = event.target.value + inputChar;

            const isValidChar =
                (inputChar.match(/[a-zA-Z0-9@#$_.\-!\/]/) !==
                    null);
            const isValidSequence = !/(.)\1{2,}/.test(currentValue);
            return isValidChar && isValidSequence;
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        var emailInputElements = document.querySelectorAll('.email');
        emailInputElements.forEach(function(element) {
            element.addEventListener('keypress', function(event) {
                if (!EmailValidator.validateEmail(event)) {
                    event.preventDefault();
                }
            });
        });
    });


    function check_accurance(item_array, item) {
        for (var k = 0; k < item_array.length; k++) {
            if (item_array[k] == item)
                return true;
        }
        return false;
    }
    // (function ($) {
    //         $.fn.preventHtmlTags = function () {
    //             this.on('input', 'input, textarea', function () {
    //                 const sanitizedValue = $(this).val().replace(/<\/?[^>]*>/g, ""); // Remove HTML tags
    //                 $(this).val(sanitizedValue);
    //             });
    //             return this; // Allow chaining
    //         };
    //     })(jQuery);
    function showLoader() {}

    function hideLoader() {}
</script>
<script>
    function toggleDropdown(button) {
        const dropdown = button.nextElementSibling;
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== dropdown) {
                menu.classList.add('hidden');
                menu.classList.remove('top', 'bottom');
            }
        });
        dropdown.classList.toggle('hidden');
        const rect = button.getBoundingClientRect();
        const viewportHeight = window.innerHeight;
        const enoughSpaceBelow = viewportHeight - rect.bottom > 270;
        dropdown.classList.remove('top', 'bottom');

        if (enoughSpaceBelow) {
            dropdown.classList.add('bottom');
        } else {
            dropdown.classList.add('top');
        }
    }
    window.addEventListener('click', function(e) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (!menu.contains(e.target) && !menu.previousElementSibling.contains(e.target)) {
                menu.classList.add('hidden');
                menu.classList.remove('top', 'bottom');
            }
        });
    });
</script>




</html>
