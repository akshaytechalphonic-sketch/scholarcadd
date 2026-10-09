@extends('layouts.app')
@section('css')
    @include('layouts.select2css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    @php echo breadcrums('Upload Brand Logo',['common.home','Upload Brand Logo'],['vendor-list']) @endphp
@section('back_button')
    @php echo back_button('vendor-list') @endphp
@endsection
@section('extra_buttons')
@endsection
<div class="bg-white rounded-md border p-4">
    <div class="max-w-md mt-6">
        <!-- Dropzone -->
        <div id="dropzone"
            class="flex items-center justify-center px-6 py-6 border-2 border-dashed rounded-md cursor-pointer bg-white hover:bg-gray-50 transition">
            <div class="text-center space-y-1">
                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5V6a2 2 0 012-2h3.172a2 2 0 011.414.586l1.828 1.828A2 2 0 0012.828 7H19a2 2 0 012 2v7.5M16 21H8a2 2 0 01-2-2v-5h12v5a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm text-gray-600">Click to upload or <span
                        class="text-indigo-600 font-medium">paste</span></p>
                <p class="text-xs text-gray-500">PNG, JPG, JPEG (max 2MB)</p>
            </div>
            <input id="logoInput" type="file" accept="image/png, image/jpeg" class="hidden" />
        </div>

        <!-- Error Message -->
        <p id="errorMessage" class="text-sm text-red-500 mt-2 hidden"></p>

        <!-- Preview Table -->
        <div id="uploadInfoContainer" class="mt-4 hidden">
            <table class="w-full text-sm border border-gray-200 rounded-md">
                <thead class="bg-gray-50 text-gray-700 font-semibold">
                    <tr>
                        <th class="px-3 py-2 text-left">File</th>
                        <th class="px-3 py-2 text-center">Size</th>
                        <th class="px-3 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-t border-gray-200">
                        <td class="px-3 py-2 flex items-center gap-2">
                            <i class="ri-image-line text-indigo-600 text-xl"></i>
                            <span id="fileName" class="truncate max-w-[200px]"></span>
                        </td>
                        <td class="px-3 py-2 text-center" id="fileSize"></td>
                        <td class="px-3 py-2 text-center space-x-2">
                            <a target="_blank" id="previewLink" class="text-green-600 hover:text-green-800 text-lg">
                                <i class="ri-eye-line"></i>
                            </a>
                            <button type="button" class="text-red-600 hover:text-red-800 text-lg"
                                onclick="deleteUploadedLogo()">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('logoInput');
    const uploadInfoContainer = document.getElementById('uploadInfoContainer');
    const fileNameSpan = document.getElementById('fileName');
    const fileSizeSpan = document.getElementById('fileSize');
    const previewLink = document.getElementById('previewLink');
    const errorMessage = document.getElementById('errorMessage');

    const validateFile = (file) => {
        const validTypes = ['image/png', 'image/jpeg', 'image/jpg'];
        const maxSize = 2 * 1024 * 1024;
        if (!validTypes.includes(file.type)) return 'Only PNG, JPG or JPEG allowed';
        if (file.size > maxSize) return 'File size must be less than 2MB';
        return null;
    };

    const humanFileSize = (size) => {
        const i = size == 0 ? 0 : Math.floor(Math.log(size) / Math.log(1024));
        return (size / Math.pow(1024, i)).toFixed(1) + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    };

    const previewFile = (file) => {
        const error = validateFile(file);
        if (error) {
            errorMessage.textContent = error;
            errorMessage.classList.remove('hidden');
            uploadInfoContainer.classList.add('hidden');
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            errorMessage.classList.add('hidden');
            fileNameSpan.textContent = file.name;
            fileSizeSpan.textContent = humanFileSize(file.size);
            previewLink.href = e.target.result;
            uploadInfoContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    };


    dropzone.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) previewFile(file);
    });

    window.addEventListener('paste', (e) => {
        const items = e.clipboardData.items;
        for (let item of items) {
            if (item.type.startsWith('image')) {
                const file = item.getAsFile();
                if (file) previewFile(file);
            }
        }
    });

    window.deleteUploadedLogo = () => {
        fileInput.value = '';
        previewLink.href = '#';
        fileNameSpan.textContent = '';
        fileSizeSpan.textContent = '';
        uploadInfoContainer.classList.add('hidden');
    };
</script>
@endsection
