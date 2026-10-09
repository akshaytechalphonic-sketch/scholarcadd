@extends('layouts.app')
@section('css')
    @include('layouts.sweetalert_css')
@endsection
@section('content')
    <div class="bg-white border rounded-md p-3 ">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold">Brand Logo Management</h2>
            <button onclick="openNewModal('upload-brand-logo-modal')" class="btn-primary-sm">
                + Add New Brand Logo
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border w-12 text-center">Sr No</th>
                        <th class="px-4 py-2 border">Logo Preview</th>
                        <th class="px-4 py-2 border">File Name</th>
                        <th class="px-4 py-2 border">File Size</th>
                        <th class="px-4 py-2 border">Uploaded On</th>
                        <th class="px-4 py-2 border">Uploaded By</th>
                        <th class="px-4 py-2 border text-center w-32">Show / Hide</th>
                        <th class="px-4 py-2 border text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center">1</td>
                        <td class=" ">
                            <img src="{{ asset('assets/image/logo/logo-black.png') }}" alt="Logo"
                                class="h-[50px] w-[100px] object-contain rounded p-2">
                        </td>
                        <td class="px-4 py-2 border">logo-black.png</td>
                        <td class="px-4 py-2 border">120 KB</td>
                        <td class="px-4 py-2 border">2025-09-03 14:20</td>
                        <td class="px-4 py-2 border">Admin User</td>
                        <td class="px-4 py-2 border text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="radio" name="active_logo" value="1" checked class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-primary transition duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5">
                                </div>
                            </label>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <i class="ri-edit-2-line text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="ri-delete-bin-6-line text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Example Row 2 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center">2</td>
                        <td class=" ">
                            <img src="{{ asset('assets/image/logo/logo-black.png') }}" alt="Logo"
                                class="h-[50px] w-[100px] object-contain rounded p-2">
                        </td>
                        <td class="px-4 py-2 border">amysoft.png</td>
                        <td class="px-4 py-2 border">98 KB</td>
                        <td class="px-4 py-2 border">2025-09-01 10:45</td>
                        <td class="px-4 py-2 border">John Doe</td>

                        <td class="px-4 py-2 border text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="radio" name="active_logo" value="1" checked class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-primary transition duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5">
                                </div>
                            </label>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <i class="ri-edit-2-line text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="ri-delete-bin-6-line text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div class="bg-white border rounded-md p-3 mt-3">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-lg font-semibold">Brand Name Management</h2>
            <button onclick="openNewModal('upload-brand-name-modal')" class="btn-primary-sm">
                + Add New Brand Name
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm text-left">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border w-12 text-center">Sr No</th>
                        <th class="px-4 py-2 border">Brand Name</th>
                        <th class="px-4 py-2 border">Uploaded On</th>
                        <th class="px-4 py-2 border">Uploaded By</th>
                        <th class="px-4 py-2 border">Comments</th>
                        <th class="px-4 py-2 border text-center w-32">Show / Hide</th>
                        <th class="px-4 py-2 border text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <!-- Example Row 1 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center">1</td>
                        <td class="px-4 py-2 border">Demo ERP</td>
                        <td class="px-4 py-2 border">2025-09-03 14:20</td>
                        <td class="px-4 py-2 border">Admin User</td>
                        <td class="px-4 py-2 border">Logo for amy</td>

                        <td class="px-4 py-2 border text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="radio" name="active_brand" value="1" checked class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-primary transition duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5">
                                </div>
                            </label>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <i class="ri-edit-2-line text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="ri-delete-bin-6-line text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Example Row 2 -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border text-center">2</td>
                        <td class="px-4 py-2 border">AmySoft</td>
                        <td class="px-4 py-2 border">2025-09-01 10:45</td>
                        <td class="px-4 py-2 border">John Doe</td>
                        <td class="px-4 py-2 border">Logo for amy</td>

                        <td class="px-4 py-2 border text-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="radio" name="active_brand" value="2" class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-300 rounded-full peer peer-checked:bg-primary transition duration-300">
                                </div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-5">
                                </div>
                            </label>
                        </td>
                        <td class="px-4 py-2 border text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <i class="ri-edit-2-line text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800" title="Delete">
                                    <i class="ri-delete-bin-6-line text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @include('pages.settings.interfaceMaster.modal')
@endsection
@section('scripts')
    @include('layouts.modal_js')
    @include('layouts.sweetalert_js')
    <script>
        document.getElementById('logo').addEventListener('change', function(e) {
            let files = Array.from(e.target.files);
            const previewList = document.getElementById('logoPreviewList');
            previewList.innerHTML = "";
            files.forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) {
                    alert(`${file.name} is larger than 2MB`);
                    return;
                }
                const allowed = ["image/jpeg", "image/png", "image/svg+xml", "image/webp"];
                if (!allowed.includes(file.type)) {
                    alert(`${file.name} is not a valid image format`);
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement("div");
                    div.classList.add("flex", "items-center", "justify-between", "border", "rounded",
                        "p-3", "bg-gray-50");

                    const sizeKB = (file.size / 1024).toFixed(1);
                    const sizeText = sizeKB > 1024 ? (sizeKB / 1024).toFixed(1) + " MB" : sizeKB +
                        " KB";

                    div.innerHTML = `
                <div class="flex items-center space-x-3">
                    <img src="${event.target.result}" class="h-12 w-12 object-contain border rounded">
                    <div>
                        <p class="text-sm font-medium text-gray-700 truncate max-w-[200px]">${file.name}</p>
                        <p class="text-xs text-gray-500">${sizeText}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="${event.target.result}" target="_blank" class="text-blue-600 hover:text-blue-800" title="View">
                        <i class="ri-eye-line text-lg"></i>
                    </a>
                    <button type="button" class="text-red-600 hover:text-red-800 delete-btn" title="Delete">
                        <i class="ri-delete-bin-6-line text-lg"></i>
                    </button>
                </div>
            `;
                    // delete button
                    div.querySelector(".delete-btn").addEventListener("click", () => {
                        files.splice(index, 1);
                        const dt = new DataTransfer();
                        files.forEach(f => dt.items.add(f));
                        e.target.files = dt.files;
                        div.remove();
                    });
                    previewList.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection
