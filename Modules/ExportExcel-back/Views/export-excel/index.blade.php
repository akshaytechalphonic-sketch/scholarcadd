@extends('layouts.app')
@section('css')
    @include('layouts.datatable_css')
@endsection
@section('content')

    <div>

        @if (session('success'))
    <div class="mb-2 px-4 py-2 bg-green-100 text-green-800 rounded text-sm">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-2 px-4 py-2 bg-red-100 text-red-800 rounded text-sm">
        {{ session('error') }}
    </div>
@endif


        @php echo breadcrums('common.export-excel',['common.home','common.export-excel'],['dashboard']) @endphp
    @section('back_button')
        @php echo back_button('dashboard') @endphp
    @endsection
    @section('extra_buttons')

        @php echo filter_button(); @endphp
    @endsection
</div>
@include('pages.common.custom_filter_html')
<div class="text-xs w-full border bg-white rounded-md">
    <div class="w-full overflow-x-auto p-2">
        <table class="min-w-full table-fixed border-collapse">
            <thead class="bg-primary text-white text-xs">
                <tr>
                    <th class="px-3 py-2 text-center w-[70px]">{{ __('common.srno.') }}</th>
                    <th class="px-3 py-2 text-center">{{ __('common.module-name') }}</th>
                    <th class="px-3 py-2 text-center w-[90px]">{{ __('common.action') }}</th>
                </tr>
            </thead>
            <tbody class="text-xs divide-y">
                {{-- ================ course Pointers ================= --}}
                <tr class="hover:bg-gray-50">
                    <td class="text-center">1</td>
                    <td class="text-center">Course Pointers</td>
                    <td class="px-3 py-2 text-center w-[90px]">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                   <ul class="text-sm text-black">
                                   <div id="downloadLoader"
                                        style="display:none; position:fixed; inset:0;
                                        background:rgba(0,0,0,0.4);
                                        z-index:9999;
                                        align-items:center;
                                        justify-content:center;">

                                        <div style="background:white; padding:20px 30px; border-radius:8px;">
                                            Preparing CSV... wait for 3 to 5 minutes
                                        </div>
                                    </div>


                                    <li>
                                        <button onclick="openImportModal('pointers')"
                                            class="block w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-upload-2-line mr-2"></i> Import Course Pointers
                                        </button>
                                    </li>
                                    <li>
                                        <button onclick="downloadCSV()"
                                            class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-download-2-line mr-2"></i> Export Pointers Format
                                        </button>
                                    </li>






                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- ================ course highlights ================= --}}

                 <tr>
                    <td class="text-center">2</td>
                    <td class="text-center">Course Highlights</td>
                    <td class="text-center">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                   <ul class="text-sm text-black">
                                    <div id="downloadhighlightsLoader"
                                        style="display:none; position:fixed; inset:0;
                                        background:rgba(0,0,0,0.4);
                                        z-index:9999;
                                        align-items:center;
                                        justify-content:center;">

                                        <div style="background:white; padding:20px 30px; border-radius:8px;">
                                            Preparing CSV... wait for 3 to 5 minutes
                                        </div>
                                    </div>
                                        <li>
                                            <button onclick="openImportModal('highlights')"
                                                class="block w-full text-left px-3 py-1 hover:bg-gray-100">
                                                <i class="ri-upload-2-line mr-2"></i> Import Course Highlights
                                            </button>
                                        </li>
                                        <li>
                                        <button onclick="downloadHighlightsCSV()"
                                            class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-download-2-line mr-2"></i> Export Hghights Format
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>


                {{-- ================ course why_choose ================= --}}

                 <tr>
                    <td class="text-center">3</td>
                    <td class="text-center">Course Why Choose Us</td>
                    <td class="text-center">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                   <ul class="text-sm text-black">
                                    <div id="downloadWhyChooseUsLoader"
                                        style="display:none; position:fixed; inset:0;
                                        background:rgba(0,0,0,0.4);
                                        z-index:9999;
                                        align-items:center;
                                        justify-content:center;">

                                        <div style="background:white; padding:20px 30px; border-radius:8px;">
                                            Preparing CSV... wait for 3 to 5 minutes
                                        </div>
                                    </div>
                                    <li>
                                        <button onclick="openImportModal('why_choose')"
                                            class="block w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-upload-2-line mr-2"></i> Import Why Choose Us
                                        </button>
                                    </li>
                              <li>
                                        <button onclick="downloadWhyChooseUsCSV()"
                                            class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-download-2-line mr-2"></i> Export Why Choose Us Format
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>


                {{-- ================ course eligiblity ================= --}}

                <tr>
                    <td class="text-center">4</td>
                    <td class="text-center">Course  Eligiblity</td>
                    <td class="text-center">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                   <ul class="text-sm text-black">
                                    <div id="downloadEligiblityLoader"
                                        style="display:none; position:fixed; inset:0;
                                        background:rgba(0,0,0,0.4);
                                        z-index:9999;
                                        align-items:center;
                                        justify-content:center;">

                                        <div style="background:white; padding:20px 30px; border-radius:8px;">
                                            Preparing CSV... wait for 3 to 5 minutes
                                        </div>
                                    </div>
                                <li>
                                    <button onclick="openImportModal('eligiblity')"
                                        class="block w-full text-left px-3 py-1 hover:bg-gray-100">
                                        <i class="ri-upload-2-line mr-2"></i> Import Course Eligiblity
                                    </button>
                                </li>
                              <li>
                                        <button onclick="downloadEligiblityCSV()"
                                            class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-download-2-line mr-2"></i> Export Eligiblity Format
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- ================ course roadmap ================= --}}

                <tr>
                    <td class="text-center">5</td>
                    <td class="text-center">Course Roadmap</td>
                    <td class="text-center">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                   <ul class="text-sm text-black">
                                    <div id="downloadRoadmapLoader"
                                        style="display:none; position:fixed; inset:0;
                                        background:rgba(0,0,0,0.4);
                                        z-index:9999;
                                        align-items:center;
                                        justify-content:center;">

                                        <div style="background:white; padding:20px 30px; border-radius:8px;">
                                            Preparing CSV... wait for 3 to 5 minutes
                                        </div>
                                    </div>
                                <li>
                                    <button onclick="openImportModal('roadmap')"
                                        class="block w-full text-left px-3 py-1 hover:bg-gray-100">
                                        <i class="ri-upload-2-line mr-2"></i> Import Course Roadmap
                                    </button>
                                </li>
                              <li>
                                        <button onclick="downloadRoadmapCSV()"
                                            class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-download-2-line mr-2"></i> Export Roadmap Format
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- ================ course learning_objective ================= --}}

                <tr>
                    <td class="text-center">6</td>
                    <td class="text-center">Course Learning Objective</td>
                    <td class="text-center">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                   <ul class="text-sm text-black">
                                    <div id="downloadObjectiveLoader"
                                        style="display:none; position:fixed; inset:0;
                                        background:rgba(0,0,0,0.4);
                                        z-index:9999;
                                        align-items:center;
                                        justify-content:center;">

                                        <div style="background:white; padding:20px 30px; border-radius:8px;">
                                            Preparing CSV... wait for 3 to 5 minutes
                                        </div>
                                    </div>
                                <li>
                                    <button onclick="openImportModal('learning_objective')"
                                        class="block w-full text-left px-3 py-1 hover:bg-gray-100">
                                        <i class="ri-upload-2-line mr-2"></i> Import Course Objective
                                    </button>
                                </li>
                              <li>
                                        <button onclick="downloadObjectiveCSV()"
                                            class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-download-2-line mr-2"></i> Export Objective Format
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- ================ course about geography ================= --}}

                <tr>
                    <td class="text-center">7</td>
                    <td class="text-center">Course About Geography</td>
                    <td class="text-center">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                   <ul class="text-sm text-black">
                                    <div id="downloadGeographyLoader"
                                        style="display:none; position:fixed; inset:0;
                                        background:rgba(0,0,0,0.4);
                                        z-index:9999;
                                        align-items:center;
                                        justify-content:center;">

                                        <div style="background:white; padding:20px 30px; border-radius:8px;">
                                            Preparing CSV... wait for 3 to 5 minutes
                                        </div>
                                    </div>
                                 <li>
                                    <button onclick="openImportModal('about_geography')"
                                        class="block w-full text-left px-3 py-1 hover:bg-gray-100">
                                        <i class="ri-upload-2-line mr-2"></i> Import Course geography
                                    </button>
                                </li>
                              <li>
                                        <button onclick="downloadGeographyCSV()"
                                            class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-download-2-line mr-2"></i> Export Geography Format
                                        </button>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>


                {{-- ======== faqs ================ --}}

                <tr>
                    <td class="text-center">8</td>
                    <td class="text-center">Faqs</td>
                    <td class="text-center">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                    <li><button onclick="toggle_model_faqs('faqs-sidebar')" class="block px-3 py-1 hover:bg-gray-100"><i class="ri-upload-2-line mr-2"></i></i>Import</button></li>
                                     {{-- <li>
                                        <a href="{{ route('faqs.excel.download') }}" class="block px-3 py-1 hover:bg-gray-100">
                                            <i class="ri-download-2-line mr-2"></i>Export Format
                                        </a>
                                    </li> --}}
                              <li>
                                    <button onclick="downloadTemplate('faqs')"
                                        class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                        <i class="ri-download-2-line mr-2"></i> Export faqs Format
                                    </button>
                              </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>

                {{-- ========== states =============== --}}

                <tr>
                    <td class="text-center">9</td>
                    <td class="text-center">States</td>
                    <td class="text-center">
                        <div class="dropdown relative inline-block bg-gray-100 hover:bg-gray-200 p-1 rounded-sm border">
                            <button onClick="toggleDropdown(this)" class="inline-flex items-center text-sm font-medium text-gray-900" type="button">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 z-10 hidden bg-white divide-y divide-gray-100 rounded-md border w-[240px]">
                                <ul class="text-sm text-black">
                                    <li><button onclick="toggle_model_state('state-sidebar')" class="block px-3 py-1 hover:bg-gray-100"><i class="ri-upload-2-line mr-2"></i></i>Import</button></li>
                                <li>
                                    <button onclick="downloadTemplate('state')"
                                        class="w-full text-left px-3 py-1 hover:bg-gray-100">
                                        <i class="ri-download-2-line mr-2"></i> Export State Format
                                    </button>
                              </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>
