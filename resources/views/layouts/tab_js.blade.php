<script>
    function changeTab(tabNumber) {
        const allTabs = document.querySelectorAll('.tab-pane');
        allTabs.forEach(tab => tab.classList.add('hidden'));
        const allButtons = document.querySelectorAll('.tab-btn');
        allButtons.forEach(button => button.classList.remove('text-blue-600', 'border-blue-500', 'font-semibold', ));
        document.getElementById(`content${tabNumber}`).classList.remove('hidden');
        const activeButton = document.getElementById(`tab${tabNumber}`);
        activeButton.classList.add('text-blue-600', 'border-blue-500', 'font-semibold', );
    }
    changeTab(1);
</script>
