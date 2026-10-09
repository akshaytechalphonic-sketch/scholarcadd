 <script>
 function toggle_model(modal) {
        const viewSidebar = document.getElementById(modal);
        const overlay = document.querySelector(".drawer-overlay");
        const isOpen = !viewSidebar.classList.contains('translate-x-full');
        if (isOpen) {
            viewSidebar.classList.add('translate-x-full');
        } else {
            viewSidebar.classList.remove('translate-x-full');
        }
        toggleOverlay();
    }

     function closeAllSidebars(modal) {
        const addSidebar = document.getElementById(modal);
        const overlay = document.querySelector(".drawer-overlay");
        addSidebar.classList.add('translate-x-full');
        overlay.classList.add('hidden');
    }

    function toggleOverlay() {
        const addSidebar = document.getElementById('add-sidebar');
        const viewSidebar = document.getElementById('view-sidebar');
        const overlay = document.querySelector(".drawer-overlay");
        if (!addSidebar.classList.contains('translate-x-full') || !viewSidebar.classList.contains('translate-x-full')){
            overlay.classList.remove('hidden');
        } else {
            overlay.classList.add('hidden');
        }
    }
</script>
