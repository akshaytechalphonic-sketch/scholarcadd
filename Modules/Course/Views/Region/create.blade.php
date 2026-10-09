@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
    @include('layouts.select2css')

    <style>
        .h-20 {
            height: 3rem !important;
        }

        /* hide selected tags */
        .select-all-mode .select2-selection__choice {
            display: none !important;
        }

        /* make selection area a flex container */
        .select-all-mode .select2-selection--multiple {
            display: flex !important;
            align-items: center !important;
            /* 🔥 vertical center */
        }

        /* rendered container full height */
        .select-all-mode .select2-selection__rendered {
            display: flex !important;
            align-items: center !important;
            width: 100%;
            padding-left: 12px;
            /* optional: same as input */
        }

        /* text */
        .select-all-mode .select2-selection__rendered::before {
            content: "All Selected";
            color: #374151;
            font-size: 14px;
        }
    </style>

@endsection
@section('content')
    <div>
        @php echo breadcrums('Course.Country Region Create',['common.home','Course.Country Region Create'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('back') @endphp
    @endsection
    @section('extra_buttons')
        {{-- @php echo filter_button(); @endphp --}}
    @endsection
</div>
@include('pages.common.custom_filter_html')
<div class="text-xs w-full bg-white border rounded-md p-4">
    @if ($data)
        <form action="{{ route('region-update') }}" method="post" enctype="multipart/form-data">
        @else
            <form action="{{ route('region-store') }}" method="post" enctype="multipart/form-data">
    @endif
    @csrf
    @if ($data)
        <input type="hidden" class="limited-input form-input mt-1" value="{{ encrypt($data?->id) }}"
            data-maxlength="100" name="Course_id" required>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-2 gap-5 w-full my-2">
        <div class="w-full">
            <label for="promo_code" class="text-sm font-semibold text-black">
                {{ __('Course.Name') }} <span class="text-red-500 font-bold">*</span>
            </label>
            <input type="text" id="name" name="name" maxlength="50"
                placeholder="Enter Name (max 50 chars)..." class="text-only form-input w-full pr-10"
                value="{{ old('name', $data?->name ?? '') }}" required />
            <div class="char-count text-xs text-gray-500 text-right mt-1" id="char-count">0/50</div>
        </div>

        <div class="w-full">
            <label for="" class=" text-sm font-semibold text-black">{{ __('Course.Status') }}
                <span class="text-red-500 font-bold">*</span></label>
            <select class="select-single mt-1" style="width:100%" name="status" required>
                <option value=""></option>
                <option value="Active" @if ($data?->status == 'Active') selected @endif>Active
                </option>
                <option value="In active" @if ($data?->status == 'In active') selected @endif>Inactive
                </option>
            </select>
        </div>


        <div class="w-full">
            <label for="promo_text" class="text-sm font-semibold text-black">
                {{ __('Course.description') }} <span class="text-red-500 font-bold">*</span>
            </label>
            <textarea id="promo_text" name="description" rows="4" placeholder="Enter region description..."
                class="input-box form-input w-full" required>{{ old('description', $data?->description ?? '') }}</textarea>
        </div>

                                    <div class="w-full">
    <div class="flex justify-between items-center mb-1">
        <label class="text-sm font-semibold text-black">Country Name <span class="text-red-500">*</span></label>
        <label class="flex items-center text-sm cursor-pointer">
            Select All
            <input id="selectAllCountries" type="checkbox" class="w-4 h-4 ml-2 text-blue-600 border-gray-300 rounded" >
        </label>
    </div>

    <select id="country_id" name="country_id[]" class="select-country w-full" multiple required>
        @foreach ($countries as $country)
            <option value="{{ $country->id }}">
                {{ $country->name }}
            </option>
        @endforeach
    </select>
</div>
    </div>

    <div class="flex justify-end mt-4">
        <div class="flex gap-2">
            <button type="reset" class="btn-primary-sm flex items-center gap-1">
                <i class="ri-refresh-line"></i>
                {{ __('common.reset') }}
            </button>
            <button type="submit" class="btn-success-sm flex items-center gap-1">
                <i class="ri-check-line"></i>
                {{ __('common.submit') }}
            </button>
        </div>
    </div>
    </div>

    </form>

@endsection
@section('scripts')
    @include('layouts.select2js')
    @include('pages.common.custom_filter_js')
    @include('layouts.datatable_js')
    @include('layouts.sidebar_modal_js')

 {{-- <script>
        $(document).ready(function() {
            const $select = $('#country_id');
            let selectAllActive = false;

            function formatOption(option) {
                if (!option.id) return option.text;
                return $(`
                    <span>
                        <input type="checkbox" class="mr-2 form-checkbox"/>
                        ${option.text}
                    </span>
                `);
            }

            // Destroy if exists
            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                placeholder: "Select Country...",
                closeOnSelect: false,
                templateResult: formatOption,
                width: '100%'
            });

            // Refresh display
            $select.trigger('change');

            function syncDropdownCheckboxes() {
                setTimeout(() => {
                    $('.select2-results__option').each(function() {
                        const selected = $(this).attr('aria-selected') === 'true';
                        const $checkbox = $(this).find('input[type="checkbox"]');
                        if ($checkbox.length) {
                            $checkbox.prop('checked', selected);
                        }
                    });
                }, 50);
            }

            // Sync multiple times
            syncDropdownCheckboxes();
            setTimeout(syncDropdownCheckboxes, 100);
            setTimeout(syncDropdownCheckboxes, 300);

            $select.on('select2:select select2:unselect', syncDropdownCheckboxes);
            $select.on('select2:open', syncDropdownCheckboxes);

            $('#selectAllCountries').on('change', function() {
                const $select2 = $select.next('.select2');

                if (this.checked) {
                    selectAllActive = true;
                    const allValues = $select.find('option').map(function() {
                        return this.value;
                    }).get();
                    $select.val(allValues).trigger('change');
                    $select2.addClass('select-all-mode');
                } else {
                    selectAllActive = false;
                    $select.val(null).trigger('change');
                    $select2.removeClass('select-all-mode');
                }
                syncDropdownCheckboxes();
            });
        });
</script> --}}

