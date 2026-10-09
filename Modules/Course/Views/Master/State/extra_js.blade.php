<script>
function downloadStateExcel() {


    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    Toast.fire({
        icon: 'success',
        title: 'Download started'
    });


    window.open("{{ route('course-state-excel') }}", "_blank");

    window.history.replaceState(null, '', "{{ route('course-state-list') }}");
}
</script>
