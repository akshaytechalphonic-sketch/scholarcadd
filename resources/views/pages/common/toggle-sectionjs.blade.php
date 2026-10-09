<script>
    let sidebarVisible = true;
    function toggleSidebar() {
        const main = document.querySelector('.mainContent');
        const sidebar = document.querySelector('.sidebarContent');
        const toggleBtn = document.querySelector('.toggleSidebarBtn');
        const icon = toggleBtn.querySelector('i');
        if (sidebarVisible) {
            sidebar.classList.add('hidden');
            main.classList.remove('md:w-[70%]');
            main.classList.add('md:w-full');
            toggleBtn.innerHTML = '<i class="ri-collapse-diagonal-fill text-xl text-gray-500"></i>';
        } else {
            sidebar.classList.remove('hidden');
            main.classList.remove('md:w-full');
            main.classList.add('md:w-[70%]');
            toggleBtn.innerHTML = '<i class="ri-expand-horizontal-s-fill text-xl text-gray-500"></i>';
        }
        sidebarVisible = !sidebarVisible;
    }
</script>