@if(isset($data) && $data->country_array)
<script>
    window.preSelectedCountries = @json($data->country_array);
</script>
@else
<script>
    window.preSelectedCountries = [];
</script>
@endif

<script>
$(document).ready(function() {
    const $select = $('#country_id');
    let selectAllActive = false;

    const preSelectedCountries = window.preSelectedCountries || [];

    console.log('Pre-selecting countries:', preSelectedCountries);


    function formatOption(option) {
        if (!option.id) return option.text;
        return $(`
            <span>
                <input type="checkbox" class="mr-2 form-checkbox"/>
                ${option.text}
            </span>
        `);
    }

    // Destroy if exists
    if ($select.hasClass('select2-hidden-accessible')) {
        $select.select2('destroy');
    }


    $select.select2({
        placeholder: "Select Country...",
        closeOnSelect: false,
        templateResult: formatOption,
        width: '100%'
    });

    if (preSelectedCountries.length > 0) {
        setTimeout(() => {
            $select.val(preSelectedCountries).trigger('change');
            console.log('Countries selected:', $select.val()); // DEBUG
        }, 100);
    }

    // Refresh display
    $select.trigger('change');

    function syncDropdownCheckboxes() {
        setTimeout(() => {
            $('.select2-results__option').each(function() {
                const selected = $(this).attr('aria-selected') === 'true';
                const $checkbox = $(this).find('input[type="checkbox"]');
                if ($checkbox.length) {
                    $checkbox.prop('checked', selected);
                }
            });
        }, 50);
    }

    // Sync multiple times
    syncDropdownCheckboxes();
    setTimeout(syncDropdownCheckboxes, 100);
    setTimeout(syncDropdownCheckboxes, 300);

    $select.on('select2:select select2:unselect', syncDropdownCheckboxes);
    $select.on('select2:open', syncDropdownCheckboxes);

    $('#selectAllCountries').on('change', function() {
        const $select2 = $select.next('.select2');

        if (this.checked) {
            selectAllActive = true;
            const allValues = $select.find('option').map(function() {
                return this.value;
            }).get();
            $select.val(allValues).trigger('change');
            $select2.addClass('select-all-mode');
        } else {
            selectAllActive = false;
            $select.val(null).trigger('change');
            $select2.removeClass('select-all-mode');
        }
        syncDropdownCheckboxes();
    });
});
</script>


@endsection
