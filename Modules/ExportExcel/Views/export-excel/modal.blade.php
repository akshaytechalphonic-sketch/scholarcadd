<!-- Overlay -->

<style>
    .drawer-overlay {
        pointer-events: none;   /* 👈 default me click block nahi kare */
    }

    .drawer-overlay.active {
        pointer-events: auto;   /* 👈 sirf sidebar open hone par */
    }

</style>

<div id="course-overlay"
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"
     onclick="closeCourseSidebar()">
</div>



<!-- Sidebar -->
<div id="add-sidebar"
     onclick="event.stopPropagation()"
     class="fixed top-0 right-0 z-50 h-screen w-[380px] bg-white border-l transition-transform translate-x-full overflow-y-auto">


    <!-- Header -->
    <div class="flex justify-between items-center bg-primary text-white px-4 py-3">
        <h5 id="importModalTitle" class="text-base font-semibold">
            Import Courses
        </h5>
        <button type="button"
            onclick="closeCourseSidebar()"
            class="hover:bg-white/20 rounded h-8 w-8 flex items-center justify-center">
            <i class="ri-close-line text-lg"></i>
        </button>
    </div>

    <!-- Form -->
    <form id="add_modal" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="p-4 space-y-3">
            <div>
                <label class="text-sm font-semibold text-black">
                    Import File <span class="text-red-500">*</span>
                </label>

                <input type="file"
                    name="excel_file"
                    id="excel_file"
                    class="form-input mt-2 w-full"
                    accept=".xlsx,.xls,.csv"
                    required>

                <p class="text-xs text-gray-500 mt-1">
                    Only Excel / CSV files. Max 30MB.
                </p>
            </div>
        </div>

        <!-- Footer -->
<!-- Footer -->
<div class="sticky bottom-0 bg-white border-t px-4 py-3 space-y-2">

    <!-- 🔽 Action Buttons -->
    <div class="flex justify-end gap-2 pt-2">
        <button type="button"
            onclick="closeCourseSidebar()"
            class="btn-primary-sm">
            <i class="ri-close-line"></i> Close
        </button>

        <button type="submit"
            class="btn-success-sm save-btn">
            <i class="ri-save-line"></i> Import
        </button>
    </div>

</div>

    </form>

</div>



<script>

let importRoute = '';

function openImportModal(type) {

    if (typeof closeStateSidebar === 'function') {
        closeStateSidebar();
    }

    /* Route Selection */
    if (type === 'pointers') {
        importRoute = "{{ route('course.excel.update') }}";
        $('#importModalTitle').text('Import Course Pointers');
    }

    if (type === 'highlights') {
        importRoute = "{{ route('course.excel.highlights') }}";
        $('#importModalTitle').text('Import Course Highlights');
    }

    if (type === 'why_choose') {
        importRoute = "{{ route('course.excel.whyChooseUs') }}";
        $('#importModalTitle').text('Import Why Choose Us');
    }

    if (type === 'eligiblity') {
        importRoute = "{{ route('course.excel.eligiblity') }}";
        $('#importModalTitle').text('Import Course Eligibility');
    }

    if (type === 'roadmap') {
        importRoute = "{{ route('course.excel.roadmap') }}";
        $('#importModalTitle').text('Import Course Roadmap');
    }

    if (type === 'learning_objective') {
        importRoute = "{{ route('course.excel.learningObjectives') }}";
        $('#importModalTitle').text('Import Course Objective');
    }

    if (type === 'about_geography') {
        importRoute = "{{ route('course.excel.aboutGeography') }}";
        $('#importModalTitle').text('Import Course Geography');
    }

     if (type === 'curriculum') {
        importRoute = "{{ route('course.excel.curriculum') }}";
        $('#importModalTitle').text('Import Course Curriculum');
    }

    if (type === 'faqs') {
        importRoute = "{{ route('course.excel.faqs') }}";
        $('#importModalTitle').text('Import faqs');
    }

    $('#excel_file').val('');

    $('#course-overlay').removeClass('hidden');
    $('#add-sidebar').removeClass('translate-x-full');
}


/* =========================
   FORM SUBMIT
========================= */

$(document).on('submit', '#add_modal', function (e) {

    e.preventDefault();

    let formData = new FormData(this);

    $('.save-btn').prop('disabled', true).html('Importing...');

    $('#global-loader').css('display','flex');
    // showLoader();

    $.ajax({
        url: importRoute,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        timeout: 0,

        success: function (response) {

            // hideLoader();
            $('#global-loader').hide();

            $('.save-btn').prop('disabled', false).html('<i class="ri-save-line"></i> Import');

            if(response.status){
                alert(response.message);
                closeCourseSidebar();
                location.reload();
            }else{
                alert(response.message);
            }
        },

        error: function () {

            // hideLoader();
            $('#global-loader').hide();

            $('.save-btn').prop('disabled', false).html('<i class="ri-save-line"></i> Import');

            alert('Import failed');
        }
    });

});


/* =========================
   HELPERS
========================= */

function resetBtn() {

    $('.save-btn')
        .prop('disabled', false)
        .html('<i class="ri-save-line"></i> Import');

}

function closeCourseSidebar() {

    $('#course-overlay').addClass('hidden');
    $('#add-sidebar').addClass('translate-x-full');

}



</script>


