@props(['tableId' => '', 'columns' => []])
<div class="relative inline-block text-left mr-2">
    <button onclick="toggleColumnDropdown('{{ $tableId }}')" type="button"
        class="btn-primary-sm flex items-center gap-1">
        <i class="ri-filter-3-line"></i> Columns Filter
    </button>
    <div id="columnDropdown-{{ $tableId }}"
        class="hidden absolute right-0 mt-2 w-64 bg-white border rounded-xl shadow-lg z-50 p-4 text-sm space-y-2 max-h-48 overflow-auto">
        <h4 class="text-xs font-semibold text-gray-600 uppercase mb-1 tracking-wide border-b pb-1">
            Show/Hide Columns
        </h4>
        @foreach ($columns as $col)
            <label
                class="flex items-center justify-between cursor-pointer hover:bg-gray-50 px-2 py-1 rounded-md transition">
                <span class="text-gray-700">{{ $col['label'] }}</span>
                <input type="checkbox" checked class="form-checkbox text-primary h-4 w-4 cursor-pointer"
                    data-table="{{ $tableId }}" data-column="{{ $col['column'] }}" onchange="toggleColumn(this)">
            </label>
        @endforeach
    </div>

</div>

<script>
    function toggleColumnDropdown(tableId) {
        document.querySelectorAll("[id^='columnDropdown-']").forEach(dropdown => {
            if (!dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
        const dropdown = document.getElementById(`columnDropdown-${tableId}`);
        dropdown?.classList.toggle("hidden");
    }

    function toggleColumn(checkbox) {
        const tableId = checkbox.dataset.table;
        const colIndex = parseInt(checkbox.dataset.column);
        const table = document.getElementById(tableId);
        if (!table) return;
        table.querySelectorAll("tr").forEach(row => {
            const cell = row.querySelector(`th:nth-child(${colIndex}), td:nth-child(${colIndex})`);
            if (cell) {
                cell.style.display = checkbox.checked ? "" : "none";
            }
        });
    }
    document.addEventListener("click", function(event) {
        const isInsideDropdown = event.target.closest("[id^='columnDropdown-']");
        const isToggleButton = event.target.closest("button[onclick^='toggleColumnDropdown']");

        if (!isInsideDropdown && !isToggleButton) {
            document.querySelectorAll("[id^='columnDropdown-']").forEach(dropdown => {
                dropdown.classList.add("hidden");
            });
        }
    });
</script>
