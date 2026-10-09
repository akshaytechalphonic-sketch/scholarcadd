<div class="drawer-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 hidden z-50"></div>
<div id="state-sidebar"
    class="fixed top-0 right-0 z-50 h-screen border-l-2 overflow-y-auto transition-transform translate-x-full bg-white min-w-[350px]"
    tabindex="-1" aria-labelledby="drawer-right-label">
    <div class="flex justify-between items-center bg-primary text-white p-[12px]">
        <h5 class="inline-flex items-center text-base font-semibold">Import States</h5>
        <button type="button" onclick="toggle_model_state('state-sidebar')"
            class="bg-transparent hover:bg-gray-200 hover:text-black rounded-lg text-sm h-8 w-8">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>

    <form id="add_modal_state" method="POST" enctype="multipart/form-data">
    @csrf

        <div class="p-3 max-h-[85vh] overflow-y-auto">
            {{-- Excel File Upload --}}
            <div class="w-full">
                <label class="text-sm font-semibold text-black">{{ __('common.import-document') }} <span class="text-red-500 font-bold">*</span></label>
                <input type="file" name="excel_file" id="excel_file" class="form-input mt-2"
                       accept=".xlsx,.xls,.csv" required>
                <div class="text-xs text-gray-500 mt-1">
                    Only Excel/CSV files. Max 10MB.
                    {{-- <a href="{{ route('export-excel.index') }}" class="text-blue-600 hover:underline font-medium">Download Template</a> --}}
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 w-full p-3 border-t bg-white flex justify-end gap-2">
            <button type="button" onclick="closeAndRefresh()" class="btn-primary-sm close-drawer-btn">
                <i class="ri-close-line"></i> {{ __('common.close') }}
            </button>
            <button type="submit" class="btn-success-sm close-drawer-btn save-btn">
                <i class="ri-save-line"></i> Import
            </button>
        </div>

    </form>
</div>

    {{-- <script>
        $(document).ready(function() {
            // Auto-refresh on successful import
            $(document).on('submit', '#add_modal', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                $('.save-btn').prop('disabled', true).html('<i class="ri-loader-2-line animate-spin"></i> Importing...');

                $.ajax({
                    url: "{{ route('course.excel.faqs') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === true || response.status === 'true') {
                            alert('✅ Courses imported successfully!');
                            closeAndRefresh(); // ✅ Auto-refresh after import
                        } else {
                            alert('❌ Import failed: ' + (response.message || 'Unknown error'));
                            $('.save-btn').prop('disabled', false).html('<i class="ri-save-line"></i> Import');
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON?.message || 'Import failed';
                        alert('❌ ' + errorMsg);
                        $('.save-btn').prop('disabled', false).html('<i class="ri-save-line"></i> Import');
                    }
                });
            });
        });

        // ✅ Global function: Close sidebar + Refresh page
        function closeAndRefresh() {
            toggle_model_faqs('add-sidebar');
            location.reload(); // Refresh Yajra table
        }
    </script> --}}

    <script>
    $(document).ready(function () {

        // ✅ Submit FAQ Excel Import Form
        $(document).on('submit', '#add_modal_state', function (e) {
            e.preventDefault();

            let $form = $(this);
            let $btn  = $form.find('.save-btn');

            if ($btn.prop('disabled')) return;

            let formData = new FormData(this);

            $btn.prop('disabled', true)
                .html('<i class="ri-loader-2-line animate-spin mr-1"></i> Importing...');

            $.ajax({
                url: "{{ route('course.excel.state') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function (response) {
                    if (response?.status === true || response?.status === 'true') {
                        alert('✅ States imported successfully!');
                        // closeAndRefresh();
                    } else {
                        alert('❌ Import failed: ' + (response?.message ?? 'Unknown error'));
                        resetButton($btn);
                    }
                },

                error: function (xhr) {
                    let message = 'Import failed';

                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }

                    alert('❌ ' + message);
                    resetButton($btn);
                }
            });
        });

    });

    // 🔁 Reset submit button
    function resetButton(btn) {
        btn.prop('disabled', false)
           .html('<i class="ri-save-line mr-1"></i> Import');
    }

    // ✅ Toggle drawer open/close
    function toggle_model_state(id) {
        const drawer  = document.getElementById(id);
        const overlay = document.querySelector('.drawer-overlay');

        if (!drawer || !overlay) return;

        const isOpen = !drawer.classList.contains('translate-x-full');

        if (isOpen) {
            drawer.classList.add('translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        } else {
            drawer.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    // ✅ Close drawer and refresh page
    function closeAndRefresh() {
        toggle_model_state('state-sidebar');
        setTimeout(() => location.reload(), 300);
    }

    // ✅ Close drawer on overlay click
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('drawer-overlay')) {
            toggle_model_state('state-sidebar');
        }
    });
</script>

<script>
function closeStateSidebar() {
    $('#state-overlay').addClass('hidden');
    $('#state-sidebar').addClass('translate-x-full');
}
</script>
