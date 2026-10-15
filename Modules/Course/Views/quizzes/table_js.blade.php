<script>
    $(document).ready(function() {
        var table = $('#data-table-list').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            searching: true,
            ajax: {
                url: "{{ route('course-quiz-list') }}",
                type: 'GET',
                data: function(d) {
                    d.type = $('#filter-type').val();
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                { data: 'course_name', name: 'course_name' },
                { data: 'quiz_title', name: 'quiz_title' },
                { data: 'type_badge', name: 'type_badge' },
                { data: 'exam_details', name: 'exam_details' },
                { data: 'questions_count', name: 'questions_count' },
                { data: 'status_badge', name: 'status_badge' },
                { data: 'actions', name: 'actions', className: 'text-center' }
            ],
            @include('layouts.common_yajra')
        });

        @include('layouts.yajra_table_js')

        // Dropdown toggle handler
        $(document).on('click', '.dropdown-toggle', function(e) {
            e.stopPropagation();
            $('.dropdown-menu').not($(this).next('.dropdown-menu')).addClass('hidden');
            $(this).next('.dropdown-menu').toggleClass('hidden');
        });

        $(document).on('click', function() {
            $('.dropdown-menu').addClass('hidden');
        });

        // Filter change
        $('#filter-type, #filter-status').on('change', function() {
            table.draw();
        });

        // Quick Toggle Status
        window.toggleQuizStatus = function(encId) {
            $.ajax({
                url: "{{ route('course-quiz-toggle-status') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: encId
                },
                success: function(res) {
                    if (res.status) {
                        toastr ? toastr.success(res.message) : alert(res.message);
                        table.ajax.reload(null, false);
                    } else {
                        toastr ? toastr.error(res.message) : alert(res.message);
                    }
                },
                error: function(err) {
                    var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error updating quiz status';
                    toastr ? toastr.error(msg) : alert(msg);
                }
            });
        };

        // Confirm Delete
        window.confirmDeleteQuiz = function(encId) {
            if (confirm("Are you sure you want to delete this Quiz / Mock Exam?")) {
                $.ajax({
                    url: "{{ url('course-quiz/delete') }}/" + encId,
                    type: 'GET',
                    success: function(res) {
                        if (res.status) {
                            toastr ? toastr.success(res.message) : alert(res.message);
                            table.ajax.reload(null, false);
                        } else {
                            toastr ? toastr.error(res.message) : alert(res.message);
                        }
                    },
                    error: function(err) {
                        var msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Error deleting quiz';
                        toastr ? toastr.error(msg) : alert(msg);
                    }
                });
            }
        };
    });
</script>
