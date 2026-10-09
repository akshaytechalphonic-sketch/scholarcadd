<div id="general-ledger-menu"
    class="drawer border-r  fixed top-0  left-0 h-[93vh]   overflow-y-auto transition-transform -translate-x-full bg-white ltr:w-[40vw] rtl:w-full "
    tabindex="-1" aria-labelledby="drawer-label">
    <div class="sticky top-0 bg-white z-50 p-4 ">
        <div class="cursor-pointer rtl:p-4 ">
            <h5 id="drawer-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500">
                General Ledger</h5>
            <button title="Close" type="button"
                class="close-drawer-btn bg-red-300 hover:bg-red-500 h-6 w-6 rounded-md p-3 text-white  absolute top-2.5 end-2.5 flex items-center justify-center">
                <i class="ri-close-line"></i>
            </button>
        </div>
        <div class="mb-2 rtl:hidden">
            <div class="relative w-[250px]">
                <input type="text" placeholder="Search General Ledger..."
                    class="pl-2 pr-8 py-1 w-full border-b border-gray-300 border-t-0 border-l-0 border-r-0 rounded-sm focus:outline-none focus:border-gray-300 active:border-gray-300" />
                <i class="ri-search-2-line absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            </div>
            <p id="noResultsMsg" class="text-red-500  text-sm hidden py-2">No Master Found</p>
        </div>
    </div>
    <div class="flex justify-between h-full rtl:p-4 bg-white z-40 p-4">
        <div class="md:w-[600px]">
            <div>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 list-disc pl-5 my-2">

                      {{-- <li class="list-view"><a href="/">AP Receipts</a></li>
                    <li class="list-view"><a href="/">Tax Codes</a></li>
                    <li class="list-view"><a href="/">Bank Accounts</a></li>
                    <li class="list-view"><a href="/">Bank Transactions</a></li>
                    <li class="list-view"><a href="/">Reconciliation Rules</a></li>
                    <li class="list-view"><a href="/">Budgets </a></li>
                    <li class="list-view"><a href="/">Fixed Assets </a></li>
                    <li class="list-view"><a href="/">Depreciation Schedules </a></li>
                    <li class="list-view"><a href="/">Payment Terms </a></li>
                    <li class="list-view"><a href="/">Currency Rates </a></li> --}}
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[placeholder="Search General Ledger..."]');
        const listItems = document.querySelectorAll('.list-view');
        const noResultsMsg = document.getElementById('noResultsMsg');

        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            let matchFound = false;

            listItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    item.style.display = "list-item";
                    matchFound = true;
                } else {
                    item.style.display = "none";
                }
            });

            // Show or hide the "No Master Found" message
            noResultsMsg.style.display = matchFound ? "none" : "block";
        });
    });
</script>
