{{-- <script>
    document.querySelectorAll('.tab-wrapper').forEach(wrapper => {
        const tabButtons = wrapper.querySelectorAll('.tab-btn');
        const tabContents = wrapper.querySelectorAll('.tab-pane');
        if (tabButtons.length > 0) {
            activateTab(wrapper, tabButtons[0].dataset.tab);
        }
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                activateTab(wrapper, button.dataset.tab);
            });
        });
        function activateTab(container, tabNumber) {
            container.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
            container.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-primary', 'border-primary', 'font-semibold', 'bg-blue-100');
                btn.classList.add('bg-gray-100');
            });
            const activePane = container.querySelector(`[data-content="${tabNumber}"]`);
            if (activePane) activePane.classList.remove('hidden');
            const activeButton = container.querySelector(`[data-tab="${tabNumber}"]`);
            if (activeButton) {
                activeButton.classList.remove('bg-gray-100');
                activeButton.classList.add('text-primary', 'border-primary', 'font-semibold', 'bg-blue-100');
            }
        }
    });
</script> --}}
<script>
    document.querySelectorAll('.tab-wrapper').forEach(wrapper => {
        const tabButtons = wrapper.querySelectorAll('.tab-btn');
        const tabContents = wrapper.querySelectorAll('.tab-pane');
        if (tabButtons.length > 0) {
            activateTab(wrapper, tabButtons[0].dataset.tab);
        }
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                activateTab(wrapper, button.dataset.tab);
            });
        });

        function activateTab(container, tabNumber) {
            container.querySelectorAll('.tab-pane').forEach(pane =>
                pane.classList.add('hidden')
            );
            container.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-[#1D70B7]', 'border-b-2', 'font-semibold');
                btn.classList.add('border-transparent', 'text-gray-700', 'font-medium');
            });

            const activePane = container.querySelector(`[data-content="${tabNumber}"]`);
            if (activePane) activePane.classList.remove('hidden');

            const activeButton = container.querySelector(`[data-tab="${tabNumber}"]`);
            if (activeButton) {
                activeButton.classList.remove('border-transparent', 'text-gray-700', 'font-medium');
                activeButton.classList.add('text-[#1D70B7]', 'border-b-2', 'font-semibold');
            }
        }
    });
</script>