</div>
@include('ExportExcel::export-excel.modal')
@include('ExportExcel::export-excel.faqsModel')
@include('ExportExcel::export-excel.stateModel')
@include('ExportExcel::export-excel.global_loader')
@endsection
@section('scripts')
@include('pages.common.custom_filter_js')
@include('layouts.datatable_js')
@include('layouts.sidebar_modal_js')
{{-- @include('ExportExcel::export-excel.table_js') --}}

<script>
    function downloadTemplate(type) {
        fetch("{{ route('course.excel.download') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
            },
            body: new URLSearchParams({ type })
        })
        .then(async response => {
            if (!response.ok) {
                const res = await response.json();
                throw res.message;
            }
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `course_${type}_template.xlsx`;
            document.body.appendChild(a);
            a.click();
            a.remove();

            toastSuccess("Template downloaded successfully");
        })
        .catch(err => {
            toastError(err || "Template not found");
        });
    }
</script>

<script>
    function toastSuccess(msg) {
        alert("✅ " + msg);
    }
    function toastError(msg) {
        alert("❌ " + msg);
    }
</script>

<script>
function downloadCSV() {

    const loader = document.getElementById('downloadLoader');
    loader.style.display = "flex";

    fetch("{{ route('download-pointer-excel') }}")
    .then(response => {

        if (!response.ok) {
            throw "Download failed";
        }

        return response.blob();
    })
    .then(blob => {

        const url = window.URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "course_headline_export.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();

        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        alert(error);
    })
    .finally(() => {
        loader.style.display = "none";
    });
}

