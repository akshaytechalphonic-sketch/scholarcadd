<div class="card custom-filter-card hidden bg-white py-2 mt-2 px-2 rounded-md mb-2 border">
    <div class="card-body">

        <div class="flex justify-between items-center">
                <div id="filterDiv" class="grid grid-cols-1 md:grid-cols-5 gap-2 mb-1">
                    <div class="col-span-1">
                        <select class="form-input w-full cursor-pointer" id="filterColumn">
                            <option value="" disabled selected>Select Filter</option>
                            <option value="status">Status</option>
                            <option value="created_by">Created By</option>
                            <option value="created_at">Created At</option>
                        </select>
                    </div>

                    <div class="col-span-1" id="disnone">
                        <input type="text" class="form-input w-full" id="filterValue"
                            placeholder="Enter filter value" disabled>
                    </div>

                    <div class="col-span-2 custom-date-range" id="custom-date-range">
                        <div class="input-daterange flex gap-2 input-group" id="datepicker6"
                            data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-provide="datepicker"
                            data-date-container='#datepicker6'>
                            <input type="date"
                                class="cursor-pointer form-input form-control custom-placeholder w-full" id="startdate"
                                name="start" placeholder="Start Date" />
                            <input type="date" class="form-input form-control custom-placeholder w-full"
                                id="enddate" name="end" placeholder="End Date" />
                        </div>
                    </div>

                    <div class="col-span-1 custom-date-range" id="custom-date">
                        <div class="input-daterange flex gap-2 input-group" id="datepicker7"
                            data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-provide="datepicker"
                            data-date-container='#datepicker7'>
                            <input type="date"
                                class="cursor-pointer form-input form-control custom-placeholder w-full" id="custom_date"
                                name="custom_date" placeholder="Date" />
                        </div>
                    </div>

                    <div class="">
                        <button id="applyColumnFilter" title="Apply Filter"
                            class=" px-3 py-[8px] mt-1 border rounded-[4px] text-sm bg-primary hover:bg-blue-900  text-white disabled">
                            Apply Filter
                            <i class="ri-filter-line"></i>
                        </button>
                        <button id="resetfilter"
                            class="px-3 py-[8px] mt-1 bg-green-500 rounded-[4px] text-white text-sm hover:bg-green-700"
                            title="Refresh Filter">
                            <i class="ri-restart-line"></i>
                        </button>
                    </div>
                </div>
            <div>
                <button class="fil-btn  p-2 text-sm border-l hover:text-red-400 " title="Close Filter">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
