@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
@endsection
@section('content')
    <div>
        @php echo breadcrums('company.create-user',['common.home','company.create-user'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('user-list') @endphp
    @endsection
    <div class="text-xs w-full  bg-white rounded-md p-4">
        <form method="POST" action="{{route('user-store')}}">
            @csrf
            <div class="w-full">
                <label for="" class="text-sm font-semibold text-black">{{__('company.employee-name')}}</label>
                <select style="width: 100%" class="select-multi mt-1" id="emp_id" name="emp_id" onchange="$.get_emp_data(this)">
                    <option value="">{{ __('common.chooseoption') }}</option>
                    @foreach ($employee as $emp)
                        <option value="{{ base64_encode(convert_uuencode($emp->id)) }}">{{ $emp?->name }} - {{ $emp?->empCode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full mt-4">
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{__('company.name')}}</label>
                    <input value="{{ old('name') }}" type="text" class="input-box form-input mt-1 " name="name" id="name" required>
                    @include('components.error_code', ['keys' => 'name'])
                </div>
                <div class="w-full">
                    <label for="" class="text-sm font-semibold text-black">{{__('company.email')}}</label>
                    <input value="{{ old('email') }}" type="email" class="form-input mt-1 " name="email" id="email" required>
                    @include('components.error_code', ['keys' => 'email',])
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{__('company.company-name')}} </label>
                    <select class="select-multi mt-1 " id="company_id" data-placeholder="{{__('company.select-company')}}" name="company_id[]" multiple="multiple" style="width: 100%" required>
                        @foreach ($company as $companies)
                            <option value="{{ $companies->id }}"> {{ $companies?->name }}</option>
                        @endforeach
                    </select>
                    @include('components.error_code', ['keys' => 'company_id',])
                </div>
                <div class="w-full">
                    <label for="" class=" text-sm font-semibold text-black">{{__('company.role')}} </label>
                    <select multiple="multiple" class="select-multi mt-1 " id="roles" data-placeholder="{{__('company.select-role')}}" name="roles[]" required style="width: 100%">
                        <option disabled>{{__('company.select-role')}}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role?->name }}" > {{ $role?->name }}</option>
                        @endforeach
                    </select>
                    @include('components.error_code', ['keys' => 'roles',])
                </div>

                <div class="w-full">
                    <label for="new-password" class="text-sm font-semibold text-black">{{__('company.new-password')}}</label>
                    <div class="relative">
                        <input id="passwordField" type="password" class="form-input mt-1 w-full p-2 border rounded" placeholder="{{__('company.enter-your-new-password')}}" minlength="6" name="password" maxlength="50" required>
                        <button type="button" onclick="togglePassword('passwordField', 'toggle-icon1')"
                            class="absolute inset-y-0 right-3 flex items-center">
                            <i id="toggle-icon1" class="ri-eye-off-line text-gray-500"></i>
                        </button>
                    </div>
                    <div id="password-requirements" class="mt-2 p-3 text-sm bg-gray-100 rounded  hidden">
                        <p class="text-blue-600 font-semibold">{{__('company.password-must-contain-the-following')}}:</p>
                        <p id="letter" class="text-red-500">❌ {{__('company.a')}} <strong>{{__('company.lowercase')}}</strong> {{__('company.letter')}}</p>
                        <p id="capital" class="text-red-500">❌ {{__('company.a')}} <strong>{{__('company.capital')}} ({{__('company.upper')}})</strong> {{__('company.letter')}}</p>
                        <p id="number" class="text-red-500">❌ {{__('company.a')}} <strong>{{__('company.number')}}</strong></p>
                        <p id="specialcharacter" class="text-red-500">❌ {{__('company.a')}} <strong>{{__('company.special-character')}}</strong></p>
                        <p id="length" class="text-red-500">❌ {{__('company.minimum')}} <strong>8 {{__('company.characters')}}</strong></p>
                    </div>
                </div>

                <div class="w-full">
                    <label for="confirm-password" class="text-sm font-semibold text-black">{{__('company.confirm-password')}}</label>
                    <div class="relative">
                        <input id="confirmPasswordField" type="password" class="form-input mt-1 w-full p-2 border rounded" placeholder="{{__('company.enter-your-confirm-paswword')}}" name="confirm_password" minlength="6" maxlength="50" required>
                        <button type="button" onclick="togglePassword('confirmPasswordField', 'toggle-icon2')"
                            class="absolute inset-y-0 right-3 flex items-center">
                            <i id="toggle-icon2" class="ri-eye-off-line text-gray-500"></i>
                        </button>
                    </div>
                    <p id="match-status" class="text-red-500 text-sm mt-2 hidden">❌ {{__('company.password-do-not-match')}}</p>
                </div>
            </div>

            <div class="flex gap-2 mt-2 justify-end">
                <button type="reset" class="btn-primary-sm">
                    {{__('common.cancel')}}
                </button>
                <button type="submit" class="btn-success-sm">{{__('common.save')}}</button>
            </div>

        </form>
    </div>
</div>

@endsection

@section('scripts')
@include('pages.settings.user.extra_js')
@include('components.formvalidationjs')


<script>
    const passwordInput = document.getElementById("passwordField");
    const confirmPasswordInput = document.getElementById("confirmPasswordField");
    const requirements = document.getElementById("password-requirements");
    const matchStatus = document.getElementById("match-status");

    const validations = {
        lowercase: /[a-z]/,
        uppercase: /[A-Z]/,
        number: /[0-9]/,
        special: /[^A-Za-z0-9]/,
        length: /.{8,}/
    };

    const validationElements = {
        lowercase: document.getElementById("letter"),
        uppercase: document.getElementById("capital"),
        number: document.getElementById("number"),
        special: document.getElementById("specialcharacter"),
        length: document.getElementById("length")
    };

    passwordInput.addEventListener("input", () => {
        requirements.classList.remove("hidden");
        let value = passwordInput.value;

        Object.keys(validations).forEach((key) => {
            if (validations[key].test(value)) {
                validationElements[key].innerHTML =
                    `✔️ ${validationElements[key].textContent.slice(2)}`;
                validationElements[key].classList.replace("text-red-500", "text-green-600");
            } else {
                validationElements[key].innerHTML = `❌ ${validationElements[key].textContent.slice(2)}`;
                validationElements[key].classList.replace("text-green-600", "text-red-500");
            }
        });
    });

    confirmPasswordInput.addEventListener("input", () => {
        if (confirmPasswordInput.value === passwordInput.value && confirmPasswordInput.value !== "") {
            matchStatus.textContent = "✔️ Passwords match";
            matchStatus.classList.replace("text-red-500", "text-green-600");
        } else {
            matchStatus.textContent = "❌ Passwords do not match";
            matchStatus.classList.replace("text-green-600", "text-red-500");
        }
        matchStatus.classList.remove("hidden");
    });

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("ri-eye-off-line", "ri-eye-line");
        } else {
            input.type = "password";
            icon.classList.replace("ri-eye-line", "ri-eye-off-line");
        }
    }
</script>
@include('layouts.select2js')
@endsection