function downloadHighlightsCSV() {

    const loader = document.getElementById('downloadhighlightsLoader');
    loader.style.display = "flex";

    fetch("{{ route('download-highlights-excel') }}")
    .then(response => {

        if (!response.ok) {
            throw "Download failed";
        }

        return response.blob();
    })
    .then(blob => {

        const url = window.URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "course_highlights_export.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();

        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        alert(error);
    })
    .finally(() => {
        loader.style.display = "none";
    });
}

function downloadWhyChooseUsCSV() {

    const loader = document.getElementById('downloadWhyChooseUsLoader');
    loader.style.display = "flex";

    fetch("{{ route('download-whyChooseUs-excel') }}")
    .then(response => {

        if (!response.ok) {
            throw "Download failed";
        }

        return response.blob();
    })
    .then(blob => {

        const url = window.URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "course_whyChooseUs_export.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();

        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        alert(error);
    })
    .finally(() => {
        loader.style.display = "none";
    });
}

function downloadEligiblityCSV() {

    const loader = document.getElementById('downloadEligiblityLoader');
    loader.style.display = "flex";

    fetch("{{ route('download-eligiblity-excel') }}")
    .then(response => {

        if (!response.ok) {
            throw "Download failed";
        }

        return response.blob();
    })
    .then(blob => {

        const url = window.URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "course_eligibity_export.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();

        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        alert(error);
    })
    .finally(() => {
        loader.style.display = "none";
    });
}

function downloadRoadmapCSV() {

    const loader = document.getElementById('downloadRoadmapLoader');
    loader.style.display = "flex";

    fetch("{{ route('download-roadmap-excel') }}")
    .then(response => {

        if (!response.ok) {
            throw "Download failed";
        }

        return response.blob();
    })
    .then(blob => {

        const url = window.URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "course_roadmap_export.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();

        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        alert(error);
    })
    .finally(() => {
        loader.style.display = "none";
    });
}

function downloadObjectiveCSV() {

    const loader = document.getElementById('downloadObjectiveLoader');
    loader.style.display = "flex";

    fetch("{{ route('download-objective-excel') }}")
    .then(response => {

        if (!response.ok) {
            throw "Download failed";
        }

        return response.blob();
    })
    .then(blob => {

        const url = window.URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "course_objective_export.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();

        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        alert(error);
    })
    .finally(() => {
        loader.style.display = "none";
    });
}

function downloadGeographyCSV() {

    const loader = document.getElementById('downloadGeographyLoader');
    loader.style.display = "flex";

    fetch("{{ route('download-geography-excel') }}")
    .then(response => {

        if (!response.ok) {
            throw "Download failed";
        }

        return response.blob();
    })
    .then(blob => {

        const url = window.URL.createObjectURL(blob);

        const a = document.createElement("a");
        a.href = url;
        a.download = "course_geography_export.csv";
        document.body.appendChild(a);
        a.click();
        a.remove();

        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        alert(error);
    })
    .finally(() => {
        loader.style.display = "none";
    });
}


</script>




@endsection

{{-- Modules\ExportExcel\Views\\.blade.php --}}